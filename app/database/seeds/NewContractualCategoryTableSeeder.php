<?php

use Illuminate\Database\Seeder;

class NewContractualCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ["slug" => "tempo-indeterminato", "label" => "Dipendente a tempo indeterminato", "private" => TRUE, 'business' => FALSE],
            ["slug" => "pensionato", "label" => "Pensionato",  "private" => TRUE, 'business' => FALSE],
            ["slug" => "libero-professionista", "label" => "Libero Professionista",  "private" => TRUE, 'business' => TRUE],
            ["slug" => "ditta-individuale", "label" => "Ditta individuale", "private" => FALSE, 'business' => TRUE ],
            ["slug" => "societa-persone", "label" => "Societa di persone",  "private" => FALSE, 'business' => TRUE],
            ["slug" => "societa-capitale", "label" => "Societa di capitale", "private" => FALSE, 'business' => TRUE],
            ["slug" => "associazioni-enti-fondazioni", "label" => "Associazioni - Enti - Fondazioni", "private" => FALSE, 'business' => TRUE ],
            ["slug" => "studi-associati", "label" => "Studi associati",  "private" => FALSE, 'business' => TRUE],
        ];

        $start_from = 11;
        foreach($data as $category) {
            $cat = new \App\ContractualCategory;
            $cat->id = $start_from;
            $cat->code = $category['slug'];
            $cat->description = $category['label'];
            $cat->for_private = $category['private'];
            $cat->for_business = $category['business'];

            if ( $cat->save() )
                $start_from ++;

        }
    }
}
