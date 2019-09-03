<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcelUpload extends Model
{
    //
    protected $fillable =['indicator_id','upload_file'];

    public function indicator()
    {
        return $this->belongsTo('App\Indicator');
    }

}
