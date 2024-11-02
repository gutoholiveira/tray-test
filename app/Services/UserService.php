<?php

namespace App\Services;

use App\Contracts\Services\User\IUserService;
use App\Mail\Admin\DailyReport;
use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserService implements IUserService
{
    public function sendMail(User $user): void
    {
        $sales = Sale::where(Sale::DATE, date('Y-m-d'))
            ->get();

        $sellers = Seller::all();

        $sellers_sales = [];

        foreach ($sellers as $key => $seller) {
            $sellers_sales[$key] = $seller->toArray();

            $seller_sales = $seller->sales()
                ->where(Sale::DATE, date('Y-m-d'))
                ->get();

            $sellers_sales[$key]['sales'] = [
                'sales_count' => $seller_sales->count(),
                'sales_value' => $seller_sales->sum(Sale::VALUE),
                'comission'   => $seller_sales->sum(Sale::COMISSION),
            ];
        }

        Mail::to($user->email)->send(new DailyReport($user->name, [
            'sales_count' => $sales->count(),
            'sales_value' => $sales->sum(Sale::VALUE),
            'comission'   => $sales->sum(Sale::COMISSION),
            'date'        => date("d-m-Y"),
            'sellers'     => $sellers_sales,
        ]));
    }

    public function sendDailyReportRoutine(): void
    {
        $users = User::all();

        $users->each(
            fn($user) => $this->sendMail($user)
        );
    }
}
