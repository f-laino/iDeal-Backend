<?php

use App\DocumentList;
use Illuminate\Database\Seeder;

class DocumentsSeeder extends Seeder
{
    const VALID_FOR_ALL = 'validForAll';
    const VALID_FOR_PRIVATE = 'validForPrivate';
    const VALID_FOR_BUSINESS = 'validForBusiness';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentsToInsert = [
            'carta_identita' => [
                'title' => 'Carta d’identità a colori fronte/retro',
                'link' => null,
                'validFor' => [
                    'tempo-indeterminato' => self::VALID_FOR_ALL,
                    'pensionato' => self::VALID_FOR_ALL,
                    'libero-professionista' => self::VALID_FOR_ALL,
                    'societa-persone' => [
                        'alphabet'
                    ],
                ],
                'customization' => []
            ],
            'tessera_sanitaria' => [
                'title' => 'Tessera sanitaria a colori fronte/retro',
                'link' => null,
                'validFor' => [
                    'tempo-indeterminato' => self::VALID_FOR_ALL,
                    'pensionato' => self::VALID_FOR_ALL,
                    'libero-professionista' => self::VALID_FOR_ALL,
                    'societa-persone' => [
                        'alphabet'
                    ],
                ],
                'customization' => []
            ],
            'patente' => [
                'title' => 'Patente a colori fronte/retro',
                'link' => null,
                'validFor' => [
                    'tempo-indeterminato' => self::VALID_FOR_ALL,
                    'pensionato' => self::VALID_FOR_ALL,
                    'libero-professionista' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'patente_firmatario' => [
                'title' => 'Patente firmatario a colori fronte/retro',
                'link' => null,
                'validFor' => [
                    'ditta-individuale' => self::VALID_FOR_ALL
                ],
                'customization' => []
            ],
            'carta_identita_firmatario' => [
                'title' => 'Carta d’identità firmatario a colori fronte/retro',
                'link' => null,
                'validFor' => [
                    'ditta-individuale' => self::VALID_FOR_ALL
                ],
                'customization' => []
            ],
            'carta_identita_rappresentante' => [
                'title' => 'Carta d’identità a colori fronte/retro del Legale Rappresentante',
                'link' => null,
                'validFor' => [
                    'societa-persone' => self::VALID_FOR_ALL,
                    'societa-capitale' => self::VALID_FOR_ALL,
                    'associazioni-enti-fondazioni' => self::VALID_FOR_ALL,
                    'studi-associati' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'tessera_sanitaria_firmatario' => [
                'title' => 'Tessera sanitaria firmatario a colori fronte/retro',
                'link' => null,
                'validFor' => [
                    'ditta-individuale' => self::VALID_FOR_ALL
                ],
                'customization' => []
            ],
            'tessera_sanitaria_rappresentante' => [
                'title' => 'Tessera sanitaria del rappresentante legale a colori fronte/retro',
                'link' => null,
                'validFor' => [
                    'associazioni-enti-fondazioni' => self::VALID_FOR_ALL,
                    'studi-associati' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'buste_paga' => [
                'title' => 'Ultime 2 Buste Paga',
                'link' => null,
                'validFor' => [
                    'tempo-indeterminato' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'ultimo_cud' => [
                'title' => 'Ultimo CUD/Modello 730/Modello Unico completo di ricevuta',
                'link' => null,
                'validFor' => [
                    'tempo-indeterminato' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'modello_unico' => [
                'title' => 'Ultimo Modello Unico',
                'link' => null,
                'validFor' => [
                    'libero-professionista' => self::VALID_FOR_ALL,
                    'societa-persone' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'ricevuta_modello_unico' => [
                'title' => 'Ricevuta di Deposito Modello Unico',
                'link' => null,
                'validFor' => [
                    'libero-professionista' => self::VALID_FOR_ALL,
                    'societa-persone' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'modello_unico_ricevuta_telematica' => [
                'title' => 'Modello Unico "Società di persone ed equiparati” con ricevuta telematica di deposito',
                'link' => null,
                'validFor' => [
                    'studi-associati' => self::VALID_FOR_ALL,
                    'associazioni-enti-fondazioni' => self::VALID_FOR_ALL,
                ],
                'customization' => [
                    self::VALID_FOR_ALL => [
                        'associazioni-enti-fondazioni' => [
                            'title' => 'Modello Unico "Enti non commerciali" e/o copia ultimo bilancio esercizio con ricevuta telematica di deposito'
                        ]
                    ]
                ]
            ],
            'ultimo_modello_iva' => [
                'title' => 'Ultimo modello IVA con ricevuta telematica di deposito',
                'link' => null,
                'validFor' => [
                    'ditta-individuale' => self::VALID_FOR_ALL
                ],
                'customization' => []
            ],
            'modello_irap' => [
                'title' => 'Modello IRAP con ricevuta telematica di deposito',
                'link' => null,
                'validFor' => [
                    'ditta-individuale' => self::VALID_FOR_ALL
                ],
                'customization' => []
            ],
            'visura_cciaa' => [
                'title' => 'Visura Camerale di massimo 6 mesi prima dalla richiesta di noleggio',
                'link' => null,
                'validFor' => [
                    'ditta-individuale' => self::VALID_FOR_ALL,
                    'societa-persone' => self::VALID_FOR_ALL,
                    'studi-associati' => self::VALID_FOR_ALL,
                ],
                'customization' => [
                    self::VALID_FOR_ALL => [
                        'societa-persone' => [
                            'title' => 'Visura Camerale di massimo 6 mesi prima dalla richiesta di noleggio (oppure certificato di iscrizione alla C.C.I.A.A.)'
                        ],
                        'studi-associati' => [
                            'title' => 'Visura Camerale di massimo 6 mesi prima dalla richiesta di noleggio (oppure certificato di iscrizione alla C.C.I.A.A.)'
                        ]
                    ]
                ]
            ],
            'ultimo_bilancio' => [
                'title' => 'Ultimo bilancio di esercizio redatto secondo IV Direttiva (deve contenere Stato Patrimoniale + Conto Economico + Nota Integrativa)',
                'link' => null,
                'validFor' => [
                    'societa-capitale' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'ricevuta_ultimo_bilancio' => [
                'title' => 'Ricevuta di Deposito di Bilancio',
                'link' => null,
                'validFor' => [
                    'societa-capitale' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'statuto' => [
                'title' => 'Statuto',
                'link' => null,
                'validFor' => [
                    'associazioni-enti-fondazioni' => self::VALID_FOR_ALL,
                    'studi-associati' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'atto_costitutivo' => [
                'title' => 'Atto costitutivo',
                'link' => null,
                'validFor' => [
                    'associazioni-enti-fondazioni' => self::VALID_FOR_ALL,
                    'studi-associati' => self::VALID_FOR_ALL,
                ],
                'customization' => [
                    self::VALID_FOR_ALL => [
                        'associazioni-enti-fondazioni' => [
                            'title' => 'Atto costitutivo e/o Statuto per Società non commerciali'
                        ]
                    ]
                ]
            ],
            'iban' => [
                'title' => 'Codice Iban',
                'link' => null,
                'validFor' => [
                    'tempo-indeterminato' => self::VALID_FOR_ALL,
                    'pensionato' => self::VALID_FOR_ALL,
                    'libero-professionista' => self::VALID_FOR_ALL,
                    'ditta-individuale' => self::VALID_FOR_ALL,
                    'societa-persone' => self::VALID_FOR_ALL,
                    'studi-associati' => self::VALID_FOR_ALL,
                    'societa-capitale' => self::VALID_FOR_ALL,
                    'associazioni-enti-fondazioni' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'luce_gas' => [
                'title' => 'Ultima bolletta luce/gas oppure tessera elettorale oppure certificato di residenza rilasciato dal comune',
                'link' => null,
                'validFor' => [
                    'tempo-indeterminato' => self::VALID_FOR_ALL,
                    'ditta-individuale' => self::VALID_FOR_ALL,
                    'pensionato' => self::VALID_FOR_ALL,
                    'associazioni-enti-fondazioni' => self::VALID_FOR_ALL,
                    'studi-associati' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'ubo' => [
                'title' => 'Modello UBO',
                'link' => 'https://cdn1.carplanner.com/docs/ubo.pdf',
                'validFor' => [
                    'societa-persone' => [
                        'lease plan'
                    ],
                    'studi-associati' => [
                        'lease plan'
                    ],
                    'societa-capitale' => [
                        'lease plan'
                    ],
                    'libero-professionista' => [
                        'lease plan'
                    ],
                ],
                'customization' => []
            ],
            'attibuzione_partita_iva' => [
                'title' => 'Documento di attribuzione della Partita Iva (certificato cartaceo o telematico)',
                'link' => null,
                'validFor' => [
                    'libero-professionista' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'modulo_privacy' => [
                'title' => 'Modulo privacy firmato',
                'link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_carplanner.pdf',
                'validFor' => [
                    self::VALID_FOR_ALL => self::VALID_FOR_ALL
                ],
                'customization' => [
                    'lease plan' => [
                        self::VALID_FOR_ALL => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_lease_plan.pdf']
                    ],
                    'leasys' => [
                        self::VALID_FOR_PRIVATE => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_leasys_privato.pdf'],
                        self::VALID_FOR_BUSINESS => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_leasys_entità_giuridiche.pdf'],
                    ],
                    'ald' => [
                        self::VALID_FOR_PRIVATE => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_ald_privato.pdf'],
                        self::VALID_FOR_BUSINESS => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_ald_aziende.pdf'],
                    ],
                    'alphabet' => [
                        self::VALID_FOR_PRIVATE => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_alphabet_privato_v2.pdf'],
                        self::VALID_FOR_BUSINESS => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_alphabet_aziende_v2.pdf'],
                    ],
                    'arval' => [
                        self::VALID_FOR_PRIVATE => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_arval_privato.pdf'],
                        self::VALID_FOR_BUSINESS => ['link' => 'https://cdn1.carplanner.com/docs/privacy/privacy_arval_aziende.pdf'],
                    ],
                ]
            ],
            'cedolino_pensione' => [
                'title' => 'Ultimo cedolino pensione',
                'link' => null,
                'validFor' => [
                    'pensionato' => self::VALID_FOR_ALL,
                ],
                'customization' => [
                    'alphabet' => [
                        self::VALID_FOR_ALL => [
                            'title' => 'Ultimi 2 cedolini pensione percepiti'
                        ]
                    ],
                ]
            ],
            'modello_730' => [
                'title' => 'Ultimo modello 730',
                'link' => null,
                'validFor' => [
                    'pensionato' => [self::VALID_FOR_ALL]
                ],
                'customization' => []
            ],
            'modulo_autocertificazione_arval' => [
                'title' => 'Modulo Autocertificazione',
                'link' => 'https://cdn1.carplanner.com/docs/moduli/autocertificazione_arval.pdf',
                'validFor' => [
                    'tempo-indeterminato' => [
                        'arval'
                    ]
                ],
                'customization' => []
            ],
            'rendiconto_finanziario' => [
                'title' => 'Rendiconto finanziario',
                'link' => null,
                'validFor' => [
                    'associazioni-enti-fondazioni' => self::VALID_FOR_ALL,
                ],
                'customization' => []
            ],
            'modulo_sepa_alphabet' => [
                'title' => 'Modulo SEPA',
                'link' => 'https://cdn1.carplanner.com/docs/sepa/sepa_alphabet.pdf',
                'validFor' => [
                    self::VALID_FOR_ALL => [
                        'alphabet'
                    ],
                ],
                'customization' => []
            ],
        ];

        $contractualCategories = App\ContractualCategory::where('id', '>=', App\ContractualCategory::$DEFAULT)->get();
        $brokers = DocumentList::getBrokerCodes();

        foreach ($documentsToInsert as $documentType => $documentData) {
            $this->command->info('Saving ' . $documentType);

            $document = new App\Document();
            $document->title = $documentData['title'];
            $document->type = $documentType;
            $document->link = $documentData['link'];
            $document->save();

            $this->command->info(' -> saved');
        }

        $documents = App\Document::all();

        foreach ($documents as $document) {
            $this->command->info('Saving combinations for ' . $document->type);
            foreach ($contractualCategories as $contractualCategory) {
                if (
                    false !== ($contractualCategoryValid = $this->isValidFor($documentsToInsert[$document->type]['validFor'], $contractualCategory->code, $contractualCategory))
                    && (
                        !in_array($contractualCategoryValid, [self::VALID_FOR_BUSINESS, self::VALID_FOR_PRIVATE]) ||
                        (
                            ($contractualCategoryValid === self::VALID_FOR_BUSINESS && $contractualCategory->for_business) ||
                            ($contractualCategoryValid === self::VALID_FOR_PRIVATE && $contractualCategory->for_private)
                        )
                    )
                ) {
                    $this->command->info(' -> contractual category: ' . $contractualCategory->code);

                    foreach ($brokers as $broker) {
                        if (
                            $documentsToInsert[$document->type]['validFor'][$contractualCategoryValid] === self::VALID_FOR_ALL ||
                            in_array($broker, $documentsToInsert[$document->type]['validFor'][$contractualCategoryValid])
                        ) {
                            $this->command->info('    -> broker: ' . $broker);

                            $documentList = new App\Common\Models\DocumentList();
                            $documentList->contractual_category_id = $contractualCategory->id;
                            $documentList->broker = $broker;
                            $documentList->document_id = $document->id;
                            $documentList->title = $this->hasCustomizedValue($documentsToInsert[$document->type]['customization'], $broker, $contractualCategory, 'title');
                            $documentList->link = $this->hasCustomizedValue($documentsToInsert[$document->type]['customization'], $broker, $contractualCategory, 'link');
                            $documentList->save();

                            $this->command->info(' -> saved');
                        }
                    }
                }
            }
        }
    }

    private function isValidFor(array $item, string $subject, App\ContractualCategory $contractualCategory)
    {
        return
            isset($item[self::VALID_FOR_ALL]) ? self::VALID_FOR_ALL :
                ((isset($item[self::VALID_FOR_PRIVATE]) && $contractualCategory->for_private) ? self::VALID_FOR_PRIVATE :
                    ((isset($item[self::VALID_FOR_BUSINESS]) && $contractualCategory->for_business) ? self::VALID_FOR_BUSINESS :
                        (isset($item[$subject]) ? $subject : false)))
            ;
    }

    private function hasCustomizedValue(
        array $customization,
        string $broker,
        App\ContractualCategory $contractualCategory,
        string $field
    )
    {
        // $customization[VALID_FOR_ALL | {broker}]
        //                 [VALID_FOR_ALL | VALID_FOR_PRIVATE | VALID_FOR_BUSINESS | {category}]
        //                     [{fieldname} => {value}]

        if (!empty($customization) && false !== ($validBroker = $this->isValidFor($customization, $broker, $contractualCategory))) {
            if (false !== ($validCategory = $this->isValidFor($customization[$validBroker], $contractualCategory->code, $contractualCategory))) {
                if (
                    isset($customization[$validBroker][$validCategory][$field]) &&
                    (
                        !in_array($validCategory, [self::VALID_FOR_BUSINESS, self::VALID_FOR_PRIVATE]) ||
                        (
                            ($validCategory === self::VALID_FOR_BUSINESS && $contractualCategory->for_business) ||
                            ($validCategory === self::VALID_FOR_PRIVATE && $contractualCategory->for_private)
                        )
                    )
                ) {
                    return $customization[$validBroker][$validCategory][$field];
                }
            }
        }

        return null;
    }
}
