<?php

namespace SuperAdmin\Admin\Form\Field\Traits;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageField
{
    /**
     * Intervention calls.
     *
     * @var array
     */
    protected $interventionCalls = [];

    /**
     * Thumbnail settings.
     *
     * @var array
     */
    protected $thumbnails = [];

    /**
     * Default directory for file to upload.
     *
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.directory.image');
    }

    /**
     * Check if intervention/image is available.
     *
     * @return bool
     */
    protected function interventionImageAvailable()
    {
        return class_exists(\Intervention\Image\ImageManager::class);
    }

    /**
     * Create an intervention image instance.
     *
     * @param  mixed  $source
     * @return mixed
     */
    protected function makeInterventionImage($source)
    {
        $manager = new \Intervention\Image\ImageManager(
            new \Intervention\Image\Drivers\Gd\Driver()
        );

        return $manager->read($source);
    }

    /**
     * Execute Intervention calls.
     *
     * @param  string  $target
     * @return mixed
     */
    public function callInterventionMethods($target)
    {
        if (! empty($this->interventionCalls)) {
            $image = $this->makeInterventionImage($target);

            foreach ($this->interventionCalls as $call) {
                call_user_func_array(
                    [$image, $call['method']],
                    $call['arguments']
                );
            }

            $image->save($target);
        }

        return $target;
    }

    /**
     * Call intervention methods.
     *
     * @param  string  $method
     * @param  array  $arguments
     * @return $this
     *
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        if (static::hasMacro($method)) {
            return $this;
        }

        if (! $this->interventionImageAvailable()) {
            throw new \Exception('To use image handling and manipulation, please install [intervention/image] first.');
        }

        $this->interventionCalls[] = [
            'method' => $method,
            'arguments' => $arguments,
        ];

        return $this;
    }

    /**
     * Render a image form field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->options(['allowedFileTypes' => ['image'], 'msgPlaceholder' => trans('admin.choose_image')]);

        return parent::render();
    }

    /**
     * @param  string|array  $name
     * @return $this
     */
    public function thumbnail($name, ?int $width = null, ?int $height = null)
    {
        if (func_num_args() == 1 && is_array($name)) {
            foreach ($name as $key => $size) {
                if (count($size) >= 2) {
                    $this->thumbnails[$key] = $size;
                }
            }
        } elseif (func_num_args() == 3) {
            $this->thumbnails[$name] = [$width, $height];
        }

        return $this;
    }

    /**
     * @param  string|array  $name
     * @param  callable  $function
     * @return $this
     */
    public function thumbnailFunction($name, \Closure $function)
    {
        $this->thumbnails[$name] = $function;

        return $this;
    }

    /**
     * Destroy original thumbnail files.
     *
     * @return void.
     */
    public function destroyThumbnail($delete_all = false)
    {
        if ($this->retainable) {
            return;
        }

        foreach ($this->thumbnails as $name => $_) {
            if (is_array($this->original)) {
                if (empty($this->original)) {
                    continue;
                }
                if ($delete_all) {
                    foreach ($this->original as $original) {
                        $this->destroyThumbnailFile($original, $name);
                    }
                }
            } else {
                $this->destroyThumbnailFile($this->original, $name);
            }
        }
    }

    /**
     * Remove thumbnail file from disk.
     *
     * @return void.
     */
    public function destroyThumbnailFile($original, $name)
    {
        $ext = @pathinfo($original, PATHINFO_EXTENSION);

        // We remove extension from file name so we can append thumbnail type
        $path = @Str::replaceLast('.'.$ext, '', $original);

        // We merge original name + thumbnail name + extension
        $path = $path.'-'.$name.'.'.$ext;

        if ($this->storage->exists($path)) {
            $this->storage->delete($path);
        }
    }

    /**
     * Upload file and delete original thumbnail files.
     *
     *
     * @return $this
     */
    protected function uploadAndDeleteOriginalThumbnail(UploadedFile $file)
    {
        foreach ($this->thumbnails as $name => $size_or_closure) {
            // We need to get extension type ( .jpeg , .png ...)
            $ext = pathinfo($this->name, PATHINFO_EXTENSION);

            // We remove extension from file name so we can append thumbnail type
            $path = Str::replaceLast('.'.$ext, '', $this->name);

            // We merge original name + thumbnail name + extension
            $path = $path.'-'.$name.'.'.$ext;

            $image = $this->makeInterventionImage($file);

            if ($size_or_closure instanceof \Closure) {
                $image = $size_or_closure->call($this, $image);
            } else {
                $size = $size_or_closure;
                $action = $size[2] ?? 'resize';
                $image->$action($size[0], $size[1]);
            }

            $encoded = $image->encodeByExtension($ext);

            if (! is_null($this->storagePermission)) {
                $this->storage->put("{$this->getDirectory()}/{$path}", (string) $encoded, $this->storagePermission);
            } else {
                $this->storage->put("{$this->getDirectory()}/{$path}", (string) $encoded);
            }
        }

        $this->destroyThumbnail();

        return $this;
    }
}
