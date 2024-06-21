<?php
    require_once('../../config.php');

    session_start();

    if(!isset($_SESSION["user"]["id"])) {
        header("Location: ../logout.php");
    }

    if((!isset($_GET['exam']) || $_GET['exam'] == null) || (!isset($_GET['vcid']) || $_GET['vcid'] == null)) {
        header("Location: lecture_Assign.php");
    }

    $type = 'bar';
    //set chart type
    if(isset($_POST['type'])) {
        echo $_POST['type'];
        $type = $_POST['type'];
    }
    //find course name
    function findCourseCount($course) {

        $length = strlen($course);
        $courseName = '';

        for($i = 0; $i < $length; $i++) {

            
            if(is_numeric($course[$i])) {
                if($courseName == 'IT') {
                    return "SELECT COUNT(regNu) as count FROM student WHERE course = 'ICT'";
                }
                else if($courseName == 'AMC') {
                    return "SELECT COUNT(regNu) as count FROM student WHERE course = 'AMC'";
                }
                else {
                    return "SELECT COUNT(regNu) as count FROM student WHERE course = 'BIO'";
                }
            }
            else {
                $courseName = $courseName . $course[$i];
            }
        }
    }

    $countSqlQuery = findCourseCount($_GET['vcid']);

    $result = $conn->query($countSqlQuery);
    //get total number of students
    $GLOBALS['courseStudentCount'] = $result->fetch_assoc()['count'];

    $GLOBALS['dataPoints'] = array();
    $GLOBALS['totalAttendence'] = 0;
    //set all grades
    $gradeArray = array(
        'A+','A','A-','B+','B','B-','C+','C','C-','D+','E',
    );
    //get count of a grade one by one
    function getGrade($exam, $courseId, $grade, $conn) {

        $sql = '';

        switch($exam) {

            case 'ica1':
                $sql = "SELECT COUNT(ica1) AS count FROM studentMarks
                        WHERE
                        courseId = '$courseId'
                        AND
                        ica1 = '$grade'";
                break;
            case 'ica2':
                $sql = "SELECT COUNT(ica1) AS count FROM studentMarks
                        WHERE
                        courseId = '$courseId'
                        AND
                        ica2 = '$grade'";
                break;
            case 'ica3':
                $sql = "SELECT COUNT(ica1) AS count FROM studentMarks
                        WHERE
                        courseId = '$courseId'
                        AND
                        ica3 = '$grade'";
                break;
            case 'final':
                $sql = "SELECT COUNT(ica1) AS count FROM studentMarks
                        WHERE
                        courseId = '$courseId'
                        AND
                        final = '$grade'";
                break;
        }

        $result = $conn->query($sql);

        if($result) {
            $count = $result->fetch_assoc()['count'];
            $GLOBALS['totalAttendence'] = $GLOBALS['totalAttendence'] + $count;
            $resultArray = array('y' => $count, 'label' => $grade);
            array_push($GLOBALS['dataPoints'], $resultArray);
        }
    }
    //execute grade one by one
    foreach($gradeArray as $x) {
        
        getGrade($_GET['exam'], $_GET['vcid'], $x, $conn);
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
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr width=100%>
                    <td width=20%>
                        <form action='marks_Status.php?exam=<?php echo $_GET['exam']?>$vcid=<?php echo $_GET['vcid']?>' method = 'post'>
                            <select name='type' class='input-text' id='chartType' onchange="updateChart()">
                                <option value=''>Select Charts</option>
                                <option value='bar'>Bar Chart</option>
                                <option value='line'>Line Chart</option>
                                <option value='pie'>Pie Chart</option>
                                <option value='spline'>Spline Chart</option>
                                <option value='column'>Column Chart</option>
                            </select>
                        </form>
                    </td>

                    <td width=40% style='text-align:center;'>
                        <h2>Student participatient <?php echo $GLOBALS['totalAttendence']?> from <?php echo $GLOBALS['courseStudentCount']?></h2>
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

                    <td>
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../../images/download.png" width="100%"></button>
                    </td>
                </tr>
               
                <br>
                <br>
                <br>
                <!--table-->
                <tr>
                    <td colspan="4">    
                        <center>
                            <div class="abc scroll">
                                <table width="100%" class="sub-table scrolldown" border="0">
                                    <tbody>
                                    <div id="chartContainer" style="height: 370px; width: 90%;"></div>
                                    </tbody>
                                </table>
                            </div>
                        </center>
                   </td> 
                </tr>          
            </table>
        </div>
    </div>
</div>
<script>
    //update chart by chart type
    function updateChart() {
        
        const chartType = document.getElementById('chartType').value;

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title:{
                text: "Result Chart"
            },
            axisY: {
                title: "Number of students",
                includeZero: true,
            },
            data: [{
                type: chartType,
                indexLabel: "{y}",
                indexLabelPlacement: "inside",
                indexLabelFontWeight: "bolder",
                indexLabelFontColor: "white",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render(); 
    }

    $(document).ready(function() {
        updateChart();
    });
    
</script>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>