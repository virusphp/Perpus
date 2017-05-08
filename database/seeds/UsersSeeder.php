<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Role Admin
        $adminRole = new Role();
        $adminRole->name = 'admin';
        $adminRole->display_name = 'Admin';
        $adminRole->save();


        //role member
        $memberRole = new Role();
        $memberRole->name = 'member';
        $memberRole->display_name = 'Member';
        $memberRole->save();

        //membuat contoh admin
        $admin = new User();
        $admin->name = 'Admin Larapus';
        $admin->email = 'admin@gmail.com';
        $admin->password = bcrypt('rahasia');
        $admin->is_verified = 1;
        $admin->save();
        $admin->attachRole($adminRole);

        //membuat contoh member
        $member = new User();
        $member->name = 'Member Larapus';
        $member->email = 'member@gmail.com';
        $member->password = bcrypt('rahasia');
        $member->is_verified = 1;
        $member->save();
        $member->attachRole($memberRole);

    }
}
