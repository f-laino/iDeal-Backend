<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

/**
 * Class Car
 * @package App
 * @property integer $id
 * @property integer $brand_id
 * @property integer $category_id
 * @property integer $fuel_id
 * @property string $codice_motornet
 * @property string $codice_eurotax
 * @property string $codice_gruppo_storico
 * @property string $descrizione_gruppo_storico
 * @property string $codice_serie_gamma
 * @property string $descrizione_serie_gamma
 * @property string $codice_modello
 * @property string $descrizione_modello
 * @property string $cod_gamma_mod
 * @property string $modello
 * @property string $allestimento
 * @property string $segmento
 * @property string $cilindrata
 * @property string $cavalli_fiscali
 * @property string $tipo_motore
 * @property string|null $desc_motore
 * @property string $hp
 * @property string $kw
 * @property string $euro
 * @property string $emissioni_co2
 * @property string $consumo_medio
 * @property string $alimentazione
 * @property string $codice_cambio
 * @property string $nome_cambio
 * @property string $descrizione_cambio
 * @property string|null $descrizione_marce
 * @property string|null $accelerazione
 * @property string|null $altezza
 * @property string|null $cilindri
 * @property string $consumo_urbano
 * @property string $consumo_extra_urbano
 * @property string|null $coppia
 * @property string|null $numero_giri
 * @property string $larghezza
 * @property string $lunghezza
 * @property string|null $pneumatici_anteriori
 * @property string|null $pneumatici_posteriori
 * @property string|null $valvole
 * @property string|null $velocita
 * @property string|null $porte
 * @property string|null $posti
 * @property string|null $descrizione_trazione
 * @property string|null $autonomia_media
 * @property string|null $autonomia_max
 * @property string $bagagliaio
 * @property string|null $cavalli_ibrido
 * @property string|null $cavalli_totale
 * @property string|null $potenza_ibrido
 * @property string|null $potenza_totale
 * @property string|null $coppia_ibrido
 * @property string|null $coppia_totale
 * @property boolean $neo_patentati
 * @property string|null $numero_giri_ibrido
 * @property string|null $numero_giri_totale
 * @property string|null $descrizione_architettura
 * @property string|null $traino
 * @property string|null $volumi
 * @property string|null $portata
 * @property string|null $posti_max
 * @property string|null $ricarica_standard
 * @property string|null $ricarica_veloce
 * @property string|null $pendenza_max
 * @property string|null $descrizione_freni
 * @property string|null $peso
 * @property string|null $tipo_cons
 * @property string|null $emiss_urb
 * @property string|null $emiss_extraurb
 * @property string|null $tipo_guida
 * @property string|null $sosp_pneum
 * @property string|null $cap_serb_litri
 * @property string|null $cap_serb_kg
 * @property integer|null $batteria_kwh
 * @property string|null $paese_prod
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 *
 * @OA\Schema(
 *  schema="CarEntity",
 *  @OA\Property(property="accelerazione", type="string", example="9.40"),
 *  @OA\Property(property="alimentazione", type="string", example="Benzina"),
 *  @OA\Property(property="allestimento", type="string", example="1.4 T.  120cv My19"),
 *  @OA\Property(property="altezza", type="string", example="146"),
 *  @OA\Property(property="autonomia_max", type="string", example="800"),
 *  @OA\Property(property="autonomia_media", type="string", example="600"),
 *  @OA\Property(property="bagagliaio", type="string", example="350/1150"),
 *  @OA\Property(property="batteria_kwh", type="integer", example="88"),
 *  @OA\Property(property="brand", ref="#/components/schemas/BrandEntity"),
 *  @OA\Property(property="cap_serb_kg", type="string", example="19.00"),
 *  @OA\Property(property="cap_serb_litri", type="string", example="60"),
 *  @OA\Property(property="category", ref="#/components/schemas/CarCategoryEntity"),
 *  @OA\Property(property="cavalli_fiscali", type="string", example="15"),
 *  @OA\Property(property="cavalli_ibrido", type="string", example=""),
 *  @OA\Property(property="cavalli_totale", type="string", example=""),
 *  @OA\Property(property="cilindrata", type="string", example="1368"),
 *  @OA\Property(property="cilindri", type="string", example="4"),
 *  @OA\Property(property="cod_gamma_mod", type="string", example="2124"),
 *  @OA\Property(property="codice_cambio", type="string", example="M"),
 *  @OA\Property(property="codice_eurotax", type="string", example="1139195"),
 *  @OA\Property(property="codice_gruppo_storico", type="string", example="0025"),
 *  @OA\Property(property="codice_modello", type="string", example="8696"),
 *  @OA\Property(property="codice_motornet", type="string", example="ALF7011"),
 *  @OA\Property(property="codice_serie_gamma", type="string", example="0033"),
 *  @OA\Property(property="consumo_extra_urbano", type="string", example="6.20"),
 *  @OA\Property(property="consumo_medio", type="string", example="6.90"),
 *  @OA\Property(property="consumo_urbano", type="string", example="8.90"),
 *  @OA\Property(property="coppia", type="string", example="215/2500"),
 *  @OA\Property(property="coppia_ibrido", type="string", example=""),
 *  @OA\Property(property="coppia_totale", type="string", example=""),
 *  @OA\Property(property="desc_motore", type="string", example="Turbo"),
 *  @OA\Property(property="descrizione_architettura", type="string", example="4 cilindri in Linea"),
 *  @OA\Property(property="descrizione_cambio", type="string", example="Manuale"),
 *  @OA\Property(property="descrizione_freni", type="string", example=""),
 *  @OA\Property(property="descrizione_gruppo_storico", type="string", example="Giulietta"),
 *  @OA\Property(property="descrizione_marce", type="string", example="6"),
 *  @OA\Property(property="descrizione_modello", type="string", example="Giulietta Benzina"),
 *  @OA\Property(property="descrizione_serie_gamma", type="string", example="Giulietta"),
 *  @OA\Property(property="descrizione_trazione", type="string", example="Anteriore"),
 *  @OA\Property(property="emiss_extraurb", type="string", example="141"),
 *  @OA\Property(property="emiss_urb", type="string", example="204"),
 *  @OA\Property(property="emissioni_co2", type="string", example="157"),
 *  @OA\Property(property="euro", type="string", example="6"),
 *  @OA\Property(property="fuel", ref="#/components/schemas/FuelEntity"),
 *  @OA\Property(property="hp", type="string", example="120"),
 *  @OA\Property(property="id", type="integer", example="435"),
 *  @OA\Property(property="kw", type="string", example="88"),
 *  @OA\Property(property="larghezza", type="string", example="180"),
 *  @OA\Property(property="lunghezza", type="string", example="435"),
 *  @OA\Property(property="modello", type="string", example="Giulietta"),
 *  @OA\Property(property="neo_patentati", type="integer", example="0"),
 *  @OA\Property(property="nome_cambio", type="string", example=""),
 *  @OA\Property(property="numero_giri", type="string", example="5000"),
 *  @OA\Property(property="numero_giri_ibrido", type="string", example=""),
 *  @OA\Property(property="numero_giri_totale", type="string", example=""),
 *  @OA\Property(property="paese_prod", type="string", example="0"),
 *  @OA\Property(property="pendenza_max", type="string", example=""),
 *  @OA\Property(property="peso", type="string", example="1355"),
 *  @OA\Property(property="pneumatici_anteriori", type="string", example="205/55 R16"),
 *  @OA\Property(property="pneumatici_posteriori", type="string", example="205/55 R16"),
 *  @OA\Property(property="portata", type="string", example=""),
 *  @OA\Property(property="porte", type="string", example="5"),
 *  @OA\Property(property="posti", type="string", example="5"),
 *  @OA\Property(property="posti_max", type="string", example=""),
 *  @OA\Property(property="potenza_ibrido", type="string", example=""),
 *  @OA\Property(property="potenza_totale", type="string", example=""),
 *  @OA\Property(property="ricarica_standard", type="string", example="25"),
 *  @OA\Property(property="ricarica_veloce", type="string", example="1,3"),
 *  @OA\Property(property="segmento", type="string", example="C"),
 *  @OA\Property(property="sosp_pneum", type="string", example="0"),
 *  @OA\Property(property="tipo_cons", type="string", example=""),
 *  @OA\Property(property="tipo_guida", type="string", example="S"),
 *  @OA\Property(property="tipo_motore", type="string", example="AT"),
 *  @OA\Property(property="traino", type="string", example="1300"),
 *  @OA\Property(property="valvole", type="string", example="4"),
 *  @OA\Property(property="velocita", type="string", example="195"),
 *  @OA\Property(property="volumi", type="string", example=""),
 * )
 */
