<?php

require_once '../PHP/tableManager.php';
if(!isset($_SESSION))
{
    session_start();
}
class requestManager extends tableManager
{
    public function __construct()
    {
        parent::__construct();
        $this->setIndex("Student_ID");
        $this->setTable("REQUESTS");
        $this->setColumns("Student_ID, IternNo , aID");
        $this->setValues(":Student_ID, :IternNo , :aID");
        $this->setUpdateCondition("Student_ID = :Student_ID AND aID = :aID");
        $this->setColPair("IternNo = :IternNo");

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
    public function show()
    {
        $stmt = $this->conn->prepare("SELECT * FROM ASSIGNMENTS;");
        $stmt->execute();
        if($stmt->rowCount() == 0)
        {
            echo("NO Assignment Added.");
        }
        else
        {
            while($rows = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $reqStatus = (!$this->isPresentInRequest($rows['aID'])) ? "Request" : "Requested";
                $reqValue = ($reqStatus == "Requested") ? 1 : 0;
                // AS THIS GIVES 0 IF EQUAL THAT IS BOOL TRUE, GIVES FALSE
                
                $newRow = 
                "
                <tr>
                    <td>" . $rows['aID'] . "</td>
                    <td>" . $rows['aName'] . "</td>
                    <td>" . $rows['aDescLink'] . "</td>
                    <td>" . $rows['aDeadline'] . "</td>
                    <td class='actionsCol'>
                    <div class='actions' id='view'>
                    <a href='assignProfile.php?aID=" . $rows['aID'] . "'>View</a>
                    </div>
                        <div class='actions' id='request'>
                            <form action='../PHP/StudentDashboard.php' method='post'>
                                <input type='text' hidden name='request' value='".$reqValue. "'readonly required>
                                <input type='text' hidden name='aID' value='" . $rows['aID'] . "'readonly required>
                                <button type='request' class='actionBtn'>
                                " . $reqStatus . "
                                </button>
                            </form>
                        </div>
                    </td> 
                </tr>";
                    echo($newRow);
            }
        }
        return $stmt->rowCount();
    }
}