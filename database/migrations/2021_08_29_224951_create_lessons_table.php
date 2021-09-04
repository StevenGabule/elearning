<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('course_id');
            $table->string('lesson_type')->default('lecture');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('content_type', ['video', 'youtube', 'article', 'quiz'])->nullable();
            $table->decimal('duration')->default(0);
            $table->text('article_body')->nullable();
            $table->boolean('preview')->default(false);
            $table->integer('sortOrder');
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
