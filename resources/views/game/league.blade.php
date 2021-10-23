<div class="col-lg-4">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col" colspan="7">League Table</th>
        </tr>
        </thead>
        <tbody>
        <tr class="border-bottom">
            <th scope="row">Teams</th>
            <th scope="row">PTS</th>
            <th scope="row">P</th>
            <th scope="row">W</th>
            <th scope="row">D</th>
            <th scope="row">L</th>
            <th scope="row">GD</th>
        </tr>
        @foreach($teams as $team)
            <tr>
                <td>{{$team->name()->getValue()}}</td>
                <td>{{$team->points($week)->getValue()}}</td>
                <td>{{$week->getValue()}}</td>
                <td>{{$team->winnings($week)->count()}}</td>
                <td>{{$team->draft($week)->count()}}</td>
                <td>{{$team->lost($week)->count()}}</td>
                <td>{{abs($team->goalsFor($week)->getValue() - $team->goalsAgainst($week)->getValue())}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
