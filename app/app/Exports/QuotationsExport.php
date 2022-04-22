<?php

namespace App\Exports;

use App\Transformer\QuotationsExportItemTransformer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class QuotationsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    private $proposals;

    public function __construct(Collection $proposals)
    {
        $this->proposals = $proposals;
    }

    public function collection(): Collection
    {
        return $this->proposals;
    }

    public function map($proposal): array
    {
        return (new QuotationsExportItemTransformer())->transform($proposal);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach ($sheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    foreach ($cellIterator as $cell) {
                        $carriageReturns = substr_count($cell->getValue(), PHP_EOL);

                        if ($carriageReturns > 0) {
                            $sheet->getRowDimension($row->getRowIndex())->setRowHeight(14 * ($carriageReturns + 1));
                        }
                    }
                }
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Numero preventivo',
            'Preventivo confermato',
            'Status',
            'Nome',
            'Cognome',
            'Email',
            'Telefono',
            'Indirizzo',
            'CAP',
            'Categoria contrattuale',
            'Iban',
            'Utente iDEAL',
            'Data creazione',
            'Anticipo € (IVA esclusa)',
            'Rata mensile € (IVA esclusa)',
            'Durata contratto',
            'Km/Anno',
            'Documenti caricati',
            'Note',
            'Conteggio stampe',
            'Servizi inclusi',
            'Marca',
            'Modello',
            'Alimentazione',
            'Categoria',
            'Segmento',
            'Cambio',
            'Posti',
            'Cilindrata',
        ];
    }
}
