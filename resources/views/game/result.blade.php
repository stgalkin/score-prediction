<div class="col-lg-4">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col" colspan="5">Match Results</th>
        </tr>
        </thead>
        <tbody>
            <tr class="border-bottom">
                <th scope="row" colspan="5">{{$week}} Week Match Result</th>
            </tr>
            @foreach($games as $game)
                <tr class="border-bottom">
                    <td scope="row">{{$game->homeTeam()->name()->getValue()}}</td>
                    <th scope="row">
                        <input type="text" name="home_team_goals" value="{{$game->homeGoals()->getValue()}}" class="input-group" style="max-width: 50%; padding: 0; margin: 0;">
                    </th>
                    <th scope="row">:</th>
                    <th scope="row">
                        <input type="text" value="{{$game->awayGoals()->getValue()}}"  class="input-group" style="max-width: 50%; padding: 0; margin: 0;">
                    </th>
                    <td scope="row">{{$game->awayTeam()->name()->getValue()}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
