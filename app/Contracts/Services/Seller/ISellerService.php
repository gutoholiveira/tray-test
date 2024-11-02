<?php

namespace App\Contracts\Services\Seller;

use App\Http\Requests\Seller\StoreRequest;
use App\Http\Requests\Seller\UpdateRequest;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

interface ISellerService
{
    /**
     * Get all seller instances
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Store a new seller register
     *
     * @param StoreRequest $request
     * @return Seller
     */
    public function store(StoreRequest $request): Seller;

    /**
     * Update an existing seller register
     *
     * @param Seller $seller
     * @param UpdateRequest $request
     * @return Seller
     */
    public function update(Seller $seller, UpdateRequest $request): Seller;

    /**
     * Delete an existing seller register
     *
     * @param Seller $seller
     * @return void
     */
    public function delete(Seller $seller): void;

    /**
     * Send an email with the sales data.
     *
     * @param Seller $seller
     * @return void
     */
    public function sendMail(Seller $seller): void;

    /**
     * Routine to send an email to each seller
     *
     * @return void
     */
    public function sendDailyReportRoutine(): void;
}
