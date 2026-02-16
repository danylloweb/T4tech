<?php

namespace App\Presenters;

use App\Transformers\ServiceConfigurationTransformer;
use League\Fractal\TransformerAbstract;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ServiceConfigurationPresenter.
 *
 * @package namespace App\Presenters;
 */
class ServiceConfigurationPresenter extends FractalPresenter
{
    /**
     * @return ServiceConfigurationTransformer|TransformerAbstract
     */
    public function getTransformer():ServiceConfigurationTransformer|TransformerAbstract
    {
        return new ServiceConfigurationTransformer();
    }
}
