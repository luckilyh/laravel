<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFragmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fragments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('标题');
            $table->longText('content')->comment('内容');
            $table->string('type')->nullable()->comment('类型: rich_text 富文本; text 文本; json 数组字符串; image 图片;');
        });
        DB::statement("ALTER TABLE `fragments` comment '碎片表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fragments');
    }
}
