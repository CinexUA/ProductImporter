<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
    ];

    //region relation
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    //endregion relation
}
