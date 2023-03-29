<?php declare(strict_types= 1); 

/*
page 2:
Backend: initialize database and tables
NameFile.txt and CourseFile.txt as input

Frontend: display all tables and wait for first input
*/


include_once "header.php";
//the session is alreadly active in the include


/**
 * connect to mysql
 * return: (object) connection -to connect to the data base
 * or exit on fail
 */
function connectToMySQL(): object { 
    $connection = null;
    
    mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);

    try{
    
        $connection = new mysqli(SERVER_NAME, USER_NAME, PASSWORD);
    
        if($connection->connect_error){
            die("FAILED: connecting to mysqli ". $connection->connect_error ."\n");
        }
    
        print("SUCCESS: connecting to mysqli\n");
    
        $connection->set_charset("utf8mb4");

        return $connection;
    
    }catch(Exception $e){
        error_log($e->getMessage());
        exit("\n<br><br>ERROR: connecting to mysqli. try change the password");
    }
}


/**
 * delete the data bases from previous  runs
 * input: (object) connection -to connect to the data base
 */
function cleanDatabases(object $connection){
    
    $sql = "DROP DATABASE IF EXISTS ". DATABASE_NAME ;

    if($connection ->query($sql) === TRUE){
        echo "SUCCESS: deleting the database\n";
    }else{
        echo "FAILED: deleting the database ". $connection->error;
    }
}


/**
 * create an empty database (to later store the 3 tables)- with sql
 * input: (object) connection -to connect to the data base
 */
function createDatabase(object $connection){

    //create a database //check if the data base exsists alreadly 
    $sql = "CREATE DATABASE IF NOT EXISTS " . DATABASE_NAME;
    
    if($connection ->query($sql) === TRUE){
        echo "SUCCESS: creating the database\n";
    }else{
        echo "FAILED: creating the database ". $connection->error;
    }

}


/**
 * connect to the created database
 * return: (object) connection -to connect to the data base
 * or exit on fail
 */
function connectToMyDatabase(): object{ 
    $connection = null;
    
    mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);

    try{
    
        //$connection = new mysqli(SERVER_NAME, USER_NAME, PASSWORD);
        $connection = new mysqli(SERVER_NAME, USER_NAME, PASSWORD, DATABASE_NAME); //connect to the database

    
        if($connection->connect_error){
            die("FAILED: connecting to " . DATABASE_NAME . $connection->connect_error ."\n");
        }
    
        print("SUCCESS: connecting to " . DATABASE_NAME . "\n");
    
        $connection->set_charset("utf8mb4");

        return $connection;
    
    }catch(Exception $e){
        error_log($e->getMessage());
        exit("\nERROR");
    }
}


/**
 * creates 3 empty tables. focous on the schemeas - tables will be filled later
 * 2 input tables (A), 1 output table(B). 
 * input: (object) connection -to connect to the data base (the created one)
 */
function createTables(object $connection){

    //create first (input) table

    //create a table called name table. //where student id is a pk. 9 ints long//name is 30 chars. not null
    $sql= "CREATE TABLE ". NAME_TABLE_NAME . "(Student_ID INT(9) PRIMARY KEY, Student_Name VARCHAR(30) NOT NULL)";

    if($connection->query($sql)===TRUE){
        echo "SUCCESS: table created\n";
    }else{
        echo "FAILED: creating table: ". $connection->error;
    }
    

    //create second (input) table //create course table

    $sql= "CREATE TABLE ". COURSE_TABLE_NAME . "(Student_ID INT(9) , Course_Code VARCHAR(5) NOT NULL, Test_1 INT(2) NOT NULL, Test_2 INT(2) NOT NULL, Test_3 INT(2) NOT NULL, Final_Exam INT(2) NOT NULL)";
    //student id is not a pk - becuase same student can have mutli courses //stud id  is fk instead?

    if($connection->query($sql)===TRUE){
        echo "SUCCESS: table created\n";
    }else{
        echo "FAILED: creating table: ". $connection->error;
    }


    //create third (output) table  //final grade table
    $sql= "CREATE TABLE ". FINAL_GRADE_OUTPUT_TABLE_NAME . "(Student_ID INT(9) , Student_Name VARCHAR(30) NOT NULL, Course_Code VARCHAR(5) NOT NULL, Final_Grade FLOAT(2) NOT NULL)";

    if($connection->query($sql)===TRUE){
        echo "SUCCESS: table created\n";
    }else{
        echo "FAILED: creating table: ". $connection->error;
    }

}


/**
 * read both input files and fills 2 input tables
 * input: (object) connection -to connect to the data base (the created one)
 * input: (string) $fileName1, $fileName2 -files to read from 
 */

