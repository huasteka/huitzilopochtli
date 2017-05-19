<?php
namespace App\Http\Controllers;

use App\Product;
use App\Services\JsonResponseFormatter;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function create(Request $request)
    {
        
    }

    public function update(Request $request, $productId)
    {
        
    }

    public function destroy($productId)
    {
        
    }

    public function findOne($productId)
    {
        return $this->withJson(new JsonResponseFormatter(Product::find($productId)));
    }

    public function findAll()
    {
        return $this->withJson(new JsonResponseFormatter(Product::all()));
    }

}
