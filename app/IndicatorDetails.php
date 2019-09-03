<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class IndicatorDetails extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'indicator_form_details';
}
