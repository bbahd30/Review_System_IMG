<?php
require_once '../PHP/reviewers.php';
$rev = new reviewer();
require_once '../PHP/requestManager.php';
$reqManager = new requestManager();

$rev->statChecker($rev->conn, $rev->getTable(), $rev->getType());

$reqID = $_SESSION['reqID'];
$stmt = $rev->conn->prepare("SELECT * FROM 
RESPONSES JOIN REQUESTS ON RESPONSES.reqID = REQUESTS.reqID WHERE RESPONSES.reqID = :reqID");
$stmt->execute(array(":reqID" => $reqID));
$rowData = $stmt->fetch(PDO::FETCH_ASSOC);

echo(var_dump($_POST)); 

$changesGiven = (int)count($_POST) - 2;
echo($changesGiven);
while($changesGiven > 0)
{
    $arrValues2 = 
    array
    (
        ":reqID" => $_SESSION['reqID'],
        ":iternDesc" => $_POST['change'.$changesGiven],
        "Reviewer_ID" => $_SESSION['member_ID']
    );
    $reqManager->adder("reqID, iternDesc, Reviewer_ID",":reqID,     :iternDesc, :Reviewer_ID", $arrValues2, "ITERATIONS");
    $changesGiven--;
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
                <label for="iternNum">Iteration Number</label>
                <input required type="text" name="iternNum" id="iternNum" readonly 
                
                value="<?php echo($rowData['IternNo']); ?>">

            </div>

            <div class="inp">
                <label for="aName">Are there any iterations to be made?</label>
                <div class="check">
                    <label class="checkbox">
                        Yes
                        <input type="radio" name="iternPresent" required id='yes' value='yes'>
                    </label>
                    
                    <label class="checkbox">
                        No
                        <input type="radio" name="iternPresent" id='no' value='no' >
                    </label>
                </div>
            </div>
            
            <div class="inp makeInvisible" id='itern'>
                <div id="addItern">
                    <label for="aName"><h2>Add Iterations</h2></label>

                    <div class="itern">
                        <label for="change1">Add Change 1</label>
                        <input type="text" name="change1" placeholder="Change 1" class='newItern'  id='change1' autocomplete = 'off'>
                    </div>

                </div>

                <div class="btnDiv"  id='moreBtn'>
                    <input type="button" id='addBtn' class="btn" value='Add more'>
                    <input type="button" id='removeBtn' class="btn" value='Remove'>
                </div>
            </div>

            <div class="btnDiv">
                <div class="btn" id='closeBtn'>
                    <a href="../PHP/ReviewerDashboard.php" >Close</a>
                </div>
                <button type="submit" id='submitBtn' class="btn">Update</button>
            </div>

        </form>
    </div>
</body>

<script src="../SCRIPTS/manageItern.js"></script>
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
