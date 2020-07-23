<?php



Route::resource('products', 'Products\ProductsController');



Route::resource('cart', 'Cart\CartController', [
	'parameters' => [
		'cart' => 'productVariation'
	]
]);

Route::group([ 'prefix' => 'auth'], function () {
	Route::post('register', 'Auth\\RegisterController@store');

	Route::post('login', 'Auth\\LoginController@store');

	Route::get('me', 'Auth\\MeController@show');
});
