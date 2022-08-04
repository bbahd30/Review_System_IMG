<?php

require_once '../PHP/databaseConnection.php';
require_once '../PHP/sessionFunctions.php';

class member extends databaseConnection
{
    // private $status = false;

    public function __construct()
    {
        databaseConnection::__construct();
    }

    public function login($conn, $table, $type, $username, $password)
    {
        $sessionTable = $table . "_SESSIONS";
        if (!isset($_SESSION))
        {
            session_start();
        }

        $var = ($type == 1) ? "Reviewer" : "Student";
        $ID = $var. '_ID';
        
        $_SESSION['username'] = $username;
              
        $stmt = $conn->prepare("SELECT * FROM $table where Username = :uname and Password = :pass;");
        $stmt->execute(array(':uname' => $username, ':pass' => $password));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        $member_ID = $rows[0][$ID];
        $_SESSION['member_ID'] = $member_ID;

        if ($stmt->rowCount() == 1)
        {
            $inStmt = $this->conn->prepare("INSERT INTO SESSIONS (Username,sessionID) VALUES (:Username, :sessionID);");
            $inStmt->execute(array(":Username" => $_POST['username'], ":sessionID" => $_COOKIE['PHPSESSID']));
            
            // FOR REMEMBERING IF NOT LOGGED OUT
            setcookie('sessionID', $_COOKIE['PHPSESSID'], time()+86400 * 30, "/");
            setcookie('type', $type, time()+86400 * 30, "/");
            $_SESSION['stat'] = true;

            header("location: ../PHP/"."$var"."Dashboard.php");
        }
        else
        {
            $_SESSION['credWrong'] = true;
            $_SESSION['die'] = true;
            $this->failed($type);
        }
    }
    
    public function autoLogin($conn, $table, $type)
    {
        if (!isset($_SESSION))
        {
            session_start();
        }

        $var = ($type == 1) ? "Reviewer" : "Student";
        $ID = $var. '_ID';
        // MATCHED SO CHECK IN THE DB
        $stmt = $conn->prepare("SELECT * FROM SESSIONS JOIN $table 
        ON SESSIONS.Username = $table.Username where  SESSIONS.sessionID = :sessionID;");
        $stmt->execute(array(":sessionID" => $_COOKIE['sessionID']));
        

        if(!isset($_SESSION['once']))
        {
            $_SESSION['once'] = false;
        }
        
        if(!isset($_COOKIE['PHPSESSID']))
        {
            $varLower = strtolower($var);
            header("location: ../PHP/" . $varLower . "sLoginPage.php");
            $_SESSION['once'] = true;
        }


        if ($stmt->rowCount() == 1)
        {
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( $rows['sessionID'] == $_COOKIE['sessionID'])
            {                
                $_SESSION['username'] = $rows['Username'];
                $_SESSION['member_ID'] = $rows[$ID];

                
                $sessionIDCookie = $_COOKIE['PHPSESSID'];
                $upStmt = $this->conn->prepare("UPDATE SESSIONS SET sessionID = :sessionID WHERE SNo = :SNo;");
                $upStmt->execute(array(":sessionID" => $sessionIDCookie, ":SNo" => $rows['SNo']));
                
                setcookie('sessionID', $sessionIDCookie , time()+86400 * 30, "/");

                $_SESSION['stat'] = true;
                header("location: ../PHP/"."$var"."Dashboard.php");
            }
        }
        else
        {
            $_SESSION['die'] = true;
            $this->failed($type);
        }
    }

    
    public function failed ($type)
    {

        $opt = (mySession::findSessVar('credWrong', 'true') == 1) ? "?invalid=1" : "";
        

        if ($type == 1)
        {
            header("location: ../PHP/reviewersLoginPage.php" . $opt);
            die();
        }
        else
        {
            header("location: ../PHP/studentsLoginPage.php" . $opt);
            die();
        }
    }

    
    public function sessionIDTaker($table, $sessionID) 
    {
        $stmt = $this->conn->prepare("SELECT * FROM SESSIONS 
        JOIN $table ON SESSIONS.Username = :table.Username WHERE sessionID = :sessionID;");
        $stmt->execute(array(":sessionID" => $sessionID, ":table" => $table));
        if($stmt->rowCount() == 1)
        {
            $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            return $rows;
        }
        else
        {
            return 0;
        }
    }
    public function statChecker($conn, $table, $type)
    {
        $var = ($type == 1) ? "Reviewer" : "Student";
        $ID = $var. '_ID';
        if(!empty($_SESSION))
        {
            // MATCHED SO CHECK IN THE DB
            $stmt = $conn->prepare("SELECT * FROM SESSIONS JOIN $table 
            ON SESSIONS.Username = $table.Username where  SESSIONS.sessionID = :sessionID;");
            $stmt->execute(array(":sessionID" => $_COOKIE['sessionID']));

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() == 1)
            {
                $_SESSION['stat'] = true;
            }
            else
            {
                $_SESSION['stat'] = false;
            }
            
        }
        else
        {
            $_SESSION['stat'] = false;
        }
    }
}