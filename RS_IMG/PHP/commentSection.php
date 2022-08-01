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


        $stmt = $reqManager->conn->prepare("SELECT REQUESTS.reqID AS reqID, HOUR(`Time`) AS hour, MINUTE(`Time`) AS min, type, comment, RESPONSES.member_ID AS member_ID FROM 
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
                            $meetLink = "https://meet.google.com/";
                            if(strpos($rows['comment'], $meetLink) !== false)
                            {
                                $comment = $rows['comment'];
                                $show = explode(": ", $comment, 2);
                                $showLink = explode(" ", $show[1], 2);
                                $realLink = $showLink[0];
                                $splitNum = strpos($comment, $realLink);
                                echo($show[0]. ": <a href = ". $realLink ." >". $realLink."</a> ". $showLink[1]);
                            }
                            else
                            {
                                echo($rows['comment']);
                            }
                            ?>
                        </div>
                        <div class="time">
                        <?php
                            echo($rows['hour'].":".$rows['min']);
                            ?>
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
                                    <?php
                                    echo($rows['hour'].":".$rows        ['min']);
                                    ?>
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
