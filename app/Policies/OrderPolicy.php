<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy extends BasePolicy
{
    public $name = 'Order';
}
