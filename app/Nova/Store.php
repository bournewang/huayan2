<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Comodolab\Nova\Fields\Help\Help;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\TabsOnEdit;
class Store extends Resource
{
    use TabsOnEdit;
    public static $model = \App\Store::class;
    public static $title = 'name';
    public static $search = [
        'name', 
    ];
    public static function label()
    {
        return __('Store');
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $user = $request->user();
        return [
            Tabs::make(__('Store') . __('Detail'), [
                Tab::make('Info', [    
                    ID::make(),
                    Text::make(__('Store Name'), 'name')->sortable()->rules('required', 'max:255'),
                    Text::make(__('Company Name'), 'company_name')->rules('required', 'max:255'),
                    Text::make(__('License No'), 'license_no')->rules('required', 'max:255'),
                    Text::make(__('Account No'), 'account_no')->rules('required', 'max:255'),
                    Text::make(__('Contact Name'), 'contact_name')->nullable(),
                    Text::make(__('Telphone'), 'telphone')->nullable(),
                    Text::make(__('License Img'), 'license_img')->nullable(),
                    
                    // BooleanGroup::make(__('Category'), 'categories')
                    //     ->options(\App\Category::all()->pluck('name', 'id')->all())
                    //     // ->withMeta(['extraAttributes' => ['class'=>'boolean-group']])
                    //     ->help("勾选的代理的商品分类")
                    //     ->canSee(function()use($user){return $user->isRoot();}),
                ]),
                 Tab::make(__('Bonus Settings'), [
                     /*
                    KeyValue::make(__('Bonus Title'), 'bonus_title')
                        ->keyLabel(__('Eligible DD Quantity'))
                        ->valueLabel(__('Bonus Title'))
                        ->actionText(__('Add'))
                        ->nullable(),
                    */
                    Help::info(__('Retail Income'), __('Retail Income Help Text'))->onlyOnDetail(),
                    KeyValue::make(__('Commission'), 'tier_bonus')
                        ->keyLabel(__('GPV'))
                        ->valueLabel(__('Commission'))
                        ->actionText(__('Add Tier'))
                        ->nullable()
                        ->help('点数写数字即可，无需写百分号'),
                    Help::warning(__('Level Bonus'), __('Level Bonus Help Text'))->onlyOnDetail(),    
                    
                    /*
                    Help::warning(__('Expand Market Width Bonus'), "领取资格：需要".($this->width_bonus['dd_qty']??null)."个达标部门，并且去除达标部门外的小部门营业额达到".($this->width_bonus['pgpv']??null).", 即可再领取小部门业绩总和的".($this->width_bonus['bonus']??null)."%作为宽度市场拓展奖金。")->onlyOnDetail(),
                    KeyValue::make(__('Expand Market Width Bonus'), 'width_bonus')
                        ->keyLabel(__('Option'))
                        ->valueLabel(__('Value'))
                        ->disableEditingKeys()
                        ->disableAddingRows()
                        ->disableDeletingRows()
                        ->help('bonus: 奖金点数； dd_qty： 需要达标部门的数量； pgpv：除去达标部门之外的小组营业额')
                        ->nullable(),  
                    Help::success(__('Expand Market Depth Bonus'), "领取资格：需要".($this->depth_bonus['dd_qty']??null)."个达标部门，, 即可领取达标部门的下级销售额的".($this->width_bonus['bonus']??null)."%作为深度市场拓展奖金。")->onlyOnDetail(),    
                    KeyValue::make(__('Expand Market Depth Bonus'), 'depth_bonus')
                        ->keyLabel(__('Option'))
                        ->valueLabel(__('Value'))
                        ->disableEditingKeys()
                        ->disableAddingRows()
                        ->disableDeletingRows()
                        ->help('bonus: 奖金点数； dd_qty： 需要达标部门的数量')
                        ->nullable(),  
                    */    
                    KeyValue::make(__('Leader Bonus'), 'leader_bonus')
                        ->keyLabel(__('Option'))
                        ->valueLabel(__('Value'))
                        ->disableEditingKeys()
                        ->disableAddingRows()
                        ->disableDeletingRows()
                        ->help('bonus: 奖金点数； pgpv: 去除达标部门外的小组营业额')
                        ->nullable(), 
                    Help::danger(__('Leader Bonus'), __('Leader Bonus Help Text', ['pgpv' => $this->leader_bonus['pgpv']??null, 'bonus' => $this->leader_bonus['bonus']??null]))->onlyOnDetail(),
                                                             
                    // bonus_titles
                    // Text::make('-')->displayUsing(function(){return "<div class=clearfix/>";})->asHtml()->onlyOnDetail(),  
                    // Number::make(__('Leader Bonus'), 'leader_bonus')->nullable()->help("当成员A的直接下属B的小组业绩达标（达到或超过最高层级）后，B的业绩即脱离A的小组业绩；同时B组业绩划出一定的比例作为领导奖，奖励给上级A。"),
                ]),
            ])->withToolbar(),
            BelongsToMany::make(__('Category'), 'categories', Category::class),
            // HasMany::make(__('Sales'), 'users', User::class)//->fields(new Fields\SalesFields),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return self::storeQuery($request, $query, 'id');
    }
}
