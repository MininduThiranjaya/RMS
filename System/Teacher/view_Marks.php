<?php
    require_once('../../config.php');

    session_start();

    if(!isset($_SESSION["user"]["id"])) {
        header("Location: ../logout.php");
    }

    if(!isset($_GET['vcid']) || $_GET['vcid'] == null) {
        header("Location: lecture_Assign.php");
    }

    $GLOBALS['courseId'] = $_GET['vcid'];

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
    //edit individual results
    if(isset($_POST['edit'])) {

        $cid = $GLOBALS['courseId'];
        $regNu = $_POST['edit'];
        $ica1 = convertMarksIntoGrade($_POST['ica1']);
        $ica2 = convertMarksIntoGrade($_POST['ica2']);
        $ica3 = convertMarksIntoGrade($_POST['ica3']);
        $final = convertMarksIntoGrade($_POST['final']);

        if(!empty($cid) && !empty($regNu)) {

            $sql = "UPDATE studentMarks SET ica1 = '$ica1', ica2 = '$ica2', ica3 = '$ica3', final = '$final'
                    WHERE
                    courseId = '$cid'
                    AND
                    regNu = '$regNu'";

            $result = $conn->query($sql);

            if($result) {
                header('Location: view_Marks.php?action=see&sid=&vcid='.$cid);
            }
        }
    }
    //delete individual results
    if(isset($_POST['delete'])) {

        $id = $_POST['delete'];
        $cid = $GLOBALS['courseId'];

        if(!empty($id)) {

            $sql = "DELETE FROM studentMarks
                    WHERE regNu = '$id'
                    AND
                    courseId = '$cid'";
            
            $result = $conn->query($sql);

            if($result) {
                header('Location: view_Marks.php?action=see&sid=&vcid='.$cid);
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
                    <td class="menu-btn menu-icon-doctor">
                        <a href="lecture_Assign.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">All Subjects</p></a></div>
                    </td>
                </tr>

            </table>
        </div>

        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr>
                    <td width=30%>
                    </td>

                    <td width=40%>
                        <form action="view_Marks.php?vcid=<?php echo $_GET['vcid']?>" method="post" class="header-search">
                            <input type="text" name="searchText" id='searchCentent' class="input-text header-searchbar" placeholder="Search Student" list="doctors">&nbsp;&nbsp;
                            <input type="Submit" name="search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        </form>  
                    </td>

                    <td width=30%>
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
                    </td>

                    
                </tr>

                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">Subject Marks - <?php echo $_GET['vcid']?></p>
                    </td>
                </tr>
                <table width=100%>
                <tr>
                    <td width=25%>
                        <a href="marks_Status.php?exam=ica1&vcid=<?php echo $GLOBALS['courseId'];?>">
                        <div class="dashboard-items"  style="padding:20px;margin:auto;width:100%;display: flex">
                            <div>
                                <div class="h3-dashboard">
                                        ICA 01 
                                </div>
                            </div>
                        </div>
                        </a>
                    </td>

                    <td width=25%>
                        <a href="marks_Status.php?exam=ica2&vcid=<?php echo $GLOBALS['courseId'];?>">
                        <div class="dashboard-items"  style="padding:20px;margin:auto;width:100%;display: flex;">
                            <div>
                            
                                <div class="h3-dashboard">
                                        ICA 02
                                </div>
                            </div>
                        </div>
                        </a>
                    </td>

                    <td width=25%>
                        <a href="marks_Status.php?exam=ica3&vcid=<?php echo $GLOBALS['courseId'];?>">
                        <div class="dashboard-items"  style="padding:20px;margin:auto;width:100%;display: flex; ">
                            <div>
                            
                                <div class="h3-dashboard" >
                                        ICA 03
                                </div>
                            </div>    
                        </div>
                        </a>
                    </td>

                    <td width=25%>
                        <a href="marks_Status.php?exam=final&vcid=<?php echo $GLOBALS['courseId'];?>">
                        <div  class="dashboard-items"  style="padding:20px;margin:auto;width:100%;display: flex;">
                            <div>
                            
                                <div class="h3-dashboard" >
                                        Final
                                </div>
                            </div>    
                        </div>
                        </a>
                    </td>                 
                </tr>
                </table>
                <br>
                <br>
                <br>
                <!--table-->
                <tr>
                    <td colspan="4">    
                        <center>
                            <div class="abc scroll">
                                <table width="100%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">
                                                Course Id
                                            </th>

                                            <th class="table-headin">
                                                Student Id
                                            </th>
                                            
                                            <th class="table-headin">
                                                ICA 1
                                            </th>

                                            <th class="table-headin">
                                                ICA 2
                                            </th>
                                            
                                            <th class="table-headin">
                                                ICA 3
                                            </th>

                                            <th class="table-headin">
                                                FINAL
                                            </th>

                                            <th class="table-headin">
                                                Event
                                            </tr>                                   
                                        </tr>
                                    </thead>
                                    <tbody>
                        
                                        <?php

                                            //search for individual results
                                            if(isset($_POST['search'])) {

                                                $id = $_GET['vcid'];
                                                $search = $_POST['searchText'];
                                                
                                                if(!empty($search)) {
                                                    $sql = "SELECT * FROM studentMarks
                                                            WHERE
                                                            courseId = '$id'
                                                            AND
                                                            regNU LIKE '$search%'";
                                                    
                                                    $result = $conn->query($sql);

                                                    if($result->num_rows > 0) {
                                                        while($row = $result->fetch_assoc()) {

                                                            $courseId = $row['courseId'];
                                                            $RegNu = $row['regNu'];
                                                            $ica1 = $row['ica1'];
                                                            $ica2 = $row['ica2'];
                                                            $ica3 = $row['ica3'];
                                                            $final = $row['final'];

                                                            echo "<tr style='text-align:center;'>
                                                                <td>$courseId</td>
                                                                <td>$RegNu</td>
                                                                <td>$ica1</td>
                                                                <td>$ica2</td>
                                                                <td>$ica3</td>
                                                                <td>$final</td>
                                                                <td>
                                                                    <div style='display:flex;justify-content: center;'>
                                                                        <a href='?action=edit&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=view&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=drop&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
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
                                                    $id = $_GET['vcid'];
                                                    $sql = "SELECT * FROM studentMarks
                                                            WHERE courseId = '$id'";

                                                    $result = $conn->query($sql);

                                                    if($result->num_rows > 0) {

                                                        while($row = $result->fetch_assoc()) {

                                                            $courseId = $row['courseId'];
                                                            $RegNu = $row['regNu'];
                                                            $ica1 = $row['ica1'];
                                                            $ica2 = $row['ica2'];
                                                            $ica3 = $row['ica3'];
                                                            $final = $row['final'];

                                                            echo "<tr style='text-align:center;'>
                                                                <td>$courseId</td>
                                                                <td>$RegNu</td>
                                                                <td>$ica1</td>
                                                                <td>$ica2</td>
                                                                <td>$ica3</td>
                                                                <td>$final</td>
                                                                <td>
                                                                    <div style='display:flex;justify-content: center;'>
                                                                        <a href='?action=edit&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=view&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=drop&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
                                                                    </div>
                                                                </td>
                                                            </tr>";
                                                        }
                                                    }
                                                }
                                            }
                                            //load all results of students
                                            else {

                                                $id = $_GET['vcid'];

                                                $sql = "SELECT * FROM studentMarks
                                                        WHERE courseId = '$id'";

                                                $result = $conn->query($sql);

                                                if($result) {

                                                    while($row = $result->fetch_assoc()) {
                                                        
                                                        $courseId = $row['courseId'];
                                                        $RegNu = $row['regNu'];
                                                        $ica1 = $row['ica1'];
                                                        $ica2 = $row['ica2'];
                                                        $ica3 = $row['ica3'];
                                                        $final = $row['final'];

                                                        echo "<tr style='text-align:center;'>
                                                            <td>$courseId</td>
                                                            <td>$RegNu</td>
                                                            <td>$ica1</td>
                                                            <td>$ica2</td>
                                                            <td>$ica3</td>
                                                            <td>$final</td>
                                                            <td>
                                                                <div style='display:flex;justify-content: center;'>
                                                                    <a href='?action=edit&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                    <a href='?action=view&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                    <a href='?action=drop&sid=$RegNu&vcid=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
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
        if(isset($_GET)) {
            
            $action = $_GET['action'];
            $sid = $_GET['sid'];
            $cid = $_GET['vcid'];

            //delete individual result form
            if($action == 'drop') {

                echo"
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <h2>Are you sure?</h2>
                            <a class='close' href='view_Marks.php?action=see&sid=&vcid=$cid'>&times;</a>
                            <div class='content'>
                                You want to delete this record<br>   
                            </div>
                            <div style='display: flex;justify-content: center;'>
                                <form action='view_Marks.php?action=see&sid=&vcid=$cid' method='post'>
                                    <button  class='btn-primary btn' name='delete' value='$sid' style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'<font class='tn-in-text'>&nbsp;Yes&nbsp;</font></button>
                                </form>
                                <a href='view_Marks.php?action=see&sid=&vcid=$cid' class='non-style-link'><button  class='btn-primary btn'  style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'><font class='tn-in-text'>&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>
                            </div>
                        </center>
                    </div>
                </div>";
            }
            //view individual result form 
            else if($action == 'view') {

                $sql = "SELECT * FROM studentMarks
                        WHERE courseId = '$cid'
                        AND
                        regNu = '$sid'";

                $result = $conn->query($sql);

                if($result) {
                    $row = $result->fetch_assoc();
                    
                    $courseId = $row['courseId'];
                    $RegNu = $row['regNu'];
                    $ica1 = $row['ica1'];
                    $ica2 = $row['ica2'];
                    $ica3 = $row['ica3'];
                    $final = $row['final'];

                    echo "
                    <div id='popup1' class='overlay'>
                        <div class='popup'>
                            <center>
                                <h2></h2>
                                <a class='close' href='view_Marks.php?action=see&sid=&vcid=$cid'>&times;</a>
                                <div class='content'>
                                    Teacher Details Document
                                    <br>
                                </div>
                                <div style='display: flex;justify-content: center;'>

                                    <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                        <tr>
                                            <td width=20%>
                                                <p style='padding: 0;margin: 0; text-align: left;font-size: 25px;font-weight: 500;'>Details</p><br><br>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                <label for='name' class='form-label'>Course Id : </label>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                $courseId
                                                <br>
                                                <br>
                                            </td>
                                            
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                <label for='Email' class='form-label'>Student Id : </label>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                            $RegNu
                                            <br>
                                            <br>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' style='width:20%'>
                                                <label for='Email' class='form-label'>ICA1 : </label>
                                            </td>
                                            <td class='label-td' >
                                                <label for='Email' class='form-label'>ICA2 : </label>
                                            </td>
                                            <td class='label-td' >
                                                <label for='Email' class='form-label'>ICA3 : </label>
                                            </td>
                                            <td class='label-td' >
                                                <label for='Email' class='form-label'>FINAL : </label>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' style='width:20%'>
                                                $ica1
                                                <br>
                                                <br>
                                            </td>
                                            <td class='label-td'>
                                                $ica2
                                                <br>
                                                <br>
                                            </td> 
                                            <td class='label-td'>
                                                $ica3
                                                <br>
                                                <br>
                                            </td> 
                                            <td class='label-td'>
                                                $final
                                                <br>
                                                <br>
                                            </td> 
                                        </tr>

                                        <tr>
                                            <td colspan='2'>
                                                <a href='view_Marks.php?action=see&sid=&vcid=$cid'><input type='button' value='OK' class='login-btn btn-primary-soft btn' ></a> 
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
            //edit individualo result form
            else if($action == 'edit') {

                $sql = "SELECT * FROM studentMarks
                        WHERE courseId = '$cid'
                        AND
                        regNu = '$sid'";

                $result = $conn->query($sql);

                if($result) {
                    $row = $result->fetch_assoc();
                    
                    $courseId = $row['courseId'];
                    $RegNu = $row['regNu'];
                    $ica1 = $row['ica1'];
                    $ica2 = $row['ica2'];
                    $ica3 = $row['ica3'];
                    $final = $row['final'];

                    echo "
                    <div id='popup1' class='overlay'>
                        <div class='popup'>
                            <center>
                                <a class='close' href='view_Marks.php?action=see&sid=&vcid=$cid'>&times;</a> 
                                <div style='display: flex;justify-content: center;'>
                                    <div class='abc'>
                                        <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                            
                                            <tr>
                                                <td>
                                                    <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Edit Teacher Name - $id</p><br><br>
                                                </td>
                                            </tr>
                                            
                                            <form action='view_Marks.php?action=see&sid=&vcid=$cid' method='POST' class='add-new-form'>
                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <label class='form-label'>ICA 1 : </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <input type='text' name='ica1' class='input-text' placeholder='Course Name' value='$ica1'><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <label class='form-label'>ICA 2 : </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <input type='text' name='ica2' class='input-text' placeholder='Course Name' value='$ica2'><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <label class='form-label'>ICA 3 : </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <input type='text' name='ica3' class='input-text' placeholder='Course Name' value='$ica3'><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <label class='form-label'>Final : </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <input type='text' name='final' class='input-text' placeholder='Course Name' value='$final'><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan='2'>
                                                        <input type='reset' value='Reset' class='login-btn btn-primary-soft btn' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        
                                                        <button  class='btn-primary btn' name='edit' value='$sid' style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'<font class='tn-in-text'>&nbsp;Edit&nbsp;</font></button

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
        }
    ?>
</body>
</html>