<?php
require_once '../PHP/requestManager.php';

if(!isset($_SESSION))
{
    session_start();
}
$reqManager = new requestManager();
?>
<div class="parts" id="requests">
    <div class="uniTable">
        <div class="head">
            <h3>Requests Made Till Now</h3>
        </div>
        <div class="tableDiv">
            <table class="table">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Name</th>
                        <th>Iteration Number</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $aNum = $reqManager->showRequests();
                    $_SESSION['aNum'] = $aNum;
                    ?>                        
                </tbody>
            </table>
        </div>
    </div>
</div>