<?php namespace HolgerBaumann\News\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use HolgerBaumann\News\Models\Subscribers as Item;
use Db;
use Flash;
use Lang;

class Subscribers extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\ImportExportController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public $requiredPermissions = ['holgerbaumann.news.subscribers'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('HolgerBaumann.News', 'news', 'subscribers');
    }

    public function onSubscribe()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $itemId) {
                if (!$item = Item::where('status', 2)->whereId($itemId)) {
                    continue;
                }

                $item->update(['status' => 1]);
            }

            Flash::success(Lang::get('holgerbaumann.content::lang.flash.subscribe'));
        }

        return $this->listRefresh();
    }

    public function onUnsubscribe()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $itemId) {
                if (!$item = Item::where('status', 1)->whereId($itemId)) {
                    continue;
                }

                $item->update(['status' => 2]);
            }

            Flash::success(Lang::get('holgerbaumann.content::lang.flash.unsubscribe'));
        }

        return $this->listRefresh();
    }

    public function onRemove()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $itemId) {
                if (!$item = Item::whereId($itemId)) {
                    continue;
                }

                $item->delete();

                Db::table('holgerbaumann_news_relations')->where('subscriber_id', $itemId)->delete();
            }

            Flash::success(Lang::get('holgerbaumann.news::lang.flash.remove'));
        }

        return $this->listRefresh();
    }
}
