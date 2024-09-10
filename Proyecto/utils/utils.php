<?php

require_once "../domain/User.php";
class Utils {
    public static function setUserLogged($user) {
        $_SESSION['user'] = $user;
    }

    public static function getUserLogged() {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }
}


?>