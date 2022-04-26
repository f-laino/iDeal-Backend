<?php

use Illuminate\Database\Seeder;
use App\Group;
use App\Service;

class GroupServiceSeeder extends Seeder
{
    private $defaultServicesMap = [
        'cambio-pneumatici' => 15,
        'vettura-sostitutiva' => 25,
    ];

    private $eklyServicesMap = [
        'cambio-pneumatici' => 10,
        'vettura-sostitutiva' => 5,
        'pai' => 3,
        'tutela-legale' => 3,
        'assistenza-stradale' => 2,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = Group::where('name', '!=', 'Ekly')->get();
        $eklyGroup = Group::where('name', 'Ekly')->first();

        foreach ($groups as $group) {
            foreach ($this->defaultServicesMap as $serviceSlug => $servicePrice){
                $group->attachService(
                    Service::where(['slug' => $serviceSlug, 'price' => $servicePrice, 'included' => false])
                            ->first()
                            ->id
                );
            }
        }

        if (!empty($eklyGroup)) {
            foreach ($this->eklyServicesMap as $serviceSlug => $servicePrice){
                $eklyGroup->attachService(
                    Service::where(['slug' => $serviceSlug, 'price' => $servicePrice, 'included' => false])
                            ->first()
                            ->id
                );
            }
        }
    }
}
