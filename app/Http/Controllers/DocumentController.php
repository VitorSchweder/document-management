<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\Document as DocumentRepository;
use PDF;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class DocumentController extends Controller
{
    protected DocumentRepository $repository;

    public function __construct(DocumentRepository $documentRepository) 
    {
        $this->repository = $documentRepository;
    }

    public function index()
    {
        return response()->json([
            'data' => DocumentResource::collection($this->repository->all()),
        ], Response::HTTP_OK);
    }

    public function store(DocumentRequest $request)
    {
        try {
            $model = Document::create($request->only(['name', 'type_id']));
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
            if ($request->has('content') && is_array($request->content)) {
                foreach($request->content as $content){
                    $data[$content['column_id']] = ['content' => $content['text']];
                }

                $model->columns()->sync($data);
            }

            return response()->json([
                'data' => new DocumentResource($model),
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            log::error('An error occurred while creating data: '. $exception);
            return response()->json(['error' => 'An error occurred while creating data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id)
    {
        try {
            $model = $this->repository->byId($id);
            return response()->json([
                'data' => new DocumentResource($model),
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Data not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            log::error('An error occurred while showing data: '. $exception);
            return response()->json(['error' => 'An error occurred while showing data'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(DocumentRequest $request, int $id)
    {
        try {
            $model = $this->repository->byId($id);
            
            if ($request->has('content')) {
                $data = [];

                if ($request->has('content') && is_array($request->content)) {
                    foreach($request->content as $content){
                        $data[$content['column_id']] = ['content' => $content['text']];
                    }
                }

                $model->columns()->sync($data);
            }

            return response()->json([
                'data' => new DocumentResource($model),
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
            $model->columns->detach();
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

    public function download(int $id)
    {
        try {
            $data = [
                'document' => $this->repository->byId($id)
            ];
            
            $pdf = PDF::loadView('pdf-document', $data);
            return $pdf->download('pdf-document.pdf');
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Data not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            log::error('An error occurred while downloading file: '. $exception);
            return response()->json(['error' => 'An error occurred while downloading file'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
