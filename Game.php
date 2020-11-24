<?php

class Game
{

    public $game_time;
    public $home_team;
    public $away_team;
    public $game_status;
    public $result;
    public $game_link;

    /**
     * Game constructor.
     * @param $game_time
     * @param $home_team
     * @param $away_team
     * @param $game_status
     * @param $result
     * @param $game_link
     */

    public function __construct($game_time, $home_team, $away_team, $game_link, $result,$game_status)
    {
        $this->game_time = $game_time;
        $this->home_team = $home_team;
        $this->away_team = $away_team;
        $this->game_link = $game_link;
        $this->result = $result;
        $this->game_status= $game_status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->game_status = $status;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getGame_time()
    {
        return $this->game_time;
    }

    /**
     * @return mixed
     */
    public function getHome_team()
    {
        return $this->home_team;
    }

    /**
     * @return mixed
     */
    public function getAway_team()
    {
        return $this->away_team;
    }

    /**
     * @return mixed
     */
    public function getGame_status()
    {
        return $this->game_status;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getGame_link()
    {
        return $this->game_link;
    }


}