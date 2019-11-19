<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\Faq;
use Illuminate\Http\Request;

class FaqsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $faqs = Faq::all();
        return view('faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('faqs.create');
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'question' => 'required|string|min:3',
            'description' => 'required|string',
            'active' => 'nullable|boolean',
            'category' =>'required|string|min:3'
        ]);

        $visible = false;
        if (isset($request->active)){
            $visible = true;
        }

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->description,
            'status' => $visible,
            'category' =>$request->category,
            'added_by' => \Auth::id()
        ]);
        if ($faq){
            notify(new ToastNotification('Successful','FAQ added.','success'));
            return redirect()->route('faqs');
        }
        else{
            notify(new ToastNotification('Sorry','Something went wrong. Please check your value.','warning'));
            return back();
        }
    }

    public function edit($id)
    {
        $faq = Faq::find($id);

        return view('faqs.edit',compact('faq'));
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'question' => 'required|string|min:3',
            'description' => 'required|string',
            'category' =>'required|string|min:3',
            'active' => 'nullable|boolean',
        ]);

        $visible = false;
        if (isset($request->active)){
            $visible = true;
        }

        $faq = Faq::updateOrCreate(['id' => $id],[
            'question' => $request->question,
            'answer' => $request->description,
            'status' => $visible,
            'category' =>$request->category,
            'added_by' => \Auth::id()
        ]);
        if ($faq){
            notify(new ToastNotification('Successful','FAQ updated.','success'));
            return redirect()->route('faqs');
        }
        else{
            notify(new ToastNotification('Sorry','Something went wrong. Please check your value.','warning'));
            return back();
        }
    }


    public function destroy($id)
    {
        Faq::destroy($id);
        notify(new ToastNotification('Successful','FAQ deleted.','success'));
        return back();
    }


    public function faqs()
    {
        $allfaqs = Faq::where('status','=',1)->get();
        $system = Faq::where('category','=','System FAQs')->get();
        $aces = Faq::where('category','=','ACE FAQs')->get();
        $general = Faq::where('category','=','General FAQs')->get();

        return view('faqs.faqs', compact('allfaqs','system','aces','general'));
    }


}
