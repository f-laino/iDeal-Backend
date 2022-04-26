<?php

use Illuminate\Database\Seeder;

class CarCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [ 'slug' => 'city-car', 'name' => 'City Car' ],
            [ 'slug' => 'berlina', 'name' => 'Berlina' ],
            [ 'slug' => 'sportiva-coupe', 'name' => 'Sportiva/Coupe' ],
            [ 'slug' => 'suv-crossover', 'name' => 'SUV/Crossover' ],
            [ 'slug' => 'monovolume', 'name' => 'Monovolume' ],
            [ 'slug' => 'station-wagon', 'name' => 'Station Wagon' ],
            [ 'slug' => 'veicoli-commerciali', 'name' => 'Veicoli Commerciali' ],
        ];


        foreach ( $categories as $category ){
            $model = new \App\CarCategory;
            $model->slug = $category['slug'];
            $model->name = $category['name'];
            $model->save();
        }
    }
}
