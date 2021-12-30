<?php

namespace App\Imports;

use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Goods;
use App\Models\Category;
use App\Models\Supplier;

class GoodsImport implements ToModel
{
    public $pk = 'name';
    public function model(array $row)
    {
        $status = array_flip((new Goods)->statusOptions());
        $data = [
            'name'          => $row[0],
            'qty'           => $row[1], 
            'category_id'   => $this->parse($row[2], Category::class),
            'supplier_id'   => $this->parse($row[3], Supplier::class),
            'brand'         => $row[4],
            'price'         => $row[5],
            'price_ori'     => $row[6],
            'detail'        => $row[7],
            'status'        => $status[$row[8]] ?? null,
        ];
        \Log::debug('origin: '.implode(', ',$row));
        \Log::debug('insert: '.implode(', ',array_values($data)) );
        if($goods = Goods::where('name', $data['name'])->first()){
            $goods->update($data);
            return $goods;
        }else {
            return new Goods($data);
        }
    }
    
    protected function parse($str, $class, $field='name')
    {
        if ($record = $class::where($field, $str)->first()) {
            return $record->id;
        }
        return 1;
    }
}
