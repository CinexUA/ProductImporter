<?php

namespace App\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class ProductsCountImport implements
    ToModel,
    SkipsOnError,
    SkipsOnFailure
{
    use Importable;

    private $rowsCount = 0;

    public function model(array $row)
    {
        ++$this->rowsCount;
    }

    public function getAmountRows(): int
    {
        return $this->rowsCount;
    }

    public function onError(Throwable $e){}
    public function onFailure(Failure ...$failures){}
}
