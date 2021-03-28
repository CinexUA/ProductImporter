<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Import;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class ProductsImport implements
    ToCollection,
    SkipsOnError,
    SkipsOnFailure,
    ShouldQueue,
    WithCalculatedFormulas,
    WithColumnLimit,
    WithEvents,
    WithLimit,
    WithChunkReading
{
    use Importable, RegistersEventListeners;

    private $skipHeading = false;

    private $importModel;
    private $amountRows;

    public function __construct(Import $import, int $amountRows)
    {
        $this->importModel = $import;
        $this->amountRows = $amountRows;
    }

    public function validateCollection(Collection $rows): array
    {
        $validator = Validator::make($rows->toArray(), [
            '*.2' => 'required|max:255',
            '*.3' => 'required|max:255',
            '*.4' => 'required|max:255',
            '*.5' => 'required|max:15', //|unique:products,vendor_code
            '*.6' => 'required|string',
            '*.7' => 'required|integer',
            '*.8' => 'required',
            '*.9' => 'required',
        ], [
            '*.2.required' => 'The category field is required.',
            '*.3.required' => 'The manufacturer field is required.',
            '*.4.required' => 'The name field is required.',
            '*.5.required' => 'The vendor code field is required.',
            '*.6.required' => 'The description code field is required.',
            '*.7.required' => 'The price field is required.',
            '*.8.required' => 'The guarantee field is required.',
            '*.9.required' => 'The availability field is required.',

            '*.2.max' => 'The category must not be greater than :max characters.',
            '*.3.max' => 'The manufacturer must not be greater than :max characters.',
            '*.4.max' => 'The name must not be greater than :max characters.',
            '*.5.max' => 'The vendor code must not be greater than :max characters.',

            //'*.5.unique' => 'The vendor code has already been taken.',
            '*.7.integer' => 'The price must be an integer.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $k=>$error){
                list($rowNum, $celNum) = explode('.' , $k);
                $this->addErrorToImportLog($error, $rowNum + 1);
            }
        }

        return $validator->valid();
    }

    public function collection(Collection $rows)
    {
        $this->checkIfExistHeading($rows);

        if($this->skipHeading()){
            $rows->shift();
            $this->importModel->decrement('total');
        }

        $this->insertOrUpdateCategories($rows->groupBy(2));
        $this->insertOrUpdateManufacturers($rows->groupBy(3));

        $data = $this->validateCollection($rows);

        $countRow = 1;
        $time = now()->format('Y-m-d H:m:s');
        foreach ($data as $row)
        {
            if(Product::whereVendorCode($row[5])->exists()){
                $this->addErrorToImportLog(['The vendor code has already been taken.'], $countRow);
            } else {
                Product::insert([
                    'name'          => $row[4],
                    'vendor_code'   => $row[5],
                    'description'   => $row[6],
                    'price'         => $row[7],
                    'guarantee'     => is_int($row[8]) ? $row[8] : 0,
                    'availability'  => (int)in_array($row[9], ['есть в наличие']),
                    'category_id'   => optional(Category::whereName($row[2])->first())->id,
                    'manufacturer_id' => optional(Manufacturer::whereName($row[3])->first())->id,
                    'created_at'    => $time,
                    'updated_at'    => $time,
                ]);

            }
            $this->importModel->increment('processed');
            $countRow++;
        }

    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function endColumn(): string
    {
        return 'J';
    }

    public function limit(): int
    {
        return $this->amountRows;
    }

    public static function beforeImport(BeforeImport $event)
    {
        $event->getConcernable()->importModel->increment('total', $event->getConcernable()->amountRows);
    }

    public static function afterImport(AfterImport $event)
    {
        $event->getConcernable()->importModel->setStatusActive();
    }

    public function onError(Throwable $e)
    {
        //info($e);
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure){
            $this->addErrorToImportLog($failure->errors(), $failure->row());
        }
    }

    private function addErrorToImportLog(array $errors, ?int $row = null): void
    {
        $errorsAsString = implode(',', $errors);

        $importLog = $this->importModel->importLogs()->firstOrNew(['row' => $row]);

        if($importLog->exists){
            $importLog->description = $importLog->description . '; ' . $errorsAsString;
        } else {
            $this->importModel->increment('fail_count');
            $importLog->description = $errorsAsString;
        }

        $importLog->save();
    }

    private function checkIfExistHeading(Collection $rows): void
    {
        $columns = $rows->first();
        $lookingFor = [
            'Рубрика',
            'Категория товара',
            'Производитель',
            'Наименование товара',
            'Код модели (артикул производителя)',
            'Описание товара',
            'Цена розн., грн.',
            'Гарантия',
            'Наличие',
        ];

        foreach ($columns as $column){
            if(in_array($column, $lookingFor)){
                $this->skipHeading = true;
                $this->amountRows--;
                break;
            }
        }
    }

    private function skipHeading(): bool
    {
        return $this->skipHeading;
    }

    private function insertOrUpdateCategories(Collection $categories): void
    {
        $chunks = $this->prepareChunksDataByName($categories);
        foreach ($chunks as $chunk){
            Category::insertOrIgnore($chunk);
        }
    }

    private function insertOrUpdateManufacturers(Collection $manufacturers): void
    {
        $chunks = $this->prepareChunksDataByName($manufacturers);
        foreach ($chunks as $chunk){
            Manufacturer::insertOrIgnore($chunk);
        }
    }

    private function prepareChunksDataByName(Collection $collection)
    {
        $data = [];
        $count = 0;
        foreach ($collection as $k=>$v){
            $data[] = ['name' => $k];
            $count++;
        }

        return array_chunk($data, 5000);
    }
}
