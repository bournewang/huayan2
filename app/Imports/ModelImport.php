<?php

namespace App\Imports;

use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use App\Models\Goods;
use App\Models\Category;
use App\Models\Supplier;
use App\Helpers\ValidatorHelper;

class ModelImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    private $log;
    public function __construct()
    {
        HeadingRowFormatter::default('none'); 
        $model = strtolower(explode('\\', $this->model)[2]);
        $log = public_path("storage/import/${model}.html");
        if (!file_exists(public_path("storage/import"))) {
            mkdir(public_path("storage/import"), 0755);
        }
        $this->log = fopen($log, 'w');
    }
    
    public function __destruct()
    {
        fclose($this->log);
    }
    public $pk = 'name';
    public $model = 'model';//Goods::class;
    private $break = "<br/>";
    protected function prepareData(array $row)
    {
        return [
            'name'  => $row[0],
            'qty'   => $row[1], 
            'supplier_id'   => $this->parse($row[2], 'Supplier')
        ];
    }
    
    public function prepareForValidation(array $row)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        fputs($this->log, "<tr><td>".implode(',', array_values($row))."</td>");
        return $this->prepareData($row);
    }
    
    public function model(array $data)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        if($model = $this->model::where($this->pk, $data[$this->pk])->first()){
            \Log::debug("-  update $model->id ".$data[$this->pk]);
            // fputs($this->log, " ".__("Update"));
            $model->update($data);
            fputs($this->log, "<td>".$this->label(__("Update"). __('Success'), 'bg-warning')."</td></tr>");
            return $model;
        }else {
            \Log::debug('-  create '.$data[$this->pk]);
            $model = new $this->model($data);
            fputs($this->log, "<td>".$this->label(__("Create"). __('Success'))."</td></tr>");
            return $model;
        }
    }
    
    protected function parse($str, $class, $field='name')
    {
        if ($record = $class::where($field, $str)->first()) {
            return $record->id;
        }
        return null;
    }
    
    public function batchSize(): int
    {
        return 1000;
    }  
    
    public function rules(): array
    {
        return $this->model::$rules;
    }
    
    public function onFailure(Failure ...$failures)
    {
        $errors = [];
        foreach ($failures as $failure) {
            $errors = array_merge($errors, $failure->errors());
        }
        fputs($this->log, "<td>".$this->label(implode(", ", $errors), 'text-danger')."</td></tr>");
    }    
    
    private function label($label, $class='text-success')
    {
        return "<span class='text $class'>$label</span>";
    }
}
