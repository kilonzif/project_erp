<table class="table table-bordered table-striped" id="generalReporting">
    {{--<tr>--}}
    @php
        $indicators = \App\Indicator::where('is_parent','=',1)
                ->where('status','=',1)
                ->where('show_on_report','=',1)
               ->orderBy('order_on_report','asc')->get();
        $year_number = ['2019'=>1,'2020'=>2,'2021'=>3,'2022'=>4,'2023'=>5];
    @endphp
    <thead>
    <tr>
        <th style="width: 300px" rowspan="2" >ACE Level Results Indicators</th>
        <th style="width: 15px" rowspan="2">Core</th>
        <th style="min-width: 300px" rowspan="2">Unit of Measure</th>
        <th style="min-width: 250px;" rowspan="2">Specifics</th>
        <th style="width: 50px;text-align: right;" rowspan="2">Baseline</th>
        <th style="width: 50px;text-align: center;" colspan="{{sizeof($years)}}">Annual Target Values</th>
        @for($a = 1; $a <= sizeof($years); $a++)
        <th style="width: 50px;text-align: right;" rowspan="2">{{$years[$a-1]}} Results</th>
        @endfor
        <th style="width: 50px;text-align: right;" rowspan="2">Total Results</th>
    </tr>
    <tr>
        @for($a = 1; $a <= sizeof($years); $a++)
        <th style="width: 50px;text-align: right;">{{"Year ".$year_number[$years[$a-1]]}}</th>
        @endfor
    </tr>
    </thead>
    <tbody>

    @foreach($indicators as $indicator)
        @php
            $counter = $indicator->indicators->where('show_on_report','=',1)->count();
        @endphp

            <tr>
                <td @if($counter > 0) rowspan="{{$counter}}" @endif>
                    <strong>{{$indicator->identifier}}:</strong> {{$indicator->title}}
                </td>
                <td @if($counter > 0) rowspan="{{$counter}}" @endif></td>
                <td @if($counter > 0) rowspan="{{$counter}}" @endif>
                    {{$indicator->unit_measure}}
                </td>
                @php
                    $sub_indicators = $indicator->indicators->where('status','=',1)->where('show_on_report','=',1);

                    $count = 0;
                    $total_result = 0;
                @endphp
                @if($sub_indicators->count() > 0)
                    @foreach($sub_indicators as $sub_indicator)
                        @php
                            $total_result = 0;
                        @endphp
                        <td>
                            {{$sub_indicator->title}}
                        </td>
                        <td class="text-right">
                            @if(sizeof($baseline_values) > 0 && array_key_exists($sub_indicator->id,$baseline_values))
                                {{$baseline_values[$sub_indicator->id]}}
                            @else
                                0
                            @endif
                        </td>

                        {{--Set targets--}}
                        @foreach($years as $key=>$year)
                            <td class="text-right">
                                @if(sizeof($target_values["$year"]) > 0 && array_key_exists($sub_indicator->id,$target_values["$year"]))
                                    {{$target_values["$year"][$sub_indicator->id]}}
                                @else
                                    0
                                @endif
                            </td>
                        @endforeach

                        {{--Set Year Result--}}
                        @foreach($years as $key=>$year)
                            @php
                                $count +=1;
                                try{
                                    $value = $report_values["$year"]->where('indicator_id','=',$sub_indicator->id)
                                    ->pluck('ind_values')->first();
                                    $total_result += (integer)$value;
                                }
                                catch(Exception $exception){
                                    $value = 0;
                                }
                            @endphp
                            <td class="text-right">
                                <strong>{{($value== ""|| $value== 0)?0:$value}}</strong>
                            </td>
                        @endforeach

                        <td class="text-right"><strong>{{$total_result}}</strong></td>
                        @if($count = 1)
                            </tr><tr>
                        @else
                            </tr>
                        @endif
                    @endforeach
                @else
                    <td></td>
                    <td class="text-right">
                        @if(sizeof($baseline_values) > 0 && array_key_exists($indicator->id,$baseline_values))
                            {{$baseline_values[$indicator->id]}}
                        @else
                            0
                        @endif
                    </td>
                    @foreach($years as $key=>$year)
                        <td class="text-right">
                            @if(sizeof($target_values["$year"]) > 0 && array_key_exists($indicator->id,$target_values["$year"]))
                                {{$target_values["$year"][$indicator->id]}}
                            @else
                                0
                            @endif
                        </td>
                    @endforeach
                    @foreach($years as $key=>$year)
                        @php
                            $count +=1;
                            try{
                                $value = $report_values["$year"]->where('indicator_id','=',$indicator->id)
                                ->pluck('ind_values')->first();
                                $total_result += (integer)$value;
                            }
                            catch(Exception $exception){
                                $value = 0;
                            }
                        @endphp
                        <td class="text-right">
                            <strong>{{($value== ""|| $value== 0)?0:$value}}</strong>
                        </td>
                    @endforeach
                    <td class="text-right"><strong>{{$total_result}}</strong></td>
                    </tr>
                @endif
        @endforeach
    </tbody>
</table>