<?php
    require_once('../../config.php');
    session_start();

    if(!isset($_SESSION["user"]["id"])) {
        header("Location: ../logout.php");
    }

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

    if(!empty($_POST['selectedSem']) && !empty($_SESSION['year'])) {

        $userId = $_SESSION['user']['id'];

        $pattern = $_SESSION['year'].$_POST['selectedSem'];

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
                        <td style='padding:20px;font-size:20px;font-weight:700;width:20%'> 
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
                    </tr>
                ";
            }
            echo "
                <tr style='text-align:center;'>
                    <td style='padding:10px;font-size:20px;font-weight:700;width:20%' colspan=4>GPA of ".$_SESSION['year']." year ".$_POST['selectedSem']. " semester</td>
                    <td> <b>".calculateGPA()."</b></td>
                </tr>";

        }

    }
    else {
        $userId = $_SESSION['user']['id'];

        $pattern = $_POST['selectedSem'];

        $sqlTwo = "SELECT * FROM studentMarks
                    WHERE
                    regNu = '$userId' 
                    AND 
                    (courseId LIKE 'IT_$pattern%' 
                    OR courseId LIKE 'AMC_$pattern%' 
                    OR courseId LIKE 'BIO_$pattern%')";

        $resultTwo = $conn->query($sqlTwo);
        
        if($resultTwo->num_rows > 0) {
        
            while($rowTwo = $resultTwo->fetch_assoc()) {

                $courseId = $rowTwo['courseId'];
                $ica1 = $rowTwo['ica1'];
                $ica2 = $rowTwo['ica2'];
                $ica3 = $rowTwo['ica3'];
                $final = $rowTwo['final'];
                if(!empty($ica1)) {
                    totalValueOfCreditAndCreditGrade($courseId, $ica1);
                }

                echo "
                    <tr style='text-align:center;'>
                        <td style='padding:20px;font-size:20px;font-weight:700;width:20%'> 
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
                    </tr>
                ";
            }
        }
    }

?>