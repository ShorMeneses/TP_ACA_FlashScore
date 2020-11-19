<?php

class Liga
{
   public $name;
   public $games;

    public function __construct(){
        $games= array();
    }

    public function setLigaN($name){
        $this->name=$name;
    }

    public function getLigaN(){
        return $this->name;
    }

    public function pushJogos($newItem){
        array_push($games,$newItem);
    }
}