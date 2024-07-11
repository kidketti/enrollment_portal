<?php // start of PHP
    require_once 'creds.php'; // get credentials from creds.php

    $conn = new mysqli($host, $user, $pass, $dbname, $port); // create a new connection between MySQL and PHP with those credentials  
    
    if($conn->connect_error){ // if there is a connection error
        die("Fatal Error"); // kill the connection, and display error
	}
	// end of PHP
	?> 
<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	<style>
		/* Style for body and h1 tags */
		body {
			background-color: #f2f2f2;
			font-family: Arial, sans-serif;
			margin: 0;
			background-repeat: no-repeat;
			background-position: center center;
			margin: 0;
			padding: 0;
			font-family: Arial, sans-serif;
			background-image: url(loginImage.jpg);

		}

		h1 {
			color: #FEC52E;
			text-shadow: black 1px 1px;
			text-align: center;
			margin-top: 50px;
		}

		/* Style for form */
		form {
			background-color: #ffffff;
			border-radius: 5px;
			box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
			margin: 20px auto;
			max-width: 500px;
			padding: 20px;
		}

		/* Style for form labels */
		form label {
			display: block;
			font-size: 14px;
			font-weight: bold;
			margin-top: 10px;
		}

		/* Style for form inputs */
		form input[type="text"], form input[type="email"], form input[type="password"], form select {
			border: none;
			border-radius: 2px;
			box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.1);
			padding: 10px;
			width: 100%;
		}

		form input[type="submit"] {
			background-color: black;
			border: none;
			border-radius: 2px;
			color: #ffffff;
			cursor: pointer;
			font-size: 16px;
			margin-top: 20px;
			padding: 10px;
			width: 100%;
		}

		form input[type="submit"]:hover {
			background-color: #FEC52E; ;
		}

		/* Style for form message */
		form p {
			font-size: 14px;
			margin-top: 20px;
			text-align: center;
		}

		/* Style for user type options */
		#user_type {
			margin-top: 20px;
		}

		/* Style for student and faculty options */
		#student_options, #faculty_options {
			margin-top: 20px;
			padding: 10px;
		}

		#student_options label, #faculty_options label {
			display: inline-block;
			margin-right: 10px;
		}

		#student_options select, #faculty_options select {
			margin-right: 20px;
			width: 120px;
		}

		#major {
			width: 200px;
		}

		#office {
			width: 100px;
		}

	</style>
</head>
<body>
	<h1>Sign Up</h1>
	<form action="signup_page.php" method="POST">
		<label for="fname">First Name:</label>
		<input type="text" id="fname" name="fname" required>

		<label for="lname">Last Name:</label>
		<input type="text" id="lname" name="lname" required>

		<label for="email">Email:</label>
		<input type="email" id="email" name="email" required>

		<label for="phone">Phone:</label>
		<input type="text" id="phone" name="phone" required>

		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required>

		<label for="c_password">Confirm Password:</label>
		<input type="password" id="c_password" name="c_password" required>

		<input type="checkbox" onclick="conceal()">Show Password

		<p>Return to <a href="login.php">login</a>.</p>

		<label for="user_type">User Type:</label>
		<select id="user_type" name="user_type" required onchange="displayOptions()">
			<option value="">Select User Type</option>
			<option value="faculty">Faculty</option>
			<option value="student">Student</option>
		</select>

		<div id="student_options" style="display: none;">
			<label for="classification">Classification:</label>
			<select id="classification" name="classification">
				<option value="">Select classification</option>
				<option value="freshman">Freshman</option>
				<option value="sophomore">Sophomore</option>
				<option value="junior">Junior</option>
				<option value="senior">Senior</option>
			</select>

			<label for="major">Major:</label>
			<select id="major" name="major">
        <option value="">Select Major</option>

        <?php //  start of PHP

        // Query to retrieve departmentAbbrv from the department table
        $result = mysqli_query($conn, "SELECT departmentAbbrv FROM department");

        // Generate "Major" options from the query. 
        while ($row = mysqli_fetch_assoc($result)) { // while there is a connection.
			//select the Major from the drop down menu 
            echo '<option value="' . $row['departmentAbbrv'] . '">' . $row['departmentAbbrv'] . '</option>';
        }
        ?>
    </select>

	
		</div>

		<div id="faculty_options" style="display: none;">
			<label for="role">Role:</label>
			<select id="role" name="role">
				<option value="">Select Role</option>
				<option value="professor">Professor</option>
				<option value="secretary">Secretary</option>
				<option value="chair">Chair</option>
			</select>

			<label for="office">Office Number:</label>
			<select id="office" name="office">
				<option value="">Select Office Number</option>
				<?php // PHP

        // Query to retrieve locationID from the location table
        $result = mysqli_query($conn, "SELECT locationID FROM location");

        // Generate "Office Number" options from the query
        while ($row = mysqli_fetch_assoc($result)) { // while there is a connection
			// select the Office Number from the drop down menu
            echo '<option value="' . $row['locationID'] . '">' . $row['locationID'] . '</option>';
        }
        ?>
			</select>
		</div>


        <?php

            if(isset($_POST['submit'])){

                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $password = $_POST['password'];
                $user_type = $_POST['user_type'];
                
                // Insert user data into appropriate table
                if ($user_type == "student") {
                    $classification = $_POST['classification'];
                    $major = $_POST['major'];

                    $sql = "INSERT INTO student (fname, lname, email, phone, classification, major) VALUES ('$fname', '$lname', '$email', '$phone', '$classification', '$major')";
                    // Insert password into "student_passwords" table
                    $sql_password = "INSERT INTO student_passwords (password, studentID) 
                                        VALUES ('$password', (SELECT sid FROM student WHERE student.email = '$email'))";

                } else if ($user_type == "faculty") {
                    $role = $_POST['role'];
                    $office = $_POST['office'];

                    $sql = "INSERT INTO faculty (fname, email, lname,role, office, phone) 
								VALUES ('$fname', '$email', '$lname',(SELECT frid from faculty_roles WHERE faculty_roles.roles = '$role'), $office, '$phone')";
                    // Insert password into "faculty_passwords" table
                    $sql_password = "INSERT INTO faculty_passwords (password, facultyID)
                                        VALUES('$password', (SELECT fid from faculty WHERE faculty.email = '$email'))";
                }

                // Execute SQL query
                if (mysqli_query($conn, $sql)) {
					if(mysqli_query($conn, $sql_password)){
						echo "User created successfully";
					}else{
						echo "Error creating user: " . mysqli_error($conn);
					}
                } else {
                    echo "Error creating user: " . mysqli_error($conn);
                }

                // Close database connection
                mysqli_close($conn);
            }

            ?>
        <input name = "submit" type="submit" value="Submit">
	</form>

	<script>
		function displayOptions() {
			var userType = document.getElementById("user_type").value;
			var studentOptions = document.getElementById("student_options");
			var facultyOptions = document.getElementById("faculty_options");

			if (userType == "student") {
				studentOptions.style.display = "block";
				facultyOptions.style.display = "none";
			} else if (userType == "faculty") {
				facultyOptions.style.display = "block";
				studentOptions.style.display = "none";
			} else {
				studentOptions.style.display = "none";
				facultyOptions.style.display = "none";
			}
		}

		function conceal(){
        var x = document.getElementById("password");
        var y = document.getElementById("c_password");
        
        if(x.type === "password" || y.type === "password") {
            x.type = "text";
            y.type = "text";
        } else {
            x.type = "password";
            y.type = "password";
        }
    }
	</script>
</body>

</html>
