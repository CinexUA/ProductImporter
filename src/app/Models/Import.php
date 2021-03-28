<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    public const STATUS_IN_PROCESS = 0;
    public const STATUS_COMPLETED = 10;

    public $fillable = [
        'name',
        'path',
    ];

    public function getHumanStatus(): string
    {
        return $this->status
            ? __('complete')
            : __('in process');
    }

    //region relation
    public function importLogs()
    {
        return $this->hasMany(ImportLog::class);
    }
    //endregion relation

    public function setStatus(int $status)
    {
        $this->status = $status;
        $this->save();
    }

    public function setStatusActive()
    {
        $this->setStatus(self::STATUS_COMPLETED);
    }
}
