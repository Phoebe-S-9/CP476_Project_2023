<?php


/*
CP476 Project v10
By: Phoebe Schulman, Yasmine Ali
March 28, 2023

Execute SQL statements given by web users
*/


/*
page 1:
Frontend: login page

//HOW TO RUN: refer to presentaion
//change the PASSWORD const in header.php
*/


$starting_program = 1;//true

//starts session allowing session data to be stored and used everytime page loads (allows script to verify if user is logged in)
session_start(); 

//sets authentication info 
$login_password = "password";
$user_login = "admin123";

//checks if post request is received, indicating user submitted login form 
if(isset($_POST['user_auth']) && isset($_POST['user_password']))
{
    //retrieves form data 
    $user_auth=$_POST['user_auth'];
    $user_password=$_POST['user_password'];

    //if authentication info entered by user is correct 
    if($user_auth == $user_login && $login_password == $user_password)
    {
        //sets user_auth key in session array to admin username entered by user
        $_SESSION['user_auth']=$user_auth;
        header('location:cp476_project.php'); // redirects user to this file
        exit();
    }
    else
    {   //if details are incorrect, user gets an alert
        echo "<script>alert('Invalid username and password! Try again');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.phptutorial.net/app/css/style.css">
    <title>Login</title>
</head>
<body>
<main>
    <form action="cp476_project_login.php" method="post">
        <h1>Login</h1>
        <div>
            <label for="user_auth">Username:</label>
            <input type="text" name="user_auth" id="user_auth">
        </div>
        <div>
            <label for="user_password">Password:</label>
            <input type="password" name="user_password" id="user_password">
        </div>
        <section>
            <button type="submit">Login</button>
        </section>
    </form>
</main>
</body>
</html>



<?php
//to do just prepeared methods?

/*
test cases

select * from Course_Table;

select Course_Code, Final_Exam from Course_Table where Final_Exam > 70;

select * from final_grade_output_table ORDER BY Student_Name;



SELECT C.Student_ID, N.Student_Name, C.Course_Code, C.Test_1,C.Test_2, C.Test_3, C.Final_Exam 
FROM Course_Table AS C
JOIN Name_Table AS N ON C.Student_ID = N.Student_ID 
ORDER BY C.Student_id;



(incorecct sql statemnts)
select t from Course_Table;

(sql statments - not select or update)

INSERT INTO Course_Table (Student_id) VALUES ( '4006');

UPDATE course_table 
SET student_id  = 88
WHERE Student_ID = 280587734;
*/

/*
more test caes

select Student_ID, Course_Code, Final_Exam from Course_Table where Final_Exam > 70;
select Student_ID, Course_Code, Final_Exam from Course_Table where Final_Exam > 70 ORDER BY Final_Exam;


select Student_ID, Course_Code, Final_Exam from Course_Table where Final_Exam > 70 ORDER BY Final_Exam;

select Student_ID, Course_Code from Course_Table where Course_Code = "MA222";

select Student_Name, Course_Code, Final_Grade from Final_Grade_Output_Table where Final_Grade > 70 ORDER BY Final_Grade;

INSERT INTO Name_Table (Student_id, Student_Name) VALUES ( 4006, 'peter');

*/
/*

command prompt commands: 
mysql -u root -p

(show db exisits and switch to it)
show databases
use cp476_database

(show tables exisits and the schemas)
show tables;

describe name_table;
describe course_table;
describe final_grade_output_table;

(a query to show data inside of table)
select * from name_table;
select * from course_table;
select * from final_grade_output_table;


select * from final_grade_output_table ORDER BY Student_Name;
*/

/*
eg

559545416, Alexander Floydd

559545416, PS275, 74, 70, 86, 65
559545416, ST262, 90, 62, 88, 86

print("\n");
print(74*0.20 + 70*0.20 + 86 *0.20 +65*0.40); ///72
print("\n");
print(90*0.20 + 62*0.20 + 88 *0.20 +86*0.40); //82.4
print("\n");
*/

/*
other notes about front end
    echo "<br>";
    for a new line instead of \n


    when
    method = "post">
    Enter a query: <input type ="text" name ="query"><br>

    get input with:
    echo $_POST['query'];
*/


/*
UPDATE table_name
SET column1 = value1, column2 = value2, ...
WHERE condition;


UPDATE Course_Table
SET Course_Code = 'PS999', Test_1= 90
WHERE Student_ID = 280587734 and Course_Code = 'PS272';

//on fianl output table- final grade and curse code is not updated...
//maybe change the update statment to inclucde both tables?
//or if update make sure it updates stuff on the backen too based on student id
//inclduing final grade, course code, name, etc. 
//either in the query itself or just check if anyting else should be updated. 


//(using command line)just this command alone wont update other things..but ideally we should?
//this will update a test and a code for 1student--make sure Final_Exam also updtaed on this tables
//and Final_Grade and Course_Code is updated on the  Final_Grade_Output_Table table
//just final grade needs updateing. the exam is dif

//maybe  a button to shpow all 3 tables again?
*/    

    //prepared statemtns around here?

    //update the tables

    //display the tables- all of them or updated ones only

?>