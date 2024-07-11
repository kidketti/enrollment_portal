
<?php
    require_once 'creds.php';

    $conn = new mysqli($host, $user, $pass, $dbname, $port);
    
    if($conn->connect_error){
        die("Fatal Error");
    }
    // Replace the student email with the desired student's email
    session_start();
    if((isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === true) &&($_SESSION['user_type']==='faculty'))
  {
// Prepare the SQL statement
$sql = "SELECT s.fname, s.lname, s.email, m.majorAbbrv 
    FROM student AS s
    JOIN major AS m ON s.majorID = m.majorID
    WHERE m.majorAbbrv = 'CS'";

// Execute the SQL statement and get the result
$result = mysqli_query($conn, $sql);

// Check if there are any results
if ($result) {
if (mysqli_num_rows($result) >0) {

    // Create a table to display the results
    echo "<h1 style='text-align: center; color: black;'>"."Computer Science Student List"."</h1>";
    echo "<table>";
    echo "<tr><th>Student Name</th><th>Student Email</th><th>Student Department</th></tr>";

    // Loop through the results and display them in the table
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["fname"] . " " . $row["lname"] . "</td><td>" . $row["email"] . "</td><td>" . $row["majorAbbrv"] . "</td></tr>";
    }

    echo "</table>";

} else {
    echo "<table>";
    echo "<tr><th>No Student Found in CS department.</th></tr>";
    
    echo "</table>";
}
}
else {
    // display error message if query execution failed
    echo 'Error executing query: ' . mysqli_error($conn);
}

// Close the database connection
$conn->close();
}
else
{
header("Location: login.php");
}
?>