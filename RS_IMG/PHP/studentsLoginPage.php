<?php
require_once "../PHP/students.php";

if (isset($_GET['invalid']))
{
    $invalid = $_GET['invalid'];
}

$stud = new student();
if (!isset($_SESSION))
{
    session_start();
}

if (isset($_SESSION['die']))
{
    if (!$_SESSION['die'])
    {
        // IF CORRECT COOKIES THEN ONLY ASK FOR COOKIES
        if (isset($_COOKIE['token']) && isset($_COOKIE['Username']))
        {
            $stud->autoLogin($stud->conn, $stud->getTable(), $stud->getType());
        }
        else 
        {
            if(!empty($_POST))
            {
                $username = $_POST['username'];
                $password = $_POST['pass']; 
                $stud->login($stud->conn, $stud->getTable(), $stud->getType(), $username, $password); 
            }
        }
    }
}
else
{
    if (isset($_COOKIE['token']) && isset($_COOKIE['Username']))
    {
        $stud->autoLogin($stud->conn, $stud->getTable(), $stud->getType());
    }
    else 
    {
        if(!empty($_POST))
        {
            $username = $_POST['username'];
            $password = $_POST['pass']; 
            $stud->login($stud->conn, $stud->getTable(), $stud->getType(), $username, $password); 
        }
    }
}

$_SESSION['die'] = false;
$_SESSION['credWrong'] = false;


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/loginPage.css?v=<?php echo time(); ?>">
</head>
<body>
   <div class="container">
        <div class="head">
            <h1>Welcome Students</h1>
        </div>

        <div class="foot">
            <div class="image">
                <img src="../IMAGES/students.svg" alt="">
            </div>
            <div class="formDiv">
                <form action="" method="POST">
                    <div class="form">
                        <div class="inp">
                            <label>
                                <input required type="text" name="username" id="username" placeholder = "Username" class="fieldInp">
                            </label>
                        </div>
                        <div class="inp">
                            <label>
                                <input required type="text" name="pass" id="pass" placeholder = "Password" class="fieldInp">
                            </label>
                        </div>
                        <div class="error">
                            <?php 
                            if (!empty($_GET))
                            {
                                if ($invalid == 1)
                                {
                                    echo("Incorrect Credentials, Try again.");
                                }
                            }
                            ?>
                        </div>
                        <div class="btnDiv">
                            <button type="submit" class="btn">Login</button>
                        </div>
                        <div class="link">
                            <a href="reviewersLoginPage.php"><nobr>Login As Reviewer</nobr></a>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
   </div>

</body>
</html>