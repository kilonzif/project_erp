<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Indicator extends Model
{

    protected $fillable = ['title','identifier','order_no','unit_measure','parent_id',
        'unit_measure_id','status','upload','show_on_report','isparent'];

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order_no', 'asc')
                ->orderBy('identifier', 'asc')
                ->orderBy('parent_id', 'asc');
        });
    }

    public function project(){
        return $this->belongsTo('App\Project');
    }

    public function sub_indicators()
    {
        return $this->hasMany('App\SubIndicator');
    }

    public function unit_measure()
    {
        return $this->belongsTo('App\UnitMeasure');
    }

    public function indicators(){
        return $this->hasMany('App\Indicator','parent_id');
    }

    public function scopeMainIndicators($query){
        return $query->where('parent_id','=',0);
    }

    public function IsUploadable($indicator_id){
        $result = Indicator::where('id','=',$indicator_id)->where('upload','=',1)->first();
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    public function isParentIndicator($indicator_id)
    {
        $indicator = Indicator::where('parent_id','=',$indicator_id)->get();
        if ($indicator){
            return true;
        }else{
            return false;
        }
    }

    public function getParentIndicator($indicator_id)
    {
        $indicator = Indicator::find($indicator_id);
        if ($indicator->parent_id == 0){
            return $indicator_id;
        }else{
            return $indicator->parent_id;
        }


    }
    public function scopeActiveIndicator($query){
        return $query->where('status',1);
    }

    public function scopeParentIndicator($query,$parent_id){
        return $query->where('parent_id',$parent_id);
    }


}
