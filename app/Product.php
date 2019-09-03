<?php

namespace App;
use Laravelista\Comments\Commentable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use Commentable;
}
