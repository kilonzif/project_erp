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
        <tr>
            <td style="{{($dlr->is_parent)?'font-weight: 700':''}}">{{$dlr->indicator_title}}</td>
            @foreach($total_currencies as $key  =>  $currency)
                <td class="text-center">
                    @if($dlr->master_parent)
                        @if(array_key_exists($dlr->master_parent->id,$dlr_currencies))
                            @if($dlr_currencies[$dlr->master_parent->id] == $key && !$dlr->is_parent)
                                <input title="cost" type="number" step="0.01" class="form-control" name="dlr_{{$dlr->id}}">
                            @endif
                        @endif
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>