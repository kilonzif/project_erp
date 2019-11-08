
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
            @endfor
        @else
                <h5 class="text-center">No Fields have been assigned to this Indicators.</h5>
        @endif