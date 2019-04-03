

<?php

class User{
    
    public static function isUser($username){
        $pdo = $GLOBALS['pdo'];
        $query = $pdo->prepare('SELECT userID FROM user where userName=?');
        $query->execute([$username]);
        if (!empty($query->rowCount())){
            return true;
        } 
        return false;
    }

    public static function create($username, $pass){
            
        $pdo = $GLOBALS["pdo"];

        if(User::isUser($username)){
            return array('status'=>true, 'message' => "user <em>$username</em> already exists");
        } else {
            $passHash = password_hash($pass,PASSWORD_BCRYPT);

            $query = $pdo->prepare('INSERT INTO user(userName, passHash) VALUES (?,?)');
            $query->execute([$username, $passHash]);

            if (!empty($query->rowCount())){
                return array('status'=>true, 'message' => "user <em>$username</em> created");
            }
        }
    }
    public static function loginWithPassword($username, $pass){
        $pdo = $GLOBALS['pdo'];
        $query = $pdo->prepare('SELECT * FROM user WHERE userName=?');
        $query->execute([$username]);
        if (!empty($query->rowCount())){
            $row = $query->fetch();
            if (password_verify($pass,$row['passHash'])){
                $cookie = mt_rand(0, 9999999);
                $cookieHash = password_hash($cookie, PASSWORD_BCRYPT);

                $query = $pdo->prepare('UPDATE user SET cookieHash = ? WHERE userID = ?');
                $query->execute([$cookieHash, $row['userID']]);

                return array('status'=>true, 'user'=>$row, 'cookie'=>$cookie);
            }
        }
        return array ('status'=>false, 'message'=> 'login invalid');
    }

    public static function loginWithCookies($username, $code){
        $pdo = $GLOBALS['pdo'];

        $query = $pdo->prepare('SELECT * FROM user WHERE userName=?');
        $query->execute([$username]);

        if (!empty($query->rowCount())){
            $row = $query->fetch();
            if (password_verify($code, $row['cookieHash'])){
                return array('status'=>true, 'user'=>$row);
            }
        }
        return array ('status'=>false, 'message'=> 'login invalid');
    }
}

?>