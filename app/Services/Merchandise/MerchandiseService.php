<?php

namespace App\Services\Merchandise;

use App\Merchandise;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @method MerchandiseValidator getValidator
 * @method MerchandiseRepository getRepository
 */
final class MerchandiseService extends AbstractService
{
    
    public function __construct(MerchandiseValidator $validator, MerchandiseRepository $repository)
    {
        parent::__construct($validator, $repository);
    }

    /**
     * @param Request $request
     * @return Model
     */
    function create(Request $request)
    {
        return $this->getRepository()->create($request);
    }

    /**
     * @param Request $request
     * @param Merchandise $model
     * @return void
     */
    function update(Request $request, $model)
    {
        $this->getRepository()->update($request, $model);
    }

}
