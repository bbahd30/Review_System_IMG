<?php
require_once '../PHP/requestManager.php';

if(!isset($_SESSION))
{
    session_start();
}

if(isset($_POST['aID'])&& isset($_POST['reqID']))
{
    $aID = $_POST['aID'];
    $_SESSION['reqID'] = $_POST['reqID'];
    $reqID = $_SESSION['reqID'];
    $reqManager = new requestManager();

    $condition = $reqManager->getJoinUpdateCondition();

    $stmt = $reqManager->conn->prepare("SELECT ROW_NUMBER() OVER (ORDER BY reqID) AS SNo, ASSIGNMENTS.aID ,reqID, aName, IternNo, Name , aDeadline FROM 
    REQUESTS JOIN ASSIGNMENTS ON ASSIGNMENTS.aID = REQUESTS.aID JOIN STUDENTS ON STUDENTS.Student_ID = REQUESTS.Student_ID WHERE $condition");
    
    $reqManager->setArrValuesMatch($aID);
    $stmt->execute($reqManager->getArrValues());

    if($stmt->rowCount() == 0)
    {
        echo("Unable to find. Try Again.");
    }
    else
    {
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $Name = $rowData[0]['Name'];
    }
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
    <link rel="stylesheet" href="../CSS/requestPage.css?v=<?php echo time(); ?>">

    <title>Document</title>
</head>
<body>
<div class="formDiv">
    <h1>
        Student's Request Profile 
    </h1>
    <div class='form'>
        <div class="inp">
            <label for="name">Submitted By</label>
            <input required type="text" name="name" readonly   id="name" value="<?php echo($Name);?>">
        </div>
        
        <div class="inp">
            <label for="aName">Requested Assignment</label>
            <input required type="text" name="aName" readonly id="assignment" value="<?php echo($rowData[0]['aName']);?>">
        </div>
        
        <div class="inp">
                <label for="aDeadline">Deadline Given</label>
                <input required type="date" readonly  name="aDeadline"   id="deadline" value="<?php echo($rowData[0]['aDeadline']);?>">
        </div>
        
        <div class="inp">
            <label for="IternNo">Iteration Number</label>
            <input required type="number" name="IternNo" id="assignment" readonly value="<?php echo($rowData[0]['IternNo']);?>">
        </div>


        <div id="commentSection">
            <?php
            require_once '../PHP/commentSection.php';
            ?>
        </div>



        <div class="inp" id="comment">
            <label for="comment">Comment</label>
                <div class="commentsTab">
                    <label class="commentInp">
                        <input type="text" placeholder="Add A Comment" id='newComment' autocomplete = 'off'>
                        <input type='button' class='actionBtnRev' id='postBtn' value='Post'>
                
                    </label>
                </div>
            </label>
        </div>
        
        <div class="inp" id="actionsInp">
            <div class="actions" id="fixMeetDiv">
                <input type='button' class='actionBtnRev' id='fixMeet' value='Get a Meeting Link' >
            </div>

            <div class='actions' id='proceed'>
                <form action='../PHP/manageItern.php' method='post'>
                    <input type='text' hidden name='proceed' value='proceed' readonly required>
                    <input type='text' hidden name='reqID' value='<?php echo($rowData[0]['reqID']); ?>' readonly required>
                    <input type='submit' class='actionBtnRev' id='proceedBtn' value='Proceed After Reviewing'>
                    </button
                </form>
            </div>
        </div>

        

    </div>

</div>




</body>
<script src="../SCRIPTS/manageRequestProfile.js"></script>
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

<?php