<?php
namespace app\common\model;

class ModelField extends Common
{
    protected $table = 'egg_model_field';

    public function get_all_category()
    {
        return $this->all();
    }

}