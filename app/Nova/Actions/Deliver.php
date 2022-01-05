<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use App\Models\Logistic;

class Deliver extends Action
{
    use InteractsWithQueue, Queueable;
    public function name()
    {
        return __('Deliver');
    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //
        foreach ($models as $model) {
            $model->deliver($fields->logistic_id, $fields->waybill_number);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            // BelongsTo::make(__('Logistic'), 'logistic', \App\Nova\Logistic::class)->required()->searchable(),
            Select::make(__('Logistic'), 'logistic_id')
                ->options(function(){
                    // FIXME, cache options;
                    return Logistic::options();
                })
                ->displayUsingLabels()
                ->searchable(),
            Text::make(__('Waybill Number'), 'waybill_number')->required()
        ];
    }
}
