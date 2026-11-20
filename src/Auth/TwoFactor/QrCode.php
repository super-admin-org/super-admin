<?php

namespace SuperAdmin\Admin\Auth\TwoFactor;

use InvalidArgumentException;

/**
 * A small QR code encoder (ISO/IEC 18004: byte mode, error correction level M,
 * versions 1-40) that draws an SVG, so the two-factor setup page needs no
 * extra package or external QR service (the secret never leaves the server).
 */
class QrCode
{
    /**
     * Error correction codewords per block for level M, by version.
     */
    protected const ECC_CODEWORDS_PER_BLOCK = [
        -1, 10, 16, 26, 18, 24, 16, 18, 22, 22, 26, 30, 22, 22, 24, 24, 28, 28, 26, 26, 26,
        26, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28,
    ];

    /**
     * Error correction blocks for level M, by version.
     */
    protected const ECC_BLOCKS = [
        -1, 1, 1, 1, 2, 2, 4, 4, 4, 5, 5, 5, 8, 9, 9, 10, 10, 11, 13, 14, 16,
        17, 17, 18, 20, 21, 23, 25, 26, 28, 29, 31, 33, 35, 37, 38, 40, 43, 45, 47, 49,
    ];

    /**
     * Format information bits of error correction level M.
     */
    protected const ECC_FORMAT_BITS = 0;

    protected int $version;

    protected int $size;

    /**
     * @var array<int, array<int, bool>>
     */
    protected array $modules = [];

    /**
     * @var array<int, array<int, bool>>
     */
    protected array $isFunction = [];

    public function __construct(string $data)
    {
        $this->version = $this->chooseVersion(strlen($data));
        $this->size = $this->version * 4 + 17;

        $row = array_fill(0, $this->size, false);
        $this->modules = array_fill(0, $this->size, $row);
        $this->isFunction = array_fill(0, $this->size, $row);

        $this->drawFunctionPatterns();
        $this->drawCodewords($this->addEccAndInterleave($this->dataCodewords($data)));

        $bestMask = 0;
        $minPenalty = PHP_INT_MAX;

        for ($mask = 0; $mask < 8; $mask++) {
            $this->applyMask($mask);
            $this->drawFormatBits($mask);

            $penalty = $this->penalty();

            if ($penalty < $minPenalty) {
                $bestMask = $mask;
                $minPenalty = $penalty;
            }

            $this->applyMask($mask);
        }

        $this->applyMask($bestMask);
        $this->drawFormatBits($bestMask);
    }

