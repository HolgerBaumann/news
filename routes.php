<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Flash;

Route::get('confirm', function () {

	$data = get();


	if (DB::table('indikator_news_subscribers')->where('email', $data['email'])->where('subscription_key', $data['key'])->count() == 1) {

		$locale = DB::table('indikator_news_subscribers')->where('email', $data['email'])->where('subscription_key', $data['key'])->value('locale');

		$unsubscription_key = md5(time().$data['email']);

		$updated_at = Carbon::now()->format('Y-m-d H:i:s');

		$sql = "UPDATE indikator_news_subscribers 
				SET status = 1,
				subscription_key = '',
				unsubscription_key = '".$unsubscription_key."',
				updated_at = '".$updated_at."'
				WHERE email = ? 
				AND subscription_key = ?";

		Db::update($sql, [$data['email'],$data['key']]);

		return Redirect::to('newsletter/success')->with('email', $data['email']);
	}

	//not found? die silently!
	exit;
});