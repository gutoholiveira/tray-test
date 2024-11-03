<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    use HasFactory;

    const ID    = 'id';
    const NAME  = 'name';
    const EMAIL = 'email';

    /**
     * The table's name.
     *
     * @var string
     */
    protected $table = 'sellers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::NAME,
        self::EMAIL,
    ];

    /**
     * Get the Seller sales.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
