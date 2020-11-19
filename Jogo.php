<?php

class Jogo
{

    public $hora;
    public $equipa_casa;
    public $equipa_fora;
    public $estado;
    public $resultado;
    public $linkJogo;

    /**
     * Jogo constructor.
     * @param $hora
     * @param $equipa_casa
     * @param $equipa_fora
     * @param $estado
     * @param $resultado
     * @param $linkJogo
     */
    public function __construct($hora, $equipa_casa, $equipa_fora, $estado, $resultado, $linkJogo)
    {
        $this->hora = $hora;
        $this->equipa_casa = $equipa_casa;
        $this->equipa_fora = $equipa_fora;
        $this->estado = $estado;
        $this->resultado = $resultado;
        $this->linkJogo = $linkJogo;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @param mixed $resultado
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
    }

    /**
     * @return mixed
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @return mixed
     */
    public function getEquipaCasa()
    {
        return $this->equipa_casa;
    }

    /**
     * @return mixed
     */
    public function getEquipaFora()
    {
        return $this->equipa_fora;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @return mixed
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * @return mixed
     */
    public function getLinkJogo()
    {
        return $this->linkJogo;
    }


}