<?php

namespace App\Http\Controllers;

use App\Services\JsonGeneratorService;
use App\Services\ServiceConfigurationService;
use App\Validators\ServiceConfigurationValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class ServiceConfigurationsController.
 *
 * @package namespace App\Http\Controllers;
 */
class ServiceConfigurationsController extends Controller
{
    /**
     * @var ServiceConfigurationService
     */
    protected $service;

    /**
     * @var ServiceConfigurationValidator
     */
    protected $validator;
    /**
     * @var JsonGeneratorService
     */
    protected JsonGeneratorService $jsonGeneratorService;

    /**
     * @param ServiceConfigurationService   $service
     * @param ServiceConfigurationValidator $validator
     * @param JsonGeneratorService          $jsonGeneratorService
     */
    public function __construct(ServiceConfigurationService   $service,
                                ServiceConfigurationValidator $validator,
                                JsonGeneratorService          $jsonGeneratorService)
    {
        $this->service              = $service;
        $this->validator            = $validator;
        $this->jsonGeneratorService = $jsonGeneratorService;
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
        $objects   = Cache::store('redis')->tags('serviceConfigurations')
            ->remember($cacheName, 120000, function () use ($limit) {
                return $this->service->all($limit);
            });
        return response()->json($objects, 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateJson(Request $request): JsonResponse
    {
        return response()->json($this->jsonGeneratorService->generatejson($request->all()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listOneOffServices(Request $request): JsonResponse
    {
        $oneOffServices = $this->jsonGeneratorService->all($request->query->get('limit', 15));
        $oneOffServices = json_encode($oneOffServices);
        $response = $this->convertPaginationResponse(json_decode($oneOffServices, true));
        return response()->json($response);
    }

}
