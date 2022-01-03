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

use App\Imports\GoodsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportGoods extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

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
        return __('Import Goods');
    }

    /**
     * @return string
     */
    public function uriKey() :string
    {
        return 'import-goods';
    }

    /**
     * Perform the action.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        Excel::import(new GoodsImport, $fields->file);

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
                ->rules('required')
                ->help(
                    "<a class='text text-primary' href='/templates/goods.xlsx' download='商品.xlsx'>".__('Template Download') . "</a><br/>".
                    "<a class='text text-default' href='/log.php?p=goods' target=_blank>".__('Import Log') .'</a>'
                )
        ];
    }
}