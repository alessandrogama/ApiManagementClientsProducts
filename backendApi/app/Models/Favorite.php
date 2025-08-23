<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Client;
use App\Models\Product;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['product_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