    /**
     * Encode text as an SVG QR code with a quiet zone of 4 modules.
     *
     * Without a size, each module gets a whole number of pixels (about 200px in all),
     * so every module renders equally wide and phone cameras read it reliably.
     */
    public static function svg(string $data, ?int $pixels = null, string $dark = '#000000', string $light = '#ffffff'): string
    {
        $qr = new static($data);

        $border = 4;
        $box = $qr->size + $border * 2;
        $pixels ??= $box * max(2, intdiv(200, $box));
        $path = '';

        foreach ($qr->matrix() as $y => $row) {
            foreach ($row as $x => $isDark) {
                if ($isDark) {
                    $path .= 'M'.($x + $border).','.($y + $border).'h1v1h-1z';
                }
            }
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$box.' '.$box.'" width="'.$pixels.'" height="'.$pixels.'" shape-rendering="crispEdges" role="img">'
            .'<rect width="100%" height="100%" fill="'.e($light).'"/>'
            .'<path d="'.$path.'" fill="'.e($dark).'"/>'
            .'</svg>';
    }

    /**
     * The modules, row by row (true is dark).
     *
     * @return array<int, array<int, bool>>
     */
    public function matrix(): array
    {
        return $this->modules;
    }

    public function version(): int
    {
        return $this->version;
    }

    protected function chooseVersion(int $length): int
    {
        for ($version = 1; $version <= 40; $version++) {
            $needed = 4 + ($version < 10 ? 8 : 16) + $length * 8;

            if ($needed <= $this->numDataCodewords($version) * 8) {
                return $version;
            }
        }

        throw new InvalidArgumentException('The text is too long for a QR code.');
    }

    /**
     * @return array<int, int>
     */
    protected function dataCodewords(string $data): array
    {
        $bits = '0100'.str_pad(decbin(strlen($data)), $this->version < 10 ? 8 : 16, '0', STR_PAD_LEFT);

        foreach (str_split($data) as $byte) {
            if ($byte !== '') {
                $bits .= str_pad(decbin(ord($byte)), 8, '0', STR_PAD_LEFT);
            }
        }

        $capacity = $this->numDataCodewords($this->version) * 8;

        $bits .= str_repeat('0', min(4, $capacity - strlen($bits)));
        $bits .= str_repeat('0', (8 - strlen($bits) % 8) % 8);

        $codewords = array_map('bindec', str_split($bits, 8));

        for ($pad = 0xEC; count($codewords) < $capacity / 8; $pad ^= 0xEC ^ 0x11) {
            $codewords[] = $pad;
        }

        return $codewords;
    }

    /**
     * @param  array<int, int>  $data
     * @return array<int, int>
     */
    protected function addEccAndInterleave(array $data): array
    {
        $numBlocks = self::ECC_BLOCKS[$this->version];
        $blockEccLength = self::ECC_CODEWORDS_PER_BLOCK[$this->version];
        $rawCodewords = intdiv($this->numRawDataModules($this->version), 8);
        $numShortBlocks = $numBlocks - $rawCodewords % $numBlocks;
        $shortBlockLength = intdiv($rawCodewords, $numBlocks);

        $divisor = $this->reedSolomonDivisor($blockEccLength);
        $blocks = [];

        for ($i = 0, $offset = 0; $i < $numBlocks; $i++) {
            $length = $shortBlockLength - $blockEccLength + ($i < $numShortBlocks ? 0 : 1);
            $block = array_slice($data, $offset, $length);
            $offset += $length;

            $ecc = $this->reedSolomonRemainder($block, $divisor);

            if ($i < $numShortBlocks) {
                $block[] = 0;
            }

            $blocks[] = array_merge($block, $ecc);
        }

        $result = [];

        for ($i = 0; $i < count($blocks[0]); $i++) {
            foreach ($blocks as $j => $block) {
                if ($i !== $shortBlockLength - $blockEccLength || $j >= $numShortBlocks) {
                    $result[] = $block[$i];
                }
            }
        }

        return $result;
    }

    protected function drawFunctionPatterns(): void
    {
        for ($i = 0; $i < $this->size; $i++) {
            $this->setFunction(6, $i, $i % 2 === 0);
            $this->setFunction($i, 6, $i % 2 === 0);
        }

        $this->drawFinder(3, 3);
        $this->drawFinder($this->size - 4, 3);
        $this->drawFinder(3, $this->size - 4);

        $positions = $this->alignmentPositions();
        $count = count($positions);

        foreach ($positions as $i => $x) {
            foreach ($positions as $j => $y) {
                $onFinder = ($i === 0 && $j === 0) || ($i === 0 && $j === $count - 1) || ($i === $count - 1 && $j === 0);

                if (! $onFinder) {
                    $this->drawAlignment($x, $y);
                }
            }
        }

        $this->drawFormatBits(0);
        $this->drawVersion();
    }

    protected function drawFinder(int $x, int $y): void
    {
        for ($dy = -4; $dy <= 4; $dy++) {
            for ($dx = -4; $dx <= 4; $dx++) {
                $distance = max(abs($dx), abs($dy));
                $xx = $x + $dx;
                $yy = $y + $dy;

                if ($xx >= 0 && $xx < $this->size && $yy >= 0 && $yy < $this->size) {
                    $this->setFunction($xx, $yy, $distance !== 2 && $distance !== 4);
                }
            }
        }
    }

    protected function drawAlignment(int $x, int $y): void
    {
        for ($dy = -2; $dy <= 2; $dy++) {
            for ($dx = -2; $dx <= 2; $dx++) {
                $this->setFunction($x + $dx, $y + $dy, max(abs($dx), abs($dy)) !== 1);
            }
        }
    }

    protected function drawFormatBits(int $mask): void
    {
        $data = self::ECC_FORMAT_BITS << 3 | $mask;
        $remainder = $data;

        for ($i = 0; $i < 10; $i++) {
            $remainder = ($remainder << 1) ^ (($remainder >> 9) * 0x537);
        }

        $bits = ($data << 10 | $remainder) ^ 0x5412;

        for ($i = 0; $i <= 5; $i++) {
            $this->setFunction(8, $i, $this->bit($bits, $i));
        }

        $this->setFunction(8, 7, $this->bit($bits, 6));
        $this->setFunction(8, 8, $this->bit($bits, 7));
        $this->setFunction(7, 8, $this->bit($bits, 8));

        for ($i = 9; $i < 15; $i++) {
            $this->setFunction(14 - $i, 8, $this->bit($bits, $i));
        }

        for ($i = 0; $i < 8; $i++) {
            $this->setFunction($this->size - 1 - $i, 8, $this->bit($bits, $i));
        }

        for ($i = 8; $i < 15; $i++) {
            $this->setFunction(8, $this->size - 15 + $i, $this->bit($bits, $i));
        }

        $this->setFunction(8, $this->size - 8, true);
    }

    protected function drawVersion(): void
    {
        if ($this->version < 7) {
            return;
        }

        $remainder = $this->version;

        for ($i = 0; $i < 12; $i++) {
            $remainder = ($remainder << 1) ^ (($remainder >> 11) * 0x1F25);
        }

        $bits = $this->version << 12 | $remainder;

        for ($i = 0; $i < 18; $i++) {
            $dark = $this->bit($bits, $i);
            $a = $this->size - 11 + $i % 3;
            $b = intdiv($i, 3);

            $this->setFunction($a, $b, $dark);
            $this->setFunction($b, $a, $dark);
        }
    }

    /**
     * @param  array<int, int>  $codewords
     */
    protected function drawCodewords(array $codewords): void
    {
        $total = count($codewords) * 8;
        $i = 0;

        for ($right = $this->size - 1; $right >= 1; $right -= 2) {
            if ($right === 6) {
                $right = 5;
            }

            for ($vertical = 0; $vertical < $this->size; $vertical++) {
                for ($j = 0; $j < 2; $j++) {
                    $x = $right - $j;
                    $upward = (($right + 1) & 2) === 0;
                    $y = $upward ? $this->size - 1 - $vertical : $vertical;

                    if (! $this->isFunction[$y][$x] && $i < $total) {
                        $this->modules[$y][$x] = $this->bit($codewords[$i >> 3], 7 - ($i & 7));
                        $i++;
                    }
                }
            }
        }
    }

    protected function applyMask(int $mask): void
    {
        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                $invert = match ($mask) {
                    0 => ($x + $y) % 2 === 0,
                    1 => $y % 2 === 0,
                    2 => $x % 3 === 0,
                    3 => ($x + $y) % 3 === 0,
                    4 => (intdiv($x, 3) + intdiv($y, 2)) % 2 === 0,
                    5 => ($x * $y) % 2 + ($x * $y) % 3 === 0,
                    6 => (($x * $y) % 2 + ($x * $y) % 3) % 2 === 0,
                    default => (($x + $y) % 2 + ($x * $y) % 3) % 2 === 0,
                };

                if ($invert && ! $this->isFunction[$y][$x]) {
                    $this->modules[$y][$x] = ! $this->modules[$y][$x];
                }
            }
        }
    }

    /**
     * Penalty score of the current modules (rules N1-N4 of the standard).
     */
    protected function penalty(): int
    {
        $size = $this->size;
        $penalty = 0;
        $dark = 0;

        $lines = [];

        for ($i = 0; $i < $size; $i++) {
            $row = '';
            $column = '';

            for ($j = 0; $j < $size; $j++) {
                $row .= $this->modules[$i][$j] ? '1' : '0';
                $column .= $this->modules[$j][$i] ? '1' : '0';
            }

            $lines[] = $row;
            $lines[] = $column;
            $dark += substr_count($row, '1');
        }

        foreach ($lines as $line) {
            preg_match_all('/0{5,}|1{5,}/', $line, $runs);

            foreach ($runs[0] as $run) {
                $penalty += 3 + strlen($run) - 5;
            }

            $penalty += 40 * (preg_match_all('/(?=10111010000|00001011101)/', $line));
        }

        for ($y = 0; $y < $size - 1; $y++) {
            for ($x = 0; $x < $size - 1; $x++) {
                $color = $this->modules[$y][$x];

                if ($color === $this->modules[$y][$x + 1] && $color === $this->modules[$y + 1][$x] && $color === $this->modules[$y + 1][$x + 1]) {
                    $penalty += 3;
                }
            }
        }

        $total = $size * $size;
        $penalty += (int) (ceil(abs($dark * 20 - $total * 10) / $total) - 1) * 10;

        return $penalty;
    }

    /**
     * @return array<int, int>
     */
    protected function alignmentPositions(): array
    {
        if ($this->version === 1) {
            return [];
        }

        $count = intdiv($this->version, 7) + 2;
        $step = intdiv($this->version * 8 + $count * 3 + 5, $count * 4 - 4) * 2;
        $positions = [];

        for ($i = 0, $position = $this->size - 7; $i < $count - 1; $i++, $position -= $step) {
            array_unshift($positions, $position);
        }

        array_unshift($positions, 6);

        return $positions;
    }

    protected function numRawDataModules(int $version): int
    {
        $result = (16 * $version + 128) * $version + 64;

        if ($version >= 2) {
            $alignments = intdiv($version, 7) + 2;
            $result -= (25 * $alignments - 10) * $alignments - 55;

            if ($version >= 7) {
                $result -= 36;
            }
        }

        return $result;
    }

    protected function numDataCodewords(int $version): int
    {
        return intdiv($this->numRawDataModules($version), 8)
            - self::ECC_CODEWORDS_PER_BLOCK[$version] * self::ECC_BLOCKS[$version];
    }

    /**
     * @return array<int, int>
     */
    protected function reedSolomonDivisor(int $degree): array
    {
        $result = array_fill(0, $degree, 0);
        $result[$degree - 1] = 1;
        $root = 1;

        for ($i = 0; $i < $degree; $i++) {
            for ($j = 0; $j < $degree; $j++) {
                $result[$j] = $this->gfMultiply($result[$j], $root);

                if ($j + 1 < $degree) {
                    $result[$j] ^= $result[$j + 1];
                }
            }

            $root = $this->gfMultiply($root, 0x02);
        }

        return $result;
    }

    /**
     * @param  array<int, int>  $data
     * @param  array<int, int>  $divisor
     * @return array<int, int>
     */
    protected function reedSolomonRemainder(array $data, array $divisor): array
    {
        $result = array_fill(0, count($divisor), 0);

        foreach ($data as $byte) {
            $factor = $byte ^ array_shift($result);
            $result[] = 0;

            foreach ($divisor as $i => $coefficient) {
                $result[$i] ^= $this->gfMultiply($coefficient, $factor);
            }
        }

        return $result;
    }

    protected function gfMultiply(int $x, int $y): int
    {
        $z = 0;

        for ($i = 7; $i >= 0; $i--) {
            $z = ($z << 1) ^ (($z >> 7) * 0x11D);
            $z ^= (($y >> $i) & 1) * $x;
        }

        return $z;
    }

    protected function setFunction(int $x, int $y, bool $dark): void
    {
        $this->modules[$y][$x] = $dark;
        $this->isFunction[$y][$x] = true;
    }

    protected function bit(int $value, int $index): bool
    {
        return (($value >> $index) & 1) !== 0;
    }
}
