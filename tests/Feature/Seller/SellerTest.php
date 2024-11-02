<?php

namespace Tests\Feature\Seller;

use App\Mail\Seller\DailyReport;
use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/sellers');

        $response->assertOk();
    }

    public function test_get_seller_list(): void
    {
        $user = User::factory()->create();
        Seller::factory(5)->create();

        $response = $this
            ->actingAs($user)
            ->get('api/v1/sellers');

        $response
            ->assertSessionHasNoErrors();

        $sellers = Seller::all();

        $this->assertNotNull($sellers);
        $this->assertSame($sellers->count(), 5);
    }

    public function test_seller_can_be_stored(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('api/v1/sellers', [
                Seller::NAME  => 'Test Seller',
                Seller::EMAIL => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors();

        $seller = Seller::latest()->first();

        $this->assertSame($seller->name, 'Test Seller');
        $this->assertSame($seller->email, 'test@example.com');
    }

    public function test_get_seller_row(): void
    {
        $user   = User::factory()->create();
        $seller = Seller::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('api/v1/sellers/' . $seller->id);

        $response
            ->assertSessionHasNoErrors()
            ->assertJson([
                'data' => [
                    'seller'  => [
                        Seller::ID    => $seller->id,
                        Seller::NAME  => $seller->name,
                        Seller::EMAIL => $seller->email,
                    ]
                ]
            ]);
    }

    public function test_seller_can_be_updated(): void
    {
        $user   = User::factory()->create();
        $seller = Seller::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put('api/v1/sellers/' . $seller->id, [
                Seller::NAME  => 'Test Seller',
                Seller::EMAIL => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors();

        $seller->refresh();

        $this->assertSame($seller->name, 'Test Seller');
        $this->assertSame($seller->email, 'test@example.com');
    }

    public function test_seller_can_be_deleted(): void
    {
        $user   = User::factory()->create();
        $seller = Seller::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('api/v1/sellers/' . $seller->id);

        $response
            ->assertSessionHasNoErrors();

        $seller = Seller::find($seller->id);

        $this->assertNull($seller);
    }

    public function test_seller_cannot_be_deleted_by_sales(): void
    {
        $user   = User::factory()->create();
        $seller = Seller::factory()->create();
        $sale   = Sale::factory()->create(['seller_id' => $seller->id]);

        $response = $this
            ->actingAs($user)
            ->delete('api/v1/sellers/' . $seller->id);

        $response
            ->assertServerError();

        $seller = Seller::find($seller->id);

        $this->assertNotNull($seller);
    }

    public function test_send_daily_report_email(): void
    {
        Mail::fake();

        $user   = User::factory()->create();
        $seller = Seller::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('api/v1/sellers/' . $seller->id . '/send-mail');

        $response
            ->assertSessionHasNoErrors();

        Mail::assertSent(DailyReport::class, function ($mail) use ($seller) {
            return $mail->hasTo($seller->email);
        });
    }
}
