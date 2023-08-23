<?php
//set your session vars. reference the login page
require_once('db.php');
session_start();

$username = "";
$pwd = "";
$name = "";
$class1 = "";
$class2 = "";
$accounttype = "";
$nummeetings = 0;
$tutoremail = "";
$rating = 0;
$error=false;
$apptdesc="";
$courseNumber="";
$appointmentID = 0;
$studentconfirmation=0;
$tutorconfirmation=0;
date_default_timezone_set("America/New_York");
$scheduledtime;


if (isset($_SESSION["username"]))
    $username = $_SESSION["username"];
if (isset($_SESSION["password"]))
    $pwd = $_SESSION["password"];
if (isset($_SESSION["name"]))
    $name = $_SESSION["name"];
if (isset($_SESSION["class1"]))
    $class1 = $_SESSION["class1"];
if (isset($_SESSION["class2"]))
    $class2 = $_SESSION["class2"];
if (isset($_SESSION["accounttype"]))
    $accounttype = $_SESSION["accounttype"];
if (isset($_SESSION["numberofmeetings"]))
    $nummeetings = $_SESSION["numberofmeetings"];
if (isset($_SESSION["rating"]))
    $rating = $_SESSION["rating"];

if(isset($_POST["submit"])){
    if(isset($_POST["apptdesc"])) $apptdesc=$_POST["apptdesc"];
    if(isset($_POST["courseNumber"])) $courseNumber=$_POST["courseNumber"];
    if(isset($_POST["scheduledtime"])) $scheduledtime=$_POST["scheduledtime"];

    if(empty($apptdesc) || $courseNumber=="" || ($scheduledtime==date("Y-m-d", strtotime("today")))){
            $error=true;

    } 
    if (!$error){
        //calc appoint id
        //sql to find max id
        //add 1
        //save to var
        require_once("db.php");
        $sql = "SELECT max(appointmentID) from Appointments";
        $result = $mydb->query($sql);
        while ($row = mysqli_fetch_array(($result))) {
            $appointmentID = $row['max(appointmentID)'] + 1;
        }


        //find classid?
        $sql = "SELECT classID from Classes WHERE courseNumber='$courseNumber'";
        $result = $mydb->query($sql);
        $row = mysqli_fetch_array($result);
        $classID = $row['classID'];

        //find tutor email
       //iterate through tutor table class 1
       //if matches selected class id set to var
       //if no match iterate through class 2
       //set to var 
    
        $sql = "SELECT tutoremail from Tutor where class1='$classID'";
        $result=$mydb->query($sql);
        $row=mysqli_fetch_array($result);
        if(empty($result)){
            $sql = "SELECT Tutor.tutoremail from Tutor,Appointments where
            class2='$classID'";
            $result=$mydb->query($sql);
            $row=mysqli_fetch_array($result);
        }
        //$tutoremail = $result;
        $tutoremail=$row['tutoremail'];

        
        //ADD SQL STATEMENTS HERE

        $sql = "INSERT into Appointments(appointmentID, tutoremail,
        studentemail, scheduledtime, studentconfirmation, tutorconfirmation,
        appointmentsdescription, classid) values($appointmentID, 
        '$tutoremail', '$username', '$scheduledtime', $studentconfirmation, 
        $tutorconfirmation, '$apptdesc', $classID)"; 
        $result=$mydb->query($sql);

        header("HTTP/1.1 307 Temporary Redirect");
        header("Location: QuestionDashJO.php"); 
    }

}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Request Appointment</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <style>
    .errlabel {
      color: red;
    }
  </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0"><a
                    class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon rotate-n-15"></div>
                    <div class="sidebar-brand-text mx-3"><span>VT Tutor Service</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="AccountManagementIB.php"><i
                                class="far fa-user-circle"></i><span>Account Management</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="performanceDashboardRH.php"><i
                                class="fas fa-tachometer-alt"></i><span>Tutor Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="QuestionDashJO.php"><i
                                class="fas fa-user"></i><span>Question Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="upcomingAppointmentLC.php"><i
                                class="fas fa-table"></i><span>Appointments Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="logoutIB.php"><i
                                class="far fa-user-circle"></i><span>Logout</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="requestAppointmentLC.php"><i
                                class="fas fa-table"></i><span>Request an Appointment</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0"
                        id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3"
                            id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text"
                                    placeholder="Search for ..."><button class="btn btn-primary py-0" type="button"><i
                                        class="fas fa-search"></i></button></div>
                        </form>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link"
                                        aria-expanded="false" data-bs-toggle="dropdown" href="#"><span
                                            class="d-none d-lg-inline me-2 text-gray-600 small"><?php echo $name; ?></span><img
                                            class="border rounded-circle img-profile"
                                            src="assets/img/avatars/avatar1.jpeg"></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a
                                            class="dropdown-item" href="#"><i
                                                class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a><a
                                            class="dropdown-item" href="#"><i
                                                class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a><a
                                            class="dropdown-item" href="#"><i
                                                class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity
                                            log</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i
                                                class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0">Request Appointment</h3><a
                            class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="#"><i
                                class="far fa-plus-square fa-sm text-white-50"></i>&nbsp;Schedule Appointment</a>
                    </div>
                </div>


<!-- BEGIN FORM -->
    <form class="text-left" method="post">
              
        <button class="btn btn-primary" type="submit" name="submit">Submit Request</button>
        <br />

        


        <label class="form-label">Appointment Description<input type="text" name="apptdesc" value="<?php echo $apptdesc; ?>">
            <?php
                if ($error && empty($apptdesc)) {
                    echo "<label class='errlabel'>Error: Please enter description.</label>";
                }
            ?>
        </label>
        <br />
        
        <label class="form-label">Preferred Date/Time of Appointment
            <input type="date" name="scheduledtime" value="<?php echo $scheduledtime; ?>">
                <?php
                    if ($error && $scheduledtime == date("Y-m-d", strtotime("today"))) {
                        echo "<label class='errlabel'>Error: Please enter a time.</label>";
                    }
                ?>
        </label>
        <br />



        
        <label class="form-label">Course Number<select name="courseNumber" value="<?php echo $courseNumber; ?>">
            <?php
                require_once("db.php");
                $sql = "SELECT courseNumber FROM Classes";  
                $result = $mydb->query($sql);

                while($row=mysqli_fetch_array(($result))){
                    echo "<option>" . $row["courseNumber"] . "</option>";
                }
    
                if ($error && $courseNumber=="") {
                    echo "<label class='errlabel'>Error: Please enter course number.</label>";
                }
            ?>
            </select>
        </label>
        <br />

                    
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </form>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>