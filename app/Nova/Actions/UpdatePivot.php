<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Store;

class UpdatePivot extends Action
{
    use InteractsWithQueue, Queueable;
    protected $_name; 
    protected $_rel; // goods/ categories
    protected $_attr;
    protected $_val;
    
    public function __construct($name, $relation, $attr, $val)
    {
        $this->_name = $name;
        $this->_rel = $relation;
        $this->_attr = $attr;
        $this->_val = $val;
    }

    public function name()
    {
        return $this->_name;
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
        $rel = $this->_rel;
        $s = Store::find(1);
        $msg = null;
        foreach ($models as $model) {
            if (!$s->$rel()->find($model->id)) {
                $msg = '已跳过未上架的！';
                continue;
            }
            $s->$rel()->updateExistingPivot($model->id, [$this->_attr => $this->_val]);
            \Log::debug("==== updateExistingPivot: $model->id $this->_attr => $this->_val ");
        }
        $s->save();
        
        $s->flush();
        
        if ($msg) {
            return Action::danger($msg);
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
