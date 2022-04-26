<?php

use Illuminate\Database\Seeder;

class FuelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fuels = [
            [ "slug" => "benzina", "name" => "Benzina" ],
            [ "slug" => "diesel", "name" => "Diesel" ],
            [ "slug" => "gpl", "name" => "GPL" ],
            [ "slug" => "metano", "name" => "Metano" ],
            [ "slug" => "elettrica", "name" => "Elettrica" ],
            [ "slug" => "ibrida", "name" => "Ibrida" ],
            [ "slug" => "plug-in", "name" => "Plug-In" ],
        ];
        foreach ( $fuels as $fuel ){
            $model = new \App\Fuel;
            $model->slug = $fuel['slug'];
            $model->name = $fuel['name'];
            $model->save();
        }
    }
}
