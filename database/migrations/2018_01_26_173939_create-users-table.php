<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("users", function(Blueprint $table) {
            $table->engine = "InnoDb";
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 255);
<<<<<<< HEAD
            $table->string('image');
=======
>>>>>>> feat(project-structure):Initial project structure
            $table->string('api_key')->nullable()->unique();
            $table->rememberToken();
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
        Schema::drop("users");
    }
}
