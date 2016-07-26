<?php
  set_time_limit(0);
  include "simple_html_dom.php";
  $fifaVersion = 16;

  if (!is_dir($fifaVersion."/")) {
    mkdir($fifaVersion."/");
  }

  $html = file_get_html("http://www.futhead.com/$fifaVersion/players/");

  $allLeaguesList = array();
  $allTeamsList = json_decode( file_get_contents("club-names.js") , true);

  foreach( $html->find('.subnav ul li.dropdown') as $li) {
    foreach( $li->find('a span') as $span) {
      if( $span->plaintext == "League " ){
        foreach( $li->find('li.dropdown ul li') as $league) {
          foreach( $league->find('a') as $a) {
            array_push($allLeaguesList, $a->attr["data-preserve-value"]);
          }
        }
      }
    }
  }

// $allLeaguesList = array( $allLeaguesList[0] );

print_r( $allLeaguesList[37] );

  $key = $allLeaguesList[$_GET["i"]*1];
  $html = file_get_html("http://www.futhead.com/$fifaVersion/players/?league=".$key*1);

  foreach( $html->find('.subnav ul li.dropdown') as $li) {
    foreach( $li->find('a span') as $span) {
      if( $span->plaintext == "Club " ){
        foreach( $li->find('ul li') as $league) {
          $team = trim($league->plaintext);
          array_push($allTeamsList, $team);

          $search = json_decode( file_get_contents("http://www.futhead.com/quicksearch/club/$fifaVersion/?term=".str_replace("%26%2339%3B", "%27", urlencode($team))), true );

          $remoteURL = $search[0]["image"];
          $urlTeamName = $fifaVersion."/".strtolower(preg_replace("/[^a-zA-Z0-9\-]+/", "", html_entity_decode(str_replace(" ", "-", $team), ENT_QUOTES))).".png";
          file_put_contents($urlTeamName, fopen($remoteURL, 'r'));
        }
      }
    }
  }

sort( $allTeamsList );

print_r( $allTeamsList );

file_put_contents("club-names.js", json_encode($allTeamsList));

if( count($allLeaguesList) > $_GET["i"]*1 ){
  echo "<script>window.location='download2.php?i=".($_GET["i"]*1+1)."'</script>";
}

?>
