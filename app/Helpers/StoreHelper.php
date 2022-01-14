<?php
namespace App\Helpers;
use App\Models\Store;
use App\Models\Order;
use App\Models\User;
use App\Models\Revenue;
use DB;
class StoreHelper
{
    static public function relationIds($store_id, $relation, $attr=null, $val=null)
    {
        $key = implode('.', array_filter(['store', $store_id, __FUNCTION__, $relation, $attr, $val]));
        return cache1("store.$store_id", $key, function()use($store_id, $relation, $attr, $val){
            $builder = Store::find($store_id)->$relation();
            if ($attr && $val) 
                $builder = $builder->wherePivot($attr, $val);
            return $builder->pluck('id')->all();
        });
    }
    
    static public function refreshSales($store, $start, $end)
    {
        foreach ($store->users as $user) {
            $user->initSales();
        }
        
        // FIXME: should be paid_at
        $res = Order::where('store_id', $store->id)
            ->whereBetween('created_at', [$start, $end])
            ->select(\DB::raw("sum(orderAmount) as total"), 'user_id')
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->all();
        foreach ($res as $user_id => $total) {
            User::find($user_id)->update(['ppv' => $total]);
        }
        
        foreach ($store->refresh()->roots() as $root) {
            $root->tgpv();
        }
        
        foreach ($store->refresh()->roots() as $user) {
            $user->make_dds();
        }
        
        foreach ($store->refresh()->roots() as $user) {
            $user->make_leader_base();
        }
        
        foreach ($store->refresh()->users as $user) {
            $user->income();
        }
    }
    
    static public function calculateRevenue($store, $year, $index)
    {
        $start = date('Y-m-d', strtotime("first day of $year-$index"));
        $end = date('Y-m-d', strtotime("last day of $year-$index")) . ' 23:59:59';

        self::refreshSales($store, $start, $end);
        
        foreach ($store->refresh()->users as $user) {
            $data = [
                'ppv' => $user->ppv, 
                'gpv' => $user->gpv,
                'tgpv' => $user->tgpv,
                'pgpv' => $user->pgpv,
                'retail_income' => $user->retail_income,
                'level_bonus'   => $user->level_bonus,
                'leader_bonus'  => $user->leader_bonus,
                'total_income'  => $user->total_income,
                'clearing_status' => 0
            ];
            if (!$revenue = $user->revenue($year, $index)) {
                $revenue = Revenue::create(array_merge([
                    'store_id' => $store->id,
                    'user_id' => $user->id,
                    'year' => $year,
                    'index' => $index,
                    'start'  => $start,
                    'end'  => $end, 
                ], $data));
            }else{
                $revenue->update($data);
            }
        }
    }

    // 月度消费额, only for manager/clerk/referer
    static public function salesStats($user, $month, $perpage, $table = 'orders')
    {
        $start = date('Y-m-d', strtotime("first day of $month"));
        $end = date('Y-m-d', strtotime("last day of $month")) . ' 23:59:59';

        $builder = DB::table($table);
        if ($user->type == User::MANAGER) {
            $builder->where($table.'.store_id', $user->store_id);
        }else {
            $builder->where('users.senior_id', $user->id);
        }
        $res = $builder->whereIn($table.'.status', array_keys(Order::validStatus()))
            ->whereBetween($table.'.created_at', [$start, $end])
            ->select('users.avatar as img', 'users.nickname', 'users.mobile', DB::raw("sum(amount) as total_amount"))
            ->join('users', $table.'.user_id', '=', 'users.id')
            ->groupBy('user_id')
            ->orderBy('total_amount', 'desc')
            ->paginate($perpage)
            ->toArray()
            ;    
        return [
            'titles'  => ['img' => __('Avatar'), 'nickname' => __('Nickname'), 'mobile' => __('Mobile'), 'amount' => __('Amount')],
            'total' => $res['total'] ?? null,
            'pages' => $res['last_page'] ?? 1,
            'page' => $res['page'] ?? 1,
            'items' => $res['data'] ?? [],
        ];
    }
}