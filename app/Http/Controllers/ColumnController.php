<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ColumnRequest;
use App\Http\Resources\ColumnResource;
use App\Models\Column;
use App\Repositories\Column as ColumnRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ColumnController extends Controller
{
    protected ColumnRepository $repository;

    public function __construct(ColumnRepository $columnRepository) 
    {
        $this->repository = $columnRepository;
    }

    public function index()
    {
        return response()->json([
            'data' => ColumnResource::collection($this->repository->all()),
        ], Response::HTTP_OK);
    }

    public function store(ColumnRequest $request)
    {
        try {
            $model = $this->repository->create($request->only(['name', 'type_id']));
            
            return response()->json([
                'data' => new ColumnResource($model),
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            log::error('An error occurred while creating data: '.$exception);
            return response()->json(['error' => 'An error occurred while creating data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id)
    {
        try {
            $model = $this->repository->byId($id);
            return response()->json([
                'data' => new ColumnResource($model),
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Data not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $exception){
            log::error('An error occurred while showing data: '. $exception);
            return response()->json(['error' => 'An error occurred while showing data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(ColumnRequest $request, int $id)
    {
        try {
            $model = $this->repository->byId($id);
            $model->fill($request->only(['name', 'type_id']))->update();
            return response()->json([
                'data' => new ColumnResource($model),
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Data not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $exception){
            log::error('An error occurred while updating data: '. $exception);
            return response()->json(['error' => 'An error occurred while updating data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id)
    {
        try {
            $model = $this->repository->byId($id);
            $model->delete();
            return response()->json([
                'data' => [],
            ], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Data not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $exception){
            log::error('An error occurred while deleting data: '. $exception);
            return response()->json(['error' => 'An error occurred while deleting data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