class Car extends Model
{
    public $table = "cars";
    protected $hidden = ['brand_id', 'category_id', 'fuel_id', 'created_at', 'updated_at'];
    protected $guarded = [];

    public static $rules = [
        'brand' => 'required|exists:brands,id',
        'category' => 'required|exists:car_categories,id',
        'fuel' => 'required|exists:fuels,id',
        'segmento' => 'required|string',
        'modello' => 'required|string',
        'allestimento' => 'required|string',
        'cilindrata' => 'required|string',
        'cavalli_fiscali' => 'required|string',
        'descrizione_trazione' => 'required|string',
        'desc_motore' => 'required|string',
        'hp' => 'required|string',
        'consumo_medio' => 'required|string',
        'kw' => 'required|string',
        'euro' => 'required|string',
        'emissioni_co2' => 'required|string',
        'descrizione_cambio' => 'required|string',
        'larghezza' => 'required|string',
        'lunghezza' => 'required|string',
        'bagagliaio' => 'required|string',
        'posti' => 'required|string',
        'porte' => 'required|string',
        'kwh' => 'nullable|integer|gt:0',
    ];

    public static $segments = [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
        'E' => 'E',
    ];

    /**
     * Ritorna l'associazione con la categoria
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\CarCategory');
    }

    /**
     * Ritorna l'associazione con il tipo di alimentazione
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fuel()
    {
        return $this->belongsTo('App\Models\Fuel');
    }

    /**
     * Ritorna le immagini associate a questo allestimento
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany('App\Models\Image')->orderBy('type', 'asc');
    }

    /**
     * Ritorna l'immagine principale di questo allestimento
     * @return Image
     */
    public function mainImage()
    {
        return $this->images()->where('type', 'MAIN')->first();
    }

