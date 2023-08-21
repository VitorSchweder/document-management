<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\Type as TypeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class TypeController extends Controller
{
    protected TypeRepository $repository;

    public function __construct(TypeRepository $typeRepository) 
    {
        $this->repository = $typeRepository;
    }

    public function index()
    {
        return response()->json([
            'data' => TypeResource::collection($this->repository->all()),
        ], Response::HTTP_OK);
    }

    public function store(TypeRequest $request)
    {
        try {
            $model = $this->repository->create($request->only(['name']));
            
            return response()->json([
                'data' => new TypeResource($model),
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
                'data' => new TypeResource($model),
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Data not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $exception){
            log::error('An error occurred while showing data: '. $exception);
            return response()->json(['error' => 'An error occurred while showing data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(TypeRequest $request, int $id)
    {
        try {
            $model = $this->repository->byId($id);
            $model->fill($request->only(['name']))->update();
            return response()->json([
                'data' => new TypeResource($model),
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Data not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
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
        } catch (Exception $exception) {
            log::error('An error occurred while deleting data: '. $exception);
            return response()->json(['error' => 'An error occurred while deleting data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
