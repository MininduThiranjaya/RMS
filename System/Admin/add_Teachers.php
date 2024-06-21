<?php
    require_once('../../config.php');
    session_start();

    if(!isset($_SESSION['user']['id'])) {
        header("Location: ../logout.php");
    }
    //add lecture details into database
    if(isset($_POST['add'])) {

        $id = $_POST['id'];
        $name = $_POST['name'];

        if(!empty($id) && !empty($name)) {

            $sql = "INSERT INTO teacher(id, name)
                    VALUES('$id', '$name')";
            
            $result = $conn -> query($sql);
            
            if($result) {
                header('Location: add_Teachers.php');
            }
        }
    }
    //edit lecture details 
    if(isset($_POST['edit'])) {

        $id = $_POST['edit'];
        $name = $_POST['name'];

        if(!empty($id) && !empty($name)) {

            $sql = "UPDATE teacher SET name ='$name' 
                    WHERE id = '$id'";
            
            $result = $conn -> query($sql);
            
            if($result) {
                header('Location: add_Teachers.php');
            }
        }
    }
    //remove lecture from databse
    if(isset($_POST['delete'])) {

        $id = $_POST['delete'];
        if(!empty($id)) {

            $sql = "DELETE lectureAssign FROM lectureAssign
                    JOIN teacher ON
                    teacher.id = lectureAssign.teacherId
                    WHERE teacher.id='$id'";
            
            $sqlTwo = "DELETE FROM teacher
                        WHERE
                        id = '$id'";
            
            $resultOne = $conn->query($sql);
            $resultTwo = $conn->query($sqlTwo);
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
        
    <title>Teacher</title>
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
                                    <img src="<?php echo "../".$_SESSION['user']['image']?>" width=100% alt='' style="border-radius:50%">
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
                    <td class="menu-btn menu-icon-doctor menu-active menu-icon-doctor-active">
                        <a href="add_Teachers.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Lectures</p></a></div>
                    </td>
                </tr>

                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="add_Students.php" class="non-style-link-menu"><div><p class="menu-text">Students</p></div></a>
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
                    <td width="13%">
                    </td>

                    <td>
                        <form action="add_Teachers.php" method="post" class="header-search">
                            <input type="text" name="searchText" class="input-text header-searchbar" placeholder="Search Lecture" list="doctors">&nbsp;&nbsp;
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
                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Add New Lecture</p>
                    </td>

                    <td colspan="2">
                        <a href="?action=add&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="display: flex;justify-content: center;align-items: center;margin-left:75px;background-image: url('../img/icons/add.svg');">Add New</font></button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Lectures</p>
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
                                                Teacher Id
                                            </th>

                                            <th class="table-headin">
                                                Name
                                            </th>  
                                            <th class="table-headin">
                                                Event
                                            </tr>                                   
                                        </tr>
                                    </thead>
                                    <tbody>
                        
                                        <?php

                                            //search lectures 
                                            if(isset($_POST['search'])) {
                                                $search = $_POST['searchText'];
                                                
                                                if(!empty($search)) {
                                                    $sql = "SELECT * FROM teacher
                                                            WHERE
                                                            id LIKE '$search%'
                                                            OR name LIKE '$search%'";
                                                    
                                                    $result = $conn->query($sql);

                                                    if($result->num_rows > 0) {
                                                        while($row = $result->fetch_assoc()) {

                                                            $id = $row['id'];
                                                            $name = $row['name'];

                                                            echo "<tr style='text-align:center;'>
                                                                <td>$id</td>
                                                                <td>$name</td>
                                                                <td>
                                                                    <div style='display:flex;justify-content: center;'>
                                                                        <a href='?action=edit&id=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=view&id=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=drop&id=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
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
                                                    $sql = "SELECT * FROM teacher";

                                                    $result = $conn->query($sql);

                                                    if($result->num_rows > 0) {

                                                        while($row = $result->fetch_assoc()) {

                                                            $id = $row['id'];
                                                            $name = $row['name'];

                                                            echo "<tr style='text-align:center;'>
                                                                <td>$id</td>
                                                                <td>$name</td>
                                                                <td>
                                                                    <div style='display:flex;justify-content: center;'>
                                                                        <a href='?action=edit&id= $id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=view&id= $id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        <a href='?action=drop&id= $id' &name='' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
                                                                    </div>
                                                                </td>
                                                            </tr>";
                                                        }
                                                    }
                                                }
                                            }
                                            //load all lectures
                                            else {
                                                $sql = "SELECT * FROM teacher";

                                                $result = $conn->query($sql);

                                                if($result) {

                                                    while($row = $result->fetch_assoc()) {

                                                        $id = $row['id'];
                                                        $name = $row['name'];

                                                        echo "<tr style='text-align:center;'>
                                                            <td>$id</td>
                                                            <td>$name</td>
                                                            <td>
                                                                <div style='display:flex;justify-content: center;'>
                                                                    <a href='?action=edit&id=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-edit'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Edit</font></button></a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                    <a href='?action=view&id=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-view'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;''><font class='tn-in-text'>View</font></button></a>
                                                                        &nbsp;&nbsp;&nbsp;
                                                                    <a href='?action=drop&id=$id' class='non-style-link'><button  class='btn-primary-soft btn button-icon btn-delete'  style='padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;'><font class='tn-in-text'>Remove</font></button></a>
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

            //delete lecture form 
            if($action == 'drop') {

                echo"
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <h2>Are you sure?</h2>
                            <a class='close' href='add_Teachers.php'>&times;</a>
                            <div class='content'>
                                You want to delete this record<br>.   
                            </div>
                            <div style='display: flex;justify-content: center;'>
                                <form action='add_Teachers.php' method='post'>
                                    <button  class='btn-primary btn' name='delete' value='$id' style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'<font class='tn-in-text'>&nbsp;Yes&nbsp;</font></button>
                                </form>
                                <a href='add_Teachers.php' class='non-style-link'><button  class='btn-primary btn'  style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'><font class='tn-in-text'>&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>
                            </div>
                        </center>
                    </div>
                </div>";
            }
            //view lecture details form 
            else if($action == 'view') {

                $sql = "SELECT * FROM teacher
                        WHERE id = '$id'";
                    
                $result = $conn->query($sql);

                if($result) {
                    $row = $result->fetch_assoc();
                    
                    $id = $row['id']; 
                    $name = $row['name'];
                    echo "
                    <div id='popup1' class='overlay'>
                        <div class='popup'>
                            <center>
                                <h2></h2>
                                <a class='close' href='add_Teachers.php'>&times;</a>
                                <div class='content'>
                                    Teacher Details Document
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
                                                <label for='name' class='form-label'>Teacher Id: </label>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                $id
                                                <br>
                                                <br>
                                            </td>
                                            
                                        </tr>

                                        <tr>
                                            <td class='label-td' colspan='2'>
                                                <label for='Email' class='form-label'>Name : </label>
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
                                            <td colspan='2'>
                                                <a href='add_Teachers.php'><input type='button' value='OK' class='login-btn btn-primary-soft btn' ></a> 
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
            //edit lecture details form
            else if($action == 'edit') {

                $sql = "SELECT * FROM teacher
                        WHERE id = '$id'";
                    
                $result = $conn->query($sql);

                if($result) {
                    $row = $result->fetch_assoc();
                    
                    $id = $row['id']; 
                    $name = $row['name'];

                    echo "
                    <div id='popup1' class='overlay'>
                        <div class='popup'>
                            <center>
                                <a class='close' href='add_Teachers.php'>&times;</a> 
                                <div style='display: flex;justify-content: center;'>
                                    <div class='abc'>
                                        <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                            <tr>
                                                <td>
                                                    <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Edit Teacher Name - $id</p><br><br>
                                                </td>
                                            </tr>
                                            
                                            <form action='add_Teachers.php' method='POST' class='add-new-form'>
                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <label class='form-label'>Name: </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class='label-td' colspan='2'>
                                                        <input type='text' name='name' class='input-text' placeholder='Course Name' value='$name'><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan='2'>
                                                        <input type='reset' value='Reset' class='login-btn btn-primary-soft btn' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                        <button  class='btn-primary btn' name='edit' value='$id' style='display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;'<font class='tn-in-text'>&nbsp;Edit&nbsp;</font></button
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
            //add lecture form
            else if($action == 'add') {
                echo "
                <div id='popup1' class='overlay'>
                    <div class='popup'>
                        <center>
                            <a class='close' href='add_Teachers.php'>&times;</a> 
                            <div style='display: flex;justify-content: center;'>
                                <div class='abc'>
                                    <table width='80%' class='sub-table scrolldown add-doc-form-container' border='0'>
                                        
                                        <tr>
                                            <td>
                                                <p style='padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;'>Add New Teacher</p><br><br>
                                            </td>
                                        </tr>
                                        
                                        <form action='add_Teachers.php' method='POST' class='add-new-form'>
                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <label class='form-label'>Teacher Id: </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class='label-td' colspan='2'>
                                                    <input type='text' name='id' class='input-text' placeholder='Teacher Id' required><br>
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
        }
    ?>
</div>

</body>
</html>