<?php

	namespace App\Providers;

	use Carbon\Carbon;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\ServiceProvider;
	use Illuminate\Support\Facades\Schema;
	use Validator;

	class AppServiceProvider extends ServiceProvider
	{
		/**
		 * Bootstrap any application services.
		 *
		 * @return void
		 */
		public function boot()
		{
			Schema ::defaultStringLength(191);
//			Validator ::extend('alpha_spaces', function ( $attribute, $value ) {
//				return preg_match('/^[\pL\s]+$/u', $value);
//			});
			//Add this custom validation rule.
			Validator ::extend('alpha_spaces', function ( $attribute, $value ) {

				// This will only accept alpha and spaces.
				// If you want to accept hyphens use: /^[\pL\s-]+$/u.
				return preg_match('/^[0-9\pL\s]+$/u', $value);
			});

            /** Set Lang **/
            App ::setLocale( 'ar' );
            Carbon ::setLocale( 'ar' );
		}


		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register()
		{
			//
		}
	}
