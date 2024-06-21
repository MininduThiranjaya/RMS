<?php
   require_once('../../config.php');
   session_start();

   if(!isset($_SESSION["user"]["id"])) {
      header("Location: ../logout.php");
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
        
    <title>Student Dashboard</title>

    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table,.anime{
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
                                    <img src="<?php echo "../".$_SESSION['user']['image']?>" alt="Profile Picture" width="100%" style="border-radius:50%">
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
                    <td class="menu-btn menu-icon-home menu-active menu-icon-home-active" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                
            </table>
        </div>

        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >     
                <tr>          
                    <td colspan="1" class="nav-bar" >
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Home</p>                   
                    </td>
                    <td width="25%">
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                                date_default_timezone_set('Asia/Kolkata');

                                $today = date('Y-m-d');
                                echo $today;
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../../images/download.png" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" >   
                        <center>
                            <table class="filter-container doctor-header patient-header" style="border: none;width:95%" border="0" >
                                <tr>
                                    <td >
                                        <h3>Welcome!</h3>
                                        <h1><?php echo $_SESSION['user']['userName']?></h1>
                                        
                                        <form action="student_Dash.php" method="post" style="display: flex">
                                            
                                            <select class='input-text' id='year' name='year'>
                                                        <option value=''>Year</option>
                                                        <option value='1'>First</option>
                                                        <option value='2'>Second</optiodn>
                                                        <option value='3'>Third</optiodn>
                                            </select>
                                            <select class='input-text' id='semester' name='semester'>
                                                        <option >Semester</option>
                                                        <option value='1'>First</option>
                                                        <option value='2'>Second</option>
                                            </select>
                                            
                                            <br>
                                            <br>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </center>    
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table border="0" width="100%"">
                            <tr>
                                <td>
                                    <p style="font-size: 20px;font-weight:600;padding-left: 40px;" class="anime">Results</p>
                                    <center>
                                        <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                                            <table width="85%" class="sub-table scrolldown" border="0" >
                                                <thead>
                                                    <tr>
                                                        <th class="table-headin">                               
                                                            Subject Id
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
                                                    
                                                    </tr>
                                                </thead>
                                                
                                                <tbody id = 'results'>
                                                    <?php

                                                        $GLOBALS['totalCreditAndGrade'] = 0;
                                                        $GLOBALS['totalCredit'] = 0;
                                                        //find course credit value
                                                        function findCourseCeditValue($course) {

                                                            $length = strlen($course);

                                                            for($i = 0; $i < $length; $i++) {

                                                                $ceditValueIndex = $i + 3;
                                                                if(is_numeric($course[$i])) {
                                                                    return ($course[$ceditValueIndex]);
                                                                }
                                                            }
                                                        }
                                                        //find subject grade value
                                                        function findGradeValue($grade) {

                                                            switch($grade) {
                                                                case 'A+':
                                                                    return 4;
                                                                case 'A':
                                                                    return 4;
                                                                case 'A-':
                                                                    return 3.7;
                                                                case 'B+':
                                                                    return 3.3;
                                                                case 'B':
                                                                    return 3;
                                                                case 'B-':
                                                                    return 2.7;
                                                                case 'C+':
                                                                    return 2.3;
                                                                case 'C':
                                                                    return 2;
                                                                case 'C-':
                                                                    return 1.7;
                                                                case 'D+':
                                                                    return 1.3;
                                                                case 'D':
                                                                    return 1;
                                                                case 'E':
                                                                    return 0;
                                                            }
                                                        }
                                                        //find total of all subject GPA and total course credit value
                                                        function totalValueOfCreditAndCreditGrade($courseId, $grade) {

                                                            $creditValue = findCourseCeditValue($courseId);
                                                            $gradeValue = findGradeValue($grade);

                                                            $creditAndGrade = $creditValue * $gradeValue;

                                                            $GLOBALS['totalCreditAndGrade'] = $GLOBALS['totalCreditAndGrade'] + $creditAndGrade;
                                                            $GLOBALS['totalCredit'] = $GLOBALS['totalCredit'] + $creditValue;
                                                        }
                                                        //find GPA of the semester
                                                        function calculateGPA() {
                                                            return ($GLOBALS['totalCreditAndGrade']/$GLOBALS['totalCredit']);
                                                        }
                                                        
                                                        $userId = $_SESSION['user']['id'];

                                                        $sql = "SELECT * FROM student
                                                                WHERE regNu = '$userId'";

                                                        $result = $conn->query($sql);
                                                        //load current semester results
                                                        if($result->num_rows > 0) {
                                                            
                                                            $row = $result->fetch_assoc();

                                                            $year = $row['year'];
                                                            $sem = $row['semester'];
                                                            $pattern = $year.$sem;

                                                            $sqlTwo = "SELECT * FROM studentMarks
                                                                        WHERE
                                                                        regNu = '$userId' 
                                                                        AND 
                                                                        (courseId LIKE 'IT$pattern%' 
                                                                        OR courseId LIKE 'AMC$pattern%' 
                                                                        OR courseId LIKE 'BIO$pattern%')";

                                                            $resultTwo = $conn->query($sqlTwo);
                                                            
                                                            if($resultTwo->num_rows > 0) {
                                                                
                                                                while($rowTwo = $resultTwo->fetch_assoc()) {

                                                                    $courseId = $rowTwo['courseId'];
                                                                    $ica1 = $rowTwo['ica1'];
                                                                    $ica2 = $rowTwo['ica2'];
                                                                    $ica3 = $rowTwo['ica3'];
                                                                    $final = $rowTwo['final'];
                                                                    if(!empty($final)) {
                                                                        totalValueOfCreditAndCreditGrade($courseId, $final);
                                                                    }

                                                                    echo "
                                                                        <tr style='text-align:center;'>
                                                                            <td style='padding:30px;font-size:20px;font-weight:700;width:20%'> 
                                                                                $courseId
                                                                            </td>
                                                                            <td>
                                                                                $ica1
                                                                            </td>
                                                                            <td>
                                                                                $ica2
                                                                            </td>
                                                                            <td>
                                                                                $ica3
                                                                            </td>
                                                                            <td>
                                                                                $final
                                                                            </td>
                                                                        </tr>";
                                                                }
                                                                echo "
                                                                    <tr style='text-align:center;'>
                                                                        <td style='padding:10px;font-size:20px;font-weight:700;width:20%' colspan=4>Current Semester GPA </td>
                                                                        <td> <b>".calculateGPA()."</b></td>
                                                                    </tr>";

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
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        
        $(document).ready(function(){
            $(year).change(function(){
                var inputYear = $(year).find(':selected').val();
                $.ajax({
                    url:'select_year.php',
                    method:'POST',
                    data:{selectedYear:inputYear},
                    success:function(data){
                        $('#results').html(data);
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
                        $('#results').html(data);
                    }
                    
                });

            })
        });
    </script>
</body>
</html>