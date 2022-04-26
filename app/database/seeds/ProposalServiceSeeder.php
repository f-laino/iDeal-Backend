<?php

use Illuminate\Database\Seeder;
use App\Models\Proposal;
use App\Models\Service;

class ProposalServiceSeeder extends Seeder
{
    private $servicesFieldsMap = [
        'change_tires' => 'cambio-pneumatici',
        'car_replacement' => 'vettura-sostitutiva',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultServicesIds = Service::whereIn('slug', array_values($this->servicesFieldsMap))->get();

        $proposals = Proposal::withTrashed()->get();

        foreach ($proposals as $proposal) {
            $fieldsToUpdate = [];

            foreach ($this->servicesFieldsMap as $oldField => $newSlug) {
                if ($proposal->$oldField) {
                    $fieldsToUpdate[$newSlug] =
                        $defaultServicesIds
                            ->where('slug', $newSlug)
                            ->sortBy('order')
                            ->first()
                            ->id;
                }
            }

            if (!empty($fieldsToUpdate)) {
                foreach ($fieldsToUpdate as $serviceId) {
                    \DB::table('proposal_service')->insert([
                        'proposal_id' => $proposal->id,
                        'service_id' => $serviceId,
                        'created_at' => $proposal->created_at,
                        'updated_at' => $proposal->updated_at,
                    ]);
                }
            }
        }
    }
}
