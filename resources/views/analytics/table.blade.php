                <div class="row pull-right">
                    <div class="col-12">
                        <a href="{{ route('analytics.export_data').'?'.http_build_query(["resultset"=>$resultset]) }}"
                           class="btn btn-outline-success btn-md form-group">Export To Excel
                        </a>
                    </div>
                </div>

                        <table class="table table-striped table-bordered" >
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

