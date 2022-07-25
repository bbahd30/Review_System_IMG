<?php
require_once '../PHP/studentManager.php';

$sManager = new studentManager();

if(isset($_GET['sID']))
{
    $sID = $_GET['sID'];
    $sManager->setArrValuesMatch($sID);
    $rowData = $sManager->rowDataFetch( $sManager->getUpdateCondition(), $sManager->getArrValues(), $sManager->getTable());
}


if (!empty($_POST))
{
    $Name = $_POST['Name'];
    $Username = $_POST['Username'];


    // $sManager->setArrValues($sID, $name, $username, $password);

    $arrValues = array
    (
        ":Student_ID" => $sID,
        ":Name" => $Name,
        ":Username" => $Username
    );

    $sManager->updater($sManager->getTable(), $sManager->getColPair(), $sManager->getUpdateCondition(), $arrValues);

    header("location: ../PHP/ReviewerDashboard.php", true, 303);
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
                <label for="Name">Name</label>
                <input required type="text" name="Name" id="name"  
                
                value="<?php if(isset($_GET['sID']))
                {
                    echo($rowData['Name']);
                } ?>">

            </div>
            <div class="inp">
                <label for="Username">Username<label>
                <input required type="text"  name="Username"   id="Username" 
                
                value="<?php if(isset($_GET['sID']))
                {
                    echo($rowData['Username']);
                } ?>">

            </div>
         
            <div class="btnDiv">
                <button type="submit" class="btn">Update</button>
            </div>

        </form>
    </div>
</body>
</html>
