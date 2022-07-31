<?php
require_once '../PHP/studentManager.php';
require_once '../PHP/assignments.php';
require_once '../PHP/requestManager.php';

if(!isset($_SESSION))
{
    session_start();
}
$_SESSION['type'] = "REVIEWERS";

if(isset($_POST['logout']))
{
    session_destroy();
    header("location: ../PHP/reviewersLoginPage.php", true, 303);
    exit();
}
?>

<!-- ********************* -->
<html>
    <script>
        window.onload = function()
        {
            // CHECK STATUS
            <?php
                require_once '../PHP/reviewers.php';
                $rev = new reviewer();
                $rev->statChecker($rev->conn, $rev->getTable(), $rev->getType());
            ?>
        };
    </script>
</html>
<!-- ********************* -->

<?php
if(isset($_POST['delete']))
{
    if(isset($_POST['aID']))
    {
        require_once '../PHP/assignments.php';
        $aID = $_POST['aID'];
        $aObj = new assignment();
        $aObj->deleteValidation($aID, $aObj);
    }

    if(isset($_POST['sID']))
    {
        require_once '../PHP/studentManager.php';
        $sID = $_POST['sID'];
        $sManager = new studentManager();
        $sManager->deleteValidation($sID, $sManager);
    }
    header('Location: ../PHP/ReviewerDashboard.php', true, 303);
    exit;
}
if(isset($_SESSION['added']))
{
    unset($_SESSION['added']);
}


if (isset($_SESSION['stat']))
{
    $stat = $_SESSION['stat'];
    if(!$stat)
    {
        $_SESSION['die'] = true;
        header("location: ../PHP/reviewersLoginPage.php");
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
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/Dashboard.css?v=<?php echo time(); ?>">
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
                    echo($_SESSION['Username'] . "<br>Reviewer");
                ?>
            </div>
        </div>
        <div id="linksMenu" class= "quickLinksDiv">
            <span class="menu">Menu</span>
                <div class = "links active" id="dash">Dashboard</div>
                <div class = "links" id="adder">Manage Students</div>
                <div class = "links" id="assign">Manage Assignments</div>
                <div class = "links" id="reviews">Review Requests</div>
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
                                Total Students
                            </div>
                            <div class="data">
                                <?php
                                    $sManager = new studentManager();
                                    echo($sManager->findLastIndex($sManager->getTable(),    $sManager->getIndex()));
                                ?>
                            </div>
                        </div>
                        <div id="requests" class="dataBoxes">
                            <div class="head">
                                Total Requests
                            </div>
                            <div class="data">
                                <?php
                                    $reqManager = new requestManager();
                                    echo($reqManager->findEntriesNum($reqManager->getTable()));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="reviewSection">
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
                    </div>
                </div>
            
            </div>

            <div class="rightPanel">
                    <div class="headPurpose">
                        Schedule a Meet with other Reviewers
                    </div>
                    <div class="image">
                        <img src="" alt="">
                    </div>
                    <div class="otherReviewers">
                        <div class="reviewers">
                            Mrinal +
                            <!-- Need to fetch from the reviewers array -->
                        </div>
                        <div class="reviewers"></div>
                        <div class="reviewers"></div>
                        <div class="reviewers"></div>
                    </div>
            </div>

        </div>
    </div>

</div>

</body>
<script src="../SCRIPTS/ReviewerDashboard.js"></script>
</html>

<?php
    }
}
else
{
    $_SESSION['die'] = true;
    header("location: ../PHP/reviewersLoginPage.php");
}
?>