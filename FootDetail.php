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
        return $tipo;
    }
/*
    public function __toString()
    {
    }
*/

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