    /**
     * Ritorna la prima immagine di questo allestimento
     * @return Image
     */
    public function firstImage()
    {
        return $this->images()->first();
    }

    /**
     * Ritorna le immagini slider di questo allestimento
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function sliderImages()
    {
        return $this->images()->where('type', 'SLIDER')->limit(11)->get();
    }

    /**
     * Ritorna le immagini delle promozioni di questo allestimento
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function promotionImage(int $limit = 1)
    {
        return $this->images()->where('type', 'PROMOTIONS')->orderBy('id', 'DESC')->limit($limit)->get();
    }

    /**
     * Ritorna le immagini usate per le newsletter di questo allestimento
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newsletterImage(int $limit = 1)
    {
        return $this->images()->where('type', 'NEWSLETTER')->orderBy('id', 'DESC')->limit($limit)->get();
    }

    /**
     * Ritorna le offerte attive associate a questo allestimento
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @deprecated
     */
    public function offer()
    {
        return $this->hasMany('App\Models\Offer')->where('status', true);
    }

    /**
     * Ritorna le offerte associate a questo allestimento
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany('App\Models\Offer')->where('status', true);
    }

    /**
     * Ritorna l'associazione con la marca
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    /**
     * Ritorna i modelli disponibili sul servizio eurotax associati alla marca di questo allestimento
     * @return \Illuminate\Support\Collection
     */
    public function models()
    {
        $eurotaxService = app('eurotax');
        $models = $eurotaxService->getModelli($this->brand->slug);
        return collect($models);
    }

