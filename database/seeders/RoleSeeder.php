<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        $role_name = __('System Admin');
        if (!$role = Role::where('name', $role_name)->first()){
            echo "create role $role_name\n";
            $role = Role::create(['name' => $role_name]);
        }
        $i=1;
        User::find($i++)->assignRole($role);
        foreach (config('seed.roles') as $role_name => $array) {
            $role_name = __(ucwords($role_name));
            if (!$role = Role::where('name', $role_name)->first()){
                echo "create role $role_name\n";
                $role = Role::create(['name' => $role_name]);
            }
            $perms = [];
            foreach ($array as $item) {
                $b = explode(' ', $item);
                $perms[] = __($b[0]) . (($b[1]??null) ? __($b[1]) : '');
            }
            echo "-----------------------------------------\n";
            echo "set perms to role $role_name \n";
            echo implode(',',$perms) . "\n";
            $permissions = Permission::whereIn('name', $perms)->get();
            $role->permissions()->sync($permissions);
            
            User::find($i++)->assignRole($role);
        }        
    }
}
