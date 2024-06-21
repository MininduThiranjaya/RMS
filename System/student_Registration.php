<?php

    require_once("../config.php");
    
    $namePattern = "/^[a-zA-Z ]+$/";
    $regNuPatternStudent = "/^20[0-9]{2}\/[a-z]{3}\/\d{1,3}+$/";
    $regNuPatternTeacher = "/IDT\d{4}/";
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    $GLOBALS['passwordError'] = "";
    $GLOBALS['nameError'] = "";
    $GLOBALS["submitError"] = "";
    $GLOBALS["regNuError"] = "";

    if((isset($_POST["register"]))) {

        //Sanitizing user input
        function sanitizedString($conn, $stringText) {
            
            return mysqli_real_escape_string($conn, $stringText);
        }

        //Validating registrationNumber
        function validateRegNumber($conn, $namePattern, $value, $error) {

            if (preg_match($namePattern, $value)) {
                return sanitizedString($conn, $value);
            }
            else {
                $GLOBALS['regNuError'] = $error;
                return "";
            }
        }

        //Validating userName
        function validateName($conn, $namePattern, $value) {

            if (preg_match($namePattern, $value)) {
                return sanitizedString($conn, $value);
            }
            else {
                $GLOBALS['nameError'] = "* Only letters can be used..!";
                return "";
            }
        }

        //validate password
        function validatePassword($conn, $passwordPattern, $value) {

            if (preg_match($passwordPattern, $value)) {
                return sanitizedString($conn, $value);
            }
            else {
                $GLOBALS['passwordError'] = "* Ex - Abcd@1234";
                return "";
            }
        }

        //start from here
        $password = $_POST["password"];
        $conPassword = $_POST["conPassword"];
        $userRole = $_POST["userRole"];
        
        if($password == $conPassword) {

            function registerUser($userId, $password, $userName, $table, $conn) {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $sql = "INSERT INTO $table(regNu, userName, password, imageUrl)
                            VALUES('$userId', '$userName', '$hashedPassword', 'profile_Images/default.png')";
    
                    $result = $conn->query($sql);
    
                    if($result) {
                        header('Location: index.php');
                        die;
                    }
                    else {
                        $GLOBALS["submitError"] = "Registration Failed...!";
                    }
            }

            if($userRole == "teachertLogIn"){
                $lecId = validateRegNumber($conn, $regNuPatternTeacher, $_POST["regNu"], '* Ex - IDT0001..!');
                $userName = validateName($conn, $namePattern, $_POST["userName"]);
                $password = validatePassword($conn, $passwordPattern, $_POST["password"]);

                if(!empty($lecId) && !empty($password) && !empty($userName)) {
                
                    registerUser($lecId, $password, $userName, 'teacherLogIn', $conn);
                }
                else {
                    $GLOBALS["submitError"] = "Registration Failed...!";
                }


            }
            else { 
                $regNu = validateRegNumber($conn, $regNuPatternStudent, $_POST["regNu"], '* Ex - 2020/ICT/49..!');
                $userName = validateName($conn, $namePattern, $_POST["userName"]);
                $password = validatePassword($conn, $passwordPattern, $_POST["password"]);

                if(!empty($regNu) && !empty($password) && !empty($userName)) {
                
                    registerUser($regNu, $password, $userName, 'studentLogIn', $conn);
                }
                else {
                    $GLOBALS["submitError"] = "Registration Failed...!";
                }
            }
            


        }
        else {
            $GLOBALS['passwordError'] = "* Password Miss Match...!";
            $GLOBALS["submitError"] = "Registration Failed...!";
            
        }

            
            
            
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/signup.css">
    <style>
        span{
            color: red;
        }
    </style>
</head>
<body>
    <center>
        <div class="container">
            <table border="0" style="width: 69%;">
                <tr>
                    <td colspan="2">
                        <p class="header-text">Let's Get Started</p>
                        <p class="sub-text">Now Create User Account.</p>
                        <span><?php echo $GLOBALS['submitError']?></span>
                    </td>
                </tr>
                <form action="student_Registration.php" method="POST" >
                    <tr>
                        <td class="label-td" colspan="2">
                            <label class="form-label">User Role : </span> </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <select name="userRole" class="input-text" required>
                                <option value="">Choose User Role</option>
                                <option value="teachertLogIn">Lecture</option>
                                <option value="studentLogIn">Student</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <label class="form-label">Registration Number : <span><?php echo $GLOBALS['regNuError']?></span> </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="text" name="regNu" class="input-text" placeholder="Registration Number" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <label class="form-label">User Name : <span><?php echo $GLOBALS['nameError']?></span></label>
                        </td>                    
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="text" name="userName" class="input-text"  placeholder="User Name" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <label class="form-label">New Password : <span><?php echo $GLOBALS['passwordError']?></span></label>
                        </td>                    
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="password" name="password" class="input-text" placeholder="New Password" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <label class="form-label">Conform Password : </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="password" name="conPassword" class="input-text" placeholder="Conform Password" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >
                        </td>
                        <td>
                            <input type="submit" value="Register" name="register" class="login-btn btn-primary btn">
                        </td>
                    </tr>
                </form>
                <tr>
                    <td colspan="2">
                        <br>
                            <label for="" class="sub-text" style="font-weight: 280;">Already have an account</label>
                            <a href="index.php" class="hover-link1 non-style-link">Login</a>
                        <br><br><br>
                    </td>
                </tr>
            </table>
        </div>
    </center>

    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>
</html>



