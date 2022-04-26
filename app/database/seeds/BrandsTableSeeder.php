<?php

use Illuminate\Database\Seeder;
use App\Interfaces\EurotaxServiceInterface;
use App\Models\Brand;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cnt = 0;
        $cntErrors = 0;
        $this->command->info('Init updating Brands from Eurotax...');
        $brands = app('eurotax')->getBrands();

        $registeredBrands = Brand::query()->delete();

        foreach ( $brands as $brand ){
            $brandModel = new Brand;
            $brandModel->slug = $brand->acronimo;
            $brandModel->name = $brand->nome;
            $brandModel->title = NULL;
            $brandModel->description = NULL;
            $logo = strtolower(str_replace(' ', '-', $brand->nome));
            $brandModel->logo = config('app.cdn.url')."/brands/$logo.svg";
            $brandModel->logo_alt = "Logo $brand->nome";
            try{
                $brandModel->saveOrFail();
                $this->command->info("Brand $brand->nome imported" );
                $cnt ++;
            } catch (\Exception $exception){
                $this->command->error("Error importing brand $brand->nome. Error code: ".$exception->getMessage());
                $cntErrors ++;
            }
        }
        $this->command->line("End synch! Brands updated: $cnt. Errors number:  $cntErrors");

    }

}
