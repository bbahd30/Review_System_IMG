<?php
require_once '../PHP/students.php';
require_once '../PHP/studentManager.php';
require_once '../PHP/assignments.php';

if(!isset($_SESSION))
{
    session_start();
}

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

if(isset($_POST['request']) && isset($_POST['aID']))
{
    require_once '../PHP/requestManager.php';
    $reqManager = new requestManager();
    echo("<html><script>alert('req clicked')</script></html>");

    $aID = $_POST['aID'];
    $reqValue = $_POST['request'];

    if($reqValue)
    {
        $reqManager->setArrValuesMatch($aID);
        $rowData = $reqManager->rowDataFetch($reqManager->getUpdateCondition(), $reqManager->getArrValues(), $reqManager->getTable());
        
        if($rowData['IternNo'] == 0)
        {
            $reqManager->setArrValuesMatch($aID);
            $reqManager->delete($reqManager->getTable(),$reqManager->getUpdateCondition(), $reqManager->getArrValues());
        }
        else
        {
            $reqManager->setArrValues($aID, $reqValue);
            $reqManager->updater($reqManager->getTable(), $reqManager->getColPair(), $getUpdateCondition(), $getArrValues());
        }
    }
    else
    {
        $reqManager->requestForReview($aID, $reqValue);
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
                <div class = "links" id="dash">Dashboard</div>
                <!-- <div class = "links" id="adder">Manage Students</div> -->
                <div class = "links" id="assign">See Assignments</div>
                <div class = "links" id="requests">Request for Review</div>
                <div class = "links">My Profile</div>
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
                                    // echo($sManager->findLastIndex($sManager->getTable(),    $sManager->getIndex()));
                                    // index of complete table
                                ?>
                            </div>
                        </div>
                        <!-- <div id="requests" class="dataBoxes">
                            <div class="head">
                                Total Requests
                            </div>
                            <div class="data">
                                2
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="reviewSection">
                        <div class="head">
                            Students
                        </div>
                        <div class="studentsInLine">
                            <div class="dataStudent"></div>
                            <div class="profile"></div>   
                            <span class="fixed">Review Requested </ span>
                        </div>
                        <div class="studentsInLine">
                            <div class="dataStudent"></div>
                            <div class="profile"></div>   
                            <span class="fixed">Review Requested </ span>
                        </div>
                        <div class="studentsInLine">
                            <div class="dataStudent"></div>
                            <div class="profile"></div>   
                            <span class="fixed">Review Requested</  span>
                        </div>
                
                        <br>
                        Other Students
                        <div class="profiles">
                            <div class="dpStudents"></div>
                            <div class="dpStudents"></div>
                            <div class="dpStudents"></div>
                        </div>
                    </div> -->
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