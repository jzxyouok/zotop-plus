<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Core extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // session
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->integer('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');
        });

        // users基础表
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->string('username', 64)->unique()->comment('用户名');
            $table->string('password', 64)->comment('密码');
            $table->string('email', 200)->nullable()->unique()->comment('邮箱');
            $table->string('mobile', 50)->nullable()->unique()->comment('手机号');
            $table->string('modelid', 64)->comment('模型编号：如admin或者member');
            $table->string('nickname',100)->nullable()->comment('昵称');            
            $table->boolean('gender')->default(0)->unsigned()->comment('性别 0=保密 1=男 2=女');
            $table->string('avatar',255)->nullable()->comment('头像');
            $table->string('sign',255)->nullable()->comment('签名');            
            $table->integer('login_times')->default(0)->comment('登录次数');
            $table->timestamp('login_at')->nullable()->comment('最后登录时间');
            $table->string('login_ip', 45)->nullable()->comment('最后登录IP');
            $table->boolean('disabled')->default(0)->unsigned()->comment('禁用 0=否 1=禁用');
            $table->string('token')->nullable()->comment('token');

            $table->rememberToken();
            $table->timestamps();

            $table->comment = '用户';
        });

        // 重设密码
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable();

            $table->comment = '重设密码';
        });

        // 角色
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('permissions')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->comment = '角色';
        });

        // 用户角色
        Schema::create('role_users', function (Blueprint $table) {
            
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->nullableTimestamps();

            $table->engine = 'InnoDB';
            $table->comment = '用户角色';

            $table->primary(['user_id', 'role_id']);
        });                
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sessions');
        Schema::drop('users');
        Schema::drop('password_resets');
        Schema::drop('roles');
        Schema::drop('role_users');
    }
}