<?php

namespace Signifly\Manageable\Test\Models;

use Signifly\Manageable\Manageable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Manageable;

    protected $fillable = [
        'title',
    ];
}
