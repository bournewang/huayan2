<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Category;

class CategoryController extends ApiBaseController
{
    public function index($store_id, Request $request)
    {
        $data = [];
        foreach (Category::all() as $category) {
            $data[] = $category->show();
        }
        return $this->sendResponse($data);
    }
}
