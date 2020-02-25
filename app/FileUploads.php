<?php
/**
 * Created by PhpStorm.
 * User: Faith Kilonzi
 * Date: 2/13/2020
 * Time: 1:37 PM
 */


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileUploads extends Model
{
    protected $table ="files_uploads";

    protected $fillable = ['ace_id','file_one','file_two','comments','file_category','status'];



    public function ace(){
        $this->hasMany('App\Ace');
    }


}