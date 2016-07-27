<?php
	include "passwords.php";
  $dbc = new mysqli(HOST, LOGIN, PASSWORD, DATABASE) or die( 'błąd' );
  $dbc->query('SET NAMES utf8');
  $query = "SELECT * FROM `tournaments` WHERE (`id`=".$dbc->real_escape_string($_POST["id"]).")";
  $data = $dbc->query($query);

  $row = mysqli_fetch_array($data);
  $adminToken = $row["admin_token"];
  $admin = (isset($_POST["admin"]) && $_POST["admin"] == $adminToken ) ? true: false;
	$fixtures = json_decode($row["fixtures"], true);
	$rounds = json_decode($row["rounds"], true);

  if( $admin ){
		if( $_POST["type"] == "Knockout" ){
	    $index = explode(",", $_POST["index"]);
	    if( array_key_exists( "value", $_POST ) ){
	      $fixtures[$index[0]*1][$index[1]*1] = [$_POST["value"][0]*1, $_POST["value"][1]*1];
	    } else {
	      $fixtures[$index[0]*1][$index[1]*1] = array();
	    }

	  } else if( $_POST["type"] == "Cup" ) {
			$p = explode(":", $_POST["index"]);

			if( array_key_exists( "value", $_POST ) ){
				$v1 = ($_POST["value"][0]*1 >= 0) ? $_POST["value"][0]*1 : "";
				$v2 = ($_POST["value"][1]*1 >= 0) ? $_POST["value"][1]*1 : "";
				$v3 = ($_POST["value"][2]*1 >= 0) ? $_POST["value"][2]*1 : "";
				$v4 = ($_POST["value"][3]*1 >= 0) ? $_POST["value"][3]*1 : "";

				$fixtures[0][$p[0]][$p[1]] = [$v1, $v2];

				if( count($fixtures) == 2 ){
					$fixtures[1][$p[0]][$p[1]] = [$v3, $v4];
				}

				if( ($p[0]+1) < (count($rounds)-1) ){
					if( count($fixtures) != 2 ){
						if( $v1 === "" || $v2 === "" ){
							$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = -1;
						} else if( $_POST["value"][0]*1 > $_POST["value"][1]*1 ){
							$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = $rounds[$p[0]][$p[1]][0];
						} else if( $_POST["value"][0]*1 < $_POST["value"][1]*1 ){
							$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = $rounds[$p[0]][$p[1]][1];
						} else if( $_POST["value"][0]*1 === $_POST["value"][1]*1 ){
							echo "Penalties";
							$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = -1;
						}
					} else {
						if( $v1 === "" || $v2 === "" || $v3 === "" || $v4 === "" ){
							$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = -1;
						} else if( ($_POST["value"][0]*1+$_POST["value"][2]*1) > ($_POST["value"][1]*1+$_POST["value"][3]*1) ){
							$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = $rounds[$p[0]][$p[1]][0];
						} else if( ($_POST["value"][0]*1+$_POST["value"][2]*1) < ($_POST["value"][1]*1+$_POST["value"][3]*1) ){
							$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = $rounds[$p[0]][$p[1]][1];
						} else if( ($_POST["value"][0]*1+$_POST["value"][2]*1) == ($_POST["value"][1]*1+$_POST["value"][3]*1) ){
							if( $_POST["value"][1]*1 > $_POST["value"][3]*1 ){
								$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = $rounds[$p[0]][$p[1]][1];
							} else if( $_POST["value"][1]*1 < $_POST["value"][3]*1 ){
								$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = $rounds[$p[0]][$p[1]][0];
							} else {
								echo "Penalties";
								$rounds[$p[0]+1][floor($p[1]/2)][$p[1]%2] = -1;
							}
						}
					}

					if( !array_key_exists( 0, $rounds[$p[0]+1][floor($p[1]/2)]) ){
						$rounds[$p[0]+1][floor($p[1]/2)][0] = -1;
					}
					if( !array_key_exists( 1, $rounds[$p[0]+1][floor($p[1]/2)]) ){
						$rounds[$p[0]+1][floor($p[1]/2)][1] = -1;
					}

				} else {
					if( $v1 === "" || $v2 === "" ){
						$rounds[$p[0]+1] = array();
					} else if( $_POST["value"][0]*1 > $_POST["value"][1]*1 ){
						$rounds[$p[0]+1] = array($rounds[$p[0]][$p[1]][0]);
					} else if( $_POST["value"][0]*1 < $_POST["value"][1]*1 ){
						$rounds[$p[0]+1] = array($rounds[$p[0]][$p[1]][1]);
					}
				}
			}
		}

		$query1 = "UPDATE `tournaments` SET `fixtures` = '".json_encode( $fixtures )."' WHERE `tournaments`.`id` = ".$dbc->real_escape_string($_POST["id"]);
		$query2 = "UPDATE `tournaments` SET `rounds` = '".json_encode( $rounds )."' WHERE `tournaments`.`id` = ".$dbc->real_escape_string($_POST["id"]);
		$data = $dbc->query($query1);
		$data = $dbc->query($query2);
		mysqli_close($dbc);
	}
?>
