<?php
  class Game {
    private $player0;
    private $player1;
    private $img0;
    private $img1;
    private $results0;
    private $results1;
    private $disabled0;
    private $disabled1;
    private $correct00;
    private $correct01;
    private $correct10;
    private $correct11;
    private $hidden;

    public static $are_two_legs = false;
    public static $are_two_legs_final = true;
    public static $admin = true;

    public function __construct($a=array(""), $b=array(""), $c="", $d="", $e=array("",""), $f=array("",""), $g=false, $h=false) {
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

      if( $c != "") {
        $this->img0 = '<img title="'.$c.'" src="'.'/logos/16/'.strtolower(preg_replace("/[^a-zA-Z0-9\-]+/", "", html_entity_decode(str_replace(" ", "-", $c), ENT_QUOTES))).'.png'.'">';
      }

      if( $d != "") {
        $this->img1 = '<img title="'.$d.'" src="'.'/logos/16/'.strtolower(preg_replace("/[^a-zA-Z0-9\-]+/", "", html_entity_decode(str_replace(" ", "-", $d), ENT_QUOTES))).'.png'.'">';
      }

      if( is_array($e) ) {
        $this->results0 = $e;
      }

      if( is_array($f) ) {
        $this->results1 = $f;
      }

      if( is_array($e) && array_key_exists(0, $e) && $e[0] !== "" ){
        $this->correct00 = " correct";
      } else {
        $this->correct00 = "";
      }

      if( is_array($e) && array_key_exists(1, $e) && $e[1] !== "" ){
        $this->correct01 = " correct";
      } else {
        $this->correct01 = "";
      }

      if( is_array($f) && array_key_exists(0, $f) && $f[0] !== "" ){
        $this->correct10 = " correct";
      } else {
        $this->correct10 = "";
      }

      if( is_array($f) && array_key_exists(1, $f) && $f[1] !== "" ){
        $this->correct11 = " correct";
      } else {
        $this->correct11 = "";
      }

      if( $g || !Game::$admin ) {
        $this->disabled0 = " disabled";
      }

      if( $h || !Game::$admin ) {
        $this->disabled1 = " disabled";
      }
    }

    public function outputHiddenHTML(){
      return '<div class="game game-top game-hidden"></div><div class="game-spacer game-hidden"></div><div class="game game-bottom game-hidden"></div><div class="spacer"></div>';
    }

    public function outputFinalHTML(){
      $player0  = $this->player0;

      $players0 = $player0[0];

      $img0 = $this->img0;

      $players0Class = "";

      if( count($player0) > 1 ){
        $players0 = $player0[0]." & ".$player0[1];
        $players0Class = ' class="small"';
      }

      return '<div class="col final"><div class="game game-top"><div>'.$img0.'<span'.$players0Class.'>'.$players0.'</span></div></div></div>';
    }

    public function outputHTML(){
      if( $this->hidden ){
        $string_to_return = '<div class="game game-top game-hidden"></div><div class="game-spacer game-hidden"></div><div class="game game-bottom game-hidden"></div><div class="spacer"></div>';
      } else {
        $player0    = $this->player0;
        $player1    = $this->player1;

        $players0 = $player0[0];
        $players1 = $player1[0];

        $img0 = $this->img0;
        $img1 = $this->img1;

        $players0Class = "";
        $players1Class = "";

        if( count($player0) > 1 ){
          $players0 = $player0[0]." & ".$player0[1];
          $players0Class = ' class="small"';
        }

        if( count($player1) > 1 ){
          $players1 = $player1[0]." & ".$player1[1];
          $players1Class = ' class="small"';
        }

        $results0   = $this->results0;
        $results1   = $this->results1;

        $correct00  = $this->correct00;
        $correct01  = $this->correct01;
        $correct10  = $this->correct10;
        $correct11  = $this->correct11;

        $disabled0  = $this->disabled0;
        $disabled1  = $this->disabled1;

        if( count($results0) > 1 ){
          $input2 = '<span class="score2"><input type="number" class="home'.$correct01.'" value="'.$results0[1].'" min="0" max="999"'.$disabled0.'></span>';
          $input4 = '<span class="score2"><input type="number" class="home'.$correct11.'" value="'.$results1[1].'" min="0" max="999"'.$disabled1.'></span>';
        } else {
          $input2 = $input4 = "";
        }

        $input1 = '<span class="score1"><input type="number" class="home'.$correct00.'" value="'.$results0[0].'" min="0" max="999"'.$disabled0.'></span>';

        $input3 = '<span class="score1"><input type="number" class="home'.$correct10.'" value="'.$results1[0].'" min="0" max="999"'.$disabled1.'></span>';
        $game_top = '<div class="game game-top"><div>'.$img0.'<span'.$players0Class.'>'.$players0.'</span>'.$input1.$input2.'</div></div>';

        $game_bottom = '<div class="game game-bottom"><div>'.$img1.'<span'.$players1Class.'>'.$players1.'</span>'.$input3.$input4.'</div></div>';

        $string_to_return = $game_top.'<div class="game-spacer"></div>'.$game_bottom.'<div class="spacer"></div>';
      }

      return $string_to_return;
    }
  }
?>
