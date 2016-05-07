<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <pre>
<?php
  $json = array(
      0 => array(
          "club" => "Real",
          "players" => array("Adam", "Marek")
      ),
      1 => array(
          "club" => "FCB"
      ),
      2 => array(
          "club" => "Anzy"
      ),
      3 => array(
          "club" => "Legia"
      ),
      4 => array(
          "club" => "Arsenal"
      ),
      5 => array(
          "club" => "Wisla"
      ),
      6 => array(
          "club" => "Lech"
      )
  );

  $players = count($json);
  $arr = array();
  $rounds = array();

  for($x=0; $x<$players; $x++){
      array_push($arr, $x);
  }

  if($players%2 != 0){
      array_unshift($arr, -1);
  }

  $len = count($arr)/2;
  $arr1 = array_slice( $arr, 0, $len );
  $arr2 = array_reverse( array_slice( $arr, $len ) );

  function clock(){
      global $arr1;
      global $arr2;
      $fix = $arr1[0];
      array_shift( $arr1 );
      array_unshift( $arr1, $arr2[0] );
      array_unshift( $arr1, $fix );
      array_shift( $arr2 );
      array_push( $arr2, $arr1[count($arr1)-1] );
      array_pop( $arr1 );
  }

  $games = count($arr1)*2-1;

  for($x=0; $x<$games; $x++){
      $rounds[$x] = array();
      for($y=0; $y<count($arr1); $y++){
          if( $arr1[$y] >= 0 ){
              if( ($x%2 == 0) && ($y == 0) ){
                  echo $arr1[$y]." vs ".$arr2[$y]."\n";
                  array_push($rounds[$x], array( $arr2[$y], $arr1[$y] ));
              } else {
                  echo $arr1[$y]." vs ".$arr2[$y]."\n";
                  array_push($rounds[$x], array( $arr1[$y], $arr2[$y] ));
              }
          }
      }
      echo "\n";
      clock();
  }

  echo "----------------------------------------------------------------------";
  echo "\n\n";


  shuffle( $rounds );
  shuffle( $rounds );

  echo JSON_encode( $rounds );
  echo "\n\n";
  echo JSON_encode( $json );
  echo "\n\n";

  echo "----------------------------------------------------------------------";
  echo "\n\n";

  for($r=0; $r<count($rounds); $r++){
      for($g=0; $g<count($rounds[$r]); $g++){
          $game = $rounds[$r][$g];
          echo $json[$game[0]]["club"]." vs ".$json[$game[1]]["club"]."\n";
      }
      echo "\n";
  }
?>
    </pre>
  </body>
</html>
