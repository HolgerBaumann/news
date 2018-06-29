<?php namespace HolgerBaumann\News\Models;

use Model;

class Categories extends Model
{
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    protected $table = 'holgerbaumann_news_categories';

    public $rules = [
        'name'   => 'required',
        'slug'   => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:holgerbaumann_news_categories'],
        'status' => 'required|between:1,2|numeric',
        'hidden' => 'required|between:1,2|numeric'
    ];

    protected $slugs = [
        'slug' => 'name'
    ];

    public $belongsToMany = [
        'subscribers' => [
            'HolgerBaumann\News\Models\Subscribers',
            'table' => 'holgerbaumann_news_relations',
            'key'   => 'categories_id',
            'otherKey' => 'subscriber_id',
            'order' => 'name'
        ]
    ];

    public $translatable = [
        'name',
        ['slug', 'index' => true],
        'summary',
        'content'
    ];

    /**
     * Sets the "url" attribute with a URL to this object
     * @param string $pageName
     * @param \Cms\Classes\Controller $controller
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id'   => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }
}
