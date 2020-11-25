<?php

class League
{
   public $name;
   public $games=array();

    public function __construct(){
        $games= array();
    }

    public function setLeagueName($name){
        $this->name=$name;
    }

    public function getLeagueName(){
        return $this->name;
    }
    //nao ser de nada acho mas still
    public function getGame($i){
        return $this->games[$i];
    }

    public function pushJogos($newItem){
        array_push($this->games,$newItem);
    }
}