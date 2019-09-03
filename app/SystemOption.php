<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemOption extends Model
{
    //
    protected $fillable = ['slug','option_name','display_name','option_value','description','option_parent_id'];
}
