<?php
	include "passwords.php";
  $dbc = new mysqli(HOST, LOGIN, PASSWORD, DATABASE) or die( 'błąd' );
  $dbc->query('SET NAMES utf8');
  $query = "SELECT * FROM `tournaments` WHERE (`id`=".$dbc->real_escape_string($_POST["id"]).")";
  $data = $dbc->query($query);

  $row = mysqli_fetch_array($data);
  $adminToken = $row["admin_token"];
  $admin = (isset($_POST["admin"]) && $_POST["admin"] == $adminToken ) ? true: false;

  if( $admin ){

    $fixtures = json_decode($row["fixtures"], true);
    $index = explode(",", $_POST["index"]);
    if( array_key_exists( "value", $_POST ) ){
      $fixtures[$index[0]][$index[1]] = [$_POST["value"][0]*1, $_POST["value"][1]*1];
    } else {
      $fixtures[$index[0]][$index[1]] = array();
    }

    $query = "UPDATE `tournaments` SET `fixtures` = '".json_encode( $fixtures )."' WHERE `tournaments`.`id` = ".$_POST["id"];
    $data = $dbc->query($query);
    mysqli_close($dbc);
  }
?>
