<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('full_name')->nullable();
            $table->string('english_name')->nullable();
            $table->string('alpha2')->nullable();
            $table->string('alpha3')->nullable();
            $table->string('iso')->nullable();
            $table->string('part_world')->nullable();
            $table->string('location')->nullable();
            $table->decimal('lat', 20,11)->nullable();
            $table->decimal('lon', 20,11)->nullable();
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
        Schema::dropIfExists('countries');
    }
}
