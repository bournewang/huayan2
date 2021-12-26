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
        //
        $name = __('Store').__('Admin');
        // foreach ($names as $name) {
        Role::whereNotNull('id')->forceDelete();
        $role = Role::create(['name' => $name, 'guard_name' => 'web']);
        echo "!!!!! create role $name \n";
        // }
        
        // $actions = ['View', 'Create', 'Update', 'Delete', 'Restore', 'ForceDelete'];
        // $resources = ['Banner', 'Goods', 'Order', 'Revenue', 'Store', 'User'];
        $data = [
            __('View').__('Banner'), __('Create').__('Banner'), 
            __('Update').__('Banner'), __('Delete').__('Banner'), 
            __('Restore').__('Banner'), __('ForceDelete').__('Banner'), 
            
            __('View').__('Category'), __('Action').__('Category'),
            __('View').__('Goods'), __('Action').__('Goods'),
            __('View').__('Order'), __('Action').__('Order'),
            __('View').__('Revenue'), __('Action').__('Revenue'),
            __('View').__('Store'), __('Update').__('Store'),
            __('View').__('User'), __('Action').__('User'), 
        ];
        // foreach ($data as $item) {}
        $permissions = Permission::whereIn('name', $data)->get();
        echo "  set permissions: " . implode(',', $permissions->pluck('name')->all()) . "\n";
        $role->syncPermissions($permissions);
        
        User::find(2)->assignRole($role);
    }
}
