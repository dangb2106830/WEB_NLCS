<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FacadesDB::table('users')->insert([
            'email' => 'admin',
            'password' => bcrypt('12345678'),
            'role' => 1,
        ]);
        FacadesDB::table('staffs')->insert([
            'user_id' => 1,
            'name' => 'Quản trị viên',
            'gender' => 'Nam',
            'birth_day' => \Carbon\Carbon::now(),
            'address' => '54 Triều Khúc, Thanh Xuân, Hà Nội',
            'phone' => '0123456789',
        ]);
    }
}