<?php

namespace App\Repositories;

use App\Models\Type as TypeModel;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;

class Type extends BaseRepository
{
    public function __construct()
    {
        $this->model = new TypeModel();
    }
}