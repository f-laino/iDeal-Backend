<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\Car;
use App\Models\ContractualCategory;
use App\Models\Customer;
use App\Models\Offer;
use App\Models\Proposal;
use App\Models\Quotation;
use App\Events\QuotationPrinted;
use App\Events\ProposalPrinted;
use App\Interfaces\PrintServiceInterface;
use Carbon\Carbon;
use PDF;

class PrintService implements PrintServiceInterface
{
    /** @var int  */
    const EQUIPPED_ACCESSORIES_LIMIT = 7;

    /** @inheritDoc */
    public static function createFileName(Proposal $proposal): string
    {
        return sprintf("%d - %s.pdf", $proposal->id, $proposal->created_at->format('d/m/Y'));
    }


    /** @inheritDoc */
    public function printQuotation(Quotation $quotation)
    {
        /** @var Proposal $proposal */
        $proposal = $quotation->proposal;
        event(new QuotationPrinted($quotation));

       return $this->createPdf($proposal);
    }

    /** @inheritDoc */
    public function printProposal(Proposal $proposal)
    {
        event(new ProposalPrinted($proposal));
        return $this->createPdf($proposal);
    }

    /**
     * @param Proposal $proposal
     * @return mixed
     */
    private function createPdf(Proposal $proposal)
    {
        $printParameters = $this->getParameters($proposal);
        $pdf = PDF::loadView('attachment.quotation', $printParameters);

        $proposal->increment('print_count', 1, ['last_print_at' => Carbon::now()]);

        $fileName = self::createFileName($proposal);

        return $pdf->setPaper('A4')
            ->download($fileName);
    }

    /** @inheritDoc */
    public function getParameters(Proposal $proposal): array
    {
        /** @var Agent $agent */
        $agent = $proposal->agent;

        $logo = $agent->getLogo();
        if($agent->isEklyAgent()){
            $logo = "https://static.ideal-rent.com/flee/ekly-logo.png";
        }

        /** @var Offer $offer */
        $offer = $proposal->offer;
        $parentOffer = Offer::getParentOffer($offer);

        /** @var Car $car */
        $car = $offer->car;


        /** @var Customer $customer */
        $customer = $proposal->customer;

        /** @var ContractualCategory $contractualCategory */
        $contractualCategory = $customer->category;

        //documents
        $documents = $offer->getMandatoryAttachments($contractualCategory);

        //image
        $image = $car->getDefaultImage();
        $image = $image->path;

        //Car accessories
        $limit = self::EQUIPPED_ACCESSORIES_LIMIT;
        $accessories = $this->getAccessories($car);

        $carName = $car->getName();
        $carModel = $car->allestimento;
        $color = $offer->getColorName();

        $referenceCode = !empty($offer->referenceCode) ? $offer->referenceCode->value : null;

        //franchigie
        $franchigie = $parentOffer->getFranchigie();

        return compact(
            'offer',
            'parentOffer',
            'logo',
            'car',
            'agent',
            'customer',
            'image',
            'proposal',
            'franchigie',
            'documents',
            'carName',
            'referenceCode',
            'carModel',
            'accessories',
            'limit',
            'color',
        );
    }

    /**
     * @param Car $car
     * @return array
     */
    private function getAccessories(Car $car): array
    {
        $accessories = [];

        $equippedAccessories = $car->getEquippedAccessories()
            ->slice(0, self::EQUIPPED_ACCESSORIES_LIMIT);

        foreach ($equippedAccessories as $acc) {
            $accessories[] = $acc->description;
        }

        return $accessories;
    }
}
