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
</div>