<?php
  class Game {
    private $player0;
    private $player1;
    private $results0;
    private $results1;
    private $disabled0;
    private $disabled1;
    private $hidden;

    public static $are_two_legs = false;
    public static $are_two_legs_final = true;

    public function __construct($a="", $b="", $c=array("",""), $d=array("",""), $e=false, $f=false) {
      if(gettype($a) == "boolean"){
        $this->hidden = true;
      } else {
        $this->hidden = false;
      }

      if($a != "") {
        $this->player0 = $a;
      }

      if( $b != "") {
        $this->player1 = $b;
      }

      if( is_array($c) ) {
        $this->results0 = $c;
      }

      if( is_array($d) ) {
        $this->results1 = $d;
      }

      if( $e ) {
        $this->disabled0 = " disabled";
      }

      if( $f ) {
        $this->disabled1 = " disabled";
      }
    }

    public function outputHiddenHTML(){
      return '<div class="game game-top game-hidden"></div><div class="game game-spacer game-hidden"></div><div class="game game-bottom game-hidden"></div><div class="spacer"></div>';
    }

    public function outputFinalHTML(){
      return '<div class="col final"><div class="game game-top"><div><span>'.$this->player0.'</span></div></div></div>';
    }

    public function outputHTML(){
      if( $this->hidden ){
        $string_to_return = '<div class="game game-top game-hidden"></div><div class="game game-spacer game-hidden"></div><div class="game game-bottom game-hidden"></div><div class="spacer"></div>';
      } else {
        $player0   = $this->player0;
        $player1   = $this->player1;
        $results0  = $this->results0;
        $results1  = $this->results1;
        $disabled0 = $this->disabled0;
        $disabled1 = $this->disabled1;

        if( count($results0) > 1 ){
          $input2 = '<span class="score2"><input type="number" class="home" value="'.$results0[1].'" min="0" max="999"'.$disabled0.'></span>';
          $input4 = '<span class="score2"><input type="number" class="home" value="'.$results1[1].'" min="0" max="999"'.$disabled1.'></span>';
        } else {
          $input2 = $input4 = "";
        }

        $input1 = '<span class="score1"><input type="number" class="home" value="'.$results0[0].'" min="0" max="999"'.$disabled0.'></span>';
        $input3 = '<span class="score1"><input type="number" class="home" value="'.$results1[0].'" min="0" max="999"'.$disabled1.'></span>';
        $game_top = '<div class="game game-top"><div><span>'.$player0.'</span>'.$input1.$input2.'</div></div>';


        $game_bottom = '<div class="game game-bottom"><div><span>'.$player1.'</span>'.$input3.$input4.'</div></div>';

        $string_to_return = $game_top.'<div class="game game-spacer"></div>'.$game_bottom.'<div class="spacer"></div>';
      }

      return $string_to_return;
    }
  }
?>