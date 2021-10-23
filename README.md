# Laravel Soccer Prediction APP

**Simple version of match prediction app**

This package based on DDD architecture, using commands from (CQRS) and running on docker.

## Installation

Clone this repo

This package runs on docker [Docker](https://docs.docker.com/engine/install/) and [docker-compose](https://docs.docker.com/compose/install/) please check that you have install it

Run the following commands:

- `docker-compose up`
- `docker-compose exec app composer install`
- `docker-compose exec app php artisan key:generate`
- `docker-compose exec app php artisan migrate`

Add the domain app to the hosts file
`127.0.0.1 prediction.loc`

Open the browser and go to http://prediction.loc/

Gif Example:
![alt-text](https://github.com/stgalkin/score-prediction/blob/main/public/screencast.gif)


Code Details:
- Teams for the tournament are chosen randomly from AVAILABLE_TEAMS(8teams) const at the [CreateTournamentService](https://github.com/stgalkin/score-prediction/blob/main/app/Components/Soccer/Domain/Tournament/Services/CreateTournamentService.php)
- The games between teams table is defined at the same service const WEEK_MAP
- Calculating the odds of winning [here](https://github.com/stgalkin/score-prediction/blob/main/app/Components/Soccer/Domain/Prediction/Services/PredictionService.php)

