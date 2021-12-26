<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Inactive extends Action
{
    use InteractsWithQueue, Queueable;
    public function name()
    {
        return __('Make Inactive');
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
        \Log::debug(__CLASS__.'->'.__FUNCTION__. " count: ".$models->count());
        foreach ($models as $model) {
            \Log::debug("update status to ".$model->inactive);
            $model->update(['status' => $model->inactive]);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
