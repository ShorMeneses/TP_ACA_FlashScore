<?php

class League
{
   public $name;
   public $games;

    public function __construct(){
        $games= array();
    }

    public function setLeagueName($name){
        $this->name=$name;
    }

    public function getLeagueName(){
        return $this->name;
    }

    public function pushJogos($newItem){
        array_push($games,$newItem);
    }
}