<?php
$flag=0;
$name="";
$pass="";
$sno;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "parking";
    
    // Create connection
    $db = mysqli_connect($servername, $username, $password, $dbname);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_POST['pass'])) {
    $p = $_POST['pass'];
    date_default_timezone_set('Asia/Kolkata');
    $end= date('Y-m-d H:i:s');
    $sql = "UPDATE `admin` SET `end`='$end' WHERE `password`=$p";
    $result = mysqli_query($db, $sql);
    if (!$result) {
        die("Error executing query: " . mysqli_error($db));
    }
}
else{
    $name=$_POST["username"];
    $pass=$_POST["password"];
$query = "SELECT * FROM `admin`";
$result = mysqli_query($db, $query);

if (!$result) {
    die("Error executing query: " . mysqli_error($db));
}

// Print the data
while ($row = mysqli_fetch_assoc($result)) {

    if($row["name"]==$name && $row["password"]==$pass){
        $sno=$row["sno"];
        $flag=1;
        break;
    }
    else{
        $flag=2;
    }
    }
    if($flag==1){
    date_default_timezone_set('Asia/Kolkata');
    $start= date('Y-m-d H:i:s');
    $sql = "UPDATE `admin` SET `start`='$start' WHERE sno=$sno";
    $result = mysqli_query($db, $sql);
    }
    if (!$result) {
        die("Error executing query: " . mysqli_error($db));
    }}
    mysqli_close($db); 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Parked:Parking Manager</title>
    <link rel="shortcut icon" href="asset\logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <nav class="navbar navbar-light bg-primary">
    <a class="navbar-brand nav" href="#">
    <img src="asset\logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="" id="img1">
    CAR PARKING MANAGEMENT SYSTEM
  </a>
</nav>
<?php 
    if ($flag== 1) {
        echo "<script>
        window.location.href = 'index.php';</script>";
        
    }
    if($flag==2){
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
  <strong>Incorrect Name and Password</strong>
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
    }
    ?>
    <div class="container">
    <div class="from">
    <img src="asset\logo.jpg" alt="" id="img2">
    <form action="admin.php" method="post">
        <label for="username">USERNAME</label>
        <input type="text" id="username" name="username"  value="" required><br>
        <label for="password">PASSWORD</label>
        <input type="password" id="password" name="password" required><br>
        <input class="bg-primary" type="submit" value="Login">
    </form></div></div>
</body>
</html>