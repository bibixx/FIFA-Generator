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
</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" class="navbar-toggle collapsed"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="/FIFA-Generator/." class="navbar-brand">FIFA tournament Generator</a>
      </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href=".">Create new tournament</a></li>
            <li class="active dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle">My tournaments <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="scores">Fixtures</a></li>
                  <li class="active"><a href="table">Table</a></li>
                </ul>
            </li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <div class="container">
    <table>
      <thead>
        <tr>
          <th title="Position">Pos</th>
            <th>Player(s)</th>
            <th>Club</th>
            <th title="Played">P</th>
            <th title="Won">W</th>
            <th title="Drawn">D</th>
            <th title="Lost">L</th>
            <th title="Golas For">GF</th>
            <th title="Goals Against">GA</th>
            <th title="Goals Difference">GD</th>
            <th title="Points">PTS</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $teams123 = array (
          0 => array(
             'player' => array('Bartek', 'Ignacy'),
             'club' => 'FC Bayern',
             'wins' => 3,
             'draws' => 1,
             'losses' => 2,
             'gf' => 4,
             'ga' => 2,
          ),
          1 => array(
             'player' => array('Kuba'),
             'club' => 'FC Barcelona',
             'wins' => 2,
             'draws' => 1,
             'losses' => 3,
             'gf' => 3,
             'ga' => 4,
          ),
          2 => array(
             'player' => array('Michał'),
             'club' => 'Leicester City',
             'wins' => 4,
             'draws' => 0,
             'losses' => 2,
             'gf' => 7,
             'ga' => 2,
          ),
        );

        $dbc = new mysqli("127.0.0.1", "root", "admin", "tournaments");
        $dbc->query('SET NAMES utf8');
        $id = $dbc->real_escape_string($_GET["id"]);
        $query = "SELECT * FROM `tournaments` WHERE (`id`=$id)";
        $data = $dbc->query($query);
        mysqli_close($dbc);
        $row = mysqli_fetch_array($data);

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
          echo "<tr><td>".($x+1)."</td><td>".urldecode($players)."</td><td>".urldecode($teams[$x]["club"])."</td><td>".$played."</td><td>".$teams[$x]["wins"]."</td><td>".$teams[$x]["draws"]."</td><td>".$teams[$x]["losses"]."</td><td>".$teams[$x]["gf"]."</td><td>".$teams[$x]["ga"]."</td><td>".$gd."</td><td>".$pts."</td></tr>";
        }
      ?>
      </tbody>
    </table>

  </div>
  <footer class="footer">
    <div class="container">
      <p class="text-muted">Made with <span class="glyphicon glyphicon-heart"></span> by <a href="https://github.com/bibixx" target="_blank">bibixx</a></p>
    </div>
  </footer>
  <script src="/FIFA-Generator/js/jquery-1.12.3.min.js" charset="utf-8">   </script>
  <script src="/FIFA-Generator/js/script.js" charset="utf-8"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <link rel="stylesheet" href="/FIFA-Generator/css/style.css" media="screen" charset="utf-8">
  <link rel="stylesheet" href="/FIFA-Generator/css/table.css" media="screen" charset="utf-8">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
