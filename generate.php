<?php
  session_start();
  include "passwords.php";
  $dbc = new mysqli(HOST, LOGIN, PASSWORD, DATABASE) or die( 'błąd' );
  $json = array();
  $settings = array();
  $error = false;

  $getKeys = array_keys($_GET);
  $settingKeys = array_search("name0", $getKeys);

  for($x=0; $x<$settingKeys; $x++){
    $settings[$getKeys[$x]] = array_shift($_GET);
  }

  print_r($settings);

  $temp = array(
    "players" => array(),
    "club" => ""
  );

  for($x=0; $x<count($getKeys); $x++){
    $key = $getKeys[$x];
    if( strpos($key, 'club') !== false ){
      $temp["club"] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
      array_push($json, $temp);
      $temp = array(
        "players" => array(),
        "club" => ""
      );
    } else if( strpos($key, 'name') !== false ){
      if( !empty($_GET[$key]) ){
        array_push($temp["players"], filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING));
      } else {
        $error = true;
        $_SESSION["team_e"] = "You have to enter names of all the players!";
      }
    }
  }

  $players = count($json);
  $arr = array();
  $rounds = array();
  $results = array();

  if( $settings["type"] == "League" ){

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

    if( !$error ){
      $title = (isset($settings["title"]) && !empty($settings["title"])) ? "'".$dbc->real_escape_string(filter_var($settings["title"], FILTER_SANITIZE_STRING))."'" : "NULL";

      $json = $dbc->real_escape_string(JSON_encode($json));
      $rounds = $dbc->real_escape_string(JSON_encode($rounds));
      $results = $dbc->real_escape_string(JSON_encode($results));


      $dbc->query('SET NAMES utf8');
      $token = bin2hex(openssl_random_pseudo_bytes(3));
      $query = "INSERT INTO `tournaments`(`title`, `type`, `players`, `rounds`, `fixtures`, `admin_token`) VALUES ($title, 'League', '$json', '$rounds', '$results', '".$token."')";
      $data = $dbc->query($query);
      $id = $dbc->insert_id;
      mysqli_close($dbc);

      header("Location: tournament/$id/scores?admin=$token");
    } else {
      header("Location: .");
    }
    die();
  } elseif ($settings["type"] == "Knockout") {

    // $players = 11;

    $leg = ( isset($settings["legsKnockout"]) ) ? true : false;

    if( $players < 33 ) {
      $rounds = array( array() );
      $results = array( array() );

      if( $players == 16 || $players == 8 || $players == 4 || $players == 2 ){

        $ava_players = array();

        for( $x=0; $x<$players; $x++ ){
          array_push($ava_players, $x);
        }

        shuffle($ava_players);
        shuffle($ava_players);

        for($x=0; $x<$players; $x+=2){
          array_push($rounds[0], array($ava_players[$x], $ava_players[$x+1]));
          array_push($results[0], array());
        }

        if($players > 2){
          $temp_stage = $players/4;
          while ( $temp_stage > 1 ) {
            $temp_arr = array();
            for($x=0; $x<$temp_stage; $x++){
              array_push($temp_arr, array());
            }
            array_push($rounds, $temp_arr);
            array_push($results, $temp_arr);
            $temp_stage /= 2;
          }

          array_push($rounds, array( array() ) );
          array_push($results, array( array() ) );
        }

        array_unshift($rounds, array());
        array_unshift($results, array());
        array_push($rounds, array() );
        array_push($results, array() );

        $results_temp = array();
        array_push($results_temp, $results);
        if( $leg ){
          array_push($results_temp, $results);
        }
        $results = $results_temp;

      } else {

        $temp_rounds = array();
        $temp_results = array();
        $temp_dummys = array();
        $temp_dummys_results = array();

        if( $players > 16 ){
          $stage = 16;
        } else if ( $players > 8 ){
          $stage = 8;
        } else if ( $players > 4 ){
          $stage = 4;
        } else if ( $players > 2 ){
          $stage = 2;
        }

        $dummys = $players - $stage;

        $ava_players = array();

        for( $x=0; $x<$players; $x++ ){
          array_push($ava_players, $x);
        }

        shuffle($ava_players);
        shuffle($ava_players);

        $dummy_ava_players = array();

        for( $x=0; $x<$dummys*2; $x++ ){
          array_push($dummy_ava_players, $ava_players[0]);
          array_shift($ava_players);
        }

        for( $x=0; $x<count($dummy_ava_players); $x+=2 ){
          array_push($temp_rounds, -1);
          array_push($temp_dummys, array($dummy_ava_players[$x], $dummy_ava_players[$x+1]));
          array_push($temp_dummys_results, array());
        }


        for( $x=0; $x<count($ava_players); $x++ ){
          array_push($temp_rounds, $ava_players[$x]);
        }

        for($x=0; $x<count($temp_rounds); $x+=2){
          array_push($rounds[0], array($temp_rounds[$x], $temp_rounds[$x+1]));
          array_push($results[0], array());
        }


        array_unshift($rounds, $temp_dummys);
        array_unshift($results, $temp_dummys_results);


        if($players > 2){
          $temp_stage = $stage/4;

          while ( $temp_stage > 1 ) {
            $temp_arr = array();
            $temp_arr_results = array();
            for($x=0; $x<$temp_stage; $x++){
              array_push($temp_arr, array());
              array_push($temp_arr_results, array());
            }
            array_push($rounds, $temp_arr);
            array_push($results, $temp_arr_results);
            $temp_stage /= 2;
          }

          array_push($rounds, array( array() ) );
          array_push($results, array( array() ));
        }
        array_push($rounds, array() );
        array_push($results, array() );

        $results_temp = array();
        array_push($results_temp, $results);
        if( $leg ){
          array_push($results_temp, $results);
        }
        $results = $results_temp;

        echo json_encode($rounds);
        echo "<br><br>";
        echo json_encode($results);
      }

      $title = (isset($settings["title"]) && !empty($settings["title"])) ? "'".$dbc->real_escape_string(filter_var($settings["title"], FILTER_SANITIZE_STRING))."'" : "NULL";

      $json = $dbc->real_escape_string(JSON_encode($json));
      $rounds = $dbc->real_escape_string(JSON_encode($rounds));
      $results = $dbc->real_escape_string(JSON_encode($results));

      $dbc->query('SET NAMES utf8');
      $token = bin2hex(openssl_random_pseudo_bytes(3));
      $query = "INSERT INTO `tournaments`(`title`, `type`, `players`, `rounds`, `fixtures`, `admin_token`) VALUES ($title, 'Knockout', '$json', '$rounds', '$results', '".$token."')";
      $data = $dbc->query($query);
      $id = $dbc->insert_id;
      mysqli_close($dbc);

      header("Location: tournament/$id/scores?admin=$token");
    }
  }
?>
