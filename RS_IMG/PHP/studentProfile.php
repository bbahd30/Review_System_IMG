<?php
require_once '../PHP/assignments.php';
require_once '../PHP/requestManager.php';

$aObj = new assignment();
$reqManager = new requestManager();

$stmt = $reqManager->conn->prepare("SELECT * FROM 
STUDENTS 
JOIN REQUESTS ON STUDENTS.Student_ID = REQUESTS.Student_ID
JOIN ASSIGNMENTS ON REQUESTS.aID = ASSIGNMENTS.aID;");
$stmt->execute();
$rowData = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_GET['sID']))
{
    $sID = $_GET['sID'];
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
        <h1>Bhoomi's Profile</h1>
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
        
            

            <div class="btnDiv">
                <button type="submit" class="btn"><a href="../PHP/ReviewerDashboard.php">Close</a></button>
            </div>

        </form>
    </div>
</body>
</html>