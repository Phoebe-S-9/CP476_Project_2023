<?php declare(strict_types= 1); 

/*
Page 3: 
Backend: processes user input 
Frontend: display results and wait for next input
*/


include_once "header.php";
//the session is alreadly active in the include
?>

<html>
    <body>

    <h1>Database project</h1>

    <form action = "cp476_project_GUI.php" method = "post">
    Enter a query: <input type ="text" name ="query"><br>
    <input type = "submit" name = "show databases">
    </form>

    <a href="cp476_project_logout.php">Logout</a>
    <br> <br>

    <?php 
    echo "Showing results for the last query: \n";
    echo "<br>";
    echo $_POST['query'];
    ?>

    </body>
</html>

<?php



/**
* display user input for select
* input (object) $con - the connection to database
* input (string) $userInput - the querry to try to exectute
*/
function showSelectInput(object $con,string $userInput){
    
    //prepared statemtns around here?...
    $sql  = $userInput;

    //show table
    echo  "<h3> RESULT OF THE SELECT IS </h3>"; //heading 3 size
    showTable($con,$sql); //from  header.php
}



/**
* display user input for update
* input (object) $con - the connection to database
* input (string) $userInput - the querry to try to exectute
*/

function showUpdateInput(object $con,string $userInput){
  

    $sql  = $userInput;

    $result = $con->query($sql); //regular query //actauly update it
    
    
    //show all tables

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

}


/**
* validate user input - select or update. 
* input (string) $userInput - the querry to try to exectute
*/

function validateUserInput(string $userInput){
   
    $userInput = trim(strtolower($userInput)); //trim and convert to lower case
    
    if (str_starts_with($userInput, "select") || str_starts_with($userInput, "update")){
        try{
    
            //connect to our database
            $con=mysqli_connect(SERVER_NAME, USER_NAME, PASSWORD, DATABASE_NAME); 

            if (mysqli_connect_errno()){
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }


            if (str_starts_with($userInput, "select")){
                showSelectInput($con,$userInput);
            } elseif (str_starts_with($userInput, "update")){
                showUpdateInput($con,$userInput);
            }

            mysqli_close($con);

        }catch(Exception $e){
            error_log($e->getMessage());
            print ("<br><h3>ERROR: Bad SQL statement</h3><br>");
        }
    
    }else{
        print "<br><h3>INVALID QUERRY: can only use update and select</h3><br>";
    }
}


//MAIN

$userInput =  $_POST['query']; //to acces the user input from html
//echo "input was ". $userInput;
validateUserInput( $userInput); //check if valid query - is  select or update?

?>


