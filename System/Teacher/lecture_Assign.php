<?php
    require_once('../../config.php');

    session_start();

    if(!isset($_SESSION["user"]["id"])) {
        header("Location: ../logout.php");
    }

    $GLOBALS['teacherId'] = $_SESSION["user"]["id"];
    //convert marks(int) into grades(char) only if marks available
    function convertMarksIntoGrade($value) {

        if(is_numeric($value)) {

            if($value >= 80 && $value <= 100) {
                return 'A+';
            }
            else if($value >= 75) {
                return 'A';
            }
            else if($value >= 70) {
                return 'A-';
            }
            else if($value >= 65) {
                return 'B+';
            }
            else if($value >= 60) {
                return 'B';
            }
            else if($value >= 55) {
                return 'B-';
            }
            else if($value >= 50) {
                return 'C+';
            }
            else if($value >= 45) {
                return 'C';
            }
            else if($value >= 40) {
                return 'C-';
            }
            else if($value >= 35) {
                return 'D+';
            }
            else if($value >= 30) {
                return  'D';
            }
            else if($value >= 0) {
                return  'E';
            }
        }
        else {
            return $value;
        }
    }
    //add result into database
    if(isset($_POST['add'])) {

        $courseId = $_POST['selectedCourse'];
        
        $file = $_FILES["fileName"]["tmp_name"];

        $openFile = fopen($file, 'r');

        if($openFile) {

            while (($line = fgets($openFile)) !== false) {

                // Remove whitespace and newline characters
                $line = trim($line);

                // Split the line by comma
                list($sId, $ica1, $ica2, $ica3, $final) = explode(",", $line);

                // Remove whitespace from identifier and value
                
                $sId = trim($sId);
                $ica1 = convertMarksIntoGrade(trim($ica1));
                $ica2 = convertMarksIntoGrade(trim($ica2));
                $ica3 = convertMarksIntoGrade(trim($ica3));
                $final = convertMarksIntoGrade(trim($final));

                // Insert the data into the database
                $sql = "INSERT INTO studentmarks(courseId, regNu, ica1, ica2, ica3, final)
                        VALUES ('$courseId', '$sId', '$ica1', '$ica2', '$ica3', '$final')";

                $result = $conn->query($sql);
            }
        }
        fclose($openFile);
    }
    //update results
    if(isset($_POST['update'])) {

        $courseId = $_POST['selectedCourse'];
        
        $file = $_FILES["fileName"]["tmp_name"];

        $openFile = fopen($file, 'r');

        if($openFile) {

            while (($line = fgets($openFile)) !== false) {
                // Remove whitespace and newline characters
                $line = trim($line);

                // Split the line by comma
                list($sId, $ica1, $ica2, $ica3, $final) = explode(",", $line);

                // Remove whitespace from identifier and value
                
                $sId = trim($sId);
                $ica1 = convertMarksIntoGrade(trim($ica1));
                $ica2 = convertMarksIntoGrade(trim($ica2));
                $ica3 = convertMarksIntoGrade(trim($ica3));
                $final = convertMarksIntoGrade(trim($final));

                // Insert the data into the database
                
                $sql = "UPDATE studentmarks SET ica1='$ica1', ica2='$ica2', ica3='$ica3', final='$final'
                        WHERE 
                        courseId = '$courseId'
                        AND
                        regNu = '$sId'";

                $result = $conn->query($sql);
            }
        }
        fclose($openFile);
    }
    //delete all results from database
    if(isset($_POST['deleteAll'])) {

        $id = $_POST['deleteAll'];

        if(!empty($id)) {

            $sql = "DELETE FROM studentMarks
                    WHERE courseId='$id'";
            
            $result = $conn->query($sql);

            if($result) {
                header('Location: lecture_Assign.php');
            }
        }
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
        
    <title>Lecture Assign</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
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
                                    <p class="profile-title"><?php echo $_SESSION['user']['userName']?></p>
                                    <p class="profile-subtitle"><?php echo $_SESSION['user']['id']?></p>
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
                        <a href="teacher_Dash.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>

                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor menu-active menu-icon-doctor-active">
                        <a href="lecture_Assign.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">All Subjects</p></a></div>
                    </td>
                </tr>

            </table>
        </div>

        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr>
                    <td width="13%">
                    </td>

                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="text" name="searchText" id='searchCentent' class="input-text header-searchbar" placeholder="Search Course" list="doctors">&nbsp;&nbsp;
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
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../../images/download.png" width="100%"></button>
                    </td>
                </tr>
               
                <tr>
                    <td colspan="2" style="padding-top:30px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Assign</p>
                    </td>

                    <td colspan="2">
                        <a href="?action=add&id=none" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="display: flex;justify-content: center;align-items: center;margin-left:75px;background-image: url('../img/icons/add.svg');">Add Marks</font></button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Courses</p>
                    </td>
                </tr>
                <!--table-->
                <tr>
                    <td colspan="4">    
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">
                                                Course Id
                                            </th>

                                            <th class="table-headin">
                                                Course Name
                                            </th>
                                            
                                            <th class="table-headin">
                                                Teacher Id
                                            </th>

                                            <th class="table-headin">
                                                Teacher Name
                                            </th> 

                                            <th class="table-headin">
                                                Event
                                            </tr>                                   
                                        </tr>
                                    </thead>
                                    <tbody>
                        
                                        <?php
                                            //search for lecture assigned subjects
                                            if(isset($_POST['search'])) {
                                                $search = $_POST['searchText'];
                                                
                                                if(!empty($search)) {

                                                    $sql = "SELECT * FROM lectureAssign
                                                            JOIN course ON
                                                            course.courseId = lectureAssign.courseId
                                                            JOIN teacher ON
                                                            teacher.id = lectureAssign.teacherId
                                                            WHERE teacherId = '$teacherId'
                                                            AND lectureAssign.courseId LIKE '$search%'";
                                                    
                                                    $result = $conn->query($sql);

                                                    if($result->num_rows > 0) {

                                                        while($row = $result->fetch_assoc()) {

                                                            $assignId = $row['assignId'];
                                                            $cId = $row['courseId'];
                                                            $cName = $row['courseName'];
                                                            $tId = $row['teacherId'];
                                                            $tName = $row['name'];

                                                            echo "<tr style='text-align:center;'>
                                                                <td>$cId</td>
                                                                <td>$cName</td>
                                                                <td>$tId</td>
                                                                <td>$tName</td>
                                                                <td>
                                                                    <div style='display:flex;justify-content: center;'>
                                                                        <a href='?action=update&id=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Update Marks</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='view_Marks.php?action=see&sid=&vcid=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View Marks</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=deleteAll&id=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Delete All</font></button></a>
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

                                                    $teacherId = $GLOBALS['teacherId'];
                                                    
                                                    $sql = "SELECT * FROM lectureAssign
                                                            JOIN course ON
                                                            course.courseId = lectureAssign.courseId
                                                            JOIN teacher ON
                                                            teacher.id = lectureAssign.teacherId
                                                            WHERE teacherId = '$teacherId'";

                                                    $result = $conn->query($sql);

                                                    if($result->num_rows > 0) {

                                                        while($row = $result->fetch_assoc()) {

                                                            $assignId = $row['assignId'];
                                                            $cId = $row['courseId'];
                                                            $cName = $row['courseName'];
                                                            $tId = $row['teacherId'];
                                                            $tName = $row['name'];

                                                            echo "<tr style='text-align:center;'>
                                                                <td>$cId</td>
                                                                <td>$cName</td>
                                                                <td>$tId</td>
                                                                <td>$tName</td>
                                                                <td>
                                                                    <div style='display:flex;justify-content: center;'>
                                                                        <a href='?action=update&id=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Update Marks</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='view_Marks.php?action=see&sid=&vcid=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View Marks</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=deleteAll&id=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Delete All</font></button></a>
                                                                    </div>
                                                                </td>
                                                            </tr>";
                                                        }
                                                    }
                                                }
                                            }
                                            //load all subjects lecture assigned
                                            else {

                                                $teacherId = $GLOBALS['teacherId'];

                                                $sql = "SELECT * FROM lectureAssign
                                                        JOIN course ON
                                                        course.courseId = lectureAssign.courseId
                                                        JOIN teacher ON
                                                        teacher.id = lectureAssign.teacherId
                                                        WHERE teacherId = '$teacherId'";

                                                $result = $conn->query($sql);

                                                if($result) {

                                                    while($row = $result->fetch_assoc()) {
                                                        
                                                        $assignId = $row['assignId'];
                                                        $cId = $row['courseId'];
                                                        $cName = $row['courseName'];
                                                        $tId = $row['teacherId'];
                                                        $tName = $row['name'];

                                                        echo "<tr style='text-align:center;'>
                                                            <td>$cId</td>
                                                            <td>$cName</td>
                                                            <td>$tId</td>
                                                            <td>$tName</td>
                                                            <td>
                                                                <div style='display:flex;justify-content: center;'>
                                                                    <a href='?action=update&id=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Update Marks</font></button></a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                    <a href='view_Marks.php?action=see&sid=&vcid=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View Marks</font></button></a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                    <a href='?action=deleteAll&id=$cId' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Delete All</font></button></a>
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

        //load forms
        if($_GET) {
            
            $action = $_GET['action'];
            $id = $_GET['id'];
            //add result form
            if($action == 'add') {
                echo "
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <a class='close' href='lecture_Assign.php'>&times;</a> 
                            <div style='display: flex;justify-content: center;'>
                                <div class='abc'>
                                    <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                        
                                        <tr>
                                            <td>
                                                <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Add New Teacher</p><br><br>
                                            </td>
                                        </tr>
                                        
                                        <form action='lecture_Assign.php' method='POST' enctype='multipart/form-data' class='add-new-form'>
                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <label class='form-label'>Course: </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <select class='input-text' id='course' name='course'>
                                                        <option value=''>Choose the course</option>
                                                        <option value='IT'>ICT</option>
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
                                                    <select class='input-text' id='year' name='year'>
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
                                                    <select class='input-text' id='semester' name='semester'>
                                                        <option value=''>Semester</option>
                                                        <option value='1'>First</option>
                                                        <option value='2'>Second</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                <br>
                                                    <label class='form-label'>Filtered Course : </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <select class='input-text' id='result' name='selectedCourse' required>
                                                    </select>
                                                </td>
                                            </tr>

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
            //update result form
            if($action == 'update') {
                echo "
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <a class='close' href='lecture_Assign.php'>&times;</a> 
                            <div style='display: flex;justify-content: center;'>
                                <div class='abc'>
                                    <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                        
                                        <tr>
                                            <td>
                                                <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Add New Teacher</p><br><br>
                                            </td>
                                        </tr>
                                        
                                        <form action='lecture_Assign.php' method='POST' enctype='multipart/form-data' class='add-new-form'>
                                            
                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                <br>
                                                    <label class='form-label'>Filtered Course : </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <select class='input-text' id='result' name='selectedCourse' required>
                                                        <option value='$id'>$id</option>
                                                    </select>
                                                </td>
                                            </tr>

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
                                                
                                                    <input type='submit' value='Update' name='update' class='login-btn btn-primary btn'>
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
            //delete all results form
            if($action == 'deleteAll') {

                echo"
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <h2>Are you sure?</h2>
                            <a class='close' href='lecture_Assign.php'>&times;</a>
                            <div class='content'>
                                You want to delete this record<br>   
                            </div>
                            <div style='display: flex;justify-content: center;'>
                                <form action='lecture_Assign.php' method='post'>
                                    <button  class='btn-primary btn' name='deleteAll' value='$id' style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'<font class='tn-in-text'>&nbsp;Yes&nbsp;</font></button>
                                </form>
                                <a href='lecture_assign.php' class='non-style-link'><button  class='btn-primary btn'  style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'><font class='tn-in-text'>&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>
                            </div>
                        </center>
                    </div>
                </div>";
            }
        }
    ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>

    $(document).ready(function(){
        $(course).change(function(){
            var inputCourse = $(course).find(':selected').val();
            
            $.ajax({
                url:'select_course.php',
                method:'POST',
                data:{selectedCourse:inputCourse},
                success:function(data){
                    $('#result').html(data);
                }
                
            });
        })
        $(year).change(function(){
            var inputYear = $(year).find(':selected').val();
            $.ajax({
                url:'select_year.php',
                method:'POST',
                data:{selectedYear:inputYear},
                success:function(data){
                    $('#result').html(data);
                }
                
            });

        })
        $(semester).change(function(){
            var inputSem = $(semester).find(':selected').val();
            $.ajax({
                url:'select_sem.php',
                method:'POST',
                data:{selectedSem:inputSem},
                success:function(data){
                    $('#result').html(data);
                }
                
            });

        })
    });

</script>
</body>
</html>