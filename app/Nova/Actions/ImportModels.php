<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Anaseqal\NovaImport\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\File;
use Maatwebsite\Excel\Facades\Excel;

trait ImportModels 
{
    use InteractsWithQueue, Queueable, SerializesModels;
    // public $model = '';
    // public $import_class = '';
    public function shownOnIndex()
    {
        return true;
    }
    public function shownOnDetail()
    {
        return false;
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name() {
        return __('Import');//.explode('\\', $this->model)[2]);
    }

    /**
     * @return string
     */
    public function uriKey() :string
    {
        return 'import-'.strtolower(explode('\\', $this->model)[2]);
    }

    /**
     * Perform the action.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        Excel::import(new $this->import_class, $fields->file);

        return Action::message('It worked!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make(__('File'), 'file')
                ->rules('required'),
        ];
    }
}