<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');

            $table->string('designation');
            $table->string('nature');
            $table->char('finance', 5);

            $table->decimal('montantAlloue', 15, 2)->default(0);
            $table->decimal('montantEC', 15, 2)->default(0);
            $table->decimal('montantPC', 15, 2)->default(0);

            $table->date('date_creation')->nullable();

            $table->string('delaiE')->default('0 j');
            $table->date('odsEtude')->nullable();
            $table->date('date_lancement')->nullable();
            $table->date('date_ouverture_plis')->nullable();
            $table->date('date_attribution')->nullable();
            $table->date('date_validation_commission')->nullable();
            $table->longText('observation_etude')->nullable();

            $table->string('delaiR')->default('0 j');
            $table->date('odsRealisation')->nullable();
            $table->date('dateReception')->nullable();
            $table->date('dateMiseEnOeuvre')->nullable();

            $table->string('etatPhysique')->default('R');
            $table->integer('tauxA')->default(0);
            $table->longText('observation')->nullable();

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
        Schema::dropIfExists('projets');
    }
}