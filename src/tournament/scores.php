
<?php
if( !isset($_GET["id"]) || empty($_GET["id"]) ){
  header("Location: /.");
}
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>FIFA Tournament generator</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/scores.css" media="screen" charset="utf-8">
</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" class="navbar-toggle collapsed"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="/." class="navbar-brand">FIFA tournament Generator</a>
      </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">Create new tournament</a></li>
            <li class="active dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle">My tournaments <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li class="active"><a href="scores<?= isset( $_GET["admin"] ) ? "?admin=".$_GET["admin"] : "" ?>">Fixtures</a></li>
                  <li><a href="table<?= isset( $_GET["admin"] ) ? "?admin=".$_GET["admin"] : "" ?>">Table</a></li>
                </ul>
            </li>
            <li><a href="https://legiec.io/">Contact</a></li>
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

      $row = mysqli_fetch_array($data);
      $title = ($row["title"]!=null) ? $row["title"] : "Tournament #".$id;
      $type = $row["type"];
      $players = json_decode($row["players"], true);
      $rounds = json_decode($row["rounds"], true);
      $results = json_decode($row["fixtures"], true);
      $adminToken = $row["admin_token"];
      $admin = (isset($_GET["admin"]) && $_GET["admin"] == $adminToken ) ? true: false;
      $count = count($rounds);
      $offset = isset($_GET["page"]) ? ($_GET["page"]-1 >= 0 ? $_GET["page"]-1 : 0) : 0;

      $date = strtotime($row["created_at"]);

      echo "<div class='row'>";
        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
          echo "<h1 class='tournament-name'>$title</h1>";
          echo "<p>Created on <span class='created-at'>".strftime("%d.%m.%Y</span> at <span class='created-at'>%H:%M:%S", $date)."</span></p>";
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
    </div>
    <div>
  <?php
      if( $type == "League" ){
        for($x=$offset*2; $x<$offset*2+2; $x++){
          if( array_key_exists($x, $rounds) ){
            echo '<div class="round">';
            echo '<div class="row head"><h2>Round '.($x+1).'</h2></div>';
            for($y=0; $y<count($rounds[$x]); $y++){
              $teams = $rounds[$x][$y];
              $result = $results[$x][$y];

              $club1 = $players[ $teams[0] ]["club"];
              $club2 = $players[ $teams[1] ]["club"];

              $disabled = ($admin) ? "" : "disabled";

              $crestPath1 = '/logos/16/'.strtolower(preg_replace("/[^a-zA-Z0-9\-]+/", "", html_entity_decode(str_replace(" ", "-", $club1), ENT_QUOTES))).'.png';
              $crest1 = ' style="background-image: url('.$crestPath1.')"';

              $crestPath2 = '/logos/16/'.strtolower(preg_replace("/[^a-zA-Z0-9\-]+/", "", html_entity_decode(str_replace(" ", "-", $club2), ENT_QUOTES))).'.png';
              $crest2 = ' style="background-image: url('.$crestPath2.')"';

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
                if( !empty($club1) ){
                  if( file_exists($_SERVER['DOCUMENT_ROOT'].$crestPath1) ){
                    echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-right"><div><p>'.html_entity_decode($club1, ENT_QUOTES).'</p><p class="name">'.html_entity_decode($player1, ENT_QUOTES).'</p></div><div class="crest hidden-xs"'.$crest1.'></div></div>';
                  } else {
                    echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-right"><div><p>'.html_entity_decode($club1, ENT_QUOTES).'</p><p class="name">'.html_entity_decode($player1, ENT_QUOTES).'</p></div></div>';
                  }
                } else {
                  echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-right"><div><p>'.html_entity_decode($player1, ENT_QUOTES).'</p></div></div>';
                }
                if( array_key_exists(0, $result) ){
                  echo '<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2 text-center"><input type="number" '.$disabled.' class="home correct" value="'.$result[0].'">:<input type="number" '.$disabled.' class="away correct" value="'.$result[1].'"></div>';
                } else {
                  echo '<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2 text-center"><input type="number" '.$disabled.' class="home" value="">:<input type="number" '.$disabled.' class="away" value=""></div>';
                }

                if( !empty($club2) ){
                  if( file_exists($_SERVER['DOCUMENT_ROOT'].$crestPath2) ){
                    echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-left"><div class="crest hidden-xs"'.$crest2.'></div><div>'.html_entity_decode($club2, ENT_QUOTES).'</p><p class="name">'.html_entity_decode($player2, ENT_QUOTES).'</p></div></div>';
                  } else {
                    echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-left"><div>'.html_entity_decode($club2, ENT_QUOTES).'</p><p class="name">'.html_entity_decode($player2, ENT_QUOTES).'</p></div></div>';
                  }
                } else {
                  echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-left"><div><p>'.html_entity_decode($player2, ENT_QUOTES).'</p></div></div>';
                }
              echo '</div>';
            }
            echo "</div>";
          }
        }
        ?>
        <nav aria-label="...">
          <ul class="pagination">

            <?php
              $count = ceil(count($rounds)/2);

              $curPage = (isset($_GET["page"])) ? $_GET["page"] : 1;

              if( $curPage == "1" ){
                echo '<li class="disabled"><a aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
              } else {
                echo '<li><a href="?page='.($curPage-1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
              }

              for( $x=1; $x<$count+1; $x++ ){
                $class = "";
                if( $x == $curPage ){
                  echo '<li class="active"><a>'.$x.' <span class="sr-only">(current)</span></a></li>';
                } else {
                  echo '<li><a href="?page='.$x.'">'.$x.'</a></li>';
                }
              }

              if( $curPage == $count ){
                echo '<li class="disabled"><a aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
              } else {
                echo '<li><a href="?page='.($curPage+1).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
              }

            ?>
          </ul>
        </nav>
        <?php
      } if( $type == "Knockout" ) {

    ?>
    <div class="bracket">
    <?php
        $disabled = ($admin) ? "" : "disabled";

        include "class.game_bracket.php";

        if( count($players) >= 16 ){
          $stage = 16;
        } else if ( count($players) >= 8 ){
          $stage = 8;
        } else if ( count($players) >= 4 ){
          $stage = 4;
        } else if ( count($players) >= 2 ){
          $stage = 2;
        }

        $html = "";

        Game::$are_two_legs = ( count($results)>1 );
        Game::$are_two_legs_final = false;
        Game::$admin = $admin;

        $n_rounds = count($rounds);
        for($x=0; $x<$n_rounds; $x++){
          $column_a = "";
          $column_b = "";
          $column_a .= "<div class='col'>";
          $column_a .= "<div class='spacer'></div>";

          if($x==$n_rounds-1) {
            $player0 = (array_key_exists(0, $rounds[$x]) && $rounds[$x][0] >= 0) ? $players[ $rounds[$x][0] ]["players"]: "";
            $club0 = (array_key_exists(0, $rounds[$x]) && $rounds[$x][0] >= 0) ? $players[ $rounds[$x][0] ]["club"]: "";
            $game = new Game($player0, "", $club0);
            $column_a .= $game->outputFinalHTML();
          } else if($x>0){
            for($y=0; $y<count($rounds[$x]); $y++){
              if( array_key_exists(0, $rounds[$x][$y]) && array_key_exists(1, $rounds[$x][$y]) ){
                $player0 = ($rounds[$x][$y][0] >= 0) ? $players[ $rounds[$x][$y][0] ]["players"] : "";
                $player1 = ($rounds[$x][$y][1] >= 0) ? $players[ $rounds[$x][$y][1] ]["players"] : "";

                $img0 = ($rounds[$x][$y][0] >= 0) ? $players[ $rounds[$x][$y][0] ]["club"] : "";
                $img1 = ($rounds[$x][$y][1] >= 0) ? $players[ $rounds[$x][$y][1] ]["club"] : "";

                $result0 = (array_key_exists(0, $results[0][$x][$y])) ? $results[0][$x][$y][0]: "";
                $result1 = (array_key_exists(1, $results[0][$x][$y])) ? $results[0][$x][$y][1]: "";

                $disabled = (!($rounds[$x][$y][0] >= 0) || !($rounds[$x][$y][1] >= 0));

                if( count($rounds[$x]) == 1 ){
                  if( Game::$are_two_legs_final ){
                    $result01 = (array_key_exists(0, $results[1][$x][$y])) ? $results[1][$x][$y][0]: "";
                    $result11 = (array_key_exists(1, $results[1][$x][$y])) ? $results[1][$x][$y][1]: "";

                    $results0 = array($result0, $result01);
                    $results1 = array($result1, $result11);
                  } else {
                    $results0 = array($result0);
                    $results1 = array($result1);
                  }
                } else {
                  if( Game::$are_two_legs ){
                    $result01 = (array_key_exists(0, $results[1][$x][$y])) ? $results[1][$x][$y][0]: "";
                    $result11 = (array_key_exists(1, $results[1][$x][$y])) ? $results[1][$x][$y][1]: "";

                    $results0 = array($result0, $result01);
                    $results1 = array($result1, $result11);
                  } else {
                    $results0 = array($result0);
                    $results1 = array($result1);
                  }
                }

                $game = new Game($player0, $player1, $img0, $img1, $results0, $results1, $disabled, $disabled);
              } else {
                if( count($rounds[$x]) == 1 ){
                  if( Game::$are_two_legs_final ){
                    $game = new Game("", "", "", "", array("", ""), array("", ""), true, true);
                  } else {
                    $game = new Game("", "", "", "", array(""), array(""), true, true);
                  }
                } else {
                  if( Game::$are_two_legs ){
                    $game = new Game("", "", "", "", array("", ""), array("", ""), true, true);
                  } else {
                    $game = new Game("", "", "", "", array(""), array(""), true, true);
                  }
                }
              }

              $column_a .= $game->outputHTML();
            }
          } else {
            if( count($rounds[$x]) > 0 ){
              for($y=0; $y<$stage; $y++){
                $player0 = $player1 = $result0 = $result01 = $result1 = $result11 = "";

                if( array_key_exists($y, $rounds[$x] ) ){
                  if( array_key_exists(0, $rounds[$x][$y]) && array_key_exists(1, $rounds[$x][$y]) ){
                    $player0 = ($rounds[$x][$y][0] >= 0) ? $players[ $rounds[$x][$y][0] ]["players"] : "";
                    $player1 = ($rounds[$x][$y][1] >= 0) ? $players[ $rounds[$x][$y][1] ]["players"] : "";

                    $img0 = ($rounds[$x][$y][0] >= 0) ? $players[ $rounds[$x][$y][0] ]["club"] : "";
                    $img1 = ($rounds[$x][$y][1] >= 0) ? $players[ $rounds[$x][$y][1] ]["club"] : "";

                    $result0 = (array_key_exists(0, $results[0][$x][$y])) ? $results[0][$x][$y][0]: "";
                    $result1 = (array_key_exists(1, $results[0][$x][$y])) ? $results[0][$x][$y][1]: "";
                    $disabled0 = !($rounds[$x][$y][0] >= 0);
                    $disabled1 = !($rounds[$x][$y][1] >= 0);
                  }

                  if( Game::$are_two_legs ){
                    $result01 = (array_key_exists(0, $results[1][$x][$y])) ? $results[1][$x][$y][0]: "";
                    $result11 = (array_key_exists(1, $results[1][$x][$y])) ? $results[1][$x][$y][1]: "";

                    $results0 = array($result0, $result01);
                    $results1 = array($result1, $result11);
                  } else {
                    $results0 = array($result0);
                    $results1 = array($result1);
                  }

                  $game = new Game($player0, $player1, $img0, $img1, $results0, $results1, false, false);

                  $column_b .= $game->outputHTML();
                } else {
                  $game = new Game();
                  $column_b .= $game->outputHiddenHTML();
                }
              }
            }
          }
          $column_a .= "</div>";

          if($x>0){
            $html .= $column_a;
          }

          if( $column_b != "" ){
            $column_b = "<div class='col'><div class='spacer'></div>". $column_b;
            $column_b .= "</div>";
            $html = $column_b.$html;
          }

        }

        echo $html;

      }
    ?>
    </div>

  </div>

  <footer class="footer">
    <div class="container">
      <p class="text-muted">Made with <span class="glyphicon glyphicon-heart"></span> by <a href="https://github.com/bibixx" target="_blank">bibixx</a></p>
    </div>
  </footer>

  <script src="/js/jquery-1.12.3.min.js" charset="utf-8"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <?php
    if( $admin ){
  ?>
    <link rel="stylesheet" href="/css/scores_admin.css" media="screen" charset="utf-8">
  <?php
    }
    $params = "";

    if($admin){
      $params = "?id=".$_GET["id"]."&admin=".$_GET["admin"];
    }

    switch ($type) {
      case 'League':
        ?>
    <script src="/js/league.js<?= $params ?>" charset="utf-8"></script>
        <?php
        break;

      case 'Knockout':
      ?>
    <script>
      var playersList = <?= json_encode( $players ) ?>;
    </script>
    <script src="/js/cup.js<?= $params ?>" charset="utf-8"></script>
      <?php
        break;

      default:
        break;
    }
  ?>
</body>
