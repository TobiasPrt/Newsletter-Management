<?php
// Skript das den Login authentifiziert

session_start();

//Database Connection conn
class Dbh {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    
    protected function connect() {
        $this->servername = '******';
        $this->username = '******';
        $this->password = '******';
        $this->dbname = '******';


        $mysqli = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname);
        return $mysqli;
    }
}

//Logik der admlog-Seite

//Daten abgreifen
class User extends Dbh {
   
    protected function getAllUsers() {
        $sql = "SELECT * FROM admin";
        $result = $this->connect()->query($sql);
        $numRows = $result->num_rows;

        if($numRows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }
}

//Credentials prüfen
class LogCheck extends User {
   
    public function checkCred() {
        $datas = $this->getAllUsers();
        $logcheck = false;
        foreach($datas as $data){
            if($_POST['username'] == $data['admin_name'] && password_verify($_POST['passwort'], $data['admin_pw'])){
                $logcheck = true;
                $_SERVER['name'] = $data['admin_name'];
            }
        }
        if($logcheck != false){
            $_SESSION['username'] = $_SERVER['name'];
            header('location: ../sites/list.php');
        }
        else
        {
            echo "Falscher Benutzername oder Passwort<br>";
            echo '<a href="../index.php">Zurück zum Login</a>';
        }
    }
}

//Ausführung
$users = new LogCheck();
$users->checkCred();
?>