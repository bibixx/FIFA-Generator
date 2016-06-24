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
  <link rel="stylesheet" href="/FIFA-Generator/css/scores.css" media="screen" charset="utf-8">
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
                  <li class="active"><a href="scores<?= isset( $_GET["admin"] ) ? "?admin=".$_GET["admin"] : "" ?>">Fixtures</a></li>
                  <li><a href="table<?= isset( $_GET["admin"] ) ? "?admin=".$_GET["admin"] : "" ?>">Table</a></li>
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
      $players = json_decode($row["players"], true);
      $rounds = json_decode($row["rounds"], true);
      $results = json_decode($row["fixtures"], true);
      $adminToken = $row["admin_token"];
      $admin = (isset($_GET["admin"]) && $_GET["admin"] == $adminToken ) ? true: false;
      $count = count($rounds);
      $offset = isset($_GET["page"]) ? ($_GET["page"]-1 >= 0 ? $_GET["page"]-1 : 0) : 0;

      $date = strtotime($row["created_at"]);

      if( empty($date) ){
        header("Location: /FIFA-Generator/.");
      }

      echo "<div class='row'>";
        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
          echo "<h1 class='tournament-name'>$title</h1>";
          echo "<p>Created on <span class='created_at'>".strftime("%d.%m.%Y</span> at <span class='created_at'>%H:%m:%S", $date)."</span></p>";
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

      for($x=$offset*2; $x<$offset*2+2; $x++){
        if( array_key_exists($x, $rounds) ){
          echo '<div class="round">';
          echo '<div class="row head"><h2>Round '.($x+1).'</h2></div>';
          for($y=0; $y<count($rounds[$x]); $y++){
            $teams = $rounds[$x][$y];
            $result = $results[$x][$y];

            $club1 = $players[ $teams[0] ]["club"];
            $club2 = $players[ $teams[1] ]["club"];

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

            $disabled = ($admin) ? "" : "disabled";

            echo '<div class="row">';
              if( !empty($club1) ){
                echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-right"><p>'.html_entity_decode($club1, ENT_QUOTES).'</p><p class="name">'.html_entity_decode($player1, ENT_QUOTES).'</p></div>';
              } else {
                echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-right"><p>'.html_entity_decode($player1, ENT_QUOTES).'</p></div>';
              }
              if( array_key_exists(0, $result) ){
                echo '<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2 text-center"><input type="number" '.$disabled.' class="home correct" value="'.$result[0].'">:<input type="number" '.$disabled.' class="away correct" value="'.$result[1].'"></div>';
              } else {
                echo '<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2 text-center"><input type="number" '.$disabled.' class="home" value="">:<input type="number" '.$disabled.' class="away" value=""></div>';
              }
              if( !empty($club1) ){
                echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-left">'.html_entity_decode($club2, ENT_QUOTES).'</p><p class="name">'.html_entity_decode($player2, ENT_QUOTES).'</p></div>';
              } else {
                echo '<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5 text-left"><p>'.html_entity_decode($player2, ENT_QUOTES).'</p></div>';
              }
            echo '</div>';
          }
          echo "</div>";
        }
      }
    ?>

    <ul class="pagination">
      <?php
        $url = "?";
        foreach ($_GET as $key => $value) {
          if( $key != 'page' && $key != 'id' ){
            $url .= $key."=".$value."&";
          }
        }

        $url1 = $url.'page='.$offset;
        $url2 = $url.'page='.($offset+2);

        if( ceil($count/2)-1 > 0 ){

          if( $offset > 0 ){
            echo '<li><a href="'.$url1.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
          }

          for($x=1; $x<=ceil($count/2); $x++){
            $urlX = $url."page=".$x;
            if( $x == ($offset+1) ){
              echo "<li class=\"active\"><a href=\"$urlX\">$x</a></li>";
            } else {
              echo "<li><a href=\"$urlX\">$x</a></li>";
            }
          }

          if( $offset < ceil($count/2)-1 ){
            echo '<li><a href="'.$url2.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
          }

        }
      ?>
    </ul>

  </div>

  <footer class="footer">
    <div class="container">
      <p class="text-muted">Made with <span class="glyphicon glyphicon-heart"></span> by <a href="https://github.com/bibixx" target="_blank">bibixx</a></p>
    </div>
  </footer>
  <?php
    if($admin){
  ?>
  <script src="/FIFA-Generator/js/scores.js<?= "?id=".$_GET["id"]."&admin=".$_GET["admin"]?>" charset="utf-8"></script>
  <link rel="stylesheet" href="/FIFA-Generator/css/scores_admin.css" media="screen" charset="utf-8">
  <?php
    } else {
  ?>
  <script src="/FIFA-Generator/js/scores.js" charset="utf-8"></script>
  <?php
    }
  ?>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
