<?php
  $dbc = mysql_connect('127.0.0.1', 'root', 'admin') or die( 'błąd' );
  $dcs = mysql_select_db('tournaments');
  mysql_query('SET NAMES utf8');
  $query = "SELECT * FROM `tournaments` WHERE (`id`=1)";
  $data = mysql_query($query);

  $row = mysql_fetch_array($data);
  $fixtures = json_decode($row["fixtures"], true);
  $index = explode(",", $_POST["index"]);
  if( array_key_exists( "value", $_POST ) ){
    $fixtures[$index[0]][$index[1]] = [$_POST["value"][0]*1, $_POST["value"][1]*1];
  } else {
    $fixtures[$index[0]][$index[1]] = array();
  }

  $query = "UPDATE `tournaments` SET `fixtures` = '".json_encode( $fixtures )."' WHERE `tournaments`.`id` = 1";
  $data = mysql_query($query);
  mysql_close($dbc);
?>
