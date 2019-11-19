<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AceComment extends Model
{
    protected $table ="ace_comments";
    public $timestamps = false;
    protected $fillable = ['user_id','report_id','comments'];



    public function user(){
        $this->hasOne('App\User');
    }




    public static function getCommentDetails($user_id)
    {
        $ace_officer = DB::table('users')
            ->join('ace_comments', 'users.id', '=', 'ace_comments.user_id')
            ->where('users.id', $user_id)
            ->pluck('name');


        $ace_id = DB::table('users')->select('ace')
            ->where('id', $user_id)
        ->pluck('ace');
        $ace_name = DB::table('aces')
            ->where('id', $ace_id)
            ->pluck('name');

        return [$ace_officer,$ace_name];

    }

}
