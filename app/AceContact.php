<?php
/**
 * Created by PhpStorm.
 * User: Faith Kilonzi
 * Date: 2/27/2020
 * Time: 11:20 AM
 */
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AceContact extends Pivot
{
    //
    protected $table = 'ace_contacts';
    protected $fillable = ['ace_id','contact_id'];
}