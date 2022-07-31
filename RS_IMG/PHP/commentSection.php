<?php
require_once '../PHP/requestManager.php';
$reqManager = new requestManager();

if(!isset($_SESSION))
{
    session_start();
}

$tableType = $_SESSION['type'];
?>

    <h3>Conversations</h3>
    <?php


        $stmt = $reqManager->conn->prepare("SELECT REQUESTS.reqID AS reqID, type, comment, RESPONSES.member_ID AS member_ID FROM 
        RESPONSES 
        JOIN REQUESTS ON RESPONSES.reqID = REQUESTS.reqID 
        WHERE REQUESTS.reqID = :reqID ORDER BY responseID");
        $stmt->execute(array
        (
            ":reqID" => $_SESSION['reqID']
        ));

        if($stmt->rowCount() == 0)
        {
            echo("<div class='nothing'>No conversations till now, comment below to begin.</div>");
        }
        else
        {   
            while($rows = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $tableType = $rows['type'];
                $member_ID = $rows['member_ID'];

                if($tableType == $_SESSION['type'] && $member_ID == $_SESSION['member_ID'])
                {
                    // sender itself
                    ?>

                    <div class="msgBox" id="sending">
                        <div class="msg">

                            <?php
                            echo($rows['comment']);
                            ?>
                        </div>
                        <div class="time">
                            12:30pm
                        </div>
                    </div>
<?php
                }
                else
                {
                    $ID = ($tableType == "REVIEWERS") ? "Reviewer_ID" : "Student_ID";
                    ?>
                    <div class="receiveMsgData">
                        <div class="pImg">
                            <img src="../IMAGES/rev1.png"  alt="">
                        </div>
                        <div class="msgBox" id="receiving">
                            <div class="msg">

                                <div class="time">
                                    12:30pm
                                </div>
                                <div class="senderName">
                                    <?php
                                        $stmt2 = $reqManager->conn->prepare("SELECT Username FROM $tableType WHERE $ID = $member_ID;");
                                        $stmt2->execute();
                                        $username = $stmt2->fetch(PDO::FETCH_ASSOC);
                                        echo($username['Username']);
                                    ?>
                                </div>
                                <div class="sentMsg">
                                    <?php
                                    echo($rows['comment'])
                                    ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
<?php

                }
                

            }
            
        }


?>
