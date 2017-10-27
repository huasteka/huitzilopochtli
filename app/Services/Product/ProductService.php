<?php

namespace App\Services\Product;

use App\Product;
use App\Services\AbstractService;
use Illuminate\Http\Request;

/**
 * @method ProductValidator getValidator
 * @method ProductRepository getRepository
 */
final class ProductService extends AbstractService
{

    public function __construct(ProductValidator $validator, ProductRepository $repository)
    {
        parent::__construct($validator, $repository);
    }

    /**
     * @param Request $request
     * @return Product
     */
    public function create(Request $request)
    {
        return $this->getRepository()->create($request);
    }

    /**
     * @param Request $request
     * @param Product $product
     */
    public function update(Request $request, $product)
    {
        $this->getRepository()->update($request, $product);
    }

}
