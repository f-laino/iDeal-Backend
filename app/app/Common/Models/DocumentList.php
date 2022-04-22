<?php

namespace App\Common\Models;

use App\Traits\Brokers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentList extends Model
{
    use SoftDeletes, Brokers;

    public $table = 'document_list';

    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function contractualCategory()
    {
        return $this->belongsTo('App\Models\ContractualCategory');
    }

    /**
     * @return BelongsTo
     */
    public function document()
    {
        return $this->belongsTo('App\Models\Document');
    }

    /**
     * @param int $contractualCategory
     * @param string $broker
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByContractualCategoryAndBroker(int $contractualCategory, string $broker)
    {
        return self::where('contractual_category_id', $contractualCategory)
                    ->where('broker', $broker)
                    ->with('document')
                    ->get()
                    ->map(function ($document) {
                        // prendo eventuali title e link customizzati, altrimenti quelli di default
                        $document->document->title = $document->title ?? $document->document->title;
                        $document->document->link = $document->link ?? $document->document->link;
                        return $document->document;
                    })
                    ;
    }

    /**
     * @param string $broker
     *
     * @return array
     */
    public static function getByBrokerGroupedByContractualCategory(string $broker): array
    {
        $result = [];

        $documentList = self::where('broker', $broker)
                            ->with('document')
                            ->get();

        foreach ($documentList as $documentListItem) {
            if (!isset($result[$documentListItem['contractual_category_id']])) {
                $result[$documentListItem['contractual_category_id']] = collect([]);
            }

            $result[$documentListItem['contractual_category_id']]->push($documentListItem);
        }

        return $result;
    }
}
