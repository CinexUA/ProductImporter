<?php

namespace App\Observers;

use App\Models\Import;
use Illuminate\Support\Facades\Storage;

class ImportObserver
{
    public function deleting(Import $import)
    {
        Storage::delete($import->path);
        $import->importLogs()->delete();
    }
}
