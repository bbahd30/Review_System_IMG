<?php
require_once '../PHP/studentManager.php';
require_once '../PHP/assignments.php';
$aObj = new assignment();
$sManager = new studentManager();

?>
<div class="parts" id="dashboard">
                    
    <div class="dataHead">
        <div id="totalAssign" class="dataBoxes">
            <div class="head">
                Total Assignments
            </div>
            <div class="data">
                <?php
                    $aObj = new assignment();
                    echo($aObj->findLastIndex($aObj->getTable(), $aObj->getIndeX()));
                ?>
            </div>
        </div>
        <div id="students" class="dataBoxes">
            <div class="head">
                Total Done
            </div>
            <div class="data">
                <?php
                    $sManager = new studentManager();
                    // echo($sManager->findLastIndex($sManager->getTable(),    $sManager->getIndex()));
                    // index of complete table
                ?>
            </div>
        </div>
        <!-- <div id="requests" class="dataBoxes">
            <div class="head">
                Total Requests
            </div>
            <div class="data">
                2
            </div>
        </div> -->
    </div>
    <!-- <div class="reviewSection">
        <div class="head">
            Students
        </div>
        <div class="studentsInLine">
            <div class="dataStudent"></div>
            <div class="profile"></div>   
            <span class="fixed">Review Requested </ span>
        </div>
        <div class="studentsInLine">
            <div class="dataStudent"></div>
            <div class="profile"></div>   
            <span class="fixed">Review Requested </ span>
        </div>
        <div class="studentsInLine">
            <div class="dataStudent"></div>
            <div class="profile"></div>   
            <span class="fixed">Review Requested</  span>
        </div>
                
        <br>
        Other Students
        <div class="profiles">
            <div class="dpStudents"></div>
            <div class="dpStudents"></div>
            <div class="dpStudents"></div>
        </div>
    </div> -->
                </div>