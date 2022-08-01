<?php

require_once '../PHP/tableManager.php';

if(!isset($_SESSION))
{
    session_start();
}
class requestManager extends tableManager
{
    public $reqIDCondition = "reqID = :reqID";
    public $joinUpdateCondition = "STUDENTS.Student_ID = :Student_ID AND ASSIGNMENTS.aID = :aID";
    public function __construct()
    {
        parent::__construct();
        $this->setIndex("reqID");
        $this->setTable("REQUESTS");
        $this->setColumns("Student_ID, IternNo , aID");
        $this->setValues(":Student_ID, :IternNo , :aID");
        $this->setUpdateCondition("Student_ID = :Student_ID AND aID = :aID");
        $this->setColPair("IternNo = :IternNo"); 
    }
    public function getReqIDCondition()
    {
        return $this->reqIDCondition;
    }
    public function getJoinUpdateCondition()
    {
        return $this->joinUpdateCondition;
    }
    public function setReqIDValues($reqID)
    {
        $this->arrValues = array
        (
            ":reqID" => $reqID
        );
    }
 
    public function setArrValuesMatch($aID)
    {
        $this->arrValues = array
        (
            ":Student_ID" => $_SESSION['member_ID'],
            ":aID" => $aID
        );
    }

    public function setArrValues($aID, $reqValue)
    {
        if(!$this->isPresentInRequest($aID))
        {
            $this->arrValues = array
            (
                ":Student_ID" => $_SESSION['member_ID'],
                ":IternNo" => 0,
                ":aID" => $aID
            );
        }
        else
        {
            if($reqValue)
            {
                $this->arrValues = array
                (
                    ":Student_ID" => $_SESSION['member_ID'],
                    ":IternNo" => `IternNo` - 1,
                    ":aID" => $aID
                );
            }
            else
            {
                $this->arrValues = array
                (
                    ":Student_ID" => $_SESSION['member_ID'],
                    ":IternNo" => `IternNo` + 1,
                    ":aID" => $aID
                );
            }
        }
    }


    // ************************
    // NEW FUNCTIONS OF STUDENTS FOR REQUESTING 
    
    public function requestForReview($aID)
    {
        $rowData = $this->isPresentInRequest($aID);
        $this->setArrValues($aID, $reqValue );

        if(!$rowData)
        {
            // false => so first request as absent in the table            
            $this->adder($this->getColumns(), $this->getValues(), $this->getArrValues(), $this->getTable());
        }
        else
        {
            // next iteration then request
            $this->updater($this->table(), $this->getColPair(), $this->getArrValues(), $this->getUpdateCondition());
        }
    }

