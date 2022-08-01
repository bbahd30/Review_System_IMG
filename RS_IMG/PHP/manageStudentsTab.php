<?php
require_once '../PHP/studentManager.php';

$sManager = new studentManager();

if(!isset($_SESSION))
{
    session_start();
}

if(!empty($_POST))
{
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sID = ($_SESSION['sNum'] + 1);
    $sManager->setArrValues($sID, $name, $username, $password);


    if(isset($_SESSION['added']))
    {
        if(!$_SESSION['added'])
        {
            $sManager->adder( $sManager->getColumns(), $sManager->getValues(), $sManager->getArrValues(), $sManager->getTable());
            
            // $stmt = $sManager->conn->prepare("INSERT INTO COMPLETED (member_ID) VALUES (". $sID . ")");
            $name = "sID" . $sID;
            $stmt = $sManager->addAColumn($name, "COMPLETE", "INT(1)");
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
unset($_SESSION['sNum']);

if(!isset($_POST['submitted']))
{
    ?>

<div class="parts" id="manageStudents">
    <div class="studTable">
        <div class="uniTable">
                <div class="head">
                    <h3>Students in IMG</h3>
                </div>
                <div class="tableDiv">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sNum = $sManager->showStudents();
                            $_SESSION['sNum'] = $sNum;
                            ?>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <div class="addStudForm">
        <h3>Add a New Member</h3>
        <form action="../PHP/manageStudentsTab.php" method="post">
            <div class="inp">
                <label for="name">
                    <input required type="text" name="name"   id="name" placeholder="Name of  the Member">
                </label>
            </div>
            <div class="inp">
                <label for="username">
                    <input required type="text"name="username"id="username" placeholder="Username of the Member">
                </label>
            </div>
            <div class="inp">
                <label for="password">
                    <input required type="text" name="password" placeholder="Password of the Member" id="password">
                </label>
            </div>
            <div class="btnDiv">
                <?php 
                if(!isset($_SESSION['added']))
                {
                    $_SESSION['added'] = 0;
                }
                ?>
                <input type="text" value="submitted"name="submitted" required readonly hidden>
                <button type="submit" class="btn"id="addBtn">Add</ button>
            </div>
        </form>
    </div>
</div>

<?php
}
else
{
    echo("Adding, Please Wait.");
}
