<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsArr = [];
        $user = User::create([
            'firstname' => 'admin',
            'lastname' => 'admin',
            'phonenumber' => '123456',
            'email' => 'masa@masasoft.net',
            'role' => 'owner',
            'language' => 'ar',
            'password' => bcrypt('masa'),
        ]);
        //  $user->syncPermissions(['create-permission','edit-permission','view-permission','delete-permission','view-role','create-role','edit-role','delete-role']);
        $permissions = Permission::get('name');
        foreach ($permissions as $permission) {
            array_push($permissionsArr, $permission->name);
        }
        $user->syncPermissions($permissionsArr);









        // $user->assignRole(['owner']);
        //  $role = Role::where('name','owner')->first();

        //  $permissions = Permission::pluck('id','id')->all();

        //  $role->syncPermissions($permissions);

        //  $user->assignRole([$role->id]);
    }
}
