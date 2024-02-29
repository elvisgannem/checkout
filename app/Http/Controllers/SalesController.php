<?php

namespace App\Http\Controllers;

use App\Commands\Sales\SaleCommand;
use App\Http\Requests\SaleFormRequest;
use App\Queries\Sales\SaleQuery;
use Illuminate\Http\JsonResponse;

class SalesController extends Controller
{
    public function __construct(
        private readonly SaleQuery $saleQuery,
        private readonly SaleCommand $saleCommand
    )
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->saleQuery->getProductsFromSale());
    }

    public function show(int $saleId): JsonResponse
    {
        return response()->json($this->saleQuery->getProductsFromSale($saleId));
    }

    public function store(SaleFormRequest $request): JsonResponse
    {
        return response()->json($this->saleCommand->createSaleWithProducts($request->products), 201);
    }

    public function destroy(int $saleId): JsonResponse
    {
        $this->saleCommand->deleteSale($saleId);
        return response()->json(['message' => 'Successfully canceled']);
    }
}
