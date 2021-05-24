<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('昵称');
            $table->string('account')->nullable()->unique()->comment('账号');
            $table->string('phone')->nullable()->unique()->comment('手机号');
            $table->string('email')->nullable()->unique()->comment('邮箱');
            $table->timestamp('email_verified_at')->nullable()->comment('邮箱认证时间');
            $table->string('weixin_openid')->nullable()->unique()->comment('微信openid');
            $table->string('password')->comment('密码');
            $table->text('token')->nullable();
            $table->rememberToken();
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('introduction')->nullable()->comment('介绍');
            $table->dateTime('last_login_at')->nullable()->comment('最后一次登录时间');
            $table->ipAddress('last_login_ip')->nullable()->comment('最后一次登陆ip');
            $table->ipAddress('register_ip')->nullable()->comment('注册ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
