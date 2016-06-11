<?php
session_start();

function print_e($key) {
  if(isset($_SESSION[$key])){
?>
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
<?php
    echo $_SESSION[$key];
?>
</div>
<?php
    unset($_SESSION[$key]);
  }
}
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>FIFA Tournament generator</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" class="navbar-toggle collapsed"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="." class="navbar-brand">FIFA tournament Generator</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href=".">Create new tournament</a></li>
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">My tournaments <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Fixtures</a></li>
              <li><a href="#">Table</a></li>
            </ul>
          </li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <form method="get" action="generate.php" class="container">
    <div id="settings">
      <div class="row">
        <div class="form-horizontal">
          <div class="form-group">
            <label for="players" class="col-md-12 control-label">Tournament name:</label>
            <div class="col-md-5">
              <input id="title" type="text" name="title" placeholder="Tournament name" class="form-control">
            </div>
          </div>
        </div>
        <div class="form-horizontal">
          <div class="form-group">
            <label for="type" class="col-md-12 control-label">Tournament type:</label>
            <div class="col-md-5">
              <select name="type" id="type" class="form-control">
                <option>League</option>
                <option>Knockout</option>
                <option>League + Knockout</option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-horizontal">
          <div class="form-group">
            <label for="players" class="col-md-12 control-label">Number of players:</label>
            <div class="col-md-2">
              <div class="input-group spinner"><span class="input-group-btn">
                  <button type="button" class="btn minus">-</button></span>
                <input id="players" name="players" type="text" value="4" class="form-control text-center"><span class="input-group-btn">
                  <button type="button" class="btn plus">+</button></span>
              </div>
            </div>
          </div>
        </div>
        <div class="form-horizontal">
          <div class="form-group">
            <label for="teams" class="col-md-12 control-label">Play in teams?</label>
            <div class="col-md-12 checkbox">
              <label>
                <input type="checkbox" name="teams" id="teams" class="checkbox-inline">Do you want to play in teams that consist of 1 or 2 players?
              </label>
            </div>
          </div>
        </div>
        <div class="form-horizontal disabled">
          <div class="form-group">
            <label for="teamsNo" class="col-md-12 control-label">Number of teams:</label>
            <div class="col-md-2">
              <div class="input-group spinner"><span class="input-group-btn">
                  <button type="button" class="btn minus">-</button></span>
                <input id="teamsNo" name="teamsNo" type="text" value="1" class="form-control text-center"><span class="input-group-btn">
                  <button type="button" class="btn plus">+</button></span>
              </div>
            </div>
          </div>
        </div>
        <div class="form-horizontal">
          <div class="form-group">
            <label for="matchesvs" class="col-md-12 control-label">Number of matches against each team:</label>
            <div class="col-md-2">
              <div class="input-group spinner"><span class="input-group-btn">
                  <button type="button" class="btn minus">-</button></span>
                <input id="matchesvs" name="matchesvs" type="text" value="1" class="form-control text-center"><span class="input-group-btn">
                  <button type="button" class="btn plus">+</button></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="teams">
      <div class="row center-block">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="center-inline-block">
            <div id="1" class="team panel panel-info">
              <div class="panel-heading">
                <input type="text" name="name1" value="" placeholder="Player" class="form-control" required>
              </div>
              <div class="panel-body">
                <input type="text" name="club" value="" placeholder="Club name" class="form-control">
              </div>
            </div>
            <div id="2" class="team panel panel-info">
              <div class="panel-heading">
                <input type="text" name="name1" value="" placeholder="Player" class="form-control" required>
              </div>
              <div class="panel-body">
                <input type="text" name="club" value="" placeholder="Club name" class="form-control">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php print_e("team_e"); ?>

    <div class="row summary">
      <div class="col-md-12">
        <div class="panel panel-info">
          <div class="panel-heading">
            <p>Summary</p>
          </div>
          <div class="panel-body">
            <ul>
              <li>Your tournament will contain <span id="number"></span> players</li>
              <li>All teams will play against all other player's teams <span id="against"></span></li>
              <li>The tournament will consist of <span id="fix"></span> fixtures</li>
              <li>It will take about <span id="time"></span> to play all fixtures (
                <select id="length">
                  <option>20</option>
                  <option>15</option>
                  <option>10</option>
                  <option>9</option>
                  <option>8</option>
                  <option selected="selected">6</option>
                  <option>5</option>
                  <option>4</option>
                </select> minutes per half)
              </li>
            </ul>
          </div>
        </div>
      </div>
      <button id="generate" class="btn btn-lg btn-success btn-block">Generate</button>
    </div>
  </form>
  <footer class="footer">
    <div class="container">
      <p class="text-muted">Made with <span class="glyphicon glyphicon-heart"></span> by <a href="https://github.com/bibixx" target="_blank">bibixx</a></p>
    </div>
  </footer>
  <script src="js/jquery-1.12.3.min.js" charset="utf-8">   </script>
  <script src="js/script.js" charset="utf-8"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css" media="screen" charset="utf-8">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
