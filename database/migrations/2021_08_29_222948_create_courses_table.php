<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->string('subTitle');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('language')->nullable();
            $table->string('image')->nullable();
            $table->enum('level', ['all', 'beginner', 'intermediate', 'advanced']);
            $table->boolean('featured')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('published')->default(false);
            $table->boolean('approved')->default(false);
            $table->text('settings')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
