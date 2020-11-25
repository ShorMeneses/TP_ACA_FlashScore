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
        $this->tempo = $tempo;
        $this->descricao = $descricao;
        $this->tipo = self::helpConstrutor($tipo);
    }

    public function helpConstrutor($tipo){
        /*cards
   y-card - cartao amarelo
   yr-card- um amarelo um vermelho
   r-card -cartao vermlho direto
   ball -golo
   substitution-substituição

   */
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