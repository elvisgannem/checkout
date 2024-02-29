<?php

namespace App\Http\Controllers;

use App\Queries\Products\ProductQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function __construct(
        private readonly ProductQuery $productQuery
    )
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->productQuery->getAllProducts());
    }
}
