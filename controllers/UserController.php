<?php

class UserController {

    public function dashboard(){

        if(!isset($_SESSION['user_id'])){
            header("Location: /cultureconnect/login");
            exit();
        }

        require '../views/user/dashboard.php';
    }

}