<?php

use Tests\Models\Tree;

class ModelTreeFeatureTest extends TestCase
{
    public function test_select_options_root()
    {
        $rootText = 'Root Text';

        $options = Tree::selectOptions(function ($query) {
            return $query->where('uri', '');
        }, $rootText);

        $count = Tree::query()->where('uri', '')->count();

        $this->assertEquals($rootText, array_shift($options));
        $this->assertEquals($count, count($options));
    }
}
