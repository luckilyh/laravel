<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * User constructor.
     * per_page 自定义每页显示条数
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->perPage = request('per_page') ?? 15;
    }
}
