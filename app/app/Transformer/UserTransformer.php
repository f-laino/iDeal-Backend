<?php

namespace App\Transformer;

use App\Models\User;

class UserTransformer extends BaseTransformer
{
    protected array $defaultIncludes = [

    ];

    protected array $availableIncludes = [
        'documents',
        'address'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (integer)$user->id,
            'email' => (string)$user->email,
            "first_name" => (string)$user->meta->first_name,
            "last_name" => (string)$user->meta->last_name,
            "picture" => (string)$user->meta->picture,
            "salary" => (string)$user->meta->salary,
            "phone" => (string)$user->meta->phone,
            "source" => (string)$user->meta->source,
            "referrer" => (string)$user->meta->referrer,
            "is_active" => (string)$user->meta->is_active,
            "marketing" => (string)$user->meta->marketing,
            "third_marketing" => (string)$user->meta->third_marketing,
            "terms_and_cond" => (string)$user->meta->terms_and_cond,
        ];
    }


    /**
     * Car $car
     *
     * @param User $item
     * @return League\Fractal\Resource\Collection
     */
    public function includeDocuments(User $item)
    {
        $documents = $item->documents;

        return $this->collection($documents, new UserdocumentTransformer);
    }

    /**
     * Car $car
     *
     * @param User $item
     * @return League\Fractal\Resource\Collection
     */
    public function includeAddress(User $item)
    {
        $address = $item->address;

        if (!$address) {
            return null;
        }

        return $this->item($address, new AddressTransformer);
    }
}
