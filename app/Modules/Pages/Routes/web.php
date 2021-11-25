<?php

Route::namespace('Api\Client')->group(function(){
    Route::get('localization-text','LocalizationController@getLangFile');

    Route::prefix('page')->name('page.')->group(function(){
        Route::get('{module}','PageController@index')->name('index');
        Route::get('type','PageController@pageType');
    });
});
