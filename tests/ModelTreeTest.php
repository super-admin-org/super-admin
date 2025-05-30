<?php

use Tests\Models\Tree;

class ModelTreeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_select_options()
    {
        $rootText = 'Root Text';

        $options = Tree::selectOptions(function ($query) {
            return $query->where('uri', '');
        }, $rootText);

        $count = Tree::query()->where('uri', '')->count();

        $this->assertEquals(array_shift($options), $rootText);
        $this->assertEquals(count($options), $count);
    }
}
