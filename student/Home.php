
<?php
    require_once 'creds.php';
 
    $conn = new mysqli($host, $user, $pass, $dbname, $port);
    
    if($conn->connect_error){
        die("Fatal Error");
    }
    session_start();
    $student_id = $_SESSION['id'];
    $student_user = $_SESSION['user_type'];
    $login_check=$_SESSION['loggedin'];
    if((isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === true) &&($_SESSION['user_type']==='student'))
  {
// Define the SQL query
$sql = "SELECT s.email, s.fname, s.lname, s.classification, m.major, s.phone
    FROM student AS s
    JOIN major AS m ON s.majorID = m.majorID
    WHERE s.sid = '$student_id'";
    $result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
  die("Query failed: " . mysqli_error($connection));
}



// Process the result
if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    $row = mysqli_fetch_assoc($result);
    echo "<h1>Welcome " . $row["fname"] . "!</h1>";
    echo "</br>";
    echo "Your student information: ";
    echo "<table>";
    echo "<tr><th>First Name</th><th>Last Name</th><th>Student Name</th></tr>";
    echo "<tr><td>" . $row["fname"] . "</td><td>" . $row["lname"] . "</td><td>" . $row["email"] . "</td></tr>";
    echo "</table>";
    echo "<table>";
    echo "<tr><th>Major</th><th>Classification</th><th>Phone Number</th></tr>";
    echo "<tr><td>" . $row["major"] . "</td><td>" . $row["classification"] . "</td><td>" . $row["phone"] . "</td></tr>";
    echo "</table>";

    
  } else {
    echo "<table>";
    echo "<tr><th>No results found</th></tr>";
    echo "</table>";
  }
}
else
{
  header("Location: login.php");
}
?>