<?php

namespace App\Transformer;

use App\Models\Customer;

/**
 * @OA\Schema(
 *  schema="CustomerItem",
 *  type="object",
 *  @OA\Property(property="code", type="string", example="1"),
 *  @OA\Property(property="name", type="string", example="Laino"),
 *  @OA\Property(property="email", type="string", format="email", example="flavio@ideal.com"),
 *  @OA\Property(property="phone", type="string", example="0612345678"),
 *  @OA\Property(property="fiscal_code", type="string", example="LNAFLV81R03H501Z"),
 *  @OA\Property(property="vat_number", type="string", example="04796900266"),
 * )
 */
class CustomerItemTransformer extends BaseTransformer
{
    public function transform(Customer $customer)
    {
        return [
            'code' => (string)$customer->id,
            'name' => (string)$customer->name,
            'email' => (string)$customer->email,
            'phone' => (string)$customer->phone,
            'fiscal_code' => (string)$customer->fiscal_code,
            'vat_number' => (string)$customer->vat_number,
        ];
    }
}
