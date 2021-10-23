<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Soccer Prediction</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Styles -->
</head>

<body class="text-center">

<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
        <div class="inner">
        </div>
    </header>

    <main role="main" class="inner cover">
        <h1 class="cover-heading mb-auto pb-5">Soccer Prediction.</h1>
        <p class="lead">Click button to start game</p>
        <p class="lead">
            <a href="#" class="btn btn-lg btn-success" id="play">New Tournament</a>
        </p>
    </main>

    <div class="mainContainer" id="content">
        <div class="leagueTable">
        </div>
    </div>

    <input type="hidden" id="tournament">
    <footer class="mastfoot mt-auto">
    </footer>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script>
    $(function() {
        $('#play').click(function() {
            $.ajax({
                type: 'POST',
                data: {},
                success: function(data) {
                    $('.mainContainer').html('<div class="leagueTable"> </div>');
                    $( ".leagueTable" ).append(data);
                    },
                error: function(){ },
                url: '/api/tournament',
                cache:false
            });
        });

        $(document).on("click", "#nextWeek" , function() {
            $.ajax({
                type: 'POST',
                data: {},
                success: function(data) {
                    $('.buttons').empty();
                    $( ".leagueTable" ).append(data);
                },
                error: function(){ },
                url: '/api/tournament/' + $("#tournament_id").val() + '/weeks/next',
                cache:false
            });
        });

        $(document).on("click", "#playAll" , function() {
            $.ajax({
                type: 'POST',
                data: {},
                success: function(data) {
                    $('.buttons').empty();
                    $( ".leagueTable" ).append(data);
                },
                error: function(){ },
                url: '/api/tournament/' + $("#tournament_id").val() + '/weeks/play_all',
                cache:false
            });
        });
    });
</script>
</body>
</html>
