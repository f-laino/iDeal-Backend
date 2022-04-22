<?php

namespace App\Http\Controllers\Crm;

use App\Models\Customer;
use App\Models\ContractualCategory;
use App\Factories\CrmFactory;
use App\Models\Quotation;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Controllers\Crm\Controller;
use Validator;
use Log;

class UsersController extends Controller
{
    //Customer crm_id was removed
    //but wee keep the name and use customer id

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:customers,id',
            'key' => 'required|min:1|max:255',
            'value' => 'required|min:1|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $key = $request->get('key');
        $value = $request->get('value');
        $userId = $request->get('crm');

        /** @var Customer $customer */
        $customer = Customer::find($userId);

        /** @var Quotation $lastQuotation */
        $lastQuotation = $customer->lastQuotation();

        $crm = CrmFactory::create($lastQuotation);

        $key = $this->getCustomerFieldKey($key);

        $fields = [ $key => $value ];
        $status = $crm->updateCustomer($customer, $fields);

        return response()->json(["status"=> $status, 'msg'=> "User updated: " . json_encode($status)], Response::HTTP_OK);
    }

    public function updateContractualCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crm' => 'required|exists:customers,id',
            'category' => 'required|exists:contractual_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $categoryId = $request->get('category');
        $userId = $request->get('crm');

        /** @var Customer $customer */
        $customer = Customer::find($userId);

        /** @var Quotation $lastQuotation */
        $lastQuotation = $customer->lastQuotation();

        /** @var ContractualCategory $category */
        $category = ContractualCategory::find($categoryId);

        $crm = CrmFactory::create($lastQuotation);

        $fields = [ "contractual_category" => $category->label ];
        $status = $crm->updateCustomer($customer, $fields);

        return response()->json(["status"=> $status, 'msg'=> "User category updated: " . json_encode($status)], Response::HTTP_OK);
    }


}
