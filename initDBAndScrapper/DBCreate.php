<?php


class DBCreate{


public $servername = "localhost";
public $username = "root";
public $password = "";
public $conn;
public $typeOfGame;

    public function __construct($typeOfGame){
        $this->$typeOfGame=$typeOfGame;
        $this->conn = new mysqli($this->servername, $this->username, $this->password);
        $this->createDataBase($this->conn);
        $this->conn ->select_db('FlashscoreDB');
        $this->deleteTableHandeler();
        $this->createTablesIfTheyDontExist($this->conn);
        $this->conn->close();

    }

    function deleteTableHandeler(){
        switch ($this->typeOfGame) {
            case 0:
                $this->deleteSoccerTables($this->conn);
                $this->deleteBasketTables($this->conn);
                break;
            case 1:
                $this->deleteSoccerTables($this->conn);
                break;
            case 2:
                $this->deleteBasketTables($this->conn);
                break;
        }
    }





    function createDataBase($conn){
        if ($conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

// Create database
        $sql = "CREATE DATABASE IF NOT EXISTS FlashscoreDB";
        if ($conn->query($sql) === TRUE) {
            echo "\n Database created successfully";
        } else {
            echo "\n Error creating database: " . $this->conn->error;
        }
    }

    function createTablesIfTheyDontExist($conn){

        $sql = "CREATE TABLE IF NOT EXISTS FootLeagues (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL)";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table FootLeagues created successfully";
        } else {
            echo "\n Error creating table: " . $this->conn->error;
        }


        //-----------------------------------------------

        $sql = "CREATE TABLE IF NOT EXISTS FootGames (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            league_id INT(6) UNSIGNED,
            game_time VARCHAR(100),
             home_team VARCHAR(100),
             away_team VARCHAR(100),
             game_status VARCHAR(100),
             hGoals VARCHAR(100),
             aGoals VARCHAR(100),
             game_link VARCHAR(100),
             game_info VARCHAR(65535),
             game_lineup VARCHAR(100),
             FOREIGN KEY (league_id) REFERENCES footleagues(id)
             )";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table FootGames created successfully";
        } else {
            echo "\n Error creating table: " . $this->conn->error;
        }

        //---------------------------------------------------BASKET

        $sql = "CREATE TABLE IF NOT EXISTS BasketLeagues (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL)";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table BasketLeagues created successfully";
        } else {
            echo "\n Error creating table: " . $this->conn->error;
        }

        //--------------------------------------------------------------

        $sql = "CREATE TABLE IF NOT EXISTS BasketGames (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            league_id INT(6) UNSIGNED,
            game_time VARCHAR(100),
             home_team VARCHAR(100),
             away_team VARCHAR(100),
             game_status VARCHAR(100),
             hGoals VARCHAR(100),
             aGoals VARCHAR(100),
             game_link VARCHAR(100),
             game_info VARCHAR(65535),
             game_lineup VARCHAR(100),
             FOREIGN KEY (league_id) REFERENCES basketleagues(id)
             )";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table BasketGames created successfully";
        } else {
            echo "\n Error creating table: " . $this->conn->error;
        }



    }


    function deleteSoccerTables($conn){
            $sql = "DROP TABLE footgames ";
    
            if ($conn->query($sql) === TRUE) {
                echo "\n Table FootGames deleted successfully";
            } else {
                echo "\n Error on deleting table: " . $this->conn->error;
            }
    
            $sql = "DROP TABLE footleagues ";
    
            if ($conn->query($sql) === TRUE) {
                echo "\n Table FootLeagues deleted successfully";
            } else {
                echo "\n Error on deleting table: " . $this->conn->error;
            }
    
    
    }


    function deleteBasketTables($conn){
        $sql = "DROP TABLE basketgames ";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table BasketGames deleted successfully";
        } else {
            echo "\n Error on deleting table: " . $this->conn->error;
        }

        $sql = "DROP TABLE basketleagues ";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table BasketLeagues deleted successfully";
        } else {
            echo "\n Error on deleting table: " . $this->conn->error;
        }


}


}