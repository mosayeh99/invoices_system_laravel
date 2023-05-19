<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Permissions
        $permissions = [
            'Show Invoices',
            'Add Invoices',
            'Delete Invoices',
            'Edit Invoices',
            'Archive Invoices',
            'Print Invoices',
            'Export Invoices Excel',
            'Show Invoices Reports',
            'Clients Invoices Reports',

            'Show Users',
            'Add Users',
            'Edit Users',
            'Delete Users',

            'Show Roles',
            'Add Roles',
            'Edit Roles',
            'Delete Roles',

            'Show Products',
            'Add Products',
            'Edit Products',
            'Delete Products',

            'Show Departments',
            'Add Departments',
            'Edit Departments',
            'Delete Departments',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
