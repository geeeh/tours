<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            "companies", function (Blueprint $table) {
                $table->engine = "InnoDb";

                $table->increments("id");
                $table->string("name");
                $table->json("location");
                $table->string("phone");
                $table->string("email");
                $table->string("description");
                $table->integer("user");
                $table->timestamps();

                $table->foreign("user")->references("id")->on("users");
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
        Schema::drop("companies");
    }
}
