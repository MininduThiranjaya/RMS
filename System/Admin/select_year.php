<?php

    require_once('../../config.php');
    session_start();
    $_SESSION['year'] = '';
    

    if(!empty($_POST['selectedYear'])) {
        $_SESSION['year'] = $_POST['selectedYear'];

        if(!empty($_SESSION['course'])) {
        
            $courseCategory = $_SESSION['course'];
            $courseYear = $_SESSION['year'];

            $check = $courseCategory.$courseYear;
    
            $sql = "SELECT * FROM course
                    WHERE courseId LIKE '$check%'";
            
            $result = $conn->query($sql);
    
            if($result->num_rows > 0) {
                echo "
                    <option value=''>Filtered Courses</option>";
    
                while($row = $result->fetch_assoc()) {
                    $courseId = $row['courseId'];
                    $courseName = $row['courseName'];
                    echo"
                        <option value='$courseId'>$courseId - $courseName</option>";
                }
            }
            else {
                echo"
                    <option value=''>No Filtered Courses</option>";
            }
        }
    }
    
    
?>