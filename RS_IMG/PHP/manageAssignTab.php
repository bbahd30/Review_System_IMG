<?php
require_once '../PHP/assignments.php';
require_once '../PHP/reviewers.php';

$aObj = new assignment();
$rev = new reviewer();

if(!empty($_POST))
{
    $aName = $_POST['aName'];
    $aDescLink = $_POST['aDescLink'];
    $aDeadline = $_POST['aDeadline'];
    
    $aID = ($_SESSION['aNum'] + 1);
    $aObj->setArrValues($aID,  $aName, $aDescLink, $aDeadline);

    if(isset($_SESSION['added']))
    {
        if(!$_SESSION['added'])
        {
            $aObj->adder( $aObj->getColumns(), $aObj->getValues(), $aObj->getArrValues(), $aObj->getTable());
            $_SESSION['added'] = 1;
        }
    }

    ?>
<html>
    <script>
        <?php if($_SESSION['added'])  ?>
        {
            setTimeout(function () 
            {
                window.location.href= '../PHP/ReviewerDashboard.php';
                once = 1;
            },900);     
        }

    </script>
</html>

    <?php
}
unset($_SESSION['aNum']);

if(!isset($_POST['submitted']))
{

    ?>

    <div class="parts" id="manageAssign">
        <!-- fetching data -->
        <div class="uniTable">
            <div class="head">
                <h3>Assignments Till Now</h3>
            </div>
            <div class="tableDiv">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Description Link</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $aNum = $rev->show();
                        $_SESSION['aNum'] = $aNum;
                        ?>                        
                    </tbody>
                </table>
            </div>
        </div>

        <div class="addForm">
            <h3>Add a New Assignment</h3>
            <form action="../PHP/manageAssignTab.php" method="post">
                <div class="inp">
                    <label for="aName">
                        <input required type="text" name="aName"    id="name" placeholder="Name of  the Assignment">
                    </label>
                </div>
                <div class="inp">
                    <label for="aDescLink">
                        <input required type="text"name="aDescLink"     id="descLink" placeholder="Add Description Link of the Assignment">
                    </label>
                </div>
                <div class="inp">
                    <label for="aDeadline">
                        <input required type="date" name="aDeadline"    id="deadline">
                    </label>
                </div>
                <div class="btnDiv">
                    <?php 
                    if(!isset($_SESSION['added']))
                    {
                        $_SESSION['added'] = 0;
                    }
                    ?>
                    <input type="text" value="submitted" name="submitted"   required readonly hidden>
                    <button type="submit" class="btn" id="addBtn">Add</ button>
                </div>
            </form>
        </div>
    </div>

<?php
}
else
{
    echo("Loading");
}