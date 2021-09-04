<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->bigInteger('views')->default(0);
            $table->string('thumbnail')->nullable();
            $table->integer('percentage')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('path');
            $table->boolean('is_processed')->default(false);
            $table->boolean('encoded')->default(false);
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
        Schema::dropIfExists('videos');
    }
}
