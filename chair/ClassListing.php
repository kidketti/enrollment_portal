
<?php
require_once 'creds.php';

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Fatal Error");
}
session_start();

    if((isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === true) &&($_SESSION['user_type']==='faculty'))
  {
    echo '
    <form action="?page=ClassListing" method="POST">
    <label>Enter CRN:</label>
    <input type="text" id="classname" name="classname">
    <input type="submit" name="submit" value="Search">
    </form>';
    if(isset($_POST['submit'])){
$classname = $_POST['classname'];
$sql_select = "SELECT course.courseTitle, course.CRN, faculty.fname, faculty.lname, time.timeRange, date.startDate, date.endDate, rooms.roomNum, building.buildAbbrv
FROM course 
JOIN class ON course.courseID = class.courseID 
JOIN time ON class.timeID = time.timeID 
JOIN date ON class.dateID = date.dateID 
JOIN location ON class.locationID = location.locationID 
JOIN rooms ON location.roomID = rooms.roomID
JOIN building ON location.buildID = building.buildID
JOIN faculty ON class.profID = faculty.fid
WHERE course.CRN = '$classname'";
$result = $conn->query($sql_select);

// Check for errors in the SELECT query
if (!$result) {
    echo "Error selecting record: " . $conn->error;
} if (mysqli_num_rows($result) >0) {
    
    echo "<table>";
    echo "<tr><th>Course Title</th><th>Course CRN</th><th>Faculty Name</th><th>Time</th><th>Start Date</th><th>End Date</th><th>Room</th><th>Building Abbrv.</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["courseTitle"] . "</td><td>" . $row["CRN"] . "</td><td>" .
         $row["fname"] . " " . $row["lname"] . "</td><td>" . $row["timeRange"] . "</td><td>" . 
         $row["startDate"] . "</td><td>" . $row["endDate"] . "</td><td>" . $row["roomNum"] . "</td><td>" . $row["buildAbbrv"] . "</td>
         </tr>";
    }

    echo "</table>";
   
}
else {
    echo "<table>";
    echo "<tr><th>No Class Found</th></tr>";
    
    echo "</table>";
}
}
  }
else
{
header("Location: login.php");
}
?>