<?php
  $json = array();
  $settings = array();

  for($x=0; $x<=4; $x++){
    array_push( $settings, array_shift($_GET) );
  }

  $getKeys = array_keys($_GET);
  $temp = array(
    "players" => array(),
    "club" => ""
  );

  for($x=0; $x<count($getKeys); $x++){
    $key = $getKeys[$x];
    if( strpos($key, 'club') !== false ){
      $temp["club"] = urlencode($_GET[$key]);
      array_push($json, $temp);
      $temp = array(
        "players" => array(),
        "club" => ""
      );
    } else {
      array_push($temp["players"], urlencode( $_GET[$key] ));
    }
  }

  $players = count($json);
  $arr = array();
  $rounds = array();
  $results = array();

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
    $results[$x] = array();
    for($y=0; $y<count($arr1); $y++){
      if( $arr1[$y] >= 0 ){
        array_push($results[$x], array());
        if( ($x%2 == 0) && ($y == 0) ){
          array_push($rounds[$x], array( $arr2[$y], $arr1[$y] ));
        } else {
          array_push($rounds[$x], array( $arr1[$y], $arr2[$y] ));
        }
      }
    }
    clock();
  }

  shuffle( $rounds );
  shuffle( $rounds );

  $dbc = mysql_connect('127.0.0.1', 'root', 'admin') or die( 'błąd' );
  $dcs = mysql_select_db('tournaments');
  mysql_query('SET NAMES utf8');
  $query = "INSERT INTO `tournaments`(`title`, `type`, `players`, `rounds`, `fixtures`) VALUES ('tytuł', 'League', '".JSON_encode( $json )."', '".JSON_encode( $rounds )."', '".JSON_encode( $results )."')";
  $data = mysql_query($query);
  $id = mysql_insert_id();
  mysql_close($dbc);

  header("Location: scores.php?id=".$id);
  die();
?>
