<?php

namespace Tests\Feature\Sale;

use App\Models\User;
use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/sales');

        $response->assertOk();
    }

    public function test_get_sales_list(): void
    {
        $user = User::factory()->create();
        Seller::factory(5)->create();
        Sale::factory(30)->create();

        $response = $this
            ->actingAs($user)
            ->get('api/v1/sales');

        $response
            ->assertSessionHasNoErrors();

        $sales = Sale::all();

        $this->assertNotNull($sales);
        $this->assertSame($sales->count(), 30);
    }

    public function test_sale_can_be_stored(): void
    {
        $user   = User::factory()->create();
        $seller = Seller::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('api/v1/sales', [
                Sale::SELLER_ID => $seller->id,
                Sale::VALUE     => 125000,
                Sale::DATE      => date('Y-m-d'),
            ]);

        $response
            ->assertSessionHasNoErrors();

        $sale = Sale::latest()->first();

        $this->assertSame($sale->seller_id, $seller->id);
        $this->assertSame($sale->value, 125000);
        $this->assertSame($sale->commission, intval($sale->value * 0.085));
    }

    public function test_get_sale_row(): void
    {
        $user   = User::factory()->create();
        $seller = Seller::factory()->create();
        $sale   = Sale::factory()->create([Sale::SELLER_ID => $seller->id]);

        $response = $this
            ->actingAs($user)
            ->get('api/v1/sales/' . $sale->id);

        $response
            ->assertSessionHasNoErrors()
            ->assertJson([
                'data' => [
                    'sale' => [
                        Sale::ID         => $sale->id,
                        Sale::SELLER_ID  => $sale->seller_id,
                        Sale::VALUE      => $sale->value,
                        Sale::COMMISSION => intval($sale->commission),
                        Sale::DATE       => $sale->date,
                    ]
                ]
            ]);
    }

    public function test_sale_can_be_updated(): void
    {
        $user     = User::factory()->create();
        $seller = Seller::factory()->create();
        $sale    = Sale::factory()->create([Sale::SELLER_ID => $seller->id]);

        $response = $this
            ->actingAs($user)
            ->put('api/v1/sales/' . $sale->id, [
                Sale::SELLER_ID => $seller->id,
                Sale::VALUE     => 500000,
                Sale::DATE      => date('Y-m-d'),
            ]);

        $response
            ->assertSessionHasNoErrors();

        $sale->refresh();

        $this->assertSame(500000, $sale->value);
        $this->assertSame(intval(500000 * 0.085), $sale->commission);
    }

    public function test_sale_can_be_deleted(): void
    {
        $user     = User::factory()->create();
        $seller = Seller::factory()->create();
        $sale    = Sale::factory()->create([Sale::SELLER_ID => $seller->id]);

        $response = $this
            ->actingAs($user)
            ->delete('api/v1/sales/' . $sale->id);

        $response
            ->assertSessionHasNoErrors();

        $sale = Sale::find($sale->id);

        $this->assertNull($sale);
    }
}
