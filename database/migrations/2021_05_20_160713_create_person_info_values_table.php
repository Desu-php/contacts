<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonInfoValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_info_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('person_info_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('person_id')->constrained('persons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('value');
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
        Schema::dropIfExists('person_info_values');
    }
}
