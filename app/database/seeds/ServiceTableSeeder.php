<?php

use Illuminate\Database\Seeder;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = [
            ["slug" => "assicurazione-rca-kasko", "name" => "Assicurazione RCA e KASKO", "description" =>"Assicurazione RCA e KASKO", "order"=>1, "icon" => "assicurazione-rca.svg"],
            ["slug" => "consegna-veicolo", "name" => "Consegna veicolo", "description" =>"Consegna veicolo", "order"=>9, "icon" => "assicurazione-rca.svg"],
            ["slug" => "furto-incendio-danni", "name" => "Incendio furto e danni al veicolo",  "description" =>"Incendio furto e danni al veicolo", "order"=>2, "icon" => "furto-incendio.svg"],
            ["slug" => "manutenzione-ordinaria", "name" => "Manutenzione ordinaria",  "description" =>"Manutenzione ordinaria", "order"=>4, "icon" => "manutenzione-ordinaria.svg"],
            ["slug" => "manutenzione-straordinaria", "name" => "Manutenzione straordinaria",  "description" =>"Manutenzione straordinaria", "order"=>5, "icon" => "manutenzione-straordinaria.svg"],
            ["slug" => "servizio-clienti", "name" => "Servizio clienti dedicato",  "description" =>"Servizio clienti dedicato", "order"=>8, "icon" => "servizio-clienti.svg"],
            ["slug" => "soccorso-stradale", "name" => "Soccorso e assistenza stradale H24",  "description" =>"Soccorso e assistenza stradale H24", "order"=>6, "icon" => "soccorso-stradale.svg"],
            ["slug" => "tassa-di-proprieta", "name" => "Tassa di proprietà",  "description" =>"Tassa di proprietà", "order"=>3, "icon" => "tassa-proprieta.svg"],
            ["slug" => "messa-su-strada-e-immatricolazione", "name" => "Messa su strada e immatricolazione",  "description" =>"Messa su strada e immatricolazione", "order"=>7, "icon" => "messa-strada.svg"],
        ];

        foreach ( $service as $service ){
            $model = new \App\Service;
            $model->slug = $service['slug'];
            $model->name = $service['name'];
            $model->description = $service['description'];
            $model->icon = 'https://cdn1.carplanner.com/icons/services/' . $service['icon'];
            $model->order = $service['order'];
            $model->save();
        }
    }
}
