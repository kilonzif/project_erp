<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubIndicator extends Model
{
    //
    protected $table = 'sub_indicators';

    protected $fillable = ['title','order_no','indicator_id','unit_measure_id'];

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order_no', 'asc')->orderBy('indicator_id', 'asc');
        });
    }

    public function indicator()
    {
        return $this->belongsTo('App\Indicator');
    }

    public function unit_measure()
    {
        return $this->belongsTo('App\UnitMeasure');
    }

    public function specifics()
    {
        return $this->hasMany('App\Specific');
    }
}
