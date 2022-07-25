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
    public function show()
    {
        $stmt = $this->conn->prepare("SELECT * FROM ASSIGNMENTS;");
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
                <td>" . $rows['aID'] . "</td>
                        <td>" . $rows['aName'] . "</td>
                        <td>" . $rows['aDescLink'] . "</td>
                        <td>" . $rows['aDeadline'] . "</td>
                        <td class='actionsCol'>
                        <div class='actions' id='view'>
                        <a href='assignProfile.php?aID=" . $rows['aID'] . "'>View</a>
                        </div>
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


}