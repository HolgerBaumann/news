<?php namespace HolgerBaumann\News;

use System\Classes\PluginBase;
use Backend;
use Event;
use HolgerBaumann\News\Models\Posts;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'holgerbaumann.news::lang.plugin.name',
            'description' => 'holgerbaumann.news::lang.plugin.description',
            'author'      => 'Gergő Szabó, Holger Baumann',
            'homepage'    => 'https://github.com/HolgerBaumann/news'
        ];
    }

    public function registerNavigation()
    {
        return [
            'news' => [
                'label'       => 'holgerbaumann.news::lang.menu.news',
                'url'         => Backend::url('holgerbaumann/news/posts'),
                'icon'        => 'icon-newspaper-o',
                'iconSvg'     => 'plugins/holgerbaumann/news/assets/images/news-icon.svg',
                'permissions' => ['holgerbaumann.news.*'],
                'order'       => 201,

                'sideMenu' => [
                    'posts' => [
                        'label'       => 'holgerbaumann.news::lang.menu.posts',
                        'url'         => Backend::url('holgerbaumann/news/posts'),
                        'icon'        => 'icon-file-text',
                        'permissions' => ['holgerbaumann.news.posts']
                    ],
                    'categories' => [
                        'label'       => 'holgerbaumann.news::lang.menu.categories',
                        'url'         => Backend::url('holgerbaumann/news/categories'),
                        'icon'        => 'icon-tags',
                        'permissions' => ['holgerbaumann.news.categories']
                    ],
                    'subscribers' => [
                        'label'        => 'holgerbaumann.news::lang.menu.subscribers',
                        'url'         => Backend::url('holgerbaumann/news/subscribers'),
                        'icon'        => 'icon-user',
                        'permissions' => ['holgerbaumann.news.subscribers']
                    ],
                    'statistics' => [
                        'label'       => 'holgerbaumann.news::lang.menu.statistics',
                        'url'         => Backend::url('holgerbaumann/news/statistics'),
                        'icon'        => 'icon-area-chart',
                        'permissions' => ['holgerbaumann.news.statistics']
                    ],
                    'logs' => [
                        'label'       => 'holgerbaumann.news::lang.menu.logs',
                        'url'         => Backend::url('holgerbaumann/news/logs'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['holgerbaumann.news.logs']
                    ],
                    'settings' => [
                        'label'       => 'holgerbaumann.news::lang.menu.settings',
                        'url'         => Backend::url('system/settings/update/holgerbaumann/news/settings'),
                        'icon'        => 'icon-cogs',
                        'permissions' => ['holgerbaumann.news.settings']
                    ]
                ]
            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'holgerbaumann.news::lang.plugin.name',
                'description' => 'holgerbaumann.news::lang.backend_settings.description',
                'category'    => 'system::lang.system.categories.cms',
                'icon'        => 'icon-newspaper-o',
                'class'       => 'HolgerBaumann\News\Models\Settings',
                'order'       => 500,
                'keywords'    => 'news newsletter email statistics',
                'permissions' => ['holgerbaumann.news.settings']
            ]
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'HolgerBaumann\News\ReportWidgets\Posts' => [
                'label'   => 'holgerbaumann.news::lang.widget.posts',
                'context' => 'dashboard'
            ],
            'HolgerBaumann\News\ReportWidgets\NewPosts' => [
                'label'   => 'holgerbaumann.news::lang.widget.newposts',
                'context' => 'dashboard'
            ],
            'HolgerBaumann\News\ReportWidgets\TopPosts' => [
                'label'   => 'holgerbaumann.news::lang.widget.topposts',
                'context' => 'dashboard'
            ],
            'HolgerBaumann\News\ReportWidgets\Subscribers' => [
                'label'   => 'holgerbaumann.news::lang.widget.subscribers',
                'context' => 'dashboard'
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'HolgerBaumann\News\Components\Posts'       => 'newsPosts',
            'HolgerBaumann\News\Components\Post'        => 'newsPost',
            'HolgerBaumann\News\Components\Categories'  => 'newsCategories',
            'HolgerBaumann\News\Components\Subscribe'   => 'newsSubscribe',
            'HolgerBaumann\News\Components\Unsubscribe' => 'newsUnsubscribe'
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'holgerbaumann.news::mail.email_en' => 'E-mail',
            'holgerbaumann.news::mail.email_de' => 'E-mail',
            'holgerbaumann.news::mail.email_hu' => 'E-mail',
            'holgerbaumann.news::mail.confirm_de' => 'Confirmation E-mail',
            'holgerbaumann.news::mail.confirm_en' => 'Confirmation E-mail'
        ];
    }

    public function registerPermissions()
    {
        return [
            'holgerbaumann.news.posts' => [
                'tab'   => 'holgerbaumann.news::lang.menu.news',
                'label' => 'holgerbaumann.news::lang.permission.posts',
                'order' => 100,
                'roles' => ['publisher']
            ],
            'holgerbaumann.news.categories' => [
                'tab'   => 'holgerbaumann.news::lang.menu.news',
                'label' => 'holgerbaumann.news::lang.permission.categories',
                'order' => 200,
                'roles' => ['publisher']
            ],
            'holgerbaumann.news.subscribers' => [
                'tab'   => 'holgerbaumann.news::lang.menu.news',
                'label' => 'holgerbaumann.news::lang.permission.subscribers',
                'order' => 300,
                'roles' => ['publisher']
            ],
            'holgerbaumann.news.statistics' => [
                'tab'   => 'holgerbaumann.news::lang.menu.news',
                'label' => 'holgerbaumann.news::lang.permission.statistics',
                'order' => 400,
                'roles' => ['publisher']
            ],
            'holgerbaumann.news.import_export' => [
                'tab'   => 'holgerbaumann.news::lang.menu.news',
                'label' => 'holgerbaumann.news::lang.permission.import_export',
                'order' => 500,
                'roles' => ['publisher']
            ],
            'holgerbaumann.news.logs' => [
                'tab'   => 'holgerbaumann.news::lang.menu.news',
                'label' => 'holgerbaumann.news::lang.permission.logs',
                'order' => 600,
                'roles' => ['publisher']
            ],
            'holgerbaumann.news.settings' => [
                'tab'   => 'holgerbaumann.news::lang.menu.news',
                'label' => 'holgerbaumann.news::lang.permission.settings',
                'order' => 700,
                'roles' => ['publisher']
            ]
        ];
    }

    public function registerSchedule($schedule)
    {
        $schedule->command('queue:work --daemon --queue=newsletter')->everyMinute()->withoutOverlapping();
    }

    public function boot()
    {
        Event::listen('pages.menuitem.listTypes', function()
        {
            return [
                'post-list' => 'holgerbaumann.news::lang.sitemap.post_list',
                'post-page' => 'holgerbaumann.news::lang.sitemap.post_page'
            ];
        });

        Event::listen('pages.menuitem.getTypeInfo', function($type)
        {
            if ($type == 'post-list' || $type == 'post-page') {
                return Posts::getMenuTypeInfo($type);
            }
        });

        Event::listen('pages.menuitem.resolveItem', function($type, $item, $url, $theme)
        {
            if ($type == 'post-list' || $type == 'post-page') {
                return Posts::resolveMenuItem($item, $url, $theme);
            }
        });
    }
}
