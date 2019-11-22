<form action="{{route('settings.app_settings.update_reporting_period')}}" method="post">
    <div class="row">
        @csrf
        <div class="col-md-5 form-group">
            <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($update_this_period->id)}}">
            <label>Starting Period<span class="required">*</span></label>
            <div class="input-group">
                <input type='text' name="period_start"  required class="form-control datepicker" placeholder="Month &amp; Year" value="{{ (old('period_start')) ? old('period_start') : $toupdate_start_period}}"
                />
                <div class="input-group-append">
                    <span class="input-group-text">
                    <span class="fa fa-calendar-o"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-5 form-group">
            <label>Ending Period<span class="required">*</span></label>
            <div class="input-group">
                <input type='text' name="period_end" required class="form-control datepicker" placeholder="Month &amp; Year"  value="{{ (old('period_end')) ? old('period_end') : $toupdate_end_period}}"
                />
                <div class="input-group-append">
                    <span class="input-group-text">
                    <span class="fa fa-calendar-o"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-2 form-group">
            <button class="btn btn-primary" style="margin-top: 1.7rem;" type="submit"> Update</button>
        </div>
    </div>
</form>


    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/js/core/libraries/bootstrap.min.js')}}" type="text/javascript"></script>



    <script src="{{asset('app-assets/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script>
        $('.datepicker').datepicker({
            format:"mm-yyyy",
            startView:"months",
            minViewMode:"months"
        });

    </script>






















