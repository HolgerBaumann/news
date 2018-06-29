<?php namespace HolgerBaumann\News\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'holgerbaumann_news_settings';

    public $settingsFields = 'fields.yaml';
}
