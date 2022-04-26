<?php
namespace App\Interfaces;

 use App\Models\Proposal;
 use App\Models\Quotation;

 interface PrintServiceInterface
 {
     /**
      * Crea il nome del file
      * @param Proposal $proposal
      * @return string
      */
    static function createFileName(Proposal $proposal): string;

     /**
      * @param Quotation $quotation
      * @return mixed
      */
     function printQuotation(Quotation $quotation);

     /**
      * @param Proposal $proposal
      * @return mixed
      */
     function printProposal(Proposal $proposal);

     /**
      * @param Proposal $proposal
      * @return array
      */
     function getParameters(Proposal $proposal): array;
 }
