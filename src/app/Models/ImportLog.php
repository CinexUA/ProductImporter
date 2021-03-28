<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasFactory;

    public $fillable = [
        'description', 'row'
    ];

    //region relation
    public function import()
    {
        return $this->belongsTo(Import::class);
    }
    //endregion relation
}
