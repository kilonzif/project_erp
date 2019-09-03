<?php

namespace App\Http\Controllers;

use App\Ace;
use App\AceReport;
use App\Classes\ToastNotification;
use App\Project;
use App\Report;
use App\ReportValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Comment;


class CommentController extends Controller
{
    //

      use ValidatesRequests, AuthorizesRequests;


    public function __construct()
    {
        $this->middleware(['web', 'auth']);
    }


    public function index($id)
    {
       //$id = Crypt::decrypt($id);
        $project = Project::where('id','=',1)->where('status','=',1)->first();
        $report = Report::find($id);
        $values = ReportValue::where('report_id','=',$id)->pluck('value','indicator_id');

        $aces = Ace::where('active','=',1)->get();
        return view('comment',compact('project','report','aces','values'));
    
    }



 
    public function store(Request $request)
    {
        $this->validate($request, [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer|min:1',
            'message' => 'required|string'
        ]);

        $model = $request->commentable_type::findOrFail($request->commentable_id);

        $comment_ob = new Comment;
        $comment_ob->commenter()->associate(auth()->user());
        $comment_ob->commentable()->associate($model);
        $comment_ob->comment = $request->message;
        $comment_ob->save();

        $comment = Comment::find($comment_ob->id);

      //  $view = $new_comment->id." => ".$new_comment->commenter->name;
        $view= view('vendor.comments._comment',compact('comment'))->render();
        return response()->json(['view'=> $view]);

        //echo"  <p> dgdg </p> ";











        
        //notify(new ToastNotification('Successful!', 'Comment Added!', 'success'));
       //echo "We got here";
        //return redirect()->to(url()->previous() . '#comment-' . $comment->id);
    }

    /**
     * Updates the message of the comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('edit-comment', $comment);

        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $comment->update([
            'comment' => $request->message
        ]);
        notify(new ToastNotification('Successful!', 'Comment Updated!', 'success'));

        return redirect()->to(url()->previous() . '#comment-' . $comment->id);
    }

    /**
     * Deletes a comment.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete-comment', $comment);

        $comment->delete();
        notify(new ToastNotification('Successful!', 'Comment Deleted!', 'success'));

        return redirect()->back();
    }

    /**
     * Creates a reply "comment" to a comment.
     */
    public function reply(Request $request, Comment $comment)
    {
        $this->authorize('reply-to-comment', $comment);

        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $reply = new Comment;
        $reply->commenter()->associate(auth()->user());
        $reply->commentable()->associate($comment->commentable);
        $reply->parent()->associate($comment);
        $reply->comment = $request->message;
        $reply->save();
                notify(new ToastNotification('Successful!', 'Comment Replied!', 'success'));

        return redirect()->to(url()->previous() . '#comment-' . $reply->id);
    }


    
}

