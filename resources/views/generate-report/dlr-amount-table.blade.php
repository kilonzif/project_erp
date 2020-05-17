<table class="table table-bordered table-striped" id="generalReporting">
    <thead>
    <tr>
        <th rowspan="2" style="width: 400px;">DISBURSEMENT LINKED INDICATORS INFO</th>
        <th colspan="{{count($total_currencies)}}" rowspan="2"></th>
        <th colspan="{{count($total_currencies)}}" class="text-center">SUMMARY OF EARNINGS & BALANCE</th>
    </tr>
    <tr>
        @foreach($total_currencies as $key  =>  $currency)
            <td class="text-center">{{$currency}}</td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
        <td rowspan="2">DLR Indicators</td>
        <td colspan="2" class="text-center">UNIT COST</td>
        @foreach($total_currencies as $key  =>  $currency)
            <td rowspan="2" class="text-center" style="width: 170px;">Maximum  {{$currency}}</td>
        @endforeach
    </tr>
    <tr>
        @foreach($total_currencies as $key  =>  $currency)
            <td class="text-center" style="width: 170px;">{{$currency}}</td>
        @endforeach
    </tr>
    @foreach($ace_dlrs as $dlr)
        @php
            $max_row_span = 0;
        @endphp
        <tr>
            <td style="{{($dlr->is_parent)?'font-weight: 700':''}}">{{$dlr->indicator_title}}
            </td>
            @foreach($total_currencies as $key  =>  $currency)
                @php
                    $index = $dlr->id;
                    $value = 0;
                        if(isset($dlr_values[$index])){
                            $value = $dlr_values[$index];
                        }
                @endphp
                <td class="text-center">
                    @if($dlr->master_parent)
                        @if(array_key_exists($dlr->master_parent->id,$dlr_currencies))
                            @if($dlr_currencies[$dlr->master_parent->id] == $key && !$dlr->is_parent)
                                {{$value}}
                            @endif
                        @endif
                    @endif
                </td>
            @endforeach

            @if($dlr->is_milestone)
        </tr>
        @if(array_key_exists($dlr->original_indicator_id,$milestone_dlrs))
            @for($a=1; $a <= $milestone_dlrs[$dlr->original_indicator_id]; $a++)
                <tr>
                    @php $max_row_span += 1;@endphp
                    <td>Milestone {{$a}}</td>
                    @foreach($total_currencies as $key  =>  $currency)
                        @php
                            $index = (integer)"$a".$dlr->id."$a";
                            $value = 0;
                                if(isset($dlr_values[$index])){
                                    $value = $dlr_values[$index];
                                }
                        @endphp
                        <td class="text-center">
                            @if($dlr->master_parent)
                                @if(array_key_exists($dlr->master_parent->id,$dlr_currencies))
                                    @if($dlr_currencies[$dlr->master_parent->id] == $key)
                                        {{$value}}
                                    @endif
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endfor
        @endif
        @endif
        @if($dlr->set_max_dlr)
            @foreach($total_currencies as $key  =>  $currency)
                <td rowspan="{{$max_row_span+$master_parent_total[$dlr->id]+1}}" class="text-center" style="width: 170px;">
                    @if(array_key_exists($dlr->id,$dlr_currencies))
                        @if($dlr_currencies[$dlr->id] == $key)
                            {{money_format($dlr_max_cost[$dlr->id],2)}}
                        @endif
                    @endif
                </td>
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>