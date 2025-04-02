<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear el usuario admin
        $adminUser = User::create([
            'name' => 'Admin',
            'last_name' => 'Casco',
            'ci' => '6560887',
            'user' => 'admin',
            'password' => bcrypt('123'),
            'email' => 'cristhian.casco@hotmail.com',
            'phone' => '0983668960',
            'address' => 'CDE',
            'profile' => 'ADMIN',
            'status' => 'ACTIVO',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
        ]);

        // Crear permisos
        $permissions = [
            'Category_Index',
            'Category_Create',
            'Category_Update',
            'Category_Destroy',
            'Category_Search',
            'Product_Index',
            'Product_Search',
            'Product_Create',
            'Product_Update',
            'Product_Destroy',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear rol de administrador y asignar todos los permisos
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Asignar rol de administrador al usuario admin
        $adminUser->assignRole($adminRole);
    }
}