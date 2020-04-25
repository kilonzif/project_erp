<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Results</h4>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered dlr-info">
                            <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                @foreach($headers as $key=>$header)
                                    <th>{{$header}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @php $counter = 1;@endphp

                            @foreach($indicator_details as $indicator_detail)
                                @php
                                    $data = $indicator_detail->data;
                                    $slugs = $englishSlugs;
                                    if($indicator_detail->language == "french") {
                                        $slugs = $frenchSlugs;
                                    }
                                @endphp

                                @foreach($data as $dlr_data)
                                    <tr>
                                        <td>{{$counter++}}</td>
                                        @foreach($slugs as $slug)
                                            @if(isset($dlr_data[$slug]))
                                                <td>{{$dlr_data[$slug]}}</td>
                                            @else
                                                <td>N/A</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach

                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>