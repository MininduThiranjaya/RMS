<?php

    include_once("../config.php");

    session_start();

    $GLOBALS['errorMessage'] = "";

    if(isset($_POST["login"])) {

        function sanitizedString($conn, $stringText) {
            
            return mysqli_real_escape_string($conn, $stringText);
        }

        function login($conn, $regNu, $password, $userRole) {
        
            $sql = "SELECT * FROM $userRole
                    WHERE
                    regNu = '$regNu'";

            $result = $conn->query($sql);

            if($result->num_rows > 0) {

                $row = $result->fetch_assoc();

                $regNu = $row["regNu"];
                $userName = $row["userName"];
                $hashedPassword = $row["password"];
                $imageUrl = $row["imageUrl"];
                
                if(password_verify($password, $hashedPassword)) {
                    
                    $_SESSION["user"]["id"] = $regNu;
                    $_SESSION["user"]["userName"] = $userName;
                    $_SESSION["user"]["image"] = $imageUrl;
                    
                    if($userRole == 'adminLogIn') {
                        header("Location: Admin/admin_Dash.php");
                    }
                    else if($userRole == 'teacherLogIn') {
                        header("Location: Teacher/teacher_Dash.php");
                    } 
                    else {
                        header("Location: Student/student_Dash.php");
                    } 
                }
                else {
                    $GLOBALS['errorMessage'] = "Invalied Login Credentials.!";
                }
            }
            else{ 
                $GLOBALS['errorMessage'] = "Invalied Login Credentials.!";
            }
        }

        $regNu = sanitizedString($conn, $_POST["regNu"]);
        $password = sanitizedString($conn, $_POST["password"]);
        $userRole = $_POST["userRole"];
    
        login($conn, $regNu, $password, $userRole);
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
    <link rel="stylesheet" href="../css/login.css">
    <style>
        span{
            color: red;
        }
    </style>
</head>
<body> 
    <center>
        <div class="container">
            <table border="0" style="margin: 0;padding: 0;width: 60%;">
                <tr>
                    <td>
                        <p class="header-text">Welcome</p>
                    </td>
                </tr>

                <div class="form-body">
                    <tr>
                        <td>
                            <p class="sub-text">Login with your details to continue</p>
                            <span><?php echo $GLOBALS['errorMessage']?></span>
                        </td>
                    </tr>
                    <form action="index.php" method="POST" >
                        <tr>
                            <td class="label-td">
                                <label class="form-label">User Role : </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <select name="userRole" class="input-text" required>
                                    <option value="">Choose User Role</option>
                                    <option value="adminLogIn">Admin</option>
                                    <option value="teacherLogIn">Lecture</option>
                                    <option value="studentLogIn">Student</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <label class="form-label">Registration Number : </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <input type="text" name="regNu" class="input-text" placeholder="Registration Number" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <label class="form-label">Password : </label>
                            </td>
                        </tr>

                        <tr>
                            <td class="label-td">
                                <input type="password" name="password" class="input-text" placeholder="Password" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <br>
                                <input type="submit" value="Login" name="login" class="login-btn btn-primary btn">
                            </td>
                        </tr>
                    </form>
                </div>
                <tr>
                    <td>
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account</label>
                        <a href="student_Registration.php" class="hover-link1 non-style-link">Register Now</a>
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