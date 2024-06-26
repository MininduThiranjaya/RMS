<?php
   require_once('../../config.php');
   session_start();

   if(!isset($_SESSION['user']['id'])) {
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
         
      <title>Admin Dashboard</title>
      <style>
         .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
         }
         .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
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
               <td class="menu-btn menu-icon-dashbord menu-active menu-icon-dashbord-active" >
                  <a href="admin_Dash.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Dashboard</p></a></div></a>
               </td>
            </tr>

            <tr class="menu-row">
               <td class="menu-btn menu-icon-doctor ">
                  <a href="add_Teachers.php" class="non-style-link-menu "><div><p class="menu-text">Lectures</p></a></div>
               </td>
            </tr>

            <tr class="menu-row" >
               <td class="menu-btn menu-icon-schedule">
                  <a href="add_Students.php" class="non-style-link-menu"><div><p class="menu-text">Student</p></div></a>
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

      <div class="dash-body" style="margin-top: 15px">
         <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
            <tr>
               <td>
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

                        $student = $conn->query("select  * from  student;");
                        $teacher = $conn->query("select  * from  teacher;");
                        $course = $conn->query("select  * from  course;");

                     ?>
                  </p>
               </td>

               <td width="10%">
                  <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../../images/download.png" width="100%"></button>
               </td>
         
         
            </tr>
                  
            <tr>
               <td colspan="4">                       
                  <center>
                     <table class="filter-container" style="border: none;" border="0">
                        <tr>
                           <td colspan="4">
                              <p style="font-size: 20px;font-weight:600;padding-left: 12px;">Status</p>
                           </td>
                        </tr>

                        <tr>
                           <td style="width: 25%;">
                              <div class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex">
                                 <div>
                                    <div class="h1-dashboard">
                                          <?php echo $teacher->num_rows  ?>
                                    </div><br>
                                    <div class="h3-dashboard">
                                          Lectures &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                 </div>
                                 <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/doctors-hover.svg');">
                                 </div>
                              </div>
                           </td>

                           <td style="width: 25%;">
                              <div class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;">
                                 <div>
                                    <div class="h1-dashboard">
                                          <?php echo $student->num_rows  ?>
                                    </div><br>
                                    <div class="h3-dashboard">
                                          Students &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                 </div>
                                 <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/patients-hover.svg');">
                                 </div>
                              </div>
                           </td>

                           <td style="width: 25%;">
                              <div class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex; ">
                                 <div>
                                    <div class="h1-dashboard" >
                                          <?php echo $course ->num_rows  ?>
                                    </div><br>
                                    <div class="h3-dashboard" >
                                          Courses &nbsp;&nbsp;
                                    </div>
                                 </div>
                                 <div class="btn-icon-back dashboard-icons" style="margin-left: 0px;background-image: url('../img/icons/book-hover.svg');">
                                 </div>
                              </div>
                           </td>
                        </tr>
                     </table>
                  </center>
               </td>
            </tr>

            <tr>
               <td colspan="4">
                  <table width="100%" border="0" class="dashbord-tables">
                     <tr>
                        <td>
                           

                        </td>

                        <td>
                           
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
         </table>
      </div>
   </div>
</body>
</html>