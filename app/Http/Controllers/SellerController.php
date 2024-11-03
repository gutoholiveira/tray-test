<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\StoreRequest;
use App\Http\Requests\Seller\UpdateRequest;
use App\Http\Resources\Seller\SellerResource;
use App\Models\Seller;
use App\Services\SellerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SellerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private SellerService $seller_service) {}

    /**
     * Display a listing of the resource
     *
     * @return JsonResponse
     */
    public function index()
    {
        $sellers = $this->seller_service->getAll();

        return response()->json(['message' => 'success', 'data' => ['sellers' => SellerResource::collection($sellers)]], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $seller = $this->seller_service->store($request);

        return response()->json(['message' => 'success', 'data' => ['seller' => new SellerResource($seller)]], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource
     *
     * @param Seller $seller
     * @return JsonResponse
     */
    public function show(Seller $seller): JsonResponse
    {
        return response()->json(['message' => 'success', 'data' => ['seller' => new SellerResource($seller)]], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage
     *
     * @param UpdateRequest $request
     * @param Seller $seller
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Seller $seller): JsonResponse
    {
        $updated_seller = $this->seller_service->update($seller, $request);

        return response()->json(['message' => 'success', 'data' => ['seller' => new SellerResource($updated_seller)]], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param Seller $seller
     * @return JsonResponse
     */
    public function destroy(Seller $seller): JsonResponse
    {
        $this->seller_service->delete($seller);

        return response()->json(['message' => 'success', 'data' => []], Response::HTTP_OK);
    }

    /**
     * Send Sales report by email
     *
     * @param Seller $seller
     * @return void
     */
    public function sendReport(Seller $seller): void
    {
        $this->seller_service->sendMail($seller);
    }
}
