<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTournamentGamesTable
 */
class CreateTournamentGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tournament_games',
            function (Blueprint $table) {
                $table->string('id', '26');
                $table->string('tournament_id', '26');
                $table->string('home_team_id', '26');
                $table->string('away_team_id', '26');
                $table->tinyInteger('week', );
                $table->tinyInteger('home_goals');
                $table->tinyInteger('away_goals');

                $table->boolean('played');

                $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
                $table->foreign('home_team_id')->references('id')->on('tournament_teams')->onDelete('cascade');
                $table->foreign('away_team_id')->references('id')->on('tournament_teams')->onDelete('cascade');

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
        Schema::dropIfExists('tournament_games');
    }
}
