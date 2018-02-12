<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Create events table
 */
class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            "events", function (Blueprint $table) {
                $table->engine = "InnoDb";
                $table->increments('id');
                $table->string('name');
                $table->json('location');
                $table->json('activities');
                $table->string('cost');
                $table->string('image');
                $table->dateTime('date');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("events");
    }
}
