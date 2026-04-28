<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepToProjetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->unsignedTinyInteger('step')->default(1)->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->dropColumn('step');
        });
    }
}
