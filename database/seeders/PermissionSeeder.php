<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::whereNotNull('id')->forceDelete();
        $actions = ['View', 'Create', 'Update', 'Delete', 'Restore', 'ForceDelete', 'Action'];
        $resources = ['Address', 'Banner', 'Cart', 'Category', 'Goods', 'Order', 'Revenue', 'Store', 'User'];
        foreach ($resources as $res) {
            foreach ($actions as $action) {
                $label = __($action) . __($res);
                $slug = $action . $res;
                $perm = Permission::create(['name' => $label, 'guard_name' => 'web']);
                echo "!!!!! create $slug/$label \n";
            }
        }
    }
}
