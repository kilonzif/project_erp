
<p class="text-danger">NB: <strong>Data will start reading from row number {{$start_row}}</strong></p>
@if(collect($data)->count() > 0)
    @php
        foreach ($data as $key => $datum){
        $headers[] = $datum['label'];
        }
    @endphp
    @for($a=0;$a < count($headers);$a++)

        <div class="badge badge-pill badge-square bg-success bg-darken-4 p-1 white" style="margin-bottom: 4px;">
            {{$headers[$a]}}
        </div>
    @endfor
@else
        <h5 class="text-center">No Fields have been assigned to this Indicators.</h5>
@endif