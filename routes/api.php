<?php



Route::resource('categories','Categories\CategoryController');
Route::resource('products','Products\ProductController');

Route::group([

		'namespace' => 'Auth',
		'prefix' => 'auth'

], function ($router) {
Route::post('register','AuthController@register');
Route::post('login','AuthController@login');
Route::get('me','AuthController@show');
});


Route::resource('cart', 'Cart\CartController', [
	'parameters' => [
		'cart' => 'productVariation'
	]
]);

Route::DELETE('destroy-cart', 'Cart\CartController@destroyAll');

Route::GET('wishlist', 'Cart\WishListController@index');
Route::POST('wishlist/{product}', 'Cart\WishListController@toggle');
