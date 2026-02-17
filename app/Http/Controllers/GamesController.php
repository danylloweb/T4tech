<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Http\Requests\GameCreateRequest;
use App\Http\Requests\GameUpdateRequest;
use App\Validators\GameValidator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class GamesController.
 *
 * @package namespace App\Http\Controllers;
 */
class GamesController extends Controller
{
    use AuthorizesRequests;

    /**
     * @var GameService
     */
    protected $service;

    /**
     * @var GameValidator
     */
    protected $validator;

    /**
     * GamesController constructor.
     *
     * @param GameService $service
     * @param GameValidator $validator
     */
    public function __construct(GameService $service, GameValidator $validator)
    {
        $this->service   = $service;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function index(Request $request): JsonResponse
    {
        $limit     = $request->query('limit', 15);
        $cacheName = $request->fullUrl();
        $objects   = Cache::store('redis')->tags('games')
            ->remember($cacheName, 12000, function () use ($limit) {
                return $this->service->all($limit);
            });
        return response()->json($objects, 200);
    }

    /**
     * @param GameCreateRequest $request
     * @return JsonResponse
     */
    public function store(GameCreateRequest $request): JsonResponse
    {
        try {
            return response()->json($this->service->create($request->all()));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception);
        }
    }


    /**
     * @param GameUpdateRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(GameUpdateRequest $request, $id): JsonResponse
    {
        try {
            return response()->json($this->service->update($request->all(), $id));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception);
        }
    }



}
