<?php

	header( 'Access-Control-Allow-Origin: *' );
	header( "Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept" );


	Route ::any( 'login', 'AuthController@login' );
	Route ::any( 'register', 'AuthController@register' );
	Route ::any( 'phone-registration', 'AuthController@phoneRegistration' );
	Route ::any( 'resend-code', 'AuthController@forgetPassword' );
	Route ::any( 'forget-password', 'AuthController@forgetPassword' );
	Route ::any( 'reset-password', 'AuthController@resetPassword' );
	Route ::any( 'code-check', 'AuthController@codeCheck' );

	Route ::any( 'send-otp', 'AuthController@sendOtp' );
	Route ::any( 'update-phone', 'AuthController@updateUserPhone' );

	Route ::any( 'social_login', 'AuthController@social_login' );

	Route ::any( 'ads-info', 'AdsController@adsInfo' );
	
	Route ::any( 'main-categories', 'SettingController@mainCategories' );
	Route ::any( 'offer-categories', 'SettingController@offerCategories' );
	Route ::any( 'project-categories', 'SettingController@projectCategories' );
	Route ::any( 'repair-types', 'SettingController@repairType' );
	Route ::any( 'add-repair', 'SettingController@addRepair' );
	Route ::any( 'about-us', 'SettingController@aboutUs' );
	Route ::any( 'terms', 'SettingController@terms' );
	Route ::any( 'app-social', 'SettingController@social' );

	Route ::any( 'terms_social', 'SettingController@terms_social' );
	Route ::any( 'ads_category', 'SearchController@ads_category' );
	Route ::any( 'filter_ads', 'SearchController@filter_ads' );

	Route ::any( 'filter_ads', 'SearchController@filter_ads' );

	Route ::any( 'order-reasons', 'HomeController@orderReason' );
	Route ::any( 'branches', 'HomeController@branches' );
	Route ::any( 'banners', 'HomeController@banners' );


	Route ::any( 'search', 'SearchController@search' );
	Route ::any( 'main-ads', 'SearchController@mainAds' );
	Route ::any( 'offer-ads', 'SearchController@offerAds' );
	Route ::any( 'all-ads', 'SearchController@allAds' );
	Route ::any( 'today-ads', 'SearchController@todayAds' );
	Route ::any( 'project-ads', 'SearchController@projectAds' );

	Route ::any( 'homescreen', 'HomeController@homescreen' );
	Route ::any( 'cities', 'HomeController@cities' );

	Route ::group( [ 'middleware' => [ 'jjwt', 'jwt.auth' ] ], function () {


		Route ::any( 'profile', 'AuthController@profile' );
		Route ::any( 'edit-profile', 'AuthController@editProfile' );
		Route ::any( 'logout', 'AuthController@logout' );


		Route ::any( 'send-msg', 'ChatController@sendMsg' );
		Route ::any( 'chat-rooms', 'ChatController@allRooms' );
		Route ::any( 'chat', 'ChatController@chat' );

		Route ::any( 'add-review', 'AdsController@addReview' );
		Route ::any( 'add-order', 'HomeController@addOrder' );

		Route ::any( 'all-fav', 'AdsController@allFav' );
		Route ::any( 'add-fav', 'AdsController@addFav' );
		Route ::any( 'remove-fav', 'AdsController@removeFav' );

		Route ::any( 'notification', 'NotificationController@notify' );
		Route ::any( 'notifilable', 'NotificationController@notifilable' );
		
		Route ::any( 'ads', 'AdsController@ads' );


		Route ::any( 'orders', 'AdsController@orders' );


		Route ::any( 'contact-us', 'SettingController@contactUs' );
		Route ::any( 'add-job', 'SettingController@addJob' );


		Route ::any( 'myOrder', 'orderController@myOrder' );


	} );

	Route ::any( 'doctor_info', 'doctorController@doctor_info' );
	Route ::any( 'offers', 'categoryController@offers' );
	Route ::any( 'order', 'orderController@Order' );
	Route ::any( 'myOrder', 'orderController@myOrder' );
	Route ::any( 'stars', 'doctorController@stars' );
