<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {

	public function up()
	{
		Schema::create('posts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title');
			$table->string('imei')->unique();
			$table->text('problem');
			$table->boolean('status')->default(0);
			$table->integer('user_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('posts');
	}
}