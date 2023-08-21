<?php

namespace App\Repositories;

use App\Models\Column as ColumnModel;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;

class Column extends BaseRepository
{
    public function __construct()
    {
        $this->model = new ColumnModel();
    }
}