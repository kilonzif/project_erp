
{{--<ul class="list-group">--}}
@if($excel_upload)
<a href="{{ route('settings.excelupload.download',  [\Illuminate\Support\Facades\Crypt::encrypt($excel_upload->id)] ) }}"
   class="btn btn-s btn-outline-secondary mb-2">
    <i class="fa fa-cloud-download"></i> Download Template
</a>
@endif
<br>
        @if(sizeof($getHeaders) > 0)
            @php
                for ($a = 0; $a < sizeof($getHeaders[0]); $a++){
                $headers[] = $getHeaders[0][$a]['label'];
                }
            @endphp
            @for($a=0;$a < sizeof($headers);$a++)
                <div class="badge badge-pill badge-square bg-success bg-darken-4 p-1 white" style="margin-bottom: 4px;">
                    {{$headers[$a]}}
                </div>
                {{--<li style="padding: 10px 5px;" class="list-group-item">{{$headers[$a]}}</li>--}}
            @endfor
        @else
                {{--<li style="padding: 10px 5px;" class="list-group-item text-center">No Indicators defined</li>--}}
                <h5 class="text-center">No Fields have been assigned to this Indicators.</h5>
        @endif
{{--</ul>--}}