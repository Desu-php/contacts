<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_connections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('with_whom')->constrained('persons')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('who')->constrained('persons')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_connections');
    }
}
