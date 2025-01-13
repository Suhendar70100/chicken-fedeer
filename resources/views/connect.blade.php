<html>
<body>
    
<?php
$dbname = 'chicken_feed';
$dbuser = 'root';
$dbpass = 'root';
$dbhost = '127.0.0.1';

// Establish database connection
$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get storage value from GET request
$storage = $_GET['storage'];

// Prepare and execute SQL query using parameterized query
$stmt = mysqli_prepare($connect, "INSERT INTO live_update (storage) VALUES (?)");
mysqli_stmt_bind_param($stmt, "s", $storage);
mysqli_stmt_execute($stmt);

// Check for errors
if (mysqli_stmt_error($stmt)) {
    echo "Error: " . mysqli_stmt_error($stmt);
} else {
    echo "Insertion successful!";
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>


</body>
</html>