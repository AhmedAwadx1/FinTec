<?php

use Illuminate\Support\Facades\Route;


  Route::get('/'                    , 'IntroController@index')->name('intro');
  Route::get('/privacy-policy'      , 'IntroController@privacyPolicy')->name('IntroPrivacyPolicy');
  Route::get('/delete-account-page' , 'IntroController@deleteAccount')->name('deleteAccountPage');
  // Route::post('/send-message'       , 'IntroController@sendMessage');
  Route::get('/lang/{lang}'         , 'IntroController@SetLanguage');

  Route::group(['middleware' => ['guest']], function () {

  });

  Route::get('/hyper-pay-form/{transaction_id}/{brand_id}/{brand_type}', 'PaymentController@getHyperPay')->name('getHyperPay');
  Route::group(['middleware' => ['auth']], function () {
    // Route::get('/show-chat/{id}', 'ChatController@getChatRoom')->name('getChatRoom');
    // Route::post('/upload-chat-file', 'ChatController@uploadChatFile')->name('uploadChatFile');
    
  });

  // redirect to home if url not found
  Route::fallback(function () {
    return redirect()->route('intro');
  });

  // payment
    Route::get('payment/get-payment-status/{brand_id?}',  [\App\Services\PaymentGateway\PaymentService::class,'callback'])->name('payment.getPaymentStatus');
  // payment


