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

    public static function getIndicatorID(){
        $total_students_ID = DB::table('ace_indicators_targets')
            ->join('indicators', 'ace_indicators_targets.indicator_id', '=', 'indicators.id')
            ->where('title', '=','National and Men')
            ->orWhere('title', '=','National and Women')
            ->orWhere('title', '=', 'Regional Students')
            ->pluck('ace_indicators_targets.indicator_id')->toArray();

        $regional_students_ID= DB::table('ace_indicators_targets')
            ->join('indicators', 'ace_indicators_targets.indicator_id', '=', 'indicators.id')
            ->where('title', '=', 'Regional Students')
            ->pluck('ace_indicators_targets.indicator_id')->toArray();


        $internships_ID = DB::table('ace_indicators_targets')
            ->join('indicators', 'ace_indicators_targets.indicator_id', '=', 'indicators.id')
                ->where('identifier','=','5.2')
            ->orWhere('title', '=', 'National')
            ->orWhere('title', '=', 'Regional')
            ->pluck('ace_indicators_targets.indicator_id')->toArray();

        $revenue_ID = DB::table('ace_indicators_targets')
            ->join('indicators', 'ace_indicators_targets.indicator_id', '=', 'indicators.id')
            ->where('identifier','=','5.1')
            ->orWhere('title', '=', 'External Revenue - National')
            ->orWhere('title', '=', 'External Revenue - Regional')
            ->pluck('ace_indicators_targets.indicator_id')->toArray();
        $accreditation_ID = DB::table('ace_indicators_targets')
            ->join('indicators', 'ace_indicators_targets.indicator_id', '=', 'indicators.id')
            ->where('identifier', '=', '7.3')
            ->orWhere('title', '=', 'International accreditation')
            ->orWhere('title', '=', 'Gap Assessment/ Self-evaluation')
            ->pluck('ace_indicators_targets.indicator_id')->toArray();

        return compact('total_students_ID','regional_students_ID','internships_ID','revenue_ID','accreditation_ID');

    }



    public static function get_target_value($start,$end,$ace_id,$indicator_id){
        $years = array();
        $start = date('Y',strtotime($start));
        $end = date('Y',strtotime($end));
        while($start <= $end){
            $years[] = (int)$start;
            $start++;
        }

        $target_values = DB::table('ace_indicators_targets')
            ->join('ace_indicators_target_years', 'ace_indicators_targets.target_year_id', '=', 'ace_indicators_target_years.id')
            ->select(DB::raw('SUM(ace_indicators_targets.target) as targets'))
            ->whereIn('ace_indicators_target_years.ace_id', $ace_id)
//            ->whereIn( DB::raw('YEAR(start_period)'),$years)
            ->whereIn('indicator_id',$indicator_id)
            ->where(function ($query) use($years){
                return $query->whereIn( DB::raw('YEAR(start_period)'),$years)
                    ->WhereIn(DB::raw('YEAR(end_period)'),$years);
            })
        ->value('targets');
//        $querystring = vsprintf(str_replace(['?'], ['\'%s\''], $target_values->toSql()), $target_values->getBindings());
//        dd($querystring);
        return $target_values;
    }

    public static function get_target_by_year($this_year,$indicator_id){

        $target_values = DB::table('ace_indicators_targets')
            ->join('ace_indicators_target_years', 'ace_indicators_targets.target_year_id', '=', 'ace_indicators_target_years.id')
            ->select(DB::raw('ace_indicators_targets.target as targets'))
            ->whereIn('indicator_id',$indicator_id)
            ->where(function ($query) use($this_year){
                return $query->where( DB::raw('YEAR(start_period)'),$this_year)
                    ->orWhere(DB::raw('YEAR(end_period)'),$this_year);
            })
            ->value('targets');

        return $target_values;
    }




}
