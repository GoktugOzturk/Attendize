<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->increments('id');
	    $table->enum('type', ['flat', 'percent']);
	    $table->decimal('amount', 8, 2)->default(0.00);
	    $table->string('code', 32);
	    $table->datetime('exp_at')->nullable();
	    $table->integer('times_used')->unsigned()->default(0);
	    $table->integer('max_times_used')->unsigned()->default(PHP_INT_MAX);	    
	    $table->integer('event_id');

	    $table->unique(['event_id', 'code']);
	    $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

	    $table->timestamps();
	    $table->softDeletes();
        });

	Schema::table('orders', function($table) {
	    $table->integer('discount_code_id')->nullable();
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	Schema::table('orders', function($table) {
	    $table->dropColumn('discount_code_id');
	});
        Schema::drop('discount_codes');
    }
}
