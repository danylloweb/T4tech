<?php

namespace App\Http\Controllers;

use App\Services\PlayerService;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PlayerCreateRequest;
use App\Http\Requests\PlayerUpdateRequest;
use App\Validators\PlayerValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class PlayersController.
 *
 * @package namespace App\Http\Controllers;
 */
class PlayersController extends Controller
{
    use AuthorizesRequests;

    /**
     * @var PlayerService
     */
    protected $service;

    /**
     * @var PlayerValidator
     */
    protected $validator;

    /**
     * PlayersController constructor.
     *
     * @param PlayerService $service
     * @param PlayerValidator $validator
     */
    public function __construct(PlayerService $service, PlayerValidator $validator)
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
        $objects   = Cache::store('redis')->tags('players')
            ->remember($cacheName, 12000, function () use ($limit) {
                return $this->service->all($limit);
            });
        return response()->json($objects, 200);
    }

    /**
     * @param PlayerCreateRequest $request
     * @return JsonResponse
     */
    public function store(PlayerCreateRequest $request): JsonResponse
    {
        try {
            return response()->json($this->service->create($request->all()));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception);
        }
    }


    /**
     * @param PlayerUpdateRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(PlayerUpdateRequest $request, $id): JsonResponse
    {
        try {
            return response()->json($this->service->update($request->all(), $id));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception);
        }
    }


}
