<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/home', function () {
    return redirect()->route('home');
});

Route::prefix('cms')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home');
    });

    Auth::routes();
    Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Cms Home  Route
    |--------------------------------------------------------------------------
    */
    Route::get('/home', 'App\Http\Controllers\Cms\HomeController@index')->name('home');


    /*
     |-----------------
     | Import Offers from CarPlanner Website
     |-----------------
     */
    Route::get('offer/import', [
        'as' => 'offer.import',
        'uses' => 'App\Http\Controllers\Cms\OfferController@import',
    ]);


    Route::get('offer/import/{id}/create', [
        'as' => 'offer.import.create',
        'uses' => 'App\Http\Controllers\Cms\OfferController@showFromImport',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Offers Routes
    |--------------------------------------------------------------------------
    */

    Route::resource('offer', 'App\Http\Controllers\Cms\OfferController');

    Route::get('offer/{id}/child/generate', [
        'as' => 'offer.generateChild',
        'uses' => 'App\Http\Controllers\Cms\OfferController@generateChildOffers',
    ]);
    Route::post('offer/regenerate/{id}', [
        'as' => 'offer.regenerate',
        'uses' => 'App\Http\Controllers\Cms\OfferController@regenerateImages',
    ]);

    Route::get('offer/{id}/child/delete', [
        'as' => 'offer.deleteChild',
        'uses' => 'App\Http\Controllers\Cms\OfferController@deleteChildOffers',
    ]);

    Route::post('offer/child/update', [
        'as' => 'offer.updateChild',
        'uses' => 'App\Http\Controllers\Cms\OfferController@updateChildOffer',
    ]);

    Route::post('offer/childs/add', [
        'as' => 'offer.addChilds',
        'uses' => 'App\Http\Controllers\Cms\OfferController@addChildOffers',
    ]);
    Route::get('offer/{id}/childs/show', [
        'as' => 'offer.showChilds',
        'uses' => 'App\Http\Controllers\Cms\OfferController@showChildOffers',
    ]);
    Route::post('offer/{id}/childs/asmain', [
        'as' => 'offer.asMain',
        'uses' => 'App\Http\Controllers\Cms\OfferController@setAsMain',
    ]);


    Route::get('offer/{id}/agents', ['as' => 'offer.agents', 'uses' => 'App\Http\Controllers\Cms\OfferController@attachAgent']);
    Route::post('offer/{id}/agents', ['as' => 'offer.updateAgent', 'uses' => 'App\Http\Controllers\Cms\OfferController@updateAgent']);

    Route::post('offer/service', ['as' => 'offer.service', 'uses' => 'App\Http\Controllers\Cms\OfferController@service',]);
    Route::post('offer/status', ['as' => 'offer.status', 'uses' => 'App\Http\Controllers\Cms\OfferController@status']);
    Route::post('offer/suggested', ['as' => 'offer.suggested', 'uses' => 'App\Http\Controllers\Cms\OfferController@suggested']);
    Route::post('offer/versions', ['as' => 'offer.versions', 'uses' => 'App\Http\Controllers\Cms\OfferController@getVersions']);
    Route::post('offers/models', ['as' => 'offer.models', 'uses' => 'App\Http\Controllers\Cms\OfferController@getModels']);

    /*
     |--------------------------------------------------------------------------
     | Rent Child Offers Routes
     |--------------------------------------------------------------------------
     */

    Route::post('offer/{id}/childs/store', [
        'as' => 'offer.childs.store',
        'uses' => 'App\Http\Controllers\Cms\ChildOfferController@store',
    ]);
    Route::post('offer/{id}/childs/destroy', [
        'as' => 'offer.childs.destroy',
        'uses' => 'App\Http\Controllers\Cms\ChildOfferController@destroy',
    ]);
    Route::post('offer/{id}/childs/main', [
        'as' => 'offer.childs.main',
        'uses' => 'App\Http\Controllers\Cms\ChildOfferController@setAsMain',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Offers Service Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('service', 'App\Http\Controllers\Cms\ServiceController');

    /*
    |--------------------------------------------------------------------------
    | Car images Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('image', 'App\Http\Controllers\Cms\ImageController');

    /*
    |--------------------------------------------------------------------------
    | Car brands Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('brand', 'App\Http\Controllers\Cms\BrandController');

    /*
    |--------------------------------------------------------------------------
    | Car category  Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('category', 'App\Http\Controllers\Cms\CategoryController');

    /*
    |--------------------------------------------------------------------------
    | Documents Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('documents', 'App\Http\Controllers\Cms\DocumentsController');

    Route::resource('document-list', 'App\Http\Controllers\Cms\DocumentListController');

    /*
     |--------------------------------------------------------------------------
     | Cars Accessory Routes
     |--------------------------------------------------------------------------
      */
    Route::prefix('cars')->controller('App\Http\Controllers\Cms\CarAccessoriesController')->group(function () {
        Route::get('accessories/{id}/edit', [
            'as' => 'cars.accessories.edit',
            'uses' => 'edit',
        ]);
        Route::patch('accessories/{id}', [
            'as' => 'cars.accessories.update',
            'uses' => 'App\pdate',
        ]);
        Route::post('accessories/status', [
            'as' => 'cars.accessories.status',
            'uses' => 'status',
        ]);
        Route::post('accessories/{id}/update', [
            'as' => 'cars.accessories.updateFromSource',
            'uses' => 'updateFromSource',
        ]);
    });
    /*
    |--------------------------------------------------------------------------
    | Agents Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('agent', 'App\Http\Controllers\Cms\AgentController');
    Route::post('agent/{id}/filters', ['as' => 'agent.filters', 'uses' => 'App\Http\Controllers\Cms\AgentController@filters']);
    Route::post('agent/{id}/suspend', ['as' => 'agent.suspend', 'uses' => 'App\Http\Controllers\Cms\AgentController@suspend']);
    Route::post('agent/{id}/activate', ['as' => 'agent.activate', 'uses' => 'App\Http\Controllers\Cms\AgentController@activate']);
    //account services
    Route::post('agent/{id}/service/api', ['as' => 'agent.service.api', 'uses' => 'App\Http\Controllers\Cms\AgentController@createApiServiceToken']);

    /*
    |--------------------------------------------------------------------------
    | Agent Groups  Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('group', 'App\Http\Controllers\Cms\GroupController');
    Route::post('group/{id}/filters', ['as' => 'group.filters', 'uses' => 'App\Http\Controllers\Cms\GroupController@filters']);

    /*
    |--------------------------------------------------------------------------
    | Customers  Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('customer', 'App\Http\Controllers\Cms\CustomerController');

    /*
    |--------------------------------------------------------------------------
    | Quotations  Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('quotation', 'App\Http\Controllers\Cms\QuotationController');


    /*
    |--------------------------------------------------------------------------
    | Car Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('car', 'App\Http\Controllers\Cms\CarController');
    Route::get('car/{id}/image', [
        'as' => 'car.image.create',
        'uses' => 'App\Http\Controllers\Cms\CarController@addImage',
    ]);
    Route::post('car/{id}/image', [
        'as' => 'car.image.upload',
        'uses' => 'App\Http\Controllers\Cms\CarController@uploadImage',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Price Indexers  Routes
    |--------------------------------------------------------------------------

    Route::resource('price-indexers', 'App\Http\Controllers\Cms\PriceIndexersController', ['except' => ['show']]);
    Route::post('price-indexers/calculate/{id}', [
        'as' => 'price-indexers.calculate',
        'uses' => 'App\Http\Controllers\Cms\PriceIndexersController@calculate'
    ]);
    */

    /*
   |--------------------------------------------------------------------------
   | Commission Indexers Routes
   |--------------------------------------------------------------------------
   */
    Route::resource('commission', 'App\Http\Controllers\Cms\FeeController', ['except' => ['show']]);

    /*
     |--------------------------------------------------------------------------
     | Promotions Routes
     |--------------------------------------------------------------------------
     */
    Route::resource('promotion', 'App\Http\Controllers\Cms\PromotionController');
});
