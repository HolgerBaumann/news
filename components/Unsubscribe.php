<?php namespace HolgerBaumann\News\Components;

use Cms\Classes\ComponentBase;
use HolgerBaumann\News\Models\Subscribers;
use Lang;
use Validator;
use ValidationException;
use Response;
use Flash;
use October\Rain\Exception\AjaxException;

class Unsubscribe extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'holgerbaumann.news::lang.component.unsubscribe',
            'description' => ''
        ];
    }

    public function onRun()
    {
        $this->page['text_messages'] = Lang::get('holgerbaumann.news::lang.messages.unsubscribed');
        $this->page['text_email']    = Lang::get('holgerbaumann.news::lang.form.email');
        $this->page['text_button']   = Lang::get('holgerbaumann.news::lang.button.unsubscribe');
    }

    public function onUnsubscribe()
    {
        $data = post();
        $subscriber = Subscribers::email($data['email'])->first();

        if ($subscriber === null || !$subscriber->isActive()) {
            return Response::make(Lang::get('holgerbaumann.news::lang.messages.not_subscribed'), 400);
        }

        $subscriber->unsubscribe();
    }
}
