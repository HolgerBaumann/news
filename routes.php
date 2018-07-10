<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;

Route::get( 'confirm', function () {

	$data = get();

	/*DB::enableQueryLog();

	$test = DB::table('holgerbaumann_news_subscribers')->where('email', $data['email'])->where('subscription_key', $data['key'])->count() == 1;

	dump($test);

	dd(
		DB::getQueryLog()
	);*/


	if ( DB::table( 'holgerbaumann_news_subscribers' )->where( 'email', $data['email'] )->where( 'subscription_key', $data['key'] )->count() == 1 ) {

		$locale = DB::table( 'holgerbaumann_news_subscribers' )->where( 'email', $data['email'] )->where( 'subscription_key', $data['key'] )->value( 'locale' );

		$unsubscription_key = md5( time() . $data['email'] );

		$updated_at = Carbon::now()->format( 'Y-m-d H:i:s' );

		$confirmed_ip = $_SERVER['REMOTE_ADDR'];

		$sql = "UPDATE holgerbaumann_news_subscribers
				SET status = 1,
				subscription_key = '',
				unsubscription_key = '" . $unsubscription_key . "',
				updated_at = '" . $updated_at . "',
				confirmed_at = '" . $updated_at . "',
				confirmed_ip = '" . $confirmed_ip . "'
				WHERE email = ? 
				AND subscription_key = ?";

		Db::update( $sql, [ $data['email'], $data['key'] ] );

		if ( $locale == 'de' ) {
			return Redirect::to( 'de/newsletter/erfolg' )->with( 'email', $data['email'] );
		} else {
			return Redirect::to( 'en/newsletter/success' )->with( 'email', $data['email'] );
		}

	}

	//not found? die silently!
	exit;
} );