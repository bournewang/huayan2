<?php
namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class RootPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)              {return $user->isRoot();}
    public function create($user)               {return $user->isRoot();}
    public function view($user, $model)         {return $user->isRoot();}
    public function update($user, $model)       {return $user->isRoot();}
    public function delete($user, $model)       {return $user->isRoot();}
    public function restore($user, $model)      {return $user->isRoot();}
    public function forceDelete($user, $model)  {return $user->isRoot();}
}
