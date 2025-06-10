<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
    DB::table('users')->delete(); // もしくは truncate()（開発環境のみ推奨）

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'テストユーザー',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'created_at' => now(),
        'updated_at' => now(),
        'email_verified_at' => Carbon::now(),
    ]);

    DB::table('users')->insert([
        'id' => 2,
        'name' => 'テストユーザー2',
        'email' => 'test2@example.com',
        'password' => bcrypt('password'),
        'created_at' => now(),
        'updated_at' => now(),
        'email_verified_at' => Carbon::now(),
    ]);

    DB::table('users')->insert([
        'id' => 3,
        'name' => 'テストユーザー3',
        'email' => 'test3@example.com',
        'password' => bcrypt('password'),
        'created_at' => now(),
        'updated_at' => now(),
        'email_verified_at' => Carbon::now(),
    ]);}

}
