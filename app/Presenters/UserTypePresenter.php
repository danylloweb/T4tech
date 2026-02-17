<?php

namespace App\Presenters;

use App\Transformers\UserTypeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserTypePresenter.
 *
 * @package namespace App\Presenters;
 */
class UserTypePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserTypeTransformer();
    }
}