    /**
     * Ritorna le versioni disponibili sul servizio eurotax associate a questo allestimento
     * @return \Illuminate\Support\Collection
     */
    public function versions()
    {
        $eurotaxService = app('eurotax');
        $versions = $eurotaxService->getVersioni($this->cod_gamma_mod);
        return collect($versions);
    }

    /**
     * Ritorna le versioni disponibili sul servizio eurotax associate a questo allestimento,
     * associate per codice Motornet ed Eurotax
     * @return array
     */
    public function pluckVersions()
    {
        $versions = $this->versions();
        $pluck = [];
        foreach ($versions as $version) {
            $pluck["$version->CodiceMotornet-$version->CodiceEurotax"] = strtoupper($version->Nome);
        }
        return $pluck;
    }


    /**
     * Memorizza un allestimento partendo dai codici Motornet ed Eurotax
     * in caso esistesse già lo restituisce
     * @param array $options
     * @param bool $withImages
     * @return Car|bool
     * @throws \Throwable
     */
    public function saveOrFail(array $options = [], $withImages = true)
    {
        if (!isset($options['codice_motornet']) || !isset($options['codice_eurotax'])) {
            throw new HttpResponseException(response()->json(["error" => "codice_motornet and codice_eurotax fields are required"], 400));
        }
        try {
            $exists = self::where('codice_motornet', $options['codice_motornet'])->where('codice_eurotax', $options['codice_eurotax'])->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            $eurotaxService = app('eurotax');
            $car = $eurotaxService->getDettaglioAuto($options['codice_motornet'], $options['codice_eurotax']);
            $this->storeFormWebservice($car);

            if ($withImages) {
                $path = "cars/$car->marca/$car->descrizione_gruppo_storico";
                Image::saveAll($options['codice_motornet'], $options['codice_eurotax'], $this->id, $path);
            }

            $exists = $this;
            //import car accessories
            CarAccessory::storeFromWebservice($exists);
        }

        return $exists;
    }

