<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('person_id')->constrained('persons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('file_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('note');
            $table->string('type')->nullable();
            $table->boolean('protected')->default(0);
            $table->decimal('lat', 20,11)->nullable();
            $table->decimal('lon', 20,11)->nullable();
            $table->boolean('pin')->default(0);
            $table->string('address')->nullable();
            $table->text('text')->nullable();
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
        Schema::dropIfExists('person_notes');
    }
}