function dataBaseReader(string $fileName1, string $fileName2, object $connection ){

    //refer to picture A


    //first file //for name table
    $fileHandler = fopen($fileName1, "r") or die("Unable to open the file");

    while(!feof($fileHandler)){ //traverse a file
        
        //grab 2 words //the delimiter is ,
        //eg: 308621686, Boone Stevenson

        //print fgets($fileHandler);

        $line = fgets($fileHandler);
        if ($line !="") { // line is not empty 
            $words = explode(", ", $line);

            $nameStudentID = $words[0];
            //$studentName = $words[1];
            $studentName = trim($words[1]); //rid a lot of empty space from name
            print($nameStudentID);
            print($studentName);

        
            //start inserting into one table //sql inserts
            //inserting -with preapared//need to use the right field names from creating the tables

           
            $sql = $connection->prepare("INSERT INTO ". NAME_TABLE_NAME . "(Student_ID, Student_Name) VALUES(?,?)");
            $sql ->bind_param("ss", $nameStudentID, $studentName);
            $sql->execute();
   
        }

    }

    $words= null;
    fclose($fileHandler);


    //second file
    $fileHandler = fopen($fileName2, "r") or die("Unable to open the file");

    while(!feof($fileHandler)){ //traverse a file

        //grab 6 words //the delimiter is ,
        //eg: 280587734, PS272, 74, 98, 76, 52

        //print fgets($fileHandler);

        $line = fgets($fileHandler);
        if ($line !="") { // line is not empty 
            $words = explode(", ", $line);

            $courseStudentID = $words[0];
            $courseCode = $words[1];
            $test1= $words[2];
            $test2 = $words[3];
            $test3 = $words[4];
            $finalExam = $words[5];

            print($courseStudentID);
            print($courseCode);
            print("\n");

    
            //start inserting into second data base //sql inserts
            $sql = $connection->prepare("INSERT INTO ". COURSE_TABLE_NAME . "(Student_ID, Course_Code,Test_1,Test_2,Test_3, Final_Exam ) VALUES(?,?,?,?,?,?)");
            $sql ->bind_param("ssssss", $courseStudentID, $courseCode, $test1, $test2, $test3, $finalExam);
            $sql->execute();

        }
    }
    fclose($fileHandler);

}

/**
 * do calcuations to fill up the output data base
 * input: (object) connection -to connect to the data base (the created one)
 */

function outputDatabase(object $connection){
    
    //info for the final answer //refer to picture B
    //formula: finalGrade = test1*0.20 + test2*0.20 + test3*0.20 + finalExam*0.40
    
    // need all info of course table //join with the name table on student id. //order by name
    //use the same column names from creating the tables
       
        
    // SELECT C.Student_ID, N.Student_Name, C.Course_Code, C.Test_1,C.Test_2, C.Test_3, C.Final_Exam FROM Course_Table AS C  JOIN Name_Table AS N ON C.Student_ID = N.Student_ID ORDER BY C.Student_id;

    $sql = "SELECT C.Student_ID, N.Student_Name, C.Course_Code, C.Test_1,C.Test_2, C.Test_3, C.Final_Exam FROM " . COURSE_TABLE_NAME . " AS C INNER JOIN ". NAME_TABLE_NAME . " AS N ON C.Student_ID = N.Student_ID";
    $result = $connection->query($sql);

    if($result->num_rows>0){

        //insert data of each row//also insert the calucated amoutn

        while($row = $result->fetch_assoc()){
            

            $finalStudentID = $row["Student_ID"];
            $finalStudentName = $row["Student_Name"];
            $finalCourseCode = $row["Course_Code"];

            $finalGrade = $row["Test_1"] *0.20 + $row["Test_2"] *0.20 + $row["Test_3"] *0.20 +$row["Final_Exam"] *0.40;


            $sql_insert= $connection->prepare("INSERT INTO " .  FINAL_GRADE_OUTPUT_TABLE_NAME. "(Student_ID, Student_Name, Course_Code, Final_Grade) VALUES(?,?,?,?)");
            $sql_insert ->bind_param("sssd", $finalStudentID, $finalStudentName, $finalCourseCode, $finalGrade); //d = float
            $sql_insert->execute();

            }
        }
}

//main============

ob_start(); //to help clear all the output -for front end //DELETE THIS TO SEE the rest of output from backend but on the front end


//print("<BR>DBCLEAN\n");
$connectionMySQL = connectToMySQL(); //connect to mysql database
cleanDatabases($connectionMySQL); //to clean the data base from last time
createDatabase($connectionMySQL);//create 1 data base (check if it alreadly exsists )


print("Closing the connection to mysql\n");
$connectionMySQL->close();


$connectionMyDatabase = connectToMyDatabase(); //connect to CP476_Database
createTables($connectionMyDatabase); //create  3 tables 
dataBaseReader($fileName1,$fileName2,$connectionMyDatabase);  //fill up input data bases
outputDatabase($connectionMyDatabase); //fill up ouptut data base


print("Closing the connection to " . DATABASE_NAME . "\n");
$connectionMyDatabase->close();

//print("\n\nEND PROGRAM");

ob_clean(); //delete the backends output 


//FRONT END BEGINS:
?>


<html>
    <body>

    <h1>Database project</h1>


    <form action = "cp476_project_GUI.php" method = "post">
    Enter a query: <input type ="text" name ="query"><br>
    <input type = "submit" name = "show databases">
    </form>
    
    <a href="cp476_project_logout.php">Logout</a>

    <?php 

    //connect to our database
    $con=mysqli_connect(SERVER_NAME, USER_NAME, PASSWORD, DATABASE_NAME); 

    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    //first table
    echo  "<h3>" . NAME_TABLE_NAME . "</h3>";
    $sql= "SELECT * FROM ". NAME_TABLE_NAME;
    showTable($con, $sql);

    //second table
    echo  "<h3>" . COURSE_TABLE_NAME . "</h3>";
    $sql= "SELECT * FROM ". COURSE_TABLE_NAME;
    showTable($con, $sql);

    //third table
    echo  "<h3>" . FINAL_GRADE_OUTPUT_TABLE_NAME . "</h3>";
    $sql= "SELECT * FROM ". FINAL_GRADE_OUTPUT_TABLE_NAME;
    showTable($con, $sql);


    mysqli_close($con);
    ?>
    
    </body>
</html>

