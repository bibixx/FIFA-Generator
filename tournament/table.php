<?php
if( !isset($_GET["id"]) || empty($_GET["id"]) ){
  header("Location: /FIFA-Generator/.");
}
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>FIFA Tournament generator</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="/FIFA-Generator/js/jquery-1.12.3.min.js" charset="utf-8"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <link rel="stylesheet" href="/FIFA-Generator/css/style.css" media="screen" charset="utf-8">
  <link rel="stylesheet" href="/FIFA-Generator/css/table.css" media="screen" charset="utf-8">
</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" class="navbar-toggle collapsed"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="/FIFA-Generator/." class="navbar-brand">FIFA tournament Generator</a>
      </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/FIFA-Generator/">Create new tournament</a></li>
            <li class="active dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle">My tournaments <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="scores<?= isset( $_GET["admin"] ) ? "?admin=".$_GET["admin"] : "" ?>">Fixtures</a></li>
                  <li class="active"><a href="table<?= isset( $_GET["admin"] ) ? "?admin=".$_GET["admin"] : "" ?>">Table</a></li>
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
      include "../passwords.php";
      $dbc = new mysqli(HOST, LOGIN, PASSWORD, DATABASE) or die( 'błąd' );
      $dbc->query('SET NAMES utf8');
      $id = $dbc->real_escape_string($_GET["id"]);
      $query = "SELECT * FROM `tournaments` WHERE (`id`=$id)";
      $data = $dbc->query($query);
      mysqli_close($dbc);
      $row = mysqli_fetch_array($data);

      $title = ($row["title"]!=null) ? $row["title"] : "Tournament #".$id;
      $date = strtotime($row["created_at"]);
      $adminToken = $row["admin_token"];
      $admin = (isset($_GET["admin"]) && $_GET["admin"] == $adminToken ) ? true: false;
      
      echo "<div class='row'>";
        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
          echo "<h1 class='tournament-name'>$title</h1>";
          echo "<p>Created on <span class='created_at'>".strftime("%d.%m.%Y</span> at <span class='created_at'>%R", $date)."</span></p>";
        echo "</div>";
        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right-sm'>";
          echo '<div class="btn-group">';
            echo '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Share tournament <span class="caret"></span></button>';
            echo '<ul class="dropdown-menu">';
              echo '<li><a href="'.strtok($_SERVER["REQUEST_URI"],'?').'">Read-only URL</a></li>';
              if( $admin ){
                echo '<li><a href="'.strtok($_SERVER["REQUEST_URI"],'?')."?admin=$_GET[admin]".'">Admin URL</a></li>';
              }
            echo '</ul>';
          echo '</div>';
        echo "</div>";
      echo "</div>";
    ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th title="Position">Pos</th>
              <th>Player(s)</th>
              <th title="Played">Played</th>
              <th title="Won">W</th>
              <th title="Drawn">D</th>
              <th title="Lost">L</th>
              <th title="Golas For:Goals Against">GF:GA</th>
              <th title="Goals Difference">GD</th>
              <th title="Points">Points</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $teams = json_decode($row["players"], true);

          for($x=0; $x<count($teams); $x++){
            $teams[$x]["wins"] = 0;
            $teams[$x]["draws"] = 0;
            $teams[$x]["losses"] = 0;
            $teams[$x]["gf"] = 0;
            $teams[$x]["ga"] = 0;
          }

          $rounds = json_decode($row["rounds"], true);
          $fixtures = json_decode($row["fixtures"], true);
          for($x=0; $x<count($rounds); $x++){
            for($y=0; $y<count($rounds[$x]); $y++){
              if( array_key_exists(0, $fixtures[$x][$y]) && array_key_exists(1, $fixtures[$x][$y]) ){
                if( $fixtures[$x][$y][0] > $fixtures[$x][$y][1] ){
                  $teams[$rounds[$x][$y][0]]["wins"] += 1;
                  $teams[$rounds[$x][$y][1]]["losses"] += 1;

                  $teams[$rounds[$x][$y][0]]["gf"] += $fixtures[$x][$y][0];
                  $teams[$rounds[$x][$y][0]]["ga"] += $fixtures[$x][$y][1];

                  $teams[$rounds[$x][$y][1]]["gf"] += $fixtures[$x][$y][1];
                  $teams[$rounds[$x][$y][1]]["ga"] += $fixtures[$x][$y][0];
                } elseif( $fixtures[$x][$y][0] < $fixtures[$x][$y][1] ){
                  $teams[$rounds[$x][$y][0]]["losses"] += 1;
                  $teams[$rounds[$x][$y][1]]["wins"] += 1;

                  $teams[$rounds[$x][$y][0]]["gf"] += $fixtures[$x][$y][0];
                  $teams[$rounds[$x][$y][0]]["ga"] += $fixtures[$x][$y][1];

                  $teams[$rounds[$x][$y][1]]["gf"] += $fixtures[$x][$y][1];
                  $teams[$rounds[$x][$y][1]]["ga"] += $fixtures[$x][$y][0];
                } else {
                  $teams[$rounds[$x][$y][0]]["draws"] += 1;
                  $teams[$rounds[$x][$y][1]]["draws"] += 1;

                  $teams[$rounds[$x][$y][0]]["gf"] += $fixtures[$x][$y][0];
                  $teams[$rounds[$x][$y][0]]["ga"] += $fixtures[$x][$y][1];

                  $teams[$rounds[$x][$y][1]]["gf"] += $fixtures[$x][$y][1];
                  $teams[$rounds[$x][$y][1]]["ga"] += $fixtures[$x][$y][0];
                }
              }
            }
          }

          uasort($teams, function($a,$b){
              $aPts = $a["wins"]*3+$a["draws"];
              $aGD = $a["gf"]-$a["ga"];
              $bPts = $b["wins"]*3+$b["draws"];
              $bGD = $b["gf"]-$b["ga"];
              if($aPts == $bPts){

                if($aGD == $bGD){

                  if($a["gf"] == $b["gf"]){
                    return 0;
                  }

                  if($a["gf"] > $b["gf"]){
                    return 1;
                  }

                  if($a["gf"] < $b["gf"]){
                    return -1;
                  }

                }

                if($aGD > $bGD){
                  return 1;
                }

                if($aGD < $bGD){
                  return 0;
                }

              }

              if($aPts < $bPts){
                  return -1;
              }

              if($aPts > $bPts){
                  return 1;
              }
          });

          $teams = array_reverse( $teams );

          for($x=0; $x<count($teams); $x++ ){
            $played = $teams[$x]["wins"]+$teams[$x]["draws"]+$teams[$x]["losses"];
            $gd = $teams[$x]["gf"]-$teams[$x]["ga"];
            $pts = $teams[$x]["wins"]*3+$teams[$x]["draws"];
            $players = "";
            for($i=0; $i<count($teams[$x]["players"]); $i++ ){
              if( $i == 0 ){
                $players .= $teams[$x]["players"][$i];
              } elseif ( $i == 1 ) {
                $players .= " & ".$teams[$x]["players"][$i];
              }
            }
            $club = (isset($teams[$x]["club"]) && $teams[$x]["club"] != "")? " (".$teams[$x]["club"].")" : "";
            echo "<tr><td><span>".($x+1)."</span></td><td>".$players.$club."</td><td>".$played."</td><td>".$teams[$x]["wins"]."</td><td>".$teams[$x]["draws"]."</td><td>".$teams[$x]["losses"]."</td><td>".$teams[$x]["gf"].":".$teams[$x]["ga"]."</td><td>".$gd."</td><td>".$pts."</td></tr>";
          }
        ?>
        </tbody>
      </table>
    </div>

  </div>
  <footer class="footer">
    <div class="container">
      <p class="text-muted">Made with <span class="glyphicon glyphicon-heart"></span> by <a href="https://github.com/bibixx" target="_blank">bibixx</a></p>
    </div>
  </footer>
  <script src="/FIFA-Generator/js/script.js" charset="utf-8"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
