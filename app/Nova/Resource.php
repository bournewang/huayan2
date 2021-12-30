<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Panel;
use NovaAjaxSelect\AjaxSelect;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use OptimistDigital\NovaDetachedFilters\NovaDetachedFilters;
use OptimistDigital\NovaDetachedFilters\HasDetachedFilters;
use App\Models\Province;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
abstract class Resource extends NovaResource
{
    use HasDetachedFilters;
    public static $tableStyle = 'tight';
    public static $showColumnBorders = true;
    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }
    
    public static function storeQuery(NovaRequest $request, $query, $field = 'store_id')
    {
        if ($store_id = $request->user()->store_id) {
            $query->where($field, $store_id);
        }
        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }
    
    public function moneyfield($label, $field)
    {
        return Text::make($label, $field)->displayUsing(function($v){return money($v);});
    }
    
    public function mediaField($label, $collection_name = 'my_multi_collection')
    {
        return Images::make($label, $collection_name) // second parameter is the media collection name
                   // ->conversionOnPreview('original') // conversion used to display the "original" image
                   ->conversionOnDetailView('medium') // conversion used on the model's view
                   ->conversionOnIndexView('thumb') // conversion used to display the image on the model's index page
                   ->conversionOnForm('thumb') // conversion used to display the image on the model's form
                   ->fullSize() // full size column
                   // ->rules('required', 'size:3') // validation rules for the collection of images
                   ->singleImageRules('dimensions:min_width=100');
    }
    
    
    public function detachedFilters(Request $request)
    {
        return [];
    }
    
    // subclass must not have an empty cards()
    public function cards(Request $request)
    {
        return [
            (new NovaDetachedFilters($this->detachedFilters($request)))->withReset()->width('full')
        ];
    }  
    
    public function addressFields()
    {
        return new Panel(__('Address'), [
            Select::make(__('Province'), 'province_id')
                ->options(Province::pluck('name', 'id')->all())
                ->displayUsingLabels()
                ->onlyOnForms(),
            
            AjaxSelect::make(__('City'), 'city_id')
                ->get('/api/provinces/{province_id}/cities')
                ->parent('province_id')
                ->onlyOnForms(),

            AjaxSelect::make(__('District'), 'district_id')
                ->get('/api/cities/{city_id}/districts')
                ->parent('city_id')
                ->onlyOnForms(),
            
            Text::make(__('Street'), 'street')->onlyOnForms(),  
            Text::make(__('Contact'), 'contact')->onlyOnForms()->nullable(),
            Text::make(__('Telephone'), 'telephone')->onlyOnForms()->nullable(),
            Text::make(__('Address'), 'address')->displayUsing(function(){return $this->display_address();})->exceptOnForms(),
        ]);
    }  
    
    public function editorField($label, $field)
    {
        return NovaTinyMCE::make($label, $field)->options([
            'height' => '600',
            'language' => 'zh_CN',
            'language_url' => '/tinymce/langs/zh_CN.js'
        ]);
    }
}
