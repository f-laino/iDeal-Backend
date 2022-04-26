<?php

use Illuminate\Database\Seeder;

class ContractualCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ["id" => 11, "slug" => "tempo-indeterminato", "label" => "Dipendente a tempo indeterminato", "private" => TRUE, 'business' => FALSE],
            ["id" => 12, "slug" => "pensionato", "label" => "Pensionato", "private" => TRUE, 'business' => FALSE],
            ["id" => 13, "slug" => "libero-professionista", "label" => "Libero professionista", "private" => TRUE, 'business' => TRUE],
            ["id" => 14, "slug" => "ditta-individuale", "label" => "Ditta individuale", "private" => FALSE, 'business' => TRUE],
            ["id" => 15, "slug" => "societa-persone", "label" => "Societa di persone", "private" => FALSE, 'business' => TRUE],
            ["id" => 16, "slug" => "societa-capitale", "label" => "Societa di capitale", "private" => FALSE, 'business' => TRUE],
            ["id" => 17, "slug" => "associazioni-enti-fondazioni", "label" => "Associazioni - Enti - Fondazioni", "private" => FALSE, 'business' => TRUE],
            ["id" => 18, "slug" => "studi-associati", "label" => "Studi associati", "private" => FALSE, 'business' => TRUE],
        ];


        foreach($data as $category) {
            $cat = new \App\ContractualCategory;
            $cat->id = $category['id'];
            $cat->code = $category['slug'];
            $cat->description = $category['label'];
            $cat->for_private = $category['private'];
            $cat->for_business = $category['business'];
            $cat->save();
        }

    }
}
