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
      ),
      7 => array(
          "club" => "Lech"
      ),
      8 => array(
          "club" => "Lech"
      ),
      9 => array(
          "club" => "Lech"
      ),
      10 => array(
          "club" => "Lech"
      ),
      11 => array(
"club" => "Lech"
),
12 => array(
"club" => "Lech"
),
13 => array(
"club" => "Lech"
),
14 => array(
"club" => "Lech"
),
15 => array(
"club" => "Lech"
),
16 => array(
"club" => "Lech"
),
17 => array(
"club" => "Lech"
),
18 => array(
"club" => "Lech"
),
19 => array(
"club" => "Lech"
),
20 => array(
"club" => "Lech"
),
21 => array(
"club" => "Lech"
),
22 => array(
"club" => "Lech"
),
23 => array(
"club" => "Lech"
),
24 => array(
"club" => "Lech"
),
25 => array(
"club" => "Lech"
),
26 => array(
"club" => "Lech"
),
27 => array(
"club" => "Lech"
),
28 => array(
"club" => "Lech"
),
29 => array(
"club" => "Lech"
),
30 => array(
"club" => "Lech"
),
31 => array(
"club" => "Lech"
),
32 => array(
"club" => "Lech"
),
33 => array(
"club" => "Lech"
),
34 => array(
"club" => "Lech"
),
35 => array(
"club" => "Lech"
),
36 => array(
"club" => "Lech"
),
37 => array(
"club" => "Lech"
),
38 => array(
"club" => "Lech"
),
39 => array(
"club" => "Lech"
),
40 => array(
"club" => "Lech"
),
41 => array(
"club" => "Lech"
),
42 => array(
"club" => "Lech"
),
43 => array(
"club" => "Lech"
),
44 => array(
"club" => "Lech"
),
45 => array(
"club" => "Lech"
),
46 => array(
"club" => "Lech"
),
47 => array(
"club" => "Lech"
),
48 => array(
"club" => "Lech"
),
49 => array(
"club" => "Lech"
),
50 => array(
"club" => "Lech"
),
51 => array(
"club" => "Lech"
),
52 => array(
"club" => "Lech"
),
53 => array(
"club" => "Lech"
),
54 => array(
"club" => "Lech"
),
55 => array(
"club" => "Lech"
),
56 => array(
"club" => "Lech"
),
57 => array(
"club" => "Lech"
),
58 => array(
"club" => "Lech"
),
59 => array(
"club" => "Lech"
),
60 => array(
"club" => "Lech"
),
61 => array(
"club" => "Lech"
),
62 => array(
"club" => "Lech"
),
63 => array(
"club" => "Lech"
),
64 => array(
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
                  // echo $arr1[$y]." vs ".$arr2[$y]."\n";
                  array_push($rounds[$x], array( $arr2[$y], $arr1[$y] ));
              } else {
                  // echo $arr1[$y]." vs ".$arr2[$y]."\n";
                  array_push($rounds[$x], array( $arr1[$y], $arr2[$y] ));
              }
          }
      }
      // echo "\n";
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
