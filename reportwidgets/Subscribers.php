<?php namespace HolgerBaumann\News\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Exception;
use HolgerBaumann\News\Models\Subscribers as Subscriber;

class Subscribers extends ReportWidgetBase
{
    public function render()
    {
        try {
            $this->loadData();
        }
        catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'backend::lang.dashboard.widget_title_label',
                'default'           => 'holgerbaumann.news::lang.widget.subscribers',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error'
            ],
            'total' => [
                'title'             => 'holgerbaumann.news::lang.widget.show_total',
                'default'           => true,
                'type'              => 'checkbox'
            ],
            'active' => [
                'title'             => 'holgerbaumann.news::lang.widget.show_active',
                'default'           => true,
                'type'              => 'checkbox'
            ],
            'unsub' => [
                'title'             => 'holgerbaumann.news::lang.widget.show_unsub',
                'default'           => true,
                'type'              => 'checkbox'
            ]
        ];
    }

    protected function loadData()
    {
        $this->vars['active'] = Subscriber::where('status', 1)->count();
        $this->vars['unsub']  = Subscriber::where('status', 2)->count();
        $this->vars['total']  = $this->vars['active'] + $this->vars['unsub'];
    }
}
