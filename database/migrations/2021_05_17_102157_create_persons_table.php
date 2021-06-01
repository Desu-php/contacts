<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('givenName', 50)->nullable();
            $table->string('familyName', 50)->nullable();
            $table->string('middleName', 50)->nullable();
            $table->boolean('moreNo')->default(0);
            $table->bigInteger('reminderCall')->nullable();
            $table->boolean('removed')->default(0);
            $table->string('thumbnailImage')->nullable();
            $table->boolean('me')->default(0);
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
        Schema::dropIfExists('persons');
    }
}
