<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Carbon\Carbon;

class IndexFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    public function name()
    {
        return __('Index No');
    }
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        // return $query->where('goods_id', $value);
        $range = explode('-', $value);
        return $query->where('year', $range[0])->where('index', $range[1]);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        // return \App\Models\Goods::pluck('id', 'name')->all();
        $index = [];
        $i = 0;
        do {
            $month = Carbon::today()->subMonth($i)->format('Y-m');
            $index[$month] = $month; //[$month->startOfMonth()->format('Y-m-d'), $month->endOfMonth()->format('Y-m-d')];
        }while ($i++ < 3);
        return $index;
    }
}
