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
        if (isset($_SESSION))
        {
            session_start();
        }

        $var = ($type == 1) ? "Reviewer" : "Student";
        $ID = $var. '_ID';
        
        $_SESSION['Username'] = $username;
              
        $stmt = $conn->prepare("SELECT * FROM $table where Username = :uname and Password = :pass;");
        $stmt->execute(array(':uname' => $username, ':pass' => $password));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        $member_ID = $rows[0][$ID];
        $_SESSION['member_ID'] = $member_ID;

        if ($stmt->rowCount() == 1)
        {
            // AS IT WAS FIRST TIME NEED TO GIVE IT A TOKEN IN SESSIONS TABLE AND NO NEED TO CHECK THE TOKEN
            
            $token = self::generateRandom();
            $_SESSION['token'] = $token;
            
            // STORE THE INFO OF HIS LOGIN IN PHP AND REDIRECT
            
            self::storeInDb($conn, $member_ID, $token, $type, $sessionTable);
            setcookie('token', $token, time()+(86400) * 30, "/");
            setcookie('Username', $username, time()+86400 * 30, "/");
            
            $_SESSION['stat'] = true;

            header("location: ../PHP/"."$var"."Dashboard.php");
        }
        else
        {

            // echo("<html><script>alert('hello')</script></html>");
            $_SESSION['credWrong'] = true;
            $_SESSION['die'] = true;
            $this->failed($type);

        }
    }
    
    public function autoLogin($conn, $table, $type)
    {

        $sessionTable = $table . "_SESSIONS";
        if (!isset($_SESSION))
        {
            session_start();
        }

        $var = ($type == 1) ? "Reviewer" : "Student";
        $ID = $var. '_ID';

        if(isset($_SESSION['token']))
        {
            // CHECK FOR COOKIES
            if ($_SESSION['token'] == $_COOKIE['token'])
            {
                // MATCHED SO CHECK IN THE DB
                $stmt = $conn->prepare("SELECT * FROM $sessionTable JOIN    $table on $sessionTable.member_ID = $table.$ID where   member_ID = :member_ID and token = :token;");
                $stmt->execute(array(':member_ID' => $_SESSION  ['member_ID'], ':token' => $_SESSION['token']));

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($stmt->rowCount() == 1)
                {
                    // ALSO IN DB SO GIVE NEW VALUES TO COOKIES AND IN  TABLE

                    // SAME USER LOGGED IN SO GENERATE NEW TOKEN
                    $token = self::generateRandom();
                    $_SESSION['token'] = $token;
                    // $_COOKIE['token'] = $token;
                    setcookie('token', $token, time()+86400 * 30, "/");

                    // STORE THE INFO OF HIS LOGIN IN PHP AND REDIRECT
                    self::storeInDb($conn, $_SESSION['member_ID'],  $token, $type, $sessionTable);

                    $_SESSION['stat'] = true;
                    header("location: ../PHP/"."$var"."Dashboard.php");
                }
                else
                {
                    // echo("<html><script>alert('hello2')</script></   html");

                    // AS THE SESSION IS ON, BUT THE COOKIES ARE WRONG SO THE MAN IS DIFFERENT
                    // COOKIES HAI PR GALAT HAI

                    // $_SESSION['wrongCookies'] = true;
                    setcookie('token', $token, time()-(86400) * 30, "/");
                    setcookie('Username', $username, time()-86400 * 30, "/");
                    
                    $_SESSION['die'] = true;
                    $this->failed($type);
                }
            }
            else
            {
                // MAY BE SOME OTHER USER USING THE SAME PC SO COOKIES OF OTHER PRESENT, SO CAN'T DESTROY COOKIES AS NEEDED FOR THE PREV USER, 
                // uniqToEnsureThatCookiesAreNotHis

                session_destroy();
                setcookie('token', $token, time()-(86400) * 30, "/");
                setcookie('Username', $username, time()-86400 * 30, "/");
                // will have died false, so just returned to that page let do this work
                

                $this->failed($type);
            }
        }
        else
        {
            // SESSION SET NHI HAI ..BAD ME AAYA HAI PR COOKIES PRESENT HAI 
            // SECURITY KE LIYE DELETE KR DIYA COOKIES TAKI KOI AUR NA ACCESS KRLE
            // SO DELELTE COOKIES BAD ME AAO TO LOGIN HI KRNA PADEGA




            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            // Need to edit this for remember me by bringing a cookie, if present, then don't delete cookie if that new cookie present then don't check for session and directly check in db

            // create some other function
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            setcookie('token', $token, time()-(86400) * 30, "/");
            setcookie('Username', $username, time()-86400 * 30, "/");
            $this->failed($type);
            // NEED TO DELETE COOKIES AS WELL AS AFTER ONE LOGS IN WE ARE GENERATING COOKIE AND SESSION VARIABLE FOR HIS PRESENCE, BUT AS THE ONE LOG OUT, SESSION AND COOKIES DELETE NO INFO NEED TO LOGIN AGAIN, BUT TILL HE CLOSES THE BROWSER, SESSION ON SO EVEN IF CLOSES THE TAB CAN ACCESS DUE TO THE COOKIES AND SESSIONS.
        }     
    }
    
    public function failed ($type)
    {
        // echo($_SESSION['credWrong']);

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
    public static function generateRandom()
    {
        return bin2hex(random_bytes(16));
    }

    public static function storeInDb($conn, $member_ID, $token, $type, $sessionTable)
    {
        $conn->prepare("DELETE FROM $sessionTable WHERE member_ID = :member_ID AND type = :type;")->execute(array(':member_ID' => $member_ID, ':type' => $type));
        
        $sql = "INSERT INTO $sessionTable (member_ID, token, type) VALUES (:member_ID, :token, :type);";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(':member_ID' => $member_ID, ':token' => $token, ':type' => $type));
    }     
    
    public function statChecker($conn, $table, $type)
    {
        if (isset($_COOKIE['Username']) && isset($_COOKIE['token']))
        {
            $sessionTable = $table . "_SESSIONS";
            if (!isset($_SESSION))
            {
                session_start();
            }
            $var = ($type == 1) ? "Reviewer" : "Student";
            $ID = $var. '_ID';

            if(!empty($_SESSION))
            {
                // CHECK FOR COOKIES
                if ($_SESSION['token'] == $_COOKIE['token'])
                {
                    // MATCHED SO CHECK IN THE DB
                    $stmt = $conn->prepare("SELECT * FROM $sessionTable     JOIN    $table on $sessionTable.member_ID = $table. $ID where   member_ID = :member_ID and token = :token;   ");
                    $stmt->execute(array(':member_ID' => $_SESSION      ['member_ID'], ':token' => $_SESSION['token']));

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
?>