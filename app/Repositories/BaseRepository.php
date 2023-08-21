<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    public function all()
    {
        return $this->model->all();
    }

    public function byId($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function delete($id)
    {
        $model = $this->model::findOrFail($id);
        return $model->delete();
    }

    public function update($data, $id)
    {
        $model = $this->model::findOrFail($id);
        $model->fill($data)->save();

        return $model;
    }
}