    /**
     * Memorizza un allestimento con le informazioni prese dal servizio Eurotax
     * @param Car $car
     * @return boolean
     */
    private function storeFormWebservice($car)
    {
        $category = CarCategory::getFromCode(strtoupper($car->segmento));
        $brand = Brand::where('slug', $car->marca_acronimo)->first();
        $fuel = Fuel::getFromCode(strtoupper($car->codice_alimentazione));

        $this->codice_motornet = $car->codice_motornet;
        $this->codice_eurotax = $car->codice_eurotax;
        $this->brand_id = $brand->id;
        $this->category_id = $category->id;
        $this->fuel_id = $fuel->id;
        $this->alimentazione = $car->alimentazione;
        $this->codice_gruppo_storico = $car->codice_gruppo_storico;

        $descrizione_gruppo_storico =self::clearString($car->descrizione_gruppo_storico);
        $this->descrizione_gruppo_storico = $descrizione_gruppo_storico;


        $this->codice_serie_gamma = $car->codice_serie_gamma;

        $descrizione_serie_gamma = self::clearString($car->descrizione_serie_gamma);
        $this->descrizione_serie_gamma = $descrizione_serie_gamma;

        $this->codice_modello = $car->codice_modello;

        $descrizione_modello = self::clearString($car->descrizione_modello);
        $this->descrizione_modello = $descrizione_modello;

        $this->cod_gamma_mod = $car->cod_gamma_mod;

        $modello = self::clearString($car->modello);
        $this->modello =  $modello;

        $allestimento = strtolower($car->allestimento);
        $allestimento = str_replace(strtolower($car->descrizione_modello), '', $allestimento);
        $allestimento = str_replace(strtolower($car->descrizione_gruppo_storico), '', $allestimento);
        $allestimento = str_replace(strtolower($car->codice_serie_gamma), '', $allestimento);
        $this->allestimento = ucwords($allestimento);

        $this->segmento = ucwords($car->segmento);
        $this->cilindrata = $car->cilindrata;
        $this->cavalli_fiscali = $car->cavalli_fiscali;
        $this->tipo_motore = $car->tipo_motore;
        $this->desc_motore = $car->desc_motore;
        $this->hp = $car->hp;
        $this->kw = $car->kw;
        $this->euro = $car->euro;
        $this->emissioni_co2 = $car->emissioni_co2;
        $this->consumo_medio = $car->consumo_medio;
        $this->codice_cambio = $car->codice_cambio;
        $this->nome_cambio = $car->nome_cambio;
        $this->descrizione_cambio = $car->descrizione_cambio;
        $this->descrizione_marce = $car->descrizione_marce;
        $this->accelerazione = $car->accelerazione;
        $this->altezza = $car->altezza;
        $this->cilindri = $car->cilindri;
        $this->consumo_urbano = $car->consumo_urbano;
        $this->consumo_extra_urbano = $car->consumo_extra_urbano;
        $this->coppia = $car->coppia;
        $this->numero_giri = $car->numero_giri;
        $this->larghezza = $car->larghezza;
        $this->lunghezza = $car->lunghezza;
        $this->pneumatici_anteriori = $car->pneumatici_anteriori;
        $this->pneumatici_posteriori = $car->pneumatici_posteriori;
        $this->valvole = $car->valvole;
        $this->velocita = $car->velocita;
        $this->porte = $car->porte;
        $this->posti = $car->posti;
        $this->descrizione_trazione = $car->descrizione_trazione;
        // $this->altezza_minima = $car->altezza_minima;
        $this->autonomia_media = $car->autonomia_media;
        $this->autonomia_max = $car->autonomia_max;
        $this->bagagliaio = $car->bagagliaio;
        $this->cavalli_ibrido = $car->cavalli_ibrido;
        $this->cavalli_totale = $car->cavalli_totale;
        $this->potenza_ibrido = $car->potenza_ibrido;
        $this->potenza_totale = $car->potenza_totale;
        $this->coppia_ibrido = $car->coppia_ibrido;
        $this->coppia_totale = $car->coppia_totale;
        $this->neo_patentati = (boolean)$car->neo_patentati;
        $this->numero_giri_ibrido = $car->numero_giri_ibrido;
        $this->numero_giri_totale = $car->numero_giri_totale;
        $this->descrizione_architettura = $car->descrizione_architettura;
        $this->traino = $car->traino;
        $this->volumi = $car->volumi;
        $this->portata = $car->portata;
        $this->posti_max = $car->posti_max;
        $this->ricarica_standard = $car->ricarica_standard;
        $this->ricarica_veloce = $car->ricarica_veloce;
        $this->pendenza_max = $car->pendenza_max;
        // $this->peso_potenza = $car->peso_potenza;
        $this->descrizione_freni = $car->descrizione_freni;
        $this->peso = $car->peso;
        $this->tipo_cons = $car->tipo_cons;
        $this->emiss_urb = $car->emiss_urb;
        $this->emiss_extraurb = $car->emiss_extraurb;
        $this->tipo_guida = $car->tipo_guida;
        // $this->massa_p_carico = $car->massa_p_carico;
        $this->sosp_pneum = $car->sosp_pneum;
        $this->cap_serb_litri = $car->cap_serb_litri;
        $this->cap_serb_kg = $car->cap_serb_kg;
        // $this->peso_vuoto = $car->peso_vuoto;
        $this->paese_prod = $car->paese_prod;
        return parent::saveOrFail();
    }

    /**
     * Ritorna la versione formattata dell'allestimento
     * @return string
     */
    public function getVersionAttribute()
    {
        $version = strtolower($this->allestimento);
        $nomeCambio = strtolower($this->nome_cambio);
        //combinedNomeCambio;
        $nomeCambioComb = str_replace(' ', '-', $nomeCambio);
        //clear dirty allestimento values
        foreach (range(17, date('y')) as $year) {
            $version = str_replace("my{$year}", '', $version);
        }
        //remove cambio from allestimento
        $version = str_replace($nomeCambio, '', $version);
        $version = str_replace($nomeCambioComb, '', $version);
        $version = ucwords($version);

        $nomeCambio = ucwords($nomeCambio);
        return $version . " " . $nomeCambio;
    }

