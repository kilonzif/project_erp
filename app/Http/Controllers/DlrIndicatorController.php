<?php

namespace App\Http\Controllers;

use App\AceDli;
use App\AceDlrIndicator;
use App\AceDlrIndicatorCost;
use App\Classes\ToastNotification;
use App\Indicator;
use App\Project;
use App\UnitMeasure;
use Complex\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class DlrIndicatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Showing the list of Main Indicators
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indicators()
    {
        $indicators = AceDlrIndicator::where('is_parent','=', 1)
            ->orderBy('order','asc')
            ->orderBy('indicator_title','asc')
            ->get();
        return view('settings.dlrs.index', compact('indicators'));
    }

    /**
     * Returns the edit view of selected Indicator
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function activate_indicator(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $indicator = AceDlrIndicator::find($id);

        if ($indicator->status == 1){
            $indicator->status = 0;
            $indicator->save();
            $indicator->where('parent_id','=',$id)->update(['status'=>0]);
            notify(new ToastNotification('Successful!', 'DLR Indicator Deactivated', 'success'));
        }else{
            $indicator->status = 1;
            $indicator->save();
            $indicator->where('parent_id','=',$id)->update(['status'=>1]);
            notify(new ToastNotification('Successful!', 'DLR Indicator Activated', 'success'));
        }
        return back();
    }
    public function activate_sub_indicator(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $indicator = AceDlrIndicator::find($id);

        if ($indicator->status == 1){
            $indicator->status = 0;
            $indicator->save();
            notify(new ToastNotification('Successful!', 'DLR Indicator Deactivated', 'success'));
        }else{
            $indicator->status = 1;
            $indicator->save();
            notify(new ToastNotification('Successful!', 'DLR Indicator Activated', 'success'));
        }
        $check_parent = new AceDlrIndicator();
        if ($check_parent->isParentIndicator($request->parent_id)) {
            $status = 1;
        }
        AceDlrIndicator::where('id','=', $request->parent_id)->update([
            'is_parent' => $status
        ]);
        return back();
    }

    /**
     * Returns the edit view of selected Indicator
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_indicator(Request $request)
    {
        $indicators = AceDlrIndicator::where('parent_id','=', 0)
            ->orderBy('order','asc')
            ->orderBy('indicator_title','asc')
            ->get();
        $indicator = AceDlrIndicator::find($request->id);
        $view = view('settings.dlrs.edit', compact('indicator','indicators'))->render();
        return response()->json(['theView'=>$view]);
    }

    /**
     * Add New Main Indicator
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_indicator(Request $request)
    {

        $this->validate($request,[
            'title' => 'required|string|min:5',
            'order' => 'required|numeric|min:1',
            'parent_id' => 'nullable|numeric|min:0',
            'set_max_dlr' => 'nullable|numeric|min:0|max:1'
        ]);

        $set_parent = 0;
        if ($request->parent_id < 0) {
            $set_parent = 1;
        }
        AceDlrIndicator::updateOrCreate([
            'indicator_title' => $request->title,
        ],[
            'order' => $request->order,
            'parent_id' => $request->parent_id,
            'set_max_dlr' => $request->set_max_dlr,
            'status' => 1,
            'is_parent' => $set_parent
        ]);
        notify(new ToastNotification('Successful!', 'DLR Indicator Added!', 'success'));
        return back();
    }

    /**
     * Update Main Indicator
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_indicator(Request $request)
    {

        $this->validate($request,[
            'title' => 'required|string|min:3',
            'order' => 'required|numeric|min:1',
            'parent_id' => 'nullable|numeric|min:0',
            'set_max_dlr' => 'nullable|numeric|min:0|max:1'
        ]);

        AceDlrIndicator::where('id','=', $request->id)->update([
            'indicator_title' => $request->title,
            'parent_id' => $request->parent_id,
            'order' => $request->order,
            'set_max_dlr' => $request->set_max_dlr,
        ]);

        if ($request->parent_id > 0) {
            $status = 0;
            $check_parent = new AceDlrIndicator();
            if ($check_parent->isParentIndicator($request->parent_id)) {
                $status = 1;
            }
            AceDlrIndicator::where('id','=', $request->parent_id)->update([
                'is_parent' => $status
            ]);
        }

        notify(new ToastNotification('Successful!', 'DLR Indicator Updated!', 'success'));
        return back();
    }

    /**
     * Main Indicator Config page
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function config_indicator($id)
    {
        $indicator = AceDlrIndicator::find($id);
        $sub_indicators = AceDlrIndicator::where('parent_id','=',$indicator->id)->orderBy('parent_id','asc')->orderBy('order','asc')->get();
        return view('settings.dlrs.config',compact('indicator','sub_indicators'));
    }

    /**
     * Add Sub Indicator
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_sub_indicator(Request $request){
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'order' => 'required|string|min:1',
        ]);
        try{
            $parent = AceDlrIndicator::find($request->indicator);
            $master_parent_id = null;
            if ($parent->parent) {
                $master_parent_id = $parent->parent->id;
            }
            $parent->update(['is_parent'=>1]);

            AceDlrIndicator::updateOrCreate([
                'indicator_title' => $request->title],
            [
                'parent_id' => $parent->id,
                'master_parent_id' => $master_parent_id,
                'status' => $parent->status,
                'order' => $request->order,
            ]);
        }catch (Exception $exception){
            notify(new ToastNotification('Unsuccessful!', 'Please check your details!', 'warning'));
        }

        notify(new ToastNotification('Successful!', 'Sub-Indicator Created!', 'success'));
        return back();
    }

    /**
     * Returns a view response for Sub-Indicator editing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_sub_indicator(Request $request)
    {
        $sub_indicator = AceDlrIndicator::find($request->id);

        $view = view('settings.dlrs.edit_sub',compact('sub_indicator'))->render();
        return response()->json(['theView'=>$view]);
    }

    /**
     * Update Sub-Indicator details
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_sub_indicator(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'id' => 'required|numeric|min:1',
            'order' => 'required|numeric|min:1',
        ]);

        AceDlrIndicator::where('id','=', $request->id)->update([
            'indicator_title' => $request->title,
            'order' => $request->order,
        ]);

        notify(new ToastNotification('Successful!', 'Sub-Indicator Updated!', 'success'));
        return back();
    }

    /**
     * Save DLR Costs
     * @param Request $request
     * @param $ace_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_dlr_costs(Request $request,  $ace_id)
    {

        $this->validate($request,[
            'single' => 'nullable|array|min:1',
            'max' => 'nullable|numeric|min:0',
            'single.*' => 'nullable|numeric|min:0',
        ]);
//           dd($request->card_id);

        if ($request->max) {
            AceDlrIndicatorCost::updateOrCreate([
                'ace_id' => $ace_id,
                'ace_dlr_indicator_id' => $request->parent_id,
            ],[
                'max_cost' => $request->max,
                'currency_id' => $request->currency,
            ]);
        }

        if ($request->single) {
            foreach ($request->single as $indicator => $value) {
                AceDlrIndicatorCost::updateOrCreate([
                    'ace_id' => $ace_id,
                    'ace_dlr_indicator_id' => $indicator,
                ], [
                    'unit_cost' => $value,
                ]);
            }
        }
        notify(new ToastNotification('Successful', 'DLR Indicator Costs Saved.', 'success'));
        $id = "#".$request->card_id;


        return Redirect::to(URL::previous().$id);

//        return back();
    }

}

