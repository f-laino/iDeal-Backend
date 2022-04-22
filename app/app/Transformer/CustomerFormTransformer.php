<?php

namespace App\Transformer;

use App\Models\Customer;

/**
 * @OA\Schema(
 *  schema="CustomerForm",
 *  type="object",
 *  @OA\Property(property="first_name", type="string", example="Flavio"),
 *  @OA\Property(property="last_name", type="string", example="Laino"),
 *  @OA\Property(property="email", type="string", format="email", example="flavio@ideal.com"),
 *  @OA\Property(property="phone", type="string", example="0612345678"),
 *  @OA\Property(property="address", type="string", example="piazza La Bomba e Scappa"),
 *  @OA\Property(property="postal_code", type="string", example="00100"),
 *  @OA\Property(property="fiscal_code", type="string", example="LNAFLV81R03H501Z"),
 *  @OA\Property(property="vat_number", type="string", example="04796900266"),
 *  @OA\Property(property="business_name", type="string", example="Laino Ltd"),
 *  @OA\Property(property="employee_category", type="string", example="tempo-indeterminato"),
 *  @OA\Property(property="note", type="string"),
 * )
 */
class CustomerFormTransformer extends BaseTransformer
{
    /**
     * Turn this item object into a generic array
     *
     * @param Brand $item
     * @return array
     */
    public function transform(Customer $customer)
    {
        return [
            'first_name' => (string)$customer->first_name,
            'last_name' => (string)$customer->last_name,
            'email' => (string)$customer->email,
            'phone' => (string)$customer->phone,
            'fiscal_code' => (string)$customer->fiscal_code,
            'address' => (string)$customer->address,
            'postal_code' => (string)$customer->zip_code,
            'employee_category' => (string)$customer->category->code,
            'vat_number' =>  (string)$customer->vat_number,
            'business_name' => (string)$customer->business_name,
            'note' => (string)$customer->note,
        ];
    }
}
