<?php

class Game
{

    public $game_time;
    public $home_team;
    public $away_team;
    public $game_status;
    public $hGoals;
    public $aGoals;
    public $game_link;
    public $game_info;

    /**
     * Game constructor.
     * @param $game_time
     * @param $home_team
     * @param $away_team
     * @param $game_status
     * @param $hGoals;
     * @param $aGoals;
     * @param $game_link
     */

    public function __construct($game_time, $home_team, $away_team, $game_link,$hGoals,$aGoals,$game_status)
    {
        $this->game_time = $game_time;
        $this->home_team = $home_team;
        $this->away_team = $away_team;
        $this->game_link = $game_link;
        $this->hGoals = $hGoals;
        $this->aGoals = $aGoals;
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
    public function setResult($hGoals,$aGoals)
    {
        $this->hGoals = $hGoals;
        $this->aGoals = $aGoals;
    }

    /**
     * @return mixed
     */
    public function getGameInfo()
    {
        return $this->game_info;
    }

    /**
     * @param mixed $game_info
     */
    public function setGameInfo($game_info)
    {
        $this->game_info = $game_info;
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
    public function getHGoals()
    {
        return $this->hGoals;
    }

    public function getAGoals()
    {
        return $this->aGoals;
    }

    /**
     * @return mixed
     */
    public function getGame_link()
    {
        return $this->game_link;
    }


}