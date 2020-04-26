<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained();
            $table->unsignedBigInteger('old_locker');
            $table->unsignedBigInteger('new_locker');
            $table->foreign('old_locker')->references('id')->on('lockers');
            $table->foreign('new_locker')->references('id')->on('lockers');
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
        Schema::dropIfExists('records');
    }
}
