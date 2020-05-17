<table class="table table-bordered table-striped" id="generalReporting">
    <thead>
    <tr>
        <th rowspan="2">DISBURSEMENT LINKED INDICATORS INFO</th>
        <th colspan="2" rowspan="2"></th>
        <th colspan="2" class="text-center">SUMMARY OF EARNINGS & BALANCE</th>
    </tr>
    <tr>
        <td class="text-center">SDR</td>
        <td class="text-center">EURO</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td rowspan="2">DLR Indicators</td>
        <td colspan="2" class="text-center">UNIT COST</td>
        <td rowspan="2" class="text-center">Maximum  SDR</td>
        <td rowspan="2" class="text-center">Maximum  EURO</td>
    </tr>
    <tr>
        <td class="text-center">SDR</td>
        <td class="text-center">EURO</td>
    </tr>
    @foreach($parent_indicators as $parent_indicator)
        <tr>
            <td>{{$parent_indicator->indicator_title}}</td>
        </tr>
    @endforeach
    </tbody>
</table>