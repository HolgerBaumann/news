<?php namespace HolgerBaumann\News\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Mail;
use HolgerBaumann\News\Models\Categories;
use HolgerBaumann\News\Models\Subscribers;
use Lang;
use Db;
use App;
use October\Rain\Support\Facades\Flash;
use Validator;
use ValidationException;

class Subscribe extends ComponentBase
{
	public function componentDetails()
	{
		return [
			'name'        => 'holgerbaumann.news::lang.component.subscribe',
			'description' => ''
		];
	}

	public function onRun()
	{
		$category = Categories::where(['status' => 1, 'hidden' => 2]);
		$this->page['category_list']  = $category->get()->all();
		$this->page['category_count'] = $category->count();

		$this->page['text_messages'] = Lang::get('holgerbaumann.news::lang.messages.subscribed');
		$this->page['text_name']     = Lang::get('holgerbaumann.news::lang.form.name');
		$this->page['text_email']    = Lang::get('holgerbaumann.news::lang.form.email');
		$this->page['text_category'] = Lang::get('holgerbaumann.news::lang.form.category');
		$this->page['text_button']   = Lang::get('holgerbaumann.news::lang.button.subscribe');
	}

	public function onSubscription()
	{
		// Get data from form
		$data = post();

		// Validate input data
		$rules = [
			'name'  => 'required|between:2,64',
			'email' => 'required|email|between:8,64'
		];

		$validation = Validator::make($data, $rules);
		if ($validation->fails()) {
			throw new ValidationException($validation);
		}

		// look for unsubscribed subscribers
		$subscriberResult = Subscribers::email($data['email']);

		if ($subscriberResult->count() > 0) {
			$subscriber = $subscriberResult->first();

			if (!$subscriber->isActive()) {
				$subscriber->name = $data['name'];
				$subscriber->activate();
			}

			// Check category
			if (!isset($data['category']) || !is_array($data['category'])) {
				return;
			}

			// Register category
			foreach ($data['category'] as $category) {
				if (is_numeric($category) && Categories::where(['id' => $category, 'hidden' => 2])->count() == 1 && Db::table('holgerbaumann_news_relations')->where(['subscriber_id' => $subscriber->id, 'categories_id' => $data['category']])->count() == 0) {
					Db::table('holgerbaumann_news_relations')->insertGetId([
						'subscriber_id' => $subscriber->id,
						'categories_id' => $category
					]);
				}
			}

			return;
		}

		// Register new one
		$id = Subscribers::insertGetId([
			'name'       => $data['name'],
			'email'      => $data['email'],
			'common'     => '',
			'locale'     => App::getLocale(),
			'created'    => 2,
			'statistics' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		]);

		// Check category
		if (!isset($data['category']) || !is_array($data['category'])) {
			return;
		}

		// Register category
		foreach ($data['category'] as $category) {
			if (is_numeric($category) && Categories::where(['id' => $category, 'hidden' => 2])->count() == 1) {
				Db::table('holgerbaumann_news_relations')->insertGetId([
					'subscriber_id' => $id,
					'categories_id' => $category
				]);
			}
		}
	}

	function onSubscriptionForConfirmation()
	{

		$data = post();

		//DB::enableQueryLog();

		if (DB::table('holgerbaumann_news_subscribers')->where('email', $data['email'])->count() == 1) {
			throw new ValidationException(['email' => 'already in db']);
		}

		/*dd(
			DB::getQueryLog()
		);*/

		$rules = [
			'name'  => 'required|between:2,64',
			'email' => 'email|required|unique:holgerbaumann_news_subscribers,email|between:8,64'
		];

		$messages = [
			'name.required' => Lang::get('holgerbaumann.news::lang.validate.name'),/*We need to know your name!*/
			'email.valid' => Lang::get('holgerbaumann.news::lang.validate.email_valid'),
			'email.required' => Lang::get('holgerbaumann.news::lang.validate.email_required'),/*'We need to know your e-mail address!'*/
		];

		$validation = Validator::make($data, $rules,$messages);
		if ($validation->fails()) {
			throw new ValidationException($validation);
		}

		$subscription_key = md5(time().$data['email']);

		DB::table('holgerbaumann_news_subscribers')->insertGetId([
			'name' => $data['name'],
			'email' => $data['email'],
			'common' => '',
			'status' => 3,
			'created' => 2,
			'statistics' => 0,
			'subscription_key' => $subscription_key,
			'locale' => $data['lang'],
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
			'registered_at' => date('Y-m-d H:i:s'),
			'registered_ip' => $_SERVER['REMOTE_ADDR']
		]);

		$email = $data['email'];

		$user = $data['name'];

		$url = env('APP_URL');

		$url_protocol_less = env('APP_URL_PROTOCOL_LESS');

		$site_name = env('APP_URL');

		$vars = [
			'subscription_key'  => $subscription_key,
			'user'              => $data['name'],
			'email'             => $data['email'],
			'url'               => $url,
			'url_protocol_less' => $url_protocol_less,
			'site_name'         => $site_name

		];

		Mail::send('holgerbaumann.news::mail.confirm_' . $data['lang'], $vars, function($message) use ($email, $user) {

			$message->to($email, $user);
			$message->subject(Lang::get('holgerbaumann.news::lang.subscription.email_subject'));

		});


		Flash::success('Jobs done!');

		return '';
	}
}
