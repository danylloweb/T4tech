<?php

namespace App\Http\Controllers;

use App\Services\TeamService;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Validators\TeamValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class TeamsController.
 *
 * @package namespace App\Http\Controllers;
 */
class TeamsController extends Controller
{
    use AuthorizesRequests;

    /**
     * @var TeamService
     */
    protected $service;

    /**
     * @var TeamValidator
     */
    protected $validator;

    /**
     * TeamsController constructor.
     *
     * @param TeamService $service
     * @param TeamValidator $validator
     */
    public function __construct(TeamService $service, TeamValidator $validator)
    {
        $this->service = $service;
        $this->validator  = $validator;
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
        $objects   = Cache::store('redis')->tags('teams')
            ->remember($cacheName, 12000, function () use ($limit) {
                return $this->service->all($limit);
            });
        return response()->json($objects, 200);
    }

    /**
     * @param TeamCreateRequest $request
     * @return JsonResponse
     */
    public function store(TeamCreateRequest $request): JsonResponse
    {
        try {
            return response()->json($this->service->create($request->all()));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception);
        }
    }

    /**
     * @param TeamUpdateRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(TeamUpdateRequest $request, $id): JsonResponse
    {
        try {
            return response()->json($this->service->update($request->all(), $id));
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $team = $this->service->find($id);
            $this->authorize('delete', $team);
            $this->service->delete($id);
            return response()->json(['message' => 'Time deletado com sucesso'], 200);
        } catch (Exception $exception) {
            return $this->sendBadResponse($exception);
        }
    }

}
