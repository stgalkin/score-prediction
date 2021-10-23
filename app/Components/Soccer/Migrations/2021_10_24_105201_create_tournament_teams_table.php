<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTournamentTeamsTable
 */
class CreateTournamentTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tournament_teams',
            function (Blueprint $table) {
                $table->string('id', '26');
                $table->string('tournament_id', '26');
                $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');

                $table->string('name');

                $table->primary('id');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournament_teams');
    }
}
