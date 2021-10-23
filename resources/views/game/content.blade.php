<div class="row">
    @include('game.league')
    @include('game.result')
    @include('game.prediction')
    <input type="hidden" value="{{$tournamentId->getValue()}}" name="tournament_id" id="tournament_id">

    @if ($renderButtons)
        <div class="row buttons">
            <div class="col-md-2">
                <a href="#" class="btn btn-lg btn-warning left" id="playAll">Play all</a>
            </div>
            <div class="col-md-8"></div>
            <div class="col-md-2">
                <a href="#" class="btn btn-lg btn-warning right " id="nextWeek">Next Week</a>
            </div>
        </div>
    @endif
</div>
