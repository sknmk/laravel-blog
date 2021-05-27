<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        $roles = Role::all()->pluck('id');
        $permissions = Permission::all()->pluck('id');

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'write articles']);
        Permission::create(['name' => 'publish articles']);

        $author = Role::create(['name' => 'author']);
        $author->givePermissionTo('edit articles');
        $author->givePermissionTo('write articles');
        $author->givePermissionTo('delete articles');

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('edit articles');
        $admin->givePermissionTo('write articles');
        $admin->givePermissionTo('delete articles');
        $admin->givePermissionTo('publish articles');

        $moderator = Role::create(['name' => 'moderator']);
        $moderator->givePermissionTo('edit articles');
        $moderator->givePermissionTo('publish articles');

        Role::create(['name' => 'reader']);

        Artisan::call('cache:clear');
    }
}
