<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database {

    public function createUser($username, $password) {        
            return $this->select("INSERT INTO `users` (`username`, `password`) VALUES (?, ?)", ["ss", $username, $password]);
    }


    public function deleteUser($username) {
        return $this->select("DELETE FROM `users` WHERE username=?", ["s", $username]);
    }


    public function updateUser($username, $password) {
        return $this->select("UPDATE users SET password=? WHERE username=?", ["ss", $username, $password]);
    }

}

