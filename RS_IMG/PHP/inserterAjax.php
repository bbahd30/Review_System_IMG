<?php

require_once '../PHP/requestManager.php';
$reqManager = new requestManager();
if(!isset($_SESSION))
{
    session_start();
}

if(isset($_POST['newComment']) && isset($_POST['time']))
{
    if($_POST['newComment'] != "")
    {
        $newComment = $_POST['newComment'];
        $time = $_POST['time'];
        $arrValues = 
        array
        (
            ":reqID" => $_SESSION['reqID'],
            ":member_ID" => $_SESSION['member_ID'],
            ":comment" => $newComment,
            ":type" => $_SESSION['type'],
            ":time" => $time
        );
        $reqManager->adder("reqID, member_ID, comment, type, Time", ":reqID, :member_ID, :comment, :type, :time", $arrValues,"RESPONSES");
    }
}
 
// *****************************
// ITERN INSERETER


if(isset($_POST['iternNumNow']))
{
    
    $reqID = $_SESSION['reqID'];

    $updatedIternNum = (int)$_POST['iternNumNow'] + 1;
    $arrValues = 
    array
    (
        ":IternNo" => (int)$updatedIternNum,
        ":reqID" => $reqID
    );
    $reqManager->updater($reqManager->getTable(), "IternNo = :IternNo", "reqID = :reqID", $arrValues);
}

if(isset($_POST['stat']))
{
    if($_POST['stat'] == "passed")
    {
        // update completed table
        $stmt = $reqManager->conn->prepare("SELECT * FROM REQUESTS WHERE reqID = :reqID;");
        $reqID = $_SESSION['reqID'];
        $stmt->execute(array(":reqID" => $reqID));
        $assignInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        $updateStmt = $reqManager->conn->prepare("UPDATE COMPLETED SET aID". $assignInfo['aID']. "= 1 WHERE member_ID = ". $assignInfo['Student_ID']. ";");
        echo("UPDATE COMPLETED SET aID". $assignInfo['aID']. "= 1 WHERE member_ID = ". $assignInfo['Student_ID']. ";");
        $updateStmt->execute();

    }
}
?>

