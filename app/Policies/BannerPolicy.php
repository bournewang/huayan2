<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BannerPolicy extends BasePolicy
{
    public $name = 'Banner';
}
