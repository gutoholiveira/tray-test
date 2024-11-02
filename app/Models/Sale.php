<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    const ID        = 'id';
    const SELLER_ID = 'seller_id';
    const VALUE     = 'value';
    const COMISSION = 'comission';
    const DATE      = 'date';

    /**
     * The table's name.
     *
     * @var string
     */
    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::SELLER_ID,
        self::VALUE,
        self::COMISSION,
        self::DATE,
    ];

    /**
     * Get the sale's seller.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
