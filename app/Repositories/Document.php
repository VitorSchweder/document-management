<?php

namespace App\Repositories;

use App\Models\Document as DocumentModel;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;

class Document extends BaseRepository
{
    public function __construct()
    {
        $this->model = new DocumentModel();
    }
}