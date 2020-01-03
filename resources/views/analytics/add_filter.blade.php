<div id="filter-{{$filtercount}}" class="row">
<div class="col-3">
    <label for="filter-by" style="margin-top: 1.1rem">Filter By</label>
    <div class="input-group">
        <select class="form-control select-lg filter_select_{{$filtercount}}" onchange="filterselect('filter_select_{{$filtercount}}','{{$filtercount}}')" name="filter_by[]" id="filter_by">
            <option selected value="Countries">Countries</option>
            <option value="Type of Centre">Type of Centre</option>
            <option value="Field of Study">Field of Study</option>
            <option value="ACE">Ace</option>
        </select>
    </div>
</div>
<div class="col-3">
    <div class="accordion-icon-rotate left" id="forField{{$filtercount}}" style="display:none">
        <div id="byField_2" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
            <a href="#byCountry" aria-expanded="false" aria-controls="byCountry"
               class="card-title lead gray">Filter By Field of Study</a>
        </div>
        <div class="card-content">
            <select  multiple="multiple" name="field[]"  class="form-control select2" id="field[]" style="width: 100% !important;">
                @foreach($fields as $key=>$field)
                    <option  value="{{$field}}">{{$field}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="accordion-icon-rotate left" id="forCountry{{$filtercount}}">
        <div id="byField_2" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
            <a href="#byCountry" aria-expanded="false" aria-controls="byCountry"
               class="card-title lead gray">Filter By Country</a>
        </div>
        <div id="byCountry" role="tabpanel" aria-labelledby="byCountry" aria-expanded="false">
            <div class="card-content">
                <select  multiple="multiple" name="country[]"  class="form-control select2 multiple_values"  style="width: 100% !important;">
                    @foreach($countries as $country)
                        <option  value="{{$country->id}}">{{$country->country}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="accordion-icon-rotate left" id="typeofcentre{{$filtercount}}" style="display:none">
        <div id="byField_3" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
            <a href="#typeofcentre" aria-expanded="false" aria-controls="typeofcentre"
               class="card-title lead gray">Filter By Type of Centre</a>
        </div>
        <div id="typeofcentre" role="tabpanel" aria-labelledby="typeofcentre" aria-expanded="false">
            <div class="card-content">
                <select  multiple="multiple" name="typeofcentre[]"  class="form-control select2 multiple_values" id="typeofcentre{{$key}}" style="width: 100% !important;">
                    @foreach($type_of_centres as $key=>$centre)
                        <option  value="{{$centre}}">{{$centre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="accordion-icon-rotate left" role="tabpanel" id="filterbyace{{$filtercount}}" style="display:none">
        <div id="byField_3" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
            <a href="#typeofcentre" aria-expanded="false" aria-controls="typeofcentre"
               class="card-title lead gray">Filter By Ace</a>
        </div>
        <div role="tabpanel" aria-labelledby="filterbyace" aria-expanded="false">
            <div class="card-content">
                <select  multiple="multiple" name="selected_ace[]"  class="form-control select2 multiple_values" id="selected_ace" style="width: 100% !important;">
                    @foreach($aces as $this_ace)
                        <option  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

</div>
<div class="col-md-1">
    <span style="float: left!important;margin-top: 42px">
        <button type="button" onclick="removefilter('filter-{{$filtercount}}')" class="btn btn-md btn-danger"><i class="fa fa-close"></i> </button>
    </span>
</div>
</div>

<script>
    $('.select2').select2({
        placeholder: "",
        allowClear: true
    });
</script>