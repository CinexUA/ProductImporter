<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImporterRequest;
use App\Models\Import;
use App\Models\ImportLog;
use App\Models\Product;
use App\Services\ProductImportService;

class ProductImporterController extends Controller
{

    private $productImportService;

    public function __construct(ProductImportService $productImportService)
    {
        $this->productImportService = $productImportService;
    }

    public function index()
    {
        return view('product-importer.index');
    }

    public function store(ImporterRequest $importerRequest)
    {
        $file = $importerRequest->file('products');
        $this->productImportService->uploadTable($file->getClientOriginalName(), $file->store('import'));

        toastr()->success('Data has been updated successfully!');

        return redirect()->route('product-importer.history');
    }

    public function history()
    {
        $imports = Import::latest()->paginate();
        return view('product-importer.history', compact('imports'));
    }

    public function historyLog(Import $import)
    {
        $historyLog = $import->importLogs()->orderBy('row')->paginate();
        return view('product-importer.history_log', compact('historyLog'));
    }

    public function removeHistory(Import $history)
    {
        $history->delete();
        toastr()->success(__("Import file has been deleted"));
        return response()->json(['message' => null, 'success' => true], 204);
    }
}
