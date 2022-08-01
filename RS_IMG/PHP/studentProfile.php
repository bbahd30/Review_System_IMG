<?php
require_once '../PHP/assignments.php';
require_once '../PHP/requestManager.php';

$aObj = new assignment();
$reqManager = new requestManager();

$stmt = $reqManager->conn->prepare("SELECT STUDENTS.Name as Name FROM 
STUDENTS 
JOIN REQUESTS ON STUDENTS.Student_ID = REQUESTS.Student_ID
JOIN ASSIGNMENTS ON REQUESTS.aID = ASSIGNMENTS.aID WHERE REQUESTS.Student_ID = :sID;");
if(isset($_GET['sID']))
{
    $sID = $_GET['sID'];
}
$stmt->execute(array(":sID" => $sID));
$rowData = $stmt->fetch(PDO::FETCH_ASSOC);

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
        <h1><?php echo($rowData['Name']); ?>'s Profile</h1>
        <form action="" method="post">
            <div class="inp">
                <label for="aName">Name</label>
                <input required type="text" name="aName" id="name"  readonly
                
                value="<?php if(isset($_GET['sID']))
                {
                    echo($rowData['Name']);
                } ?>">

            </div>

            <div class="inp">
                <label for="aDescLink">Total Assignments<label>
                <input required type="text"  name="aDescLink" readonly id="descLink" 
                
                value="<?php
                            echo($aObj->findLastIndex($aObj->getTable(), $aObj->getIndeX()));
                        ?>">

            </div>

            <div class="inp">
                <label for="aDescLink">Total Assignments Done<label>
                <input required type="text"  name="done" readonly id="done" 
                
                value="<?php
                            $stmt = $reqManager->conn->prepare("select count(*) as TOTAL_DONE FROM COMPLETE WHERE sID". $_SESSION['member_ID']." = 1 GROUP BY sID" . $_SESSION['member_ID'].";");
                            $stmt->execute();
                            $info = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo($info['TOTAL_DONE']);
                        ?>">

            </div>
        
            

            <div class="btnDiv">
                <button type="submit" class="btn"><a href="../PHP/ReviewerDashboard.php">Close</a></button>
            </div>

        </form>
    </div>
</body>
</html>