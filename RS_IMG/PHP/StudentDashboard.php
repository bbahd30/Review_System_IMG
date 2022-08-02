<?php
require_once '../PHP/students.php';
require_once '../PHP/studentManager.php';
require_once '../PHP/assignments.php';

if(!isset($_SESSION))
{
    session_start();
}
$_SESSION['type'] = "STUDENTS";

if(isset($_POST['logout']))
{
    session_destroy();
    header("location: ../PHP/studentsLoginPage.php", true, 303);
    exit();
}
?>

<html>
    <script>
        window.onload = function()
        {
            // CHECK STATUS
            <?php
                $stud = new student();
                $stud->statChecker($stud->conn, $stud->getTable(), $stud->getType());
            ?>
        };
    </script>
</html>

<?php

if(isset($_POST['delete']))
{
    if(isset($_POST['reqID']))
    {
        require_once '../PHP/requestManager.php';
        $reqID = $_POST['reqID'];
        $reqManager = new requestManager();
        
        try
        {
            $reqManager->setReqIDValues($reqID);
            $reqManager->delete($reqManager->getTable(), $reqManager->getReqIDCondition(), $reqManager->getArrValues());
        }
        catch(PDOException $e)
        {
            echo("<html><script>alert('Unable to remove the request as it is now under review'" . $e->getMessage() . ")</script></html>");
        }
    }
    header('Location: ../PHP/StudentDashboard.php', true, 303);
    exit;
}

if(isset($_POST['request']) && isset($_POST['aID']))
{
    require_once '../PHP/requestManager.php';
    $reqManager = new requestManager();

    $aID = $_POST['aID'];
    $reqValue = $_POST['request'];

    try
    {
        if($reqValue)
        {
            $reqManager->setArrValuesMatch($aID);
            $rowData = $reqManager->rowDataFetch($reqManager->getUpdateCondition(),     $reqManager->getArrValues(), $reqManager->getTable());

            if($rowData['IternNo'] == 0)
            {
                $reqManager->setArrValuesMatch($aID);
                $reqManager->delete($reqManager->getTable(),$reqManager->getUpdateCondition(),  $reqManager->getArrValues());
            }
            else
            {
                $reqManager->setArrValues($aID, $reqValue);
                $reqManager->updater($reqManager->getTable(), $reqManager->getColPair(),    $getUpdateCondition(), $getArrValues());
            }
        }
        else
        {
            $reqManager->requestForReview($aID, $reqValue);
        }
    }
    catch(PDOException $e)
    {
        header("location: ../PHP/StudentDashboard.php", true, 303);
        exit();
    }
    

    header("location: ../PHP/StudentDashboard.php", true, 303);
    exit();
}

if (isset($_SESSION['stat']))
{
    $stat = $_SESSION['stat'];
    if(!$stat)
    {
        $_SESSION['die'] = true;
        // remove above one if not working
        header("location: ../PHP/studentsLoginPage.php");
        die();
    }
    else
    {
        ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Dashboard.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>
<body>


<div class="container">

    <div class="quickLinks">
        <div id="account" class= "quickLinksDiv">
            <div class="dp">
                <img src="" alt="">
            </div>
            <div class="nameInfo">
                <?php
                    echo($_SESSION['Username'] . "<br>Student");
                ?>
            </div>
        </div>
        <div id="linksMenu" class= "quickLinksDiv">
            <span class="menu">Menu</span>
                <div class = "links active" id="dash">Dashboard</div>
                <div class = "links" id="assign">See Assignments</div>
                <div class = "links" id="requests">Request for Review</div>
                
                <form action="" method="post">
                    <input type="text" name="logout" readonly value="logout" hidden>
                    <button type = "submit">
                        <div class="linksBtn" id="logOut">
                            Log Out
                        </div>
                    </button>
                </form>

        </div>
    
    </div>

    <div class="mainPage">

        <div class="nav">
            <div class="greeting"><h1>
                            Hello <?php
                                    echo($_SESSION['Username']);
                                    ?></h1>
            </div>
        </div>

        <div class="belowNav">

            <div class="mainContainer">

                <div class="parts" id="dashboard">
                    
                    <div class="dataHead">
                        <div id="totalAssign" class="dataBoxes">
                            <div class="head">
                                Total Assignments
                            </div>
                            <div class="data">
                                <?php
                                    $aObj = new assignment();
                                    echo($aObj->findLastIndex($aObj->getTable(), $aObj->getIndeX()));
                                ?>
                            </div>
                        </div>
                        <div id="students" class="dataBoxes">
                            <div class="head">
                                Total Done
                            </div>
                            <div class="data">
                                <?php
                                    $sManager = new studentManager();
                                    $stmt = $sManager->conn->prepare("select count(*) as TOTAL_DONE FROM COMPLETE WHERE sID". $_SESSION['member_ID']." = 1 GROUP BY sID" . $_SESSION['member_ID'].";");
                                    
                                    $stmt->execute();
                                    if($stmt->rowCount() > 0)
                                    {
                                        $info = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo($info['TOTAL_DONE']);
                                    }
                                    else
                                    {
                                        echo("0");
                                    }                                
                                    ?>
                            </div>
                        </div>
       
                    </div>
                    
                </div>
            
            </div>

            <div class="rightPanel">
                    <div class="headPurpose">
                        Deadlines to Meet
                    </div>
                    <div class="image">
                        <img src="" alt="">
                    </div>
                    
            </div>

        </div>
    </div>

</div>
</body>
<script src="../SCRIPTS/StudentDashboard.js"></script>

</html>



<?php
    }
}
else
{
    // correct
    header("location: ../PHP/studentsLoginPage.php");
    die();
}
?>