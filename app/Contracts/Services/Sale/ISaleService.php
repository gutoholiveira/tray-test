<?php

namespace App\Contracts\Services\Sale;

use App\Http\Requests\Sale\StoreRequest;
use App\Http\Requests\Sale\UpdateRequest;
use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

interface ISaleService
{
    /**
     * Get all sales instances
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Store a new sale register
     *
     * @param StoreRequest $request
     * @return Sale
     */
    public function store(StoreRequest $request): Sale;

    /**
     * Update an existing sale register
     *
     * @param Sale $sale
     * @param UpdateRequest $request
     * @return Sale
     */
    public function update(Sale $sale, UpdateRequest $request): Sale;

    /**
     * Delete an existing sale register
     *
     * @param Sale $sale
     * @return void
     */
    public function delete(Sale $sale): void;

    /**
     * Get seller sales
     *
     * @param Seller $seller
     * @return Collection
     */
    public function getBySeller(Seller $seller): Collection;
}
