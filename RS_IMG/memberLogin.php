<?php

if(isset($_COOKIE['sessionID']))
{
    if(isset($_COOKIE['type']))
    {
        $type = $_COOKIE['type'];

        if ($type == 1)
        {
            header("location: PHP/reviewersLoginPage.php");
        }
        else
        {
            header("location: PHP/studentsLoginPage.php");
            die();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Take your roles to login:
    <a href="PHP/studentsLoginPage.php">Students</a>
    <a href="PHP/reviewersLoginPage.php">Reviewer</a>
</body>
</html>