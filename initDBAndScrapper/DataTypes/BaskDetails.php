<?php


class BaskDetails
{

    public $part;
    public $score;

    /**
     * BaskDetails constructor.
     * @param $part
     * @param $score
     */
    public function __construct($part, $score)
    {
        $this->part = $part;
        $this->score = $score;
    }

    /**
     * @return mixed
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $part
     */
    public function setPart($part)
    {
        $this->part = $part;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    public function __toString()
    {
     return "";
    }


}