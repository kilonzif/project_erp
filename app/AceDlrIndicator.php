<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AceDlrIndicator extends Model
{
    //
    protected $table = 'ace_dlr_indicators';
    protected $fillable = [
        'order', 'indicator_title','status','parent_id','set_max_dlr','is_parent'
    ];

    public function indicators(){
        return $this->hasMany('App\AceDlrIndicator','parent_id');
    }

    public function scopeMainIndicators($query){
        return $query->where('parent_id','=',0);
    }

    public function isParentIndicator($indicator_id)
    {
        $indicators = AceDlrIndicator::where('parent_id','=',$indicator_id)->get();
        if ($indicators->count() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getParentIndicator($indicator_id)
    {
        $indicator = AceDlrIndicator::find($indicator_id);
        if ($indicator->parent_id == 0){
            return $indicator_id;
        }else{
            return $indicator->parent_id;
        }
    }

    public function ace_dli()
    {
        return $this->belongsTo('App\AceDli', 'ace_dli_id');
    }
}
