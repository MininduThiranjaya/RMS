<?php

    require_once('../../config.php');
    session_start();

    $teacherId= $_SESSION["user"]["id"];
    
    $_SESSION['course'] = $_POST['selectedCourse'];
    
    if(!empty($_SESSION['course'])) {

        $courseCategory = $_SESSION['course'];

        $check = $courseCategory;

        $sql = "SELECT * FROM lectureAssign
                JOIN course ON
                course.courseId = lectureAssign.courseId
                JOIN teacher ON
                teacher.id = lectureAssign.teacherId
                WHERE 
                lectureAssign.courseId LIKE '$check%'
                AND
                teacherId = '$teacherId'";
        
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
    
?>