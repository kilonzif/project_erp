<?php
/**
 * Created by PhpStorm.
 * User: Faith Kilonzi
 * Date: 2/27/2020
 * Time: 11:18 AM
 */


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Position extends Model
{
    protected $fillable = ['position_title','rank'];

    protected $table = 'positions';



}