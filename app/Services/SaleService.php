<?php

namespace App\Services;

use App\Contracts\Services\Sale\ISaleService;
use App\Http\Requests\Sale\StoreRequest;
use App\Http\Requests\Sale\UpdateRequest;
use App\Models\Sale;
use App\Models\Seller;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class SaleService implements ISaleService
{
    public function getAll(): Collection
    {
        return Sale::with(['seller:id,name'])
            ->get();
    }

    public function store(StoreRequest $request): Sale
    {
        try {
            $sale = Sale::create([
                Sale::SELLER_ID => $request[Sale::SELLER_ID],
                Sale::VALUE     => $request[Sale::VALUE],
                Sale::COMISSION => $request[Sale::VALUE] * 0.085,
                Sale::DATE      => $request[Sale::DATE],
            ]);

            $sale->seller = $sale->seller;

            return $sale;
        } catch (Exception $e) {
            throw new Exception('Error on create sale!');
        }
    }

    public function update(Sale $sale, UpdateRequest $request): Sale
    {
        try {
            $sale->update([
                Sale::SELLER_ID => $request[Sale::SELLER_ID],
                Sale::VALUE     => $request[Sale::VALUE],
                Sale::COMISSION => $request[Sale::VALUE] * 0.085,
                Sale::DATE      => $request[Sale::DATE],
            ]);

            $sale         = $sale->refresh();
            $sale->seller = $sale->seller;

            return $sale;
        } catch (Exception $e) {
            throw new Exception('Error on update sale!');
        }
    }

    public function delete(Sale $sale): void
    {
        try {
            $sale->delete();
        } catch (Exception $e) {
            throw new Exception('Error on delete sale!');
        }
    }

    public function getBySeller(Seller $seller): Collection
    {
        return Sale::where(Sale::SELLER_ID, $seller->id)
            ->with(['seller:id,name'])
            ->get();
    }
}
