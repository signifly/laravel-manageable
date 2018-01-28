<?php

namespace Signifly\Manageable\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Signifly\Manageable\Manageable;

class Order extends Model
{
    use Manageable;

    protected $fillable = [
        'title',
    ];
}