    /**
     * Ritorna il segmento dell'allestimento, con fallback per segmenti non previsti
     * @param string $value
     *
     * @return string
     */
    public function getSegmentoAttribute($value)
    {
        if (empty($value) || !in_array($value, Car::$segments)) {
            return Car::$segments['A'];
        }

        return $value;
    }

    /**
     * Rimuove gli anni in vari formati da una stringa
     * @param string $string
     *
     * @return string
     */
    public static function clearString(string $string)
    {
        //Pattern numeri romani per ripulire la versione dei nomi
        $pattern_numeri_romani = '/[ ]\bM{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3}|V?)\b/';
        $pattern_anni = '/(.+)(19|20)[0-9][0-9]/';
        $trim_spaces = '/\s+/';

        $string = preg_replace($pattern_numeri_romani, ' ', $string);
        $string = preg_replace($pattern_anni, '$1', $string);

        $string = preg_replace($trim_spaces, ' ', $string);

        return trim($string);
    }


    /**
     * Generate unique code from car
     * @return string
     */
    public function generateCode()
    {
        $code = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->allestimento);
        $code = str_replace(' ', '-', $code);
        $code = strtolower(Str::random(4) . '-' . $code) ;
        return $code;
    }

    /**
     * Ritorna l'elenco degli accessori abbinati alla vettura
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accessories()
    {
        return $this->hasMany('App\Models\CarAccessory');
    }

    /**
     * Ritorna una elenco dii accessori filtrati
     * @param string $type
     * @param bool|null $available
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getFilteredAccessories(string $type = 'SERIE', $available = null)
    {
        $query = $this->accessories()->where('type', $type);
        if (!is_null($available)) {
            $query->where('available', $available);
        }
        return $query->get();
    }

    /**
     * Ritorna l'elenco degli accessori di serie
     * @param bool|NULL $available
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOptionalAccessories(bool $available = null)
    {
        return $this->getFilteredAccessories('OPTIONAL', $available);
    }

    /**
     * Ritorna l'elenco degli accessori optional
     * @param bool|NULL $available
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEquippedAccessories(bool $available = null)
    {
        return $this->getFilteredAccessories('SERIE', $available);
    }

    /**
     * Ritorna l'elenco dei colori disponbili
     * @param bool|NULL $available
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableColors(bool $available = null)
    {
        return $this->getFilteredAccessories('VERNICI', $available);
    }

    /**
     * Ritorna l'elenco dei pachetti disponibili
     * @param bool|NULL $available
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailablePacks(bool $available = null)
    {
        return $this->getFilteredAccessories('PACCHETTI', $available);
    }

    /**
     * Ritorna un attibuto contentente il nome il valore del campo model e quello del campo allestimento
     * @return string
     */
    public function getFullModelAttribute()
    {
        return $this->modello . " " . $this->allestimento;
    }

    /**
     * Ritorna l'elenco degli allestimenti custom
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCustomCars()
    {
        return self::where('codice_eurotax', 'like', '%CUSTOM')->get();
    }

    /**
     * Determina se l'allestimento corrente è di tipo custom
     * @return false|int
     */
    public function getIsCustomAttribute()
    {
        return preg_match('/CUSTOM$/', $this->codice_eurotax);
    }

    /**
     * Calcola il costo del bollo relativo alla vettura
     *
     * @return float
     */
    public function getBolloValueAttribute()
    {
        $power = intval($this->kw);
        $value =  $power > 100 ? 2.58 * 100 + 3.87 * ($power - 100) : 2.58 * $power;
        $value /= 12;
        return round($value);
    }

    /**
     * @return Image
     */
    public function getDefaultImage(): Image
    {
        $image = $this->mainImage();

        if (empty($image)) {
            $image = $this->firstImage();
        }
        if (empty($image) || !$image instanceof Image) {
            $image = new Image;
        }

        return $image;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->brand->name . " " . $this->descrizione_serie_gamma;
    }
}
