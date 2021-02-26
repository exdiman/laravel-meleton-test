<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvertingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convertings', function (Blueprint $table) {
            $table->id();
            $table->string('currency_from', 6);
            $table->string('currency_to', 6);
            $table->decimal('value', 23, 10);
            $table->decimal('converted_value', 23, 10);
            $table->decimal('rate', 23, 10);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('convertings');
    }
}
