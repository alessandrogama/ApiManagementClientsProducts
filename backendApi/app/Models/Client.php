<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Favorite;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone'];


    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
