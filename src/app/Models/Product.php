<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'vendor_code',
        'description',
        'price',
        'guarantee',
        'availability',
        'category_id',
        'manufacturer_id'
    ];


    //region relation
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
    //endregion relation


    //region mutators
    public function setGuaranteeAttribute($guarantee)
    {
        $this->attributes['guarantee'] = is_int($guarantee) ? $guarantee : 0;
    }

    public function setAvailabilityAttribute($availability)
    {
        $this->attributes['availability'] = (int)in_array($availability, ['есть в наличие']);
    }
    //endregion mutators


    public function getHumanAvailability()
    {
        return $this->availability
            ? __('In stock')
            : __('Not available');
    }

    public function getPrice()
    {
        return $this->price;
    }

}
