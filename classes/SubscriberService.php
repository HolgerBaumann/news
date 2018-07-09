<?php namespace HolgerBaumann\News\Classes;

use Db;
use HolgerBaumann\News\Models\Categories;
use HolgerBaumann\News\Models\Settings;

trait SubscriberService
{
    /**
     * Handles subscriber registration
     * either by registration in the frontend or by creating in the backend
     * @param $listOfCategoryIds array of subscribing Ids
     */
    public function onSubscriberRegister($subscriber, $listOfCategoryIds = [])
    {
        // Register category
        foreach ($listOfCategoryIds as $category) {
            if (is_numeric($category) && Categories::where(['id' => $category, 'hidden' => 2])->count() == 1 && Db::table('holgerbaumann_news_relations')->where(['subscriber_id' => $subscriber->id, 'categories_id' => $listOfCategoryIds])->count() == 0) {
                Db::table('holgerbaumann_news_relations')->insertGetId([
                    'subscriber_id' => $subscriber->id,
                    'categories_id' => $category
                ]);
            }
        }

        if (!$subscriber->isActive()) {
            if (Settings::get('newsletter_double_opt_in', true)) {
                $subscriber->register();
                ConfirmationHandler::sendConfirmationEmailToSubscriber($subscriber);
            }
            else {
                $subscriber->activate();
            }
        }
    }
}
