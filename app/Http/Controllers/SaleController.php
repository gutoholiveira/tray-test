<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\StoreRequest;
use App\Http\Requests\Sale\UpdateRequest;
use App\Http\Resources\Sale\SaleResource;
use App\Models\Sale;
use App\Models\Seller;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private SaleService $sale_service) {}

    /**
     * Display a listing of the resource
     *
     * @return JsonResponse
     */
    public function index()
    {
        $sales = $this->sale_service->getAll();

        return response()->json(['message' => 'success', 'data' => ['sales' => SaleResource::collection($sales)]], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $sale = $this->sale_service->store($request);

        return response()->json(['message' => 'success', 'data' => ['sale' => new SaleResource($sale)]], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource
     *
     * @param Sale $sale
     * @return JsonResponse
     */
    public function show(Sale $sale): JsonResponse
    {
        return response()->json(['message' => 'success', 'data' => ['sale' => new SaleResource($sale)]], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage
     *
     * @param UpdateRequest $request
     * @param Sale $sale
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Sale $sale): JsonResponse
    {
        $updated_sale = $this->sale_service->update($sale, $request);

        return response()->json(['message' => 'success', 'data' => ['sale' => new SaleResource($updated_sale)]], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param Sale $sale
     * @return JsonResponse
     */
    public function destroy(Sale $sale): JsonResponse
    {
        $this->sale_service->delete($sale);

        return response()->json(['message' => 'success', 'data' => []], Response::HTTP_OK);
    }

    /**
     * Get sales by seller
     *
     * @param Seller $seller
     * @return JsonResponse
     */
    public function getSalesBySeller(Seller $seller): JsonResponse
    {
        $sales = $this->sale_service->getBySeller($seller);

        return response()->json(['message' => 'success', 'data' => ['sales' => $sales]], Response::HTTP_OK);
    }
}
