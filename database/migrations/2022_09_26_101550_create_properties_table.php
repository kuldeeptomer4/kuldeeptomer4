<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->string('type');
            $table->integer('property_value');
            $table->integer('agency_id');
            $table->text('description');
            $table->string('estimated_completion_date');
            $table->integer('currency_id');
            $table->integer('completed_by_id');
            $table->string('completed_date');
            $table->integer('address_id');
            $table->string('cancelled_date');
            $table->integer('created_by_id');
            $table->integer('updated_by_id');
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
        Schema::dropIfExists('properties');
    }
}
