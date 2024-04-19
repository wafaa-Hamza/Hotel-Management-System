<?php

namespace Database\Seeders;

use App\Models\guest_profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuestProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        guest_profile::create(
            [
            'id' =>1,
            'Profile_no' =>1,
            'first_name' =>'GuestProfileOnlyName',
            'last_name' =>'OnlyName',
            'id_no' =>'onlyName',
            'mobile' =>'01145613',
            'phone' =>'210143131',
            'email' =>'onlyName@gmail.com',
            'address' =>' onlyName',
            'country_id' =>1,
            'city' =>'onlyName',
            'language' =>'en',
            'date_of_birth' =>fake()->date(),
            'gender' =>'femal/meal',
            'group_code' =>0,
            'created_by' =>1,      
            'updated_by' =>1,      
            'id_type_id' =>1,      
            ],
            
    );
        guest_profile::create(
            [
                'id' =>2,
                'Profile_no' =>'directBill',
                'first_name' =>'directBill',
                'last_name' =>'directBill',
                'id_no' =>'directBill',
                'mobile' =>'directBill',
                //'phone' =>'',
                //'email' =>'onlyName@gmail.com',
                'address' =>'',
                'country_id' =>1,
                //'city' =>'onlyName',
                //'language' =>'en',
                'date_of_birth' =>fake()->date(),
                'gender' =>'femal/meal',
                //'group_code' =>0,
                'created_by' =>1,      
                'updated_by' =>1,      
                'id_type_id' =>1, 
                ]
            
    );

        guest_profile::create(
            [
                'id' =>3,
                'Profile_no' =>'dummy',
                'first_name' =>'dummy',
                'last_name' =>'dummy',
                'id_no' =>'dummy',
                'mobile' =>'dummy',
                //'phone' =>'',
                //'email' =>'onlyName@gmail.com',
                'address' =>'',
                'country_id' =>1,
                //'city' =>'onlyName',
                //'language' =>'en',
                'date_of_birth' =>fake()->date(),
                'gender' =>'femal/meal',
                //'group_code' =>0,
                'created_by' =>1,      
                'updated_by' =>1,      
                'id_type_id' =>1, 
            ]
            
    );

    guest_profile::create(
        [
            'id' =>4,
            'Profile_no' =>'paymaster_profile',
            'first_name' =>'paymaster_profile',
            'last_name' =>'paymaster_profile',
            'id_no' =>'group_paymaster',
            'mobile' =>'group_paymaster',
            //'phone' =>'',
            //'email' =>'onlyName@gmail.com',
            'address' =>'',
            'country_id' =>1,
            //'city' =>'onlyName',
            //'language' =>'en',
            'date_of_birth' =>fake()->date(),
            'gender' =>'femal/meal',
            //'group_code' =>0,
            'created_by' =>1,      
            'updated_by' =>1,      
            'id_type_id' =>1, 
        ]
        
);
    }
}
