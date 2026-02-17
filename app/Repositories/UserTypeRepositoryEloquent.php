<?php

namespace App\Repositories;

use App\Presenters\UserTypePresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserTypeRepository;
use App\Entities\UserType;
use App\Validators\UserTypeValidator;

/**
 * Class UserTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserTypeRepositoryEloquent extends BaseRepository implements UserTypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserType::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return UserTypeValidator::class;
    }

    public function presenter()
    {
        return UserTypePresenter::class;
    }

}
