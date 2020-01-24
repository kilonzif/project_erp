<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
//use Jenssegers\Mongodb\Eloquent\Model;

class IndicatorForm extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'indicator_form';



}
