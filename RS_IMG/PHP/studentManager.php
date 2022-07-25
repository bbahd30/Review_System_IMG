<?php

require_once '../PHP/tableManager.php';

class studentManager extends tableManager
{
    public function __construct()
    {
        parent::__construct();
        $this->setIndex("Student_ID");
        $this->setTable("STUDENTS");
        $this->setColumns("Student_ID, Name, Username, Password");
        $this->setValues(":Student_ID, :Name, :Username, :Password");
        $this->setUpdateCondition("Student_ID = :Student_ID");
        $this->setColPair("Student_ID = :Student_ID, Name = :Name,  Username = :Username");

    }

    // for showing students
    public function show()
    {
        $stmt = $this->conn->prepare("SELECT Student_ID, Name, Username FROM STUDENTS;");
        $stmt->execute();
        if($stmt->rowCount() == 0)
        {
            echo("No Students Added.");
        }
        else
        {
            while($rows = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $newRow = 
                "
                    <tr>
                        <td>" . $rows['Student_ID'] . "</td>
                        <td>" . $rows['Name'] . "</td>
                        <td>" . $rows['Username'] . "</td>
                        <td class='actionsCol'>
                            <div class='actions' id='view'>
                                <a href='studentProfile.php?sID=" . $rows['Student_ID'] . "'>View</a>
                            </div>
                            <div class='actions' id='edit'>
                                <a href='editStudent.php?sID=" . $rows['Student_ID'] . "'>Edit</a>
                            </div>
                            <div class='actions' id='Delete'>

                                <form action='../PHP/ReviewerDashboard.php' method='post'>
                                    <input type='text' hidden name='delete' value='delete' readonly required>
                                    <input type='text' hidden name='sID' value='" . $rows['Student_ID'] . "' readonly required>
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

    public function setArrValuesMatch($Student_ID)
    {
        $this->arrValues = array
        (
            ":Student_ID" => $Student_ID
        );
    }

    public function setArrValues($Student_ID, $Name, $Username, $Password)
    {
        $this->arrValues = array
        (
            ":Student_ID" => $Student_ID,
            ":Name" => $Name,
            ":Username" => $Username,
            ":Password" => $Password
        );
    }
}