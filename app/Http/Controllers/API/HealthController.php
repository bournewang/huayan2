<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Health;

class HealthController extends ApiBaseController
{
    /**
     * Health list api 健康咨询记录
     *
     * @OA\Get(
     *  path="/api/health",
     *  tags={"User"},
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Response(response=200,description="successful operation")
     * )
     */    
    public function index(Request $request)
    {
        $records = $this->user->healths();
        $total = $records->count();
        $perpage = $request->input('perpage', 20);
        $data = [
            'titles' => ["img" => __('Avatar'), 'expert_name' => __('Expert'), 'status_label' => __('Status'), 'date' => __('Date')],
            'total' => $total,
            'pages' => ceil($total/$perpage),
            'page' => $request->input('page', 1),
            'items' => []
        ];
        $records = $records->paginate($perpage);
        foreach ($records as $record) {
            $info = $record->info();
            $data['items'][] = [
                'id' => $record->id,
                'img' => $info['expert_img'] ?? null,
                'expert_name' => $info['expert_name'] ?? null,
                // 'suggestion' => $info['suggestion'] ?? null,
                'status_label' => $info['status_label'] ??null,
                'date' => $record->created_at ? $record->created_at->toDateString() : null
            ];
        }
        return $this->sendResponse($data);        
    }  
    
    /**
     * Health detail api 健康咨询记录详情
     *
     * @OA\Get(
     *  path="/api/health/{id}",
     *  tags={"User"},
     *  @OA\Parameter(name="id",       in="path",required=false,explode=true,@OA\Schema(type="integer"),description="health record id"),
     *  @OA\Response(response=200,description="successful operation")
     * )
     */  
    public function show($id)
    {
        if (!$health = Health::find($id)) {
            return $this->sendError("no health record found");
        }
        return $this->sendResponse($health->detail());
    }
    
}