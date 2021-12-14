<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_menu')->insert([
            ['title' => '用户', 'icon' => 'fa-user-md', 'uri' => 'user'],
            ['title' => '轮播图', 'icon' => 'fa-file-image-o', 'uri' => 'banner'],
            ['title' => '碎片', 'icon' => 'fa-500px', 'uri' => 'fragment']
        ]);

        DB::table('admin_menu')->where('id', 1)
            ->update(['order' => 0]);

        DB::table('admin_permissions')->insert([
            ['name' => '用户', 'slug' => 'user', 'http_path' => '/user*'],
            ['name' => '轮播图', 'slug' => 'banner', 'http_path' => '/banner*'],
            ['name' => '碎片', 'slug' => 'fragment', 'http_path' => '/fragment*'],
        ]);
    }
}
