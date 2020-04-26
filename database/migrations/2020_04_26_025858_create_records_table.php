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
            $table->foreignId('user_id');
            $table->foreignId('package_id');
            $table->foreignId('old_rack_letter');
            $table->foreignId('old_rack_pos');
            $table->foreignId('new_rack_letter');
            $table->foreignId('new_rack_pos');
            $table->timestamp('date_modified');
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
