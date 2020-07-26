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

Route::resource('wishlist', 'Cart\WishListController', [
	'parameters' => [
		'wishlist' => 'product'
	]
]);

Route::GET('wishlist','Cart\WishCartController@index');

Route::POST('wishlist/{product}','Cart\WishCartController@update');

Route::DELETE('wishlist/{product}', 'Cart\WishCartController@destroy');

Route::DELETE('destroy-wishlist', 'Cart\WishCartController@destroyAll');
