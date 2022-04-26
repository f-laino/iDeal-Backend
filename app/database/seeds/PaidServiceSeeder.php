<?php

use Illuminate\Database\Seeder;
use App\Models\Service;

class PaidServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // come prima cosa imposto i servizi già presenti (quelli di "default") come included
        Service::where('included', false)->update(['included' => true]);

        // poi aggiungo quelli a pagamento, differenziando i prezzi
        Service::create([
            'slug' => 'cambio-pneumatici',
            'name' => 'Cambio gomme',
            'included' => false,
            'price' => 15,
            'order' => 1
        ]);

        Service::create([
            'slug' => 'cambio-pneumatici',
            'name' => 'Cambio gomme',
            'included' => false,
            'price' => 10,
            'order' => 2
        ]);

        Service::create([
            'slug' => 'vettura-sostitutiva',
            'name' => 'Vettura sostitutiva',
            'included' => false,
            'price' => 25,
            'order' => 3
        ]);

        Service::create([
            'slug' => 'vettura-sostitutiva',
            'name' => 'Vettura sostitutiva',
            'included' => false,
            'price' => 5,
            'order' => 4
        ]);

        Service::create([
            'slug' => 'pai',
            'name' => 'Pai Top',
            'included' => false,
            'price' => 3,
            'order' => 5
        ]);

        Service::create([
            'slug' => 'tutela-legale',
            'name' => 'Tutela legale',
            'included' => false,
            'price' => 3,
            'order' => 6
        ]);

        Service::create([
            'slug' => 'assistenza-stradale',
            'name' => 'Assistenza stradale',
            'included' => false,
            'price' => 2,
            'order' => 7
        ]);
    }
}
