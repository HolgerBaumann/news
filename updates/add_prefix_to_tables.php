<?php namespace HolgerBaumann\News\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddPrefixToTables extends Migration
{
    public function up()
    {
        Schema::rename('news_posts', 'holgerbaumann_news_posts');
        Schema::rename('news_subscribers', 'holgerbaumann_news_subscribers');
    }

    public function down()
    {
        Schema::rename('holgerbaumann_news_posts', 'news_posts');
        Schema::rename('holgerbaumann_news_subscribers', 'news_subscribers');
    }
}