    public function isPresentInRequest($aID)
    {
        $this->setArrValuesMatch($aID);
        if(!$this->rowDataFetch($this->getUpdateCondition(), $this->getArrValues(), $this->getTable()))
        {
            return false;
        }
        else
        {
            return $this->rowDataFetch($this->getUpdateCondition(), $this->getArrValues(), $this->getTable());
        }
    }
    public function showAssign()
    {
        $stmt = $this->conn->prepare("SELECT * FROM ASSIGNMENTS;");
        $stmt->execute();
        if($stmt->rowCount() == 0)
        {
            echo("No Assignment Added.");
        }
        else
        {
            $member_ID = $_SESSION['member_ID'];
            $stmt2 = $this->conn->prepare("SELECT REQUESTS.aID AS aID, Student_ID, IternNo FROM 
            ASSIGNMENTS
            JOIN REQUESTS ON ASSIGNMENTS.aID = REQUESTS.aID WHERE Student_ID = $member_ID AND REQUESTS.aID = :aID;");
            
            
            while($rows = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                
                $stmt2->execute(array(":aID" => $rows['aID']));
                $currentIternNum = $stmt2->fetch(PDO::FETCH_ASSOC);
                
                
                $reqStatus = (!$this->isPresentInRequest($rows['aID'])) ? "Request" : "Requested";
                $reqValue = ($reqStatus == "Requested") ? 1 : 0;
                

                // $nStmt = $this->conn->prepare("SELECT aID". $rows['aID']. " AS aID FROM COMPLETED WHERE member_ID = ". $member_ID. ";");

                $nStmt = $this->conn->prepare("SELECT sID". $member_ID ." AS aIDStat FROM COMPLETE WHERE assignID = 'aID". $rows['aID'] . "';");

                $nStmt->execute();
                $info = $nStmt->fetch(PDO::FETCH_ASSOC);

                $iternStatus = 
                    "<input type='text' hidden name='request' value='".$reqValue. "'readonly required>
                    <input type='text' hidden name='aID' value='" . $rows['aID'] .  "'readonly required>
                    <button type='request' class='actionBtn'>
                    " . $reqStatus . "
                    </button>";
                // AS THIS GIVES 0 IF EQUAL THAT IS BOOL TRUE, GIVES FALSE

                if(isset($currentIternNum['IternNo']))
                {
                    if($currentIternNum['IternNo'] != 0)
                    {
                        $iternStatus = "<button id='itern' class='actionBtn'>Under Review</button>";
                    }

                    if($info['aIDStat'] == 1)
                    {
                        $iternStatus = "<button id='passed' class='actionBtn'>Approved</button>";
                    }
                }
                

                $newRow = 
                "
                <tr>
                    <td>" . $rows['aID'] . "</td>
                    <td>" . $rows['aName'] . "</td>
                    <td> 
                        <a href='" . $rows['aDescLink'] . "' id='link'>" . $rows['aName'] . "'s Assignment Link</a> 
                    </td>
                       
                    <td>" . $rows['aDeadline'] . "</td>
                    <td class='actionsCol'>
                        <div class='actions' id='view'>
                            <a href='assignProfile.php?aID=" . $rows['aID'] . "'>View</a>
                        </div>
                        <div class='actions' id='request'>
                            <form action='../PHP/StudentDashboard.php' method='post'>
                                ". $iternStatus. "
                            </form>
                        </div>
                    </td> 
                </tr>";
                    echo($newRow);
            }
        }
        return $stmt->rowCount();
    }

    public function showRequests()
    {
        $stmt = $this->conn->prepare("SELECT ROW_NUMBER() OVER (ORDER BY reqID) AS SNo, IternNo, ASSIGNMENTS.aID AS aID,reqID, aName, IternNo, aDeadline FROM 
        REQUESTS 
        JOIN ASSIGNMENTS ON ASSIGNMENTS.aID = REQUESTS.aID 
        JOIN STUDENTS ON STUDENTS.Student_ID = REQUESTS.Student_ID 
        WHERE STUDENTS.Student_ID = :Student_ID;");

        $stmt->execute(array(":Student_ID" => $_SESSION['member_ID']));

        if($stmt->rowCount() == 0)
        {
            echo("No Requests Made.");
        }
        else
        {
            $member_ID = $_SESSION['member_ID'];

            
            while($rows = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $nStmt = $this->conn->prepare("SELECT sID". $member_ID ." AS aIDStat FROM COMPLETE WHERE assignID = 'aID". $rows['aID'] . "';");

                $nStmt->execute();
                $info = $nStmt->fetch(PDO::FETCH_ASSOC);

                $iternStatus = "<div class='actions' id='Delete'>
                                    <form action='../PHP/StudentDashboard.php' method='post'>
                                        <input type='text' hidden name='delete' value='delete' readonly required>
                                        <input type='text' hidden name='reqID' value='" . $rows['reqID'] . "' readonly  required>
                                        <button type='submit' class='actionBtn'>
                                            Remove
                                        </button>
                                    </form>
                                </div>";

                if($rows['IternNo'] != 0)
                {
                    $iternStatus = "<button type='request' id='itern' class='actionBtn'>Under Review</button>";
                }
                if($info['aIDStat'] != 1)
                {
                    $newRow = 
                    "
                    <tr>
                    <td>" . $rows['SNo'] . "</td>
                            <td>" . $rows['aName'] . "</td>
                            <td>" . $rows['IternNo'] . "</td>
                            <td>" . $rows['aDeadline'] . "</td>
                            <td class='actionsCol'>

                                <div class='actions' id='View'>
                                    <form action='../PHP/viewRequestProfile.php' method='post'>
                                        <input type='text' name='aID' required hidden readonly value = " . $rows['aID']. ">
                                        <input type='text' name='reqID' required hidden readonly value = " . $rows['reqID'] . ">
                                        <input type='submit' class='actionBtnRev'   id='viewRequest' value='View'>
                                    </form>
                                </div> "
                                . $iternStatus ."
                            </td> 
                        </tr>
                        ";
                        echo($newRow);
                }
            }
        }
        return $stmt->rowCount();
    }



    public function findIternNum($reqID)
    {
        $stmt = $this->conn->prepare("SELECT IternNo FROM REQUESTS WHERE $this->getReqIDCondition();");
        $this->setReqIDValues($reqID);
        $stmt->execute($this->getArrValues());
        $IternNum = $stmt->fetch(PDO::FETCH_ASSOC);
        return $IternNum;
    }
}