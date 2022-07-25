<?php
require_once '../PHP/assignments.php';

$aObj = new assignment();

if(isset($_GET['aID']))
{
    $aID = $_GET['aID'];
    $aObj->setArrValuesMatch($aID);
    $rowData = $aObj->rowDataFetch( $aObj->getUpdateCondition(), $aObj->getArrValues(), $aObj->getTable());
}


if (!empty($_POST))
{
    $aName = $_POST['aName'];
    $aDescLink = $_POST['aDescLink'];
    $aDeadline = $_POST['aDeadline'];

    $aObj->setArrValues($aID, $aName, $aDescLink, $aDeadline);
    $aObj->updater($aObj->getTable(), $aObj->getColPair(), $aObj->getUpdateCondition(), $aObj->getArrValues());
    header("location: ../PHP/ReviewerDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/edit.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>
<body>
    <div class="formDiv">
        <h1>Edit Assignment Details</h1>
        <form action="" method="post">
            <div class="inp">
                <label for="aName">Name</label>
                <input required type="text" name="aName" id="name"  
                
                value="<?php if(isset($_GET['aID']))
                {
                    echo($rowData['aName']);
                } ?>">

            </div>
            <div class="inp">
                <label for="aDescLink">Description Link<label>
                <input required type="text"  name="aDescLink"   id="descLink" 
                
                value="<?php if(isset($_GET['aID']))
                {
                    echo($rowData['aDescLink']);
                } ?>">

            </div>
            <div class="inp">
                <label for="aDeadline">Deadline</label>
                <input required type="date"  name="aDeadline"   id="deadline"
                
                value="<?php if(isset($_GET['aID']))
                {
                    echo($rowData['aDeadline']);
                } ?>">
            </div>
            <div class="btnDiv">
                <button type="submit" class="btn">Update</button>
            </div>

        </form>
    </div>
</body>
</html>
