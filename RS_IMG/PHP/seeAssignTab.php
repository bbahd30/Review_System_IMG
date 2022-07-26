<?php
require_once '../PHP/requestManager.php';

$reqManager= new requestManager();

?>
<div class="parts" id="seeAssign">
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
                    $aNum = $reqManager->showAssign();
                    $_SESSION['aNum'] = $aNum;
                    ?>                        
                </tbody>
            </table>
        </div>
    </div>
</div>
