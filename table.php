<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <style>
      @import url(https://fonts.googleapis.com/css?family=Roboto:400,500,700&subset=latin,latin-ext);
      body {
        font-family: Roboto;
      }

      table {
        border-spacing: 0px;
        border-collapse: separate;
      }
      table tr td, table tr th {
        border-top: 1px solid #aaa;
        border-left: 1px solid #aaa;
        padding: 5px;
        text-align: center;
        min-width: 42px;
      }
      table tr td:last-child, table tr th:last-child {
        border-right: 1px solid #aaa;
      }
      table tr:last-child tr, table tr:last-child td {
        border-bottom: 1px solid #aaa;
      }
      table thead tr {
        background: #777;
        color: #FFF;
      }
      table thead tr th {
        border-color: #aaa;
        font-weight: 500;
      }
      table tbody tr:hover {
        background: rgba(173, 216, 230, 0.47);
      }
    </style>
  </head>
  <body>
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
      <?
        $teams = array (
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
          for($i=0; $i<count($teams[$x]["player"]); $i++ ){
            if( $i == 0 ){
              $players .= $teams[$x]["player"][$i];
            } elseif ( $i == 1 ) {
              $players .= " & ".$teams[$x]["player"][$i];
            }
          }
          echo "<tr><td>".($x+1)."</td><td>".$players."</td><td>".$teams[$x]["club"]."</td><td>".$played."</td><td>".$teams[$x]["wins"]."</td><td>".$teams[$x]["draws"]."</td><td>".$teams[$x]["losses"]."</td><td>".$teams[$x]["gf"]."</td><td>".$teams[$x]["ga"]."</td><td>".$gd."</td><td>".$pts."</td></tr>";
        }
      ?>
      </tbody>
    </table>
  </body>
</html>
