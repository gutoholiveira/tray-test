<?php

namespace App\Services;

use App\Contracts\Services\Seller\ISellerService;
use App\Http\Requests\Seller\StoreRequest;
use App\Http\Requests\Seller\UpdateRequest;
use App\Mail\Seller\DailyReport;
use App\Models\Sale;
use App\Models\Seller;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class SellerService implements ISellerService
{
    public function getAll(): Collection
    {
        return Seller::all();
    }

    public function store(StoreRequest $request): Seller
    {
        try {
            return Seller::create([
                Seller::NAME  => $request[Seller::NAME],
                Seller::EMAIL => $request[Seller::EMAIL],
            ]);
        } catch (Exception $e) {
            throw new Exception('Error on create seller!');
        }
    }

    public function update(Seller $seller, UpdateRequest $request): Seller
    {
        try {
            $seller->update([
                Seller::NAME  => $request[Seller::NAME],
                Seller::EMAIL => $request[Seller::EMAIL],
            ]);

            return $seller->refresh();
        } catch (Exception $e) {
            throw new Exception('Error on update seller!');
        }
    }

    public function delete(Seller $seller): void
    {
        try {
            $seller->delete();
        } catch (Exception $e) {
            throw new Exception('Error on delete seller!');
        }
    }

    public function sendMail(Seller $seller): void
    {
        $sales = $seller->sales()
            ->where(Sale::DATE, date('Y-m-d'))
            ->get();

        Mail::to($seller->email)->send(new DailyReport($seller->name, [
            'sales_count' => $sales->count(),
            'sales_value' => $sales->sum(Sale::VALUE),
            'commission'  => $sales->sum(Sale::COMMISSION),
            'date'        => date("d-m-Y"),
        ]));
    }

    public function sendDailyReportRoutine(): void
    {
        $sellers = Seller::all();

        $sellers->each(
            fn ($seller) => $this->sendMail($seller)
        );
    }
}
