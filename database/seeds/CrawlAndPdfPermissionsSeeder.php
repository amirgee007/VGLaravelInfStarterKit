<?php

use Illuminate\Database\Seeder;
use Vanguard\Permission;
use Vanguard\Role;

class CrawlAndPdfPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', 'Admin')->first();

        $permissions[] = Permission::create([
            'name' => 'crawl.manage',
            'display_name' => 'Manage Potential Products',
            'description' => 'Scraping data and manage it.',
            'removable' => true
        ]);

        $permissions[] = Permission::create([
            'name' => 'pdf.manage',
            'display_name' => 'PDF Editor',
            'description' => 'Upload data and download Edited PDF.',
            'removable' => true
        ]);

        $adminRole->attachPermissions($permissions);
    }
}
