<?php namespace HolgerBaumann\News\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class UpdateSendFeature2 extends Migration
{
    public function up()
    {
        Schema::table('holgerbaumann_news_posts', function($table)
        {
            $table->dropColumn('send');
        });
    }

    public function down()
    {
        Schema::table('holgerbaumann_news_posts', function($table)
        {
            $table->boolean('send')->default(false);
        });
    }
}
