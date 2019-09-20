<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $males = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","M")->orWhere('gender',"=","Male")->count();
        $females = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","F")->orWhere('gender',"=","Female")->count();

        return view('analytics.index');
    }


    public function getGenderDistribution(Request $request)
    {
        $males = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","M")->orWhere('gender',"=","Male")->count();
        $females = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","F")->orWhere('gender',"=","Female")->count();

        return response()->json(['male'=>$males,'female'=>$females]);
    }

    public function getCumulativePDO(Request $request)
    {
        $the_view = view('analytics.cumulative_pdo')->render();
        return response()->json(['the_view'=>$the_view]);
    }
}
