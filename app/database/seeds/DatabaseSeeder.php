<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UserTableSeeder::class);
         $this->call(BrandsTableSeeder::class);
         $this->call(FuelsTableSeeder::class);
         $this->call(ServiceTableSeeder::class);
         $this->call(CarCategoriesTableSeeder::class);
         $this->call(ContractualCategoryTableSeeder::class);
         $this->call(CrmConnectionSeeder::class);
    }
}
