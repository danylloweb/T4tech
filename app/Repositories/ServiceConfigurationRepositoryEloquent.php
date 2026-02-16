<?php

namespace App\Repositories;

use App\Presenters\ServiceConfigurationPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ServiceConfigurationRepository;
use App\Entities\ServiceConfiguration;
use App\Validators\ServiceConfigurationValidator;

/**
 * Class ServiceConfigurationRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ServiceConfigurationRepositoryEloquent extends AppRepository implements ServiceConfigurationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ServiceConfiguration::class;
    }

    /**
    * Specify Validator class name
    *
    * @return string
    */
    public function validator(): string
    {
        return ServiceConfigurationValidator::class;
    }

    /**
     * @return string
     */
   public function presenter(): string
   {
       return ServiceConfigurationPresenter::class;
    }
}
