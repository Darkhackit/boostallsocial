<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api' , 'prefix' => 'auth'] , function ($route) {
    Route::post('/login',\App\Http\Controllers\Client\LoginController::class);
    Route::post('/register',\App\Http\Controllers\Client\RegisterController::class);
    Route::post('/code_confirm',\App\Http\Controllers\Client\ConfirmCodeController::class);
    Route::post('/forget_password',\App\Http\Controllers\Client\ForgetPasswordController::class);
    Route::post('/change_password',\App\Http\Controllers\Client\ChangePasswordController::class);
    Route::post('/lost_but_found',[\App\Http\Controllers\Admin\AdminAuthController::class,'login']);
});

Route::post('/add-provider',[\App\Http\Controllers\ProviderController::class,'create']);
Route::get('/get-provider',[\App\Http\Controllers\ProviderController::class,'index']);
Route::get('/service-provider/{provider}',[\App\Http\Controllers\ProviderController::class,'service']);
Route::get('/view-provider/{provider}',[\App\Http\Controllers\ProviderController::class,'show']);
Route::put('/update-provider/{provider}',[\App\Http\Controllers\ProviderController::class,'update']);
Route::delete('/delete-provider/{provider}',[\App\Http\Controllers\ProviderController::class,'delete']);


Route::post('/add-social-media-service',[\App\Http\Controllers\SocialMediaController::class,'create']);
Route::get('/get-social-media',[\App\Http\Controllers\SocialMediaController::class,'index']);
Route::get('/view-social/{socialMedia}',[\App\Http\Controllers\SocialMediaController::class,'show']);
Route::put('/update-social/{socialMedia}',[\App\Http\Controllers\SocialMediaController::class,'update']);
Route::delete('/delete-social/{socialMedia}',[\App\Http\Controllers\SocialMediaController::class,'delete']);
Route::get('/get-service',[\App\Http\Controllers\SocialMediaController::class,'getService']);
Route::get('/single-service/{val}',[\App\Http\Controllers\SocialMediaController::class,'service_details']);

Route::get('/get-provider-names',[\App\Http\Controllers\ProviderController::class,'names']);
Route::get('/get-media-names',[\App\Http\Controllers\SocialMediaController::class,'names']);

Route::post('/target-country',[\App\Http\Controllers\CountryController::class,'create']);
Route::get('/target-country',[\App\Http\Controllers\CountryController::class,'index']);
Route::get('/target-country/{country}',[\App\Http\Controllers\CountryController::class,'show']);
Route::put('/target-country/{country}',[\App\Http\Controllers\CountryController::class,'update']);
Route::delete('/target-country/{country}',[\App\Http\Controllers\CountryController::class,'delete']);

