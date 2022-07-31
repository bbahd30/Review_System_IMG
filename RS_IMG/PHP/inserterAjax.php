<?php

require_once '../PHP/requestManager.php';
$reqManager = new requestManager();
if(!isset($_SESSION))
{
    session_start();
}

if(isset($_POST['newComment']))
{
    if($_POST['newComment'] != "")
    {
        $newComment = $_POST['newComment'];
        $arrValues = 
        array
        (
            ":reqID" => $_SESSION['reqID'],
            ":member_ID" => $_SESSION['member_ID'],
            ":comment" => $newComment,
            ":type" => $_SESSION['type']
        );
        $reqManager->adder("reqID, member_ID, comment, type", ":reqID, :member_ID, :comment, :type", $arrValues,"RESPONSES");
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

    // $i = 1;
    // $arrValues2 = 
    // array
    // (
    //     ":reqID" => $_SESSION['reqID'],
    //     ":iternDesc" => $_POST['change'.$i],
    //     "Reviewer_ID" => $_SESSION['member_ID']
    // );
    // $reqManager->adder("reqID, iternDesc, Reviewer_ID",":reqID,     :iternDesc, :Reviewer_ID", $arrValues2, "ITERATIONS");
}

?>

