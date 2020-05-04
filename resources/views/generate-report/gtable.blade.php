<table class="table table-bordered table-striped" i="generalReport">
    {{--<tr>--}}
    @php
        $indicators = \App\Indicator::where('is_parent','=',1)
                ->where('status','=',1)
                //->where('parent_id','<>',0)
                ->where('show_on_report','=',1)
               ->orderBy('order_on_report','asc')->get();
            //$indicators = $project->indicators->where('is_parent','=',1)->where('status','=',1)
            //->where('show_on_report','=',1);
    @endphp
    <thead>
    <tr>
        <th width="300px" style="width: 300px">ACE Level Results Indicators</th>
        <th style="width: 15px">Core</th>
        <th style="width: 200px">Unit of Measure</th>
        <th style="width: 200px">Specifics</th>
        <th style="width: 50px">Baseline</th>
        <th style="width: 50px">Annual Target Values</th>
        <th style="width: 50px">Results as of<br> {{date('F Y',strtotime($end))}}</th>
    </tr>
    </thead>
    <tbody>

    @foreach($indicators as $indicator)
        @php
            $counter = $indicator->indicators->where('show_on_report','=',1)->count();
        @endphp

            <tr>
                <td @if($counter > 0) rowspan="{{$counter}}" @endif>
                    <strong>{{"Indicator ".$indicator->identifier}}:</strong> {{$indicator->title}}
                </td>

                <td @if($counter > 0) rowspan="{{$counter}}" @endif></td>
                <td @if($counter > 0) rowspan="{{$counter}}" @endif>
                    {{$indicator->unit_measure}}
                </td>
                @php
                    $sub_indicators = $indicator->indicators->where('status','=',1)->where('show_on_report','=',1);
                    $count = 0;
                @endphp
                @if($sub_indicators->count() > 1)

                    @foreach($sub_indicators as $sub_indicator)
                        @php
                            $count +=1;
                            try{
                                $value = $report_values->where('indicator_id','=',$sub_indicator->id)->pluck('ind_values')->first();
                            }
                            catch(Exception $exception){
                                $value = 0;
                            }
                        @endphp
                        <td>
                            {{$sub_indicator->title}}
                        </td>
                        <td class="text-right">
                            @if(sizeof($baseline_values) > 0)
                                {{$baseline_values[$sub_indicator->id]}}
                            @else
                                0
                            @endif
                        </td>
                        <td class="text-right">
                            @if(sizeof($target_values) > 0)
                                {{$target_values[$sub_indicator->id]}}
                            @else
                                0
                            @endif
                        </td>
                        <td class="text-right">
                            <strong>{{$value}}</strong>
                        </td>

                        @if($count = 1)
                            </tr><tr>
                        @else
                            </tr>
                        @endif
                    @endforeach
                @else
                    @php
                        try{
                            $value = $report_values->where('indicator_id','=',$indicator->id)->pluck('ind_values')->first();
                        }
                        catch(Exception $exception){
                            $value = 0;
                        }
                    @endphp
                    <td></td>
                    <td class="text-right">
                        @if(sizeof($baseline_values) > 0)
                            {{$baseline_values[$indicator->id]}}
                            @else
                            0
                        @endif
                    </td>
                    <td class="text-right">
                        @if(sizeof($target_values) > 0)
                            {{$target_values[$indicator->id]}}
                        @else
                            0
                        @endif
                    </td>
                    <td class="text-right">
                        <strong>{{$value}}</strong>
                    </td>

                    </tr>

                @endif
        @endforeach
    </tbody>
</table>