<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table ="faqs";
    protected $fillable = ['question','answer','status','category','added_by'];

    public function user()
    {
        return $this->belongsTo('App\User','added_by');
    }
}
