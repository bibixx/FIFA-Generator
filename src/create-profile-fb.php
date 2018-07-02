<?php
  if( isset($_POST["fb_id"]) && !empty($_POST["fb_id"]) ){
    include "passwords.php";
    $dbc = new mysqli(HOST, LOGIN, PASSWORD, DATABASE) or die( 'błąd' );

    $fb_id = $_POST["fb_id"];

    $query = "SELECT * FROM `fb_users` WHERE `fb_id`=".$fb_id;
    $data = $dbc->query($query);
    $row = mysqli_fetch_array($data);

    if( empty($row) ){
      $tournaments = array(array(),array(),array());
      $tournaments = $dbc->real_escape_string(JSON_encode($tournaments));

      $dbc->query('SET NAMES utf8');
      $query = "INSERT INTO `fb_users`(`fb_id`, `tournaments`) VALUES ($fb_id, '$tournaments')";
      $data = $dbc->query($query);
    }

    mysqli_close($dbc);
  }
?>
