<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected $service;
    protected $validator;


    /**
     * Set the service instance for testing
     *
     * @param mixed $service
     * @return void
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return response()->json($this->service->all($request->query->get('limit', 15)));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception);
        }
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            return response()->json($this->service->find($id));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception,404);
        }
    }


    /**
     * Restore the specified resource from storage.
     * @param $id
     * @return JsonResponse
     */
    public function restore($id): JsonResponse
    {
        try {
            return response()->json($this->service->restore($id));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception,404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            return response()->json($this->service->delete($id));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception,404);
        }
    }


    /**
     * @param Exception $exception
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function sendBadResponse(Exception $exception, int $statusCode = 422): JsonResponse
    {
        $error = [
            'error' => 'true',
            'message' => $exception->getMessage()?: 'Erro ao processar a requisição',
        ];

        try {
            return response()->json($error, $statusCode);
        } catch (Exception $exception) {
            return response()->json($error, 406);
        }
    }

}
