<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@tidop.es',
            'password' => Hash::make('admin@tidop.es')
        ]);

        $role = Role::create(['name' => 'Administrator']);

        $permission = Permission::create(['name' => 'Application user']);
        $permission1 = Permission::create(['name' => 'Permission management']);
        $permission2 = Permission::create(['name' => 'Role management']);
        $permission3 = Permission::create(['name' => 'User management']);
        $permission4 = Permission::create(['name' => 'File manager']);

        $role->syncPermissions([$permission1,$permission2,$permission3,$permission4]);

        $user->assignRole('Administrator');

    }
}
