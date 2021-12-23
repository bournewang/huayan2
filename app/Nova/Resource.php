<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Fields\Text;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

abstract class Resource extends NovaResource
{
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
}
