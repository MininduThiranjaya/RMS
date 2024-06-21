<?php
    require_once('../../config.php');
    session_start();

    if(!isset($_SESSION['user']['id'])) {
        header("Location: ../logout.php");
    }
    //add student into database 
    if(isset($_POST['add'])) {

        $regNu = $_POST['regNu'];
        $name = $_POST['name'];
        $course = $_POST['course'];
        $year = $_POST['year'];
        $semester = $_POST['semester'];

        if(!empty($regNu) && !empty($name) && !empty($course) && !empty($year) && !empty($semester)) {

            $sql = "INSERT INTO student(regNu, name, course, year, semester)
                    VALUES('$regNu', '$name', '$course', '$year', '$semester')";

            $result = $conn->query($sql);

            if($result) {
                header('Location: add_Students.php');
            }
        }
    }
    //edit student details 
    if(isset($_POST['edit'])) {

        $regNu = $_POST['edit'];
        $name = $_POST['name'];
        $course = $_POST['course'];
        $year = $_POST['year'];
        $semester = $_POST['semester'];

        if(!empty($regNu) && !empty($name) && !empty($course) && !empty($year) && !empty($semester)) {

            $sql = "UPDATE student SET name='$name', course='$course', year='$year', semester='$semester'
                    WHERE regNu = '$regNu'";

            $result = $conn->query($sql);

            if($result) {
                header('Location: add_Students.php');
            }
        }
    }
    //delete student from database
    if(isset($_POST['delete'])) {

        $id = $_POST['delete'];
        if(!empty($id)) {

            $sql = "DELETE FROM student
                    WHERE regNu='$id'";
            
            $result = $conn->query($sql);

            if($result) {
                header('Location: add_Students.php');
            }
        }
    }

    if(isset($_POST['addAsAFile'])) {
                
        $file = $_FILES["fileName"]["tmp_name"];

        $openFile = fopen($file, 'r');

        if($openFile) {

            while (($line = fgets($openFile)) !== false) {

                // Remove whitespace and newline characters
                $line = trim($line);

                // Split the line by comma
                list($regNu, $name, $course, $year, $semester) = explode(",", $line);

                // Remove whitespace from identifier and value
                
                $regNu = trim($regNu);
                $name = trim($name);
                $course = trim($course);
                $year = trim($year);
                $semester = trim($semester);

                // Insert the data into the database
                $sql = "INSERT INTO student(regNu, name, course, year, semester)
                        VALUES ('$regNu', '$name', '$course', '$year', '$semester')";

                $result = $conn->query($sql);
            }
        }
        fclose($openFile);
        
    }

    if(isset($_POST['updateAsAFile'])) {
                
        $file = $_FILES["fileName"]["tmp_name"];

        $openFile = fopen($file, 'r');

        if($openFile) {

            while (($line = fgets($openFile)) !== false) {

                // Remove whitespace and newline characters
                $line = trim($line);

                // Split the line by comma
                list($regNu, $name, $course, $year, $semester) = explode(",", $line);

                // Remove whitespace from identifier and value
                
                $regNu = trim($regNu);
                $name = trim($name);
                $course = trim($course);
                $year = trim($year);
                $semester = trim($semester);

                // Insert the data into the database
                $sql = "UPDATE student SET name='$name',course='$course',year='$year',semester='$semester' WHERE regNu='$regNu'";

                $result = $conn->query($sql);
            }
        }
        fclose($openFile);
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/animations.css">  
    <link rel="stylesheet" href="../../css/main.css">  
    <link rel="stylesheet" href="../../css/admin.css">
        
    <title>Student</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .t-headin{
            font-size: 16px;
            font-weight: 500;
            padding: 10px;
            border-bottom: 3px solid var(--primarycolor);
        }
</style>
</head>
<body>
    
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="<?php echo "../".$_SESSION['user']['image']?>" alt="" width="100%" style="border-radius:50%">
                                </td>

                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Administrator</p>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                
                </tr>

                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="admin_Dash.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>

                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="add_Teachers.php" class="non-style-link-menu"><div><p class="menu-text">Lectures</p></a></div>
                    </td>
                </tr>

                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule menu-active menu-icon-doctor-active">
                        <a href="add_Students.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Students</p></div></a>
                    </td>
                </tr>

                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="add_Course.php" class="non-style-link-menu"><div><p class="menu-text">Course</p></a></div>
                    </td>
                </tr>

                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="lecture_Assign.php" class="non-style-link-menu"><div><p class="menu-text">Lecture Assign</p></a></div>
                    </td>
                </tr>

            </table>
        </div>

        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr>
                   

                    <td colspan='2'>
                        <form action="add_Students.php" method="post" class="header-search">
                            <input type="text" name="searchText" class="input-text header-searchbar" placeholder="Search Studnet" list="doctors">&nbsp;&nbsp;
                            <input type="Submit" name="search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        </form>  
                    </td>

                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                                date_default_timezone_set('Asia/Kolkata');

                                $date = date('Y-m-d');
                                echo $date;
                            ?>
                        </p>
                    </td>

                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;width:70%"><img src="../../images/download.png" width="50%"></button>
                    </td>
                </tr>
               
                <tr>
                    <td style="padding-top:30px;width:40%">
                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Add New Student</p>
                    </td>

                    <td style='width:20%'>
                        <a href="?action=add&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="display: flex;justify-content: center;align-items: center;margin-left:50px;background-image: url('../img/icons/add.svg');">Add New</font></button></a>
                        
                    </td>
                    <td style='width:20%'>
                        <a href="?action=addAsAFile&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="display: flex;justify-content: center;align-items: center;margin-left:50px;background-image: url('../img/icons/add.svg');">Add As a file</font></button></a>
                        
                    </td>
                    <td style='width:20%'>
                        <a href="?action=updateAsAFile&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="display: flex;justify-content: center;align-items: center;margin-left:50px;background-image: url('../img/icons/add.svg');">Update as a file</font></button></a>
                        
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Students</p>
                    </td>
                </tr>
                <!--table-->
                <tr>
                    <td colspan="4">    
                        <center>
                            <div class="abc scroll">
                                <table width="100%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="t-headin">
                                                Register Number
                                            </th>

                                            <th class="t-headin">
                                                Name
                                            </th>  
                                            <th class="t-headin">
                                                Course
                                            </th>
                                            <th class="t-headin">
                                                Year
                                            </th>
                                            <th class="t-headin">
                                                Semester
                                            </th>
                                            <th class="t-headin">
                                                Event
                                            </th>                                
                                        </tr>
                                    </thead>
                                    <tbody>
                        
                                        <?php
                                            
                                            //search students
                                            if(isset($_POST['search'])) {
                                                $search = $_POST['searchText'];
                                                
                                                if(!empty($search)) {
                                                    $sql = "SELECT * FROM student
                                                            WHERE
                                                            regNu LIKE '$search%'
                                                            OR name LIKE '$search%'";
                                                    
                                                    $result = $conn->query($sql);

                                                    if($result->num_rows > 0) {
                                                        while($row = $result->fetch_assoc()) {

                                                            $regNu = $row['regNu'];
                                                            $name = $row['name'];
                                                            $course = $row['course'];
                                                            $year = $row['year'];
                                                            $semester = $row['semester'];

                                                            echo "<tr style='text-align:center;'>
                                                                <td>$regNu</td>
                                                                <td>$name</td>
                                                                <td>$course</td>
                                                                <td>$year</td>
                                                                <td>$semester</td>
                                                                <td>
                                                                    <div style='display:flex;justify-content: center;'>
                                                                        <a href='?action=edit&id=$regNu' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=view&id=$regNu' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=drop&id=$regNu' &name='' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
                                                                    </div>
                                                                </td>
                                                            </tr>";

                                                        }
                                                    }
                                                    else {
                                                        echo "<tr>
                                                            <td>Nothing to show</td>
                                                        </tr>";
                                                    }
                                                }
                                                else {
                                                    $sql = "SELECT * FROM student";

                                                    $result = $conn->query($sql);

                                                    if($result->num_rows > 0) {

                                                        while($row = $result->fetch_assoc()) {

                                                            $regNu = $row['regNu'];
                                                            $name = $row['name'];
                                                            $course = $row['course'];
                                                            $year = $row['year'];
                                                            $semester = $row['semester'];

                                                            echo "<tr style='text-align:center;'>
                                                                <td>$regNu</td>
                                                                <td>$name</td>
                                                                <td>$course</td>
                                                                <td>$year</td>
                                                                <td>$semester</td>
                                                                <td>
                                                                    <div style='display:flex;justify-content: center;'>
                                                                        <a href='?action=edit&id=$regNu' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=view&id=$regNu' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=drop&id=$regNu' &name='' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
                                                                    </div>
                                                                </td>
                                                            </tr>";
                                                        }
                                                    }
                                                }
                                            }
                                            //load all students
                                            else {
                                                $sql = "SELECT * FROM student";

                                                $result = $conn->query($sql);

                                                if($result) {

                                                    while($row = $result->fetch_assoc()) {

                                                        $regNu = $row['regNu'];
                                                        $name = $row['name'];
                                                        $course = $row['course'];
                                                        $year = $row['year'];
                                                        $semester = $row['semester'];

                                                        echo "<tr style='text-align:center;'>
                                                            <td>$regNu</td>
                                                            <td>$name</td>
                                                            <td>$course</td>
                                                            <td>$year</td>
                                                            <td>$semester</td>
                                                            <td>
                                                                <div style='display:flex;justify-content: center;'>
                                                                    <a href='?action=edit&id=$regNu' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                    <a href='?action=view&id=$regNu' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                    <a href='?action=drop&id=$regNu' &name='' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
                                                                </div>
                                                            </td>
                                                        </tr>";
                                                    }
                                                }
                                            }  
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </center>
                   </td> 
                </tr>          
            </table>
        </div>
    </div>
    
    <?php

        //load froms
        if($_GET) {
            
            $action = $_GET['action'];
            $id = $_GET['id'];
            //delete student form 
            if($action == 'drop') {

                echo"
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <h2>Are you sure?</h2>
                            <a class='close' href='add_Students.php'>&times;</a>
                            <div class='content'>
                                You want to delete this record<br>   
                            </div>
                            <div style='display: flex;justify-content: center;'>
                                <form action='add_Students.php' method='post'>
                                    <button  class='btn-primary btn' name='delete' value='$regNu' style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'<font class='tn-in-text'>&nbsp;Yes&nbsp;</font></button>
                                </form>
                                <a href='add_Course.php' class='non-style-link'><button  class='btn-primary btn'  style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'><font class='tn-in-text'>&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>
                            </div>
                        </center>
                    </div>
                </div>";
            }
            //view student form
            else if($action == 'view') {

                $sql = "SELECT * FROM student
                        WHERE regNu = '$id'";
                    
                $result = $conn->query($sql);

                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    $regNu = $row['regNu'];
                    $name = $row['name'];
                    $course = $row['course'];
                    $year = $row['year'];
                    $semester = $row['semester'];

                    echo "
                    <div id='popup1' class='overlay'>
                        <div class='popup'>
                            <center>
                                <h2></h2>
                                <a class='close' href='add_Students.php'>&times;</a>
                                <div class='content'>
                                    Student Details Document
                                    <br>
                                </div>
                                <div style='display: flex;justify-content: center;'>

                                    <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                        <tr>
                                            <td>
                                                <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>View Details.</p><br><br>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                <label for='name' class='form-label'>Regitartion Number: </label>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                $regNu
                                                <br>
                                                <br>
                                            </td>
                                            
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                <label class='form-label'>Name : </label>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                $name
                                                <br>
                                                <br>
                                            </td>
                                        </tr>

                                        <tr>
                                        <td class='label-td' colspan='2'>
                                            <label class='form-label'>Course : </label>
                                        </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                $course
                                                <br>
                                                <br>
                                            </td>
                                        </tr>

                                        <tr>
                                        <td class='label-td' colspan='2'>
                                            <label class='form-label'>Academic Year : </label>
                                        </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                $year
                                                <br>
                                                <br>
                                            </td>
                                        </tr>

                                        <tr>
                                        <td class='label-td' colspan='2'>
                                            <label class='form-label'>Semester : </label>
                                        </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                $semester
                                                <br>
                                                <br>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan='2'>
                                                <a href='add_Students.php'><input type='button' value='OK' class='login-btn btn-primary-soft btn' ></a> 
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </center>
                            <br>
                            <br>
                        </div>
                    </div>";
                }
            }
            //edit student details form 
            else if($action == 'edit') {

                $sql = "SELECT * FROM student
                        WHERE regNu = '$id'";
                    
                $result = $conn->query($sql);

                if($result) {

                    $row = $result->fetch_assoc();
                    
                    $regNu = $row['regNu'];
                    $name = $row['name'];
                    $course = $row['course'];
                    $year = $row['year'];
                    $semester = $row['semester'];

                    echo "
                    <div id='popup1' class='overlay'>
                        <div class='popup'>
                            <center>
                                <a class='close' href='add_Students.php'>&times;</a> 
                                <div style='display: flex;justify-content: center;'>
                                    <div class='abc'>
                                        <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                           
                                            <tr>
                                                <td>
                                                    <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Edit Student - $regNu</p><br>
                                                </td>
                                            </tr>
                                            
                                            <form action='add_Students.php' method='POST' class='add-new-form'>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <label class='form-label'>Name: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <input type='text' name='name' class='input-text' placeholder='Name' value='$name' required><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                    <br>
                                                        <label class='form-label'>Course: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <select class='input-text' name='course' required>
                                                            <option value='$course'>Course - $course</option>
                                                            <option value='ICT'>ICT</option>
                                                            <option value='AMC'>AMC</option>
                                                            <option value='BIO'>BIO</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                    <br>
                                                        <label class='form-label'>Year: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <select class='input-text' name='year' required>
                                                            <option value='$year '>Academic Year - $year </option>
                                                            <option value='1'>First</option>
                                                            <option value='2'>Second</option>
                                                            <option value='3'>Thired</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                    <br>
                                                        <label class='form-label'>Semester: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <select class='input-text' name='semester' required>
                                                            <option value='$semester'>Semester - $semester</option>
                                                            <option value='1'>First</option>
                                                            <option value='2'>Second</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan='2'>
                                                        <input type='reset' value='Reset' class='login-btn btn-primary-soft btn' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    
                                                        <button  class='btn-primary btn' name='edit' value='$regNu' style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'<font class='tn-in-text'>&nbsp;Edit&nbsp;</font></button

                                                    </td>
                                    
                                                </tr>
                                            </form>       
                                        </table>
                                    </div>
                                </div>
                            </center>
                            <br>
                            <br>
                        </div>
                    </div>";
                }
            }
            //add student details from
            else if($action == 'add') {
                echo "
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <a class='close' href='add_Students.php'>&times;</a> 
                            <div style='display: flex;justify-content: center;'>
                                <div class='abc'>
                                    <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                        
                                        <tr>
                                            <td>
                                                <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Add New Student</p><br>
                                            </td>
                                        </tr>
                                        
                                        <form action='add_Students.php' method='POST' class='add-new-form'>
                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <label class='form-label'>Register Number: </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <input type='text' name='regNu' class='input-text' placeholder='Register Number' required><br>
                                                </td>
                                                
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <label class='form-label'>Name: </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <input type='text' name='name' class='input-text' placeholder='Name' required><br>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                <br>
                                                    <label class='form-label'>Course: </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <select class='input-text' name='course' required>
                                                        <option value=''>Choose the course</option>
                                                        <option value='ICT'>ICT</option>
                                                        <option value='AMC'>AMC</option>
                                                        <option value='BIO'>BIO</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                <br>
                                                    <label class='form-label'>Year: </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <select class='input-text' name='year' required>
                                                        <option value=''>Academic Year</option>
                                                        <option value='1'>First</option>
                                                        <option value='2'>Second</option>
                                                        <option value='3'>Thired</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                <br>
                                                    <label class='form-label'>Semester: </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <select class='input-text' name='semester' required>
                                                        <option value=''>Semester</option>
                                                        <option value='1'>First</option>
                                                        <option value='2'>Second</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan='2'>
                                                    <input type='reset' value='Reset' class='login-btn btn-primary-soft btn' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                
                                                    <input type='submit' value='Add' name='add' class='login-btn btn-primary btn'>
                                                </td>
                                
                                            </tr>
                                        </form>       
                                    </table>
                                </div>
                            </div>
                        </center>
                        <br>
                        <br>
                    </div>
                </div>";
            }
            else if($action == 'addAsAFile') {
                echo "
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <a class='close' href='add_Students.php'>&times;</a> 
                            <div style='display: flex;justify-content: center;'>
                                <div class='abc'>
                                    <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                        
                                        <tr>
                                            <td>
                                                <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Add Students As A File</p><br><br>
                                            </td>
                                        </tr>
                                        
                                        <form action='add_Students.php' method='POST' enctype='multipart/form-data' class='add-new-form'>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                <br>
                                                    <label class='form-label'>File (txt): </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <input type='file' name='fileName' class='input-text' placeholder='File (txt)' required><br>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan='2'>
                                                    <input type='reset' value='Reset' class='login-btn btn-primary-soft btn' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                
                                                    <input type='submit' value='Add' name='addAsAFile' class='login-btn btn-primary btn'>
                                                </td>
                                
                                            </tr>
                                        </form>       
                                    </table>
                                </div>
                            </div>
                        </center>
                        <br>
                        <br>
                    </div>
                </div>";
            }
            else if($action == 'updateAsAFile') {
                echo "
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <a class='close' href='add_Students.php'>&times;</a> 
                            <div style='display: flex;justify-content: center;'>
                                <div class='abc'>
                                    <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                        
                                        <tr>
                                            <td>
                                                <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Update Students As A File</p><br><br>
                                            </td>
                                        </tr>
                                        
                                        <form action='add_Students.php' method='POST' enctype='multipart/form-data' class='add-new-form'>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                <br>
                                                    <label class='form-label'>File (txt): </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <input type='file' name='fileName' class='input-text' placeholder='File (txt)' required><br>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan='2'>
                                                    <input type='reset' value='Reset' class='login-btn btn-primary-soft btn' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                
                                                    <input type='submit' value='Update' name='updateAsAFile' class='login-btn btn-primary btn'>
                                                </td>
                                
                                            </tr>
                                        </form>       
                                    </table>
                                </div>
                            </div>
                        </center>
                        <br>
                        <br>
                    </div>
                </div>";
            }
        }
    ?>
</div>

</body>
</html>