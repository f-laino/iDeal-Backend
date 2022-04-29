<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Access Control API Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::post('activate', 'activate');
        Route::post('password/reset', 'reset');
        Route::get('password/reset', 'forgot');
        Route::patch('password', 'update');
    });

    /*
    |--------------------------------------------------------------------------
    | Offers API Routes
    |--------------------------------------------------------------------------
    */
    Route::post('offers', 'App\Http\Controllers\Api\OfferController@create');
    Route::prefix('offers')->controller('App\Http\Controllers\Api\OfferController')->group(function () {
        Route::get('{code}', 'App\Http\Controllers\Api\OfferController@show');
        Route::post('attachment', 'App\Http\Controllers\Api\OfferController@attachment');
        Route::post('{code}', 'App\Http\Controllers\Api\OfferController@update');
        Route::post('{code}/childs', 'App\Http\Controllers\Api\OfferController@addChilds');
        Route::delete('{code}/child/{idChild}', 'App\Http\Controllers\Api\OfferController@removeChild');
        Route::post('{code}/child', 'App\Http\Controllers\Api\OfferController@addChild');
        Route::post('{code}/services', 'App\Http\Controllers\Api\OfferController@addServices');
        Route::post('{code}/status', 'App\Http\Controllers\Api\OfferController@updateStatus');
        Route::post('request/new', 'App\Http\Controllers\Api\OfferController@requestNewOffer');
    });

    /*
    |--------------------------------------------------------------------------
    | Catalog API Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('catalog')->controller('App\Http\Controllers\Api\CatalogController')->group(function () {
        Route::get('{code}', 'getOffer');
        Route::delete('{code}', 'deleteOffer');
        Route::post('{code}/clone', 'cloneOffer');
    });

    /*
    |--------------------------------------------------------------------------
    | Promotion API Routes
    |--------------------------------------------------------------------------
    */
    Route::get('promotion', 'App\Http\Controllers\Api\PromotionController@index');
    Route::get('promotion/{code}/download', 'App\Http\Controllers\Api\PromotionController@download');

    /*
    |--------------------------------------------------------------------------
    | Search API Routes
    |--------------------------------------------------------------------------
    */
    //Offers
    Route::post('search/filters', 'App\Http\Controllers\Api\SearchController@filters');
    Route::post('search/offers', 'App\Http\Controllers\Api\SearchController@offers');
    //Catalog
    Route::post('search/catalog', 'App\Http\Controllers\Api\SearchController@catalog');
    Route::get('search/catalog/filters', 'App\Http\Controllers\Api\SearchController@catalogFilters');

    //Proposals
    Route::get('proposals/{id}', 'App\Http\Controllers\Api\ProposalController@show');
    Route::post('proposals', 'App\Http\Controllers\Api\ProposalController@store');
    Route::put('proposals/{id}', 'App\Http\Controllers\Api\ProposalController@update');
    Route::post('proposals/{id}/attachment', 'App\Http\Controllers\Api\ProposalController@attachment')->where('id', '[0-9]+');

    //Quotations
    Route::get('quotations/filters', 'App\Http\Controllers\Api\SearchController@quotationsFilters');
    Route::post('quotations', 'App\Http\Controllers\Api\SearchController@quotations');

    Route::post('quotations/export', 'App\Http\Controllers\Api\QuotationController@export');

    Route::get('quotations/interval', 'App\Http\Controllers\Api\QuotationController@getByInterval');
    Route::post('quotations/{code}/attachment', 'App\Http\Controllers\Api\QuotationController@attachment');
    Route::put('quotations/{code}/status', 'App\Http\Controllers\Api\QuotationController@updateStatus');
    Route::post('quote', 'App\Http\Controllers\Api\QuotationController@store');

    Route::post('quotations/documents/upload', 'App\Http\Controllers\Api\QuotationController@attachDocument');
    Route::post('quotations/{quotationId}/documents/download', 'App\Http\Controllers\Api\QuotationController@downloadAttachments');
    Route::post('quotations/documents/show', 'App\Http\Controllers\Api\QuotationController@showAttachDocuments');
    Route::post('quotations/documents/file', 'App\Http\Controllers\Api\QuotationController@destroyAttachDocuments');

    //Customer
    Route::get('customers/search', 'App\Http\Controllers\Api\CustomersController@search');
    Route::get('customers', 'App\Http\Controllers\Api\CustomersController@index');
    Route::get('customers/{code}', 'App\Http\Controllers\Api\CustomersController@show');

    Route::get('profile', 'App\Http\Controllers\Api\AgentController@profile');
    Route::get('agent/profile', 'App\Http\Controllers\Api\AgentController@profile');
    Route::put('agent/profile', 'App\Http\Controllers\Api\AgentController@updateProfile');
    Route::get('agent/statistics', 'App\Http\Controllers\Api\AgentController@statistics');
    Route::get('agent/statistics/members', 'App\Http\Controllers\Api\AgentController@membersStatistics');

    Route::get('agent/members', 'App\Http\Controllers\Api\AgentController@members');
    Route::get('agent/{code}/statistics', 'App\Http\Controllers\Api\AgentController@memberStatistics');

    /*
    |--------------------------------------------------------------------------
    | Crm API Routes
    |--------------------------------------------------------------------------
    */
    Route::post('crm/user/update', [
        'as' => 'crm.user.update',
        'uses' => 'Crm\UsersController@update'
    ]);

    Route::post('crm/user/update-category', [
        'as' => 'crm.user.update-category',
        'uses' => 'Crm\UsersController@updateContractualCategory'
    ]);

    Route::post('crm/deal/update', [
        'as' => 'crm.deal.update',
        'uses' => 'Crm\QuotationsController@update'
    ]);

    Route::post('crm/deal/update-stage', [
        'as' => 'crm.deal.update-stage',
        'uses' => 'Crm\QuotationsController@updateStage'
    ]);

    Route::post('crm/deal/calculate-sustainability', [
        'as' => 'crm.deal.calculate-sustainability',
        'uses' => 'Crm\QuotationsController@calculateDealSustainability'
    ]);

    Route::post('crm/deal/last-interaction', [
        'as' => 'crm.deal.last-interaction',
        'uses' => 'Crm\QuotationsController@getLastBlock'
    ]);

    /** Pipedrive incominq requests */
    Route::post('crm/update', 'App\Http\Controllers\Api\PipedriveController@update');
    /** End Pipedrive incoming requests */

    Route::group(['middleware' => 'request.auth'], function () {
        Route::post('deals/qualification', [
            'as' => 'deals.qualification',
            'uses' => 'Crm\DealsController@updateQualificationStep'
        ]);
        Route::post('deals/update-category', [
            'as' => 'deals.update-category',
            'uses' => 'Crm\DealsController@updateContractualCategory'
        ]);
        Route::post('deals/update-details', [
            'as' => 'deals.update-details',
            'uses' => 'Crm\DealsController@updateDealDetails'
        ]);
        Route::post('deals/qualified', [
            'as' => 'deals.qualified',
            'uses' => 'Crm\DealsController@updateDealQualification'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Quoter API Routes
    |--------------------------------------------------------------------------
    */
    Route::post('quoter/offer/update', [
        'as' => 'quoter.offer.update',
        'uses' => 'Quoter\OfferController@update'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Car API Routes
    |--------------------------------------------------------------------------
    */
    Route::get('car', 'App\Http\Controllers\Api\CarController@getCar');
    Route::post('car', 'App\Http\Controllers\Api\CarController@addCar');
    Route::get('car/brands', 'App\Http\Controllers\Api\CarController@brands');
    Route::get('car/models', 'App\Http\Controllers\Api\CarController@models');
    Route::get('car/versions/{model}', 'App\Http\Controllers\Api\CarController@versions');
    Route::get('car/images', 'App\Http\Controllers\Api\CarController@getImages');
    Route::post('car/images', 'App\Http\Controllers\Api\CarController@addImage');

    /*
   |--------------------------------------------------------------------------
   | Services API Routes
   |--------------------------------------------------------------------------
   */
    Route::get('services', 'App\Http\Controllers\Api\ServiceController@index');

    /*
     |--------------------------------------------------------------------------
     | CDK API Routes
     |--------------------------------------------------------------------------
     */
    Route::prefix('cdk')->group(function () {
        Route::get('customers', 'Cdk\CustomerController@index');
        Route::post('customers', 'Cdk\CustomerController@create');
        Route::get('vehicles', 'Cdk\VehicleController@index');
        Route::get('vehicles/inventory', 'Cdk\VehicleController@inventory');
    });
});
