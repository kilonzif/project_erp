<div class="row">
    <div class="col-12">
        <div class="card-content collapse show">
            <div class="card-body card-dashboard table-responsive">
                <div class="row">
                    <table class="table table-striped table-bordered reporttables" >
                        <thead>
                        <tr>
                            @foreach($resultset as $key=> $value)
                            <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @if($request->topic_name =="list of donors")
                        @foreach($resultset->Source as $key => $item)
                        <tr>
                            <td>{{$item}}</td>
                            <td>{{$resultset->Amount[$key]}}</td>
                        </tr>
                        @endforeach

                            @else
                            <tr>
                            @foreach($resultset as $item)
                                    <td>{{$item}}</td>
                            @endforeach
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
