<?php
    require_once('../../config.php');
    session_start();

    if(!isset($_SESSION["user"]["id"])) {
        header("Location: ../logout.php");
    }

    $_SESSION['year'] = '';
    
    if(!empty($_POST['selectedYear'])) {

        $userId = $_SESSION['user']['id'];

        $pattern = $_POST['selectedYear'];

        $_SESSION['year'] = $pattern;

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