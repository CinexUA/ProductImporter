<?php

namespace App\Services;


use App\Imports\ProductsCountImport;
use App\Imports\ProductsImport;
use App\Models\Import;

class ProductImportService extends BaseService
{
    public function uploadTable(string $filename, string $filePath): void
    {
        $import = Import::create([
            'name' => $filename,
            'path' => $filePath
        ]);

        $productCountImport = new ProductsCountImport();
        $productCountImport->import($filePath);
        // fix: forced to add a crutch because the extension incorrectly counts the lines saved by
        // the libreoffice program. Their number can exceed a billion in the actual presence of ten.
        $amountRows = $productCountImport->getAmountRows();

        try {
            $productImport = new ProductsImport($import, $amountRows);
            $productImport->import($filePath);
        } catch (\Exception $e){
            dd($e->getMessage());
        }

    }
}
