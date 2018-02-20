<?php

use Konsulting\Laravel\EditorStamps\Schema;
use Illuminate\Database\Migrations\Migration;
use Konsulting\Laravel\EditorStamps\Blueprint;

class CreateSiteConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('value');
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();
            $table->editorStamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_config');
    }
}
