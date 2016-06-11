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
            <li><a href="/FIFA-Generator/">Create new tournament</a></li>
            <li class="active dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle">My tournaments <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li class="active"><a href="scores">Fixtures</a></li>
                  <li><a href="table">Table</a></li>
                </ul>
            </li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <div class="container">
    <?php
      $dbc = new mysqli('127.0.0.1', 'root', 'admin', 'tournaments');
      $dbc->query('SET NAMES utf8');
      $id = $dbc->real_escape_string($_GET["id"]);
      $query = "SELECT * FROM `tournaments` WHERE (`id`=$id)";
      $data = $dbc->query($query);
      mysqli_close($dbc);

      $row = mysqli_fetch_array($data);
      $title = $row["title"]."\n";
      $players = json_decode($row["players"], true);
      $rounds = json_decode($row["rounds"], true);
      $results = json_decode($row["fixtures"], true);

      for($x=0; $x<count( $rounds ); $x++){
        echo '<div class="round">';
        echo '<div class="row head"><h2>Round '.($x+1).'</h2></div>';
        for($y=0; $y<count($rounds[$x]); $y++){
          $teams = $rounds[$x][$y];
          $result = $results[$x][$y];

          if( count($players[ $teams[0] ]["players"]) > 1 ){
            $player1 = $players[ $teams[0] ]["players"][0]." & ".$players[ $teams[0] ]["players"][1];
          } else {
            $player1 = $players[ $teams[0] ]["players"][0];
          }

          if( count($players[ $teams[1] ]["players"]) > 1 ){
            $player2 = $players[ $teams[1] ]["players"][0]." & ".$players[ $teams[1] ]["players"][1];
          } else {
            $player2 = $players[ $teams[1] ]["players"][0];
          }

          echo '<div class="row">';
            echo '<div class="col-md-5 text-right">'.html_entity_decode($player1, ENT_QUOTES).'</div>';
            if( array_key_exists(0, $result) ){
              echo '<div class="col-md-2 text-center"><span contenteditable class="home correct">'.$result[0].'</span>:<span contenteditable class="away correct">'.$result[1].'</span></div>';
            } else {
              echo '<div class="col-md-2 text-center"><span contenteditable class="home"></span>:<span contenteditable class="away"></span></div>';
            }
            echo '<div class="col-md-5 text-left">'.html_entity_decode($player2, ENT_QUOTES).'</div>';
          echo '</div>';
        }
        echo "</div>";
      }

    ?>
  </div>

  <footer class="footer">
    <div class="container">
      <p class="text-muted">Made with <span class="glyphicon glyphicon-heart"></span> by <a href="https://github.com/bibixx" target="_blank">bibixx</a></p>
    </div>
  </footer>
  <script src="/FIFA-Generator/js/jquery-1.12.3.min.js" charset="utf-8">   </script>
  <script src="/FIFA-Generator/js/scores.js" charset="utf-8"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <link rel="stylesheet" href="/FIFA-Generator/css/scores.css" media="screen" charset="utf-8">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
