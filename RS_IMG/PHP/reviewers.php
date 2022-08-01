<?php
require_once '../PHP/member.php';

class reviewer extends member
{
    private $table = "REVIEWERS";
    private $type = 1;

    public function getTable()
    {
        return $this->table;
    }
    public function getType()
    {
        return $this->type;
    }
    public function __construct()
    {
        parent::__construct();
    }
    public function showAssigntoRev()
    {
        $stmt = $this->conn->prepare("SELECT * FROM ASSIGNMENTS;");
        $stmt->execute();
        if($stmt->rowCount() == 0)
        {
            echo("No Assignments Added.");
        }
        else
        {
            while($rows = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $newRow = 
                "
                <tr>
                <td>" . $rows['aID'] . "</td>
                        <td>" . $rows['aName'] . "</td>
                        <td> 
                            <a href='" . $rows['aDescLink'] . "'>" . $rows['aName'] . "'s Assignment Link</a> </td>
                        <td>" . $rows['aDeadline'] . "</td>
                        <td class='actionsCol'>
                       
                            <div class='actions' id='edit'>
                                <a href='editAssign.php?aID=" . $rows['aID'] . "'>Edit</a>
                                </div>
                                <div class='actions' id='Delete'>
                                
                                <form action='../PHP/ReviewerDashboard.php' method='post'>
                                <input type='text' hidden name='delete' value='delete' readonly required>
                                <input type='text' hidden name='aID' value='" . $rows['aID'] . "' readonly required>
                                    <button type='submit' class='actionBtn'>
                                    Remove
                                    </button>
                                </form>
                                
                            </div>
                        </td> 
                    </tr>
                    ";
                    echo($newRow);
            }
        }
        return $stmt->rowCount();
    }

    public function showRequestToRev()
    {
        $stmt = $this->conn->prepare("SELECT ROW_NUMBER() OVER (ORDER BY reqID) AS SNo, ASSIGNMENTS.aID as aID,reqID , Name, aName, IternNo, STUDENTS.Student_ID as sID, aDeadline FROM 
        REQUESTS 
        JOIN ASSIGNMENTS ON ASSIGNMENTS.aID = REQUESTS.aID JOIN STUDENTS ON STUDENTS.Student_ID = REQUESTS.Student_ID;");

        $stmt->execute();

        if($stmt->rowCount() == 0)
        {
            echo("No Requests Made.");
        }
        else
        {
            while($rows = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $nStmt = $this->conn->prepare("SELECT sID". $rows['sID'] ." AS aIDStat FROM COMPLETE WHERE assignID = 'aID". $rows['aID'] . "';");

                $nStmt->execute();
                $info = $nStmt->fetch(PDO::FETCH_ASSOC);

                if($info['aIDStat'] == 1)
                {
                    $sumbitStat = "<input class='actionBtnRev passedInp'   id='passed' value='Approved' readonly>";
                }
                else
                {
                    $sumbitStat = "";
                }

                $newRow = 
                "
                <tr>
                    <td>" . $rows['SNo'] . "</td>
                    <td>" . $rows['Name'] . "</td>
                    <td>" . $rows['aName'] . "</td>
                    <td>" . $rows['IternNo'] . "</td>

                    <td class='actionsCol'>
                        <div class='actions' id='View'>
                            <form action='../PHP/manageRequestProfile.php' method='post'>
                                <input type='text' name='aID' required hidden readonly value = " . $rows['aID'] . ">
                                <input type='text' name='reqID' required hidden readonly value = " . $rows['reqID'] . ">
                                <input type='submit' class='actionBtnRev'   id='viewRequest' value='View'>
                                ". $sumbitStat ."
                            </form>
                        </div>
                    </td> 
                </tr>
                    ";
                    echo($newRow);
            }
        }
        return $stmt->rowCount();
    }


}

// <div class='actions' id='view'>
// <a href='assignProfile.php?aID=" . $rows['aID'] . "'>View</a>
// </div>
