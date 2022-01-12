<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('city')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('family_name')->nullable();
            $table->string('given_name')->nullable();
            $table->string('note')->nullable();
            $table->string('my_phone')->nullable();
            $table->string('position')->nullable();
            $table->string('site')->nullable();
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('city');
            $table->dropColumn('company');
            $table->dropColumn('email');
            $table->dropColumn('family_name');
            $table->dropColumn('given_name');
            $table->dropColumn('note');
            $table->dropColumn('my_phone');
            $table->dropColumn('position');
            $table->dropColumn('site');
            $table->dropColumn('image');
        });
    }
}
