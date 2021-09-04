<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('payer_id');
            $table->string('payment_method');
            $table->decimal('amount', 8,2);
            $table->string('description');
            $table->decimal('author_earning', 10,2)->nullable();
            $table->decimal('affiliate_earning', 10,2)->nullable();
            $table->string('gateway_payment_id')->nullable();
            $table->integer('referred_by')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->enum('status', ['created', 'refunded', 'finalized'])->default('created');
            $table->datetime('refund_deadline');
            $table->datetime('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('payer_id')->references('id')->on('users');
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
