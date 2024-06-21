<?php
	require_once("./config.php");
	
	$tableStudentLogIn = "CREATE TABLE studentLogIn
			(regNu varchar(13) primary key,
			userName varchar(50),
			password varchar(255),
			imageUrl varchar(100))";

	$tableTeachertLogIn = "CREATE TABLE teacherLogIn
			(regNu varchar(13) primary key,
			userName varchar(50),
			password varchar(255),
			imageUrl varchar(100))";

	$tableAdmintLogIn = "CREATE TABLE adminLogIn
			(regNu varchar(13) primary key,
			userName varchar(50),
			password varchar(255),
			imageUrl varchar(100))";

	$tableAddCourse = "CREATE TABLE course
			(courseId varchar(8) primary key,
			courseName varchar(50))";

	$tableStudentDetails = "CREATE TABLE student
			(regNu varchar(13) primary key,
			name varchar(20),
			course varchar(20),
			year varchar(1),
			semester varchar(1))";
	
	$tableTeacherDetails = "CREATE TABLE teacher
			(id varchar(13) primary key,
			name varchar(20))";
	
	$tableLectureAssign = "CREATE TABLE lectureAssign
			(assignId int auto_increment primary key,
			courseId varchar(8),
			teacherId varchar(13),
			FOREIGN KEY (courseId) REFERENCES course(courseId),
			FOREIGN KEY (teacherId) REFERENCES teacher(id))";

	$tableMarks = "CREATE TABLE studentMarks
			(courseId varchar(8),
			regNu varchar(13),
			ica1 varchar(3),
			ica2 varchar(3),
			ica3 varchar(3),
			final varchar(3),
			primary key(courseId, regNu))";

	//create table one
	$studentLogIn = $conn->query($tableStudentLogIn);
	$teacherLogIn = $conn->query($tableTeachertLogIn);
	$adminLogIn = $conn->query($tableAdmintLogIn);
	$addCourse = $conn->query($tableAddCourse);
	$studentDetails = $conn->query($tableStudentDetails);
	$teacherDetails = $conn->query($tableTeacherDetails);
	$lectureAssignDetails = $conn->query($tableLectureAssign);
	$marksDetails = $conn->query($tableMarks);

	if(!$studentLogIn && !$teacherLogIn && !$adminLogIn && !$addCourse && !$studentDetails && !$teacherDetails && !$lectureAssignDetails && !$marksDetails) {
		echo "Table Creation Failed";
	}

	$registerAdmin = "INSERT INTO adminlogin(regNu, userName, password, imageUrl)
						VALUES('IDA0001', 'Admin', '$2y$10$7J7BKKOgquEcdcZgw5gwr.70RDq9mstot9AE5iBONqN44Y.AntgEu', 'profile_images/default.png')";
	
	$result = $conn->query($registerAdmin);
?>