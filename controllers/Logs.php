<?php namespace HolgerBaumann\News\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Logs extends Controller
{
    public $implement = [
        \Backend\Behaviors\ListController::class
    ];

    public $requiredPermissions = ['holgerbaumann.news.logs'];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('HolgerBaumann.News', 'news', 'logs');
    }
}
