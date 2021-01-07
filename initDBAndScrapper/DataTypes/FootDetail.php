<?php


class FootDetail{

    public $tempo;
    public $descricao;
    public $tipo;

    /**
     * FootDetail constructor.
     * @param $tempo
     * @param $descricao
     * @param $tipo
     */
    public function __construct($tempo, $descricao, $tipo)
    {
        $this->tempo = str_replace("'","",$tempo);
        $this->descricao =  str_replace("'","",$descricao);;
        $this->tipo = self::helpConstrutor($tipo);
    }

    public function helpConstrutor($tipo){
        //Associate the class from the HTML element to a more pleasent String
        $res="Error";
        if(strpos($tipo,"y-card")){
            $res="Yellow Card";
        }elseif (strpos($tipo,"yr-card")){
            $res="Red Card (Second Yellow)";
        }elseif (strpos($tipo,"r-card")){
            $res="Red Card";
        }elseif (strpos($tipo,"ball")){
            $res="Goal";
        }elseif (strpos($tipo,"substitution")){
            $res="Substitution";
        }
        $res = str_replace("'","",$res);
        return $res;
    }

    public function __toString(){
        return $this->tempo . " ". $this->descricao . $this->tipo ;
    }


    /**
     * @return mixed
     */
    public function getTempo()
    {
        return $this->tempo;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }



}