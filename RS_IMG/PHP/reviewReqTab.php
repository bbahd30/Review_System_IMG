<?php
require_once '../PHP/reviewers.php';

$rev = new reviewer();
?>
<div class="parts" id="reviewReq">
    <div class="uniTable">
        <div class="head">
            <h3>Requests Made Till Now</h3>
        </div>
        <div class="tableDiv">
            <table class="table">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Request By</th>
                        <th>Assignment</th>
                        <th>Iteration Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $aNum = $rev->showRequestToRev();
                    $_SESSION['aNum'] = $aNum;
                    ?>                        
                </tbody>
            </table>

        </div>
        </div>
</div>
