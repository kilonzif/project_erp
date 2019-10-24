<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AceIndicatorsTarget extends Model
{
    //
    protected $table = 'ace_indicators_targets';

    protected $fillable = ['target','indicator_id','target_year_id','ace_id'];

    public function target_year()
    {
        return $this->belongsTo('App\AceIndicatorsTargetYear','target_year_id');
    }

    public static function get_target_value($start,$end,$ace_id,$indicator_id){
        //Get aces target values by indicators
        $target_values = DB::table('ace_indicators_targets')
            ->join('ace_indicators_target_years', 'ace_indicators_targets.target_year_id', '=', 'ace_indicators_target_years.id')
            ->select(DB::raw('SUM(ace_indicators_targets.target) as targets'))
            ->whereIn('ace_indicators_target_years.ace_id', $ace_id)
            ->where('indicator_id',$indicator_id)
            ->where(function ($query) use($start,$end){
                return $query->where('start_period','>=',$start)->orWhere('end_period','<=',$end);
            })
//            ->where('start_period', '>=', $start)
//            ->orWhere('end_period', '<=',$end)

            ->value('targets');
        return $target_values;
    }

    public static function get_target_by_year($start,$end,$indicator_id){
        $value = DB::table('ace_indicators_targets')
            ->join('ace_indicators_target_years', 'ace_indicators_targets.target_year_id', '=', 'ace_indicators_target_years.id')
            ->select(DB::raw('ace_indicators_targets.target as targets'))
            ->where('indicator_id',$indicator_id)
            ->where(function ($query) use($start,$end){
                return $query->where('start_period','>=',$start)->orWhere('end_period','<=',$end);
            })
        ->value('targets');

        return $value;
    }




}
