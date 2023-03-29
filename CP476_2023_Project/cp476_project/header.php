<?php declare(strict_types= 1); 


//resumes session, allowing script to access session array data (user_auth in this case)

session_start();

//if user_auth key in session array not set (meaning user isnt logged in), redirects user to login page

if(!isset($_SESSION['user_auth']))
{
    header('location:cp476_project_login.php');

    //global $starting_program = 0;//true
}


/**
* print a table on the front end
*/
function showTable(object $connection, string $sql){

    //first get the data

    $result = $connection->query($sql); //regular query 
    $resultInfo = $result->fetch_all(MYSQLI_ASSOC);

    //$query_rows= sizeof($resultInfo) +1;//num rows in the qeury + 1 for headers
    $query_cols = sizeof($resultInfo[0]);
    
    $fieldInfo = $result->fetch_fields(); //for the col names


    //then dispaly the data on html

    //echo  "<h3> RESULT OF THE SELECT IS </h3>"; //heading 3 size
    
    echo "<table>"; //make a table
    echo "<table border='1'>";


    //first row of headers 
    echo "<tr>";  //table record

    foreach ($fieldInfo as $val) {
        echo "<th> $val->name</th>";//col names //like student_id
    }
    echo "</tr>";  


    $result_mysqli = mysqli_query($connection,$sql);//mysqli query

    //display actaul data of input query 
    while($row = mysqli_fetch_array($result_mysqli)){ //for each row
        echo "<tr>";

        for($j = 0; $j< $query_cols;$j++){ //for each col
            echo "<td>" . $row[$j] . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<br>";
}


//vars and consts

const SERVER_NAME = "localhost";
const USER_NAME = "root";
const PASSWORD ="pass"; //CHANGE THIS to yours

$fileName1 = "NameFile.txt";
$fileName2= "CourseFile.txt";

const DATABASE_NAME = "CP476_Database"; //cant have a space in the names when querying
const NAME_TABLE_NAME = "Name_Table";
const COURSE_TABLE_NAME = "Course_Table";
const FINAL_GRADE_OUTPUT_TABLE_NAME = "Final_Grade_Output_Table";

?>