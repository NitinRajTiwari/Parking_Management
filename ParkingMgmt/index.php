<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "parking";
$flag=0;
$count=5;
$atpresent=0;
$today=0;
$fees=20;
$collection=0;
$duration_hours=0; 
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$oname=$vname=$vnum=$endate=$exdate=$extime=$entry="";
date_default_timezone_set('Asia/Kolkata');
    $entry= date('Y-m-d H:i:s');
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['snoedit'])){
    $sno=$_POST["snoedit"];
    date_default_timezone_set('Asia/Kolkata');
    $exdateupdate= date('Y-m-d H:i:s');
     //Calculate time   
  $sql="SELECT `entry` FROM vehicle WHERE sno=$sno";
  $result = $conn->query($sql);
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
  $entry_time = strtotime($row['entry']);
      $exit_time = strtotime($exdateupdate);
      $duration_hours = ($exit_time - $entry_time) / 3600;
      //echo round($duration_hours, 3) ;
      $fees=round($fees*$duration_hours,2);
}}
//
//updating exitdate

    $sql ="UPDATE `vehicle` SET `sno`='$sno',`exitdate`='$exdateupdate',`fees`='$fees' WHERE sno=$sno";
    if (mysqli_query($conn, $sql)) {
      $flag=2;
  }
}

  elseif (isset($_POST['snodelete'])){
    $sno=$_POST["snodelete"];
    $sql="DELETE FROM `vehicle` WHERE sno=$sno";
    if (mysqli_query($conn, $sql)) {
      $flag=3;
  } 
  }

  else{
  $oname=$_POST["ownername"];
  $vname=$_POST["vehiclename"];
  $vnum=$_POST["vehiclenumber"];
  $vnum= str_replace(' ', '', $vnum);
  $sql = "SELECT vehiclenumber FROM vehicle WHERE exitdate IS NULL";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if ($row["vehiclenumber"] == $vnum) {
        $flag = 4;
        break;
    }
}}

if ($flag == 4) {
  echo "<script>e.preventDefault();</script>";
} 
else {
  $sql = " INSERT INTO vehicle( ownername, vehiclename, vehiclenumber, `entry`) VALUES ('$oname','$vname','$vnum','$entry')";
  if (mysqli_query($conn, $sql)) {
      $flag=1;
  } 
  $oname=$vname=$vnum=$endate=$entime=$exdate=$extime="";
}  
}
} 
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <link rel="shortcut icon" href="asset\logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    
    <title>Parking manager</title>
  </head>
  <body>


    <!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
  Launch editModal
</button> -->

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Sure! You Want to EXIT </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <form method="post" action="index.php"  name="secondform" >
        <input type="hidden" name="snoedit" id="snoedit">
      <!-- Removed by using current time stamp on update -->
  <!-- <div class="form-group">
    <label for="exampleInputEmail1">Exit Date</label>
    <input type="date" class="form-control" id="exitdateupdate" aria-describedby="emailHelp" 
    name="exitdateupdate">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Exit Time</label>
    <input type="time" class="form-control" id="exittimeupdate" aria-describedby="emailHelp" 
    name="exittimeupdate">
  </div> -->
  
  <button type="submit" class="btn btn-primary">EXIT</button>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Sure! You want to Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <form method="post" action="index.php">
        <input type="hidden" name="snodelete" id="snodelete">
        <button type="submit" class="btn btn-primary">Yes</button>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>


<!-- image modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">--Uplaod Image--</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <form method="post" action="upload.php">
        <input type="hidden" name="snoimage" id="snoimage">
        <button type="submit" class="btn btn-primary">Uplaod</button>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



  <div class="navbar1">
  <!-- Image and text -->
<nav class="navbar navbar-dark bg-primary">
  <a class="navbar-brand" href="#">
    <img src="asset\logo.jpg"width="45" height="45" class="d-inline-block align-top logo" alt="">
    <h2 style="display:inline;">CAR PARKING MANAGEMENT SYSTEM</h2>
  </a>
  <form class="form-inline" action="admin.php" method="post">
    <input class="form-control mr-sm-2" type="Password" placeholder="Password" name="pass">
    <input class="btn btn-info my-2 my-sm-0" type="submit" value="Logout">
  </form>
</nav>
<?php
if($flag==4){
  $f=0;
  echo"<div class='alert alert-danger alert-dismissible fade show' role='alert'>
  <strong> Vehicle number " . $vnum . " already PRESENT in Parking.</strong>
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
}
if($flag==1){
  $flag=0;
  echo"<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success</strong> vehicle information inserted
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
}
if($flag==2){
  $flag=0;
  echo"<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Exit Date Time Updated</strong> vehicle exited  <strong>parking Fees  ₹".$fees."
  </strong><button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";}
if($flag==3){
  $flag=0;
  echo"<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Deleted successfully</strong> 
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";}
?>
</div>

<div class="container my-5">
  <div class="row">
    <div class="col-sm">
    <form method="post" action="index.php" onsubmit="return validateForm()" name="firstform" id="myForm">
  <div class="form-group">
    <label for="exampleInputEmail1">Owner Name </label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="ownername">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Vehicle Name </label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="vehiclename">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Vehicle Number</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="vehiclenumber">
  </div>
  <!-- <div class="form-group">
    <label for="exampleInputEmail1">Entry Date</label>
    <input type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="entrydate">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Entry Time</label>
    <input type="time" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="entrytime">
  </div> -->
  <button type="submit" class="btn btn-primary" id="myForm">Submit</button>
</form>
    </div>

    <div class="col-sm col2">
    <?php
    echo "<h2 class='count'>TOTAL PARKING SPOTS : ".$count."<h2>";
    //At present
   
    $sql = "SELECT exitdate FROM vehicle WHERE exitdate IS NULL";

    // Execute SQL statement and get the result
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        // Output data of each row
        while($row = $res->fetch_assoc()) {
           $atpresent++;
        }
        echo "<h2 class='count'>VEHICLES PRESENT NOW : " .$atpresent. "<h2>";
    } else {
        echo "<h2 class='count'>VEHICLES PRESENT NOW : " .$atpresent. "<h2>";
    }
    echo "<h2 class='count'>PARKING SPOTS LEFT : ".$count-$atpresent ."<h2>";
    
     //Today
     // Get current date
     date_default_timezone_set('Asia/Kolkata');
     $current_date = date('Y-m-d');
    $sql = "SELECT  `entrydate`,`fees` FROM `vehicle` WHERE `entrydate`='$current_date'";
     $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while($row=mysqli_fetch_assoc($result)) {
          $today++;
          $collection=$collection+$row["fees"];
        }
        echo "<h2 class='count'>VEHICLES PARKED TODAY : " . $today. "<h2>";
        echo "<h2 class='count'>TOTAL COLLECTION TODAY : ₹" . $collection. "<h2>";
    } else {
        echo "<h2 class='count'>VEHICLES PARKED TODAY : " . $today. "<h2>";
    }

?>
 <!-- count -->
 <script>
    document.getElementById('myForm').addEventListener('submit', function(e) {
        <?php
            $count = $count-$atpresent; 
            if ($count == 0) {
              
                echo 'e.preventDefault();';
                echo 'alert("NO PARKING SPACE LEFT");';
            }
        ?>
    });</script>
  </div>
</div>

<div class="datacontainer">
<div class="table-responsive">
  <table class="table table-hover" id="myTable">
  <thead>
    <tr>
      <th scope="col">SNO.</th>
      <th scope="col">Owner_Name</th>
      <th scope="col">Vehicle_Name</th>
      <th scope="col">Vehicle_Number</th>
      <th scope="col">Entry_Detail</th>
      <th scope="col">Exit_Detail</th>
      <th scope="col">FEES(₹)</th>
      <th scope="col">Image</th>
      <th scope="col">ACTIONS</th>
    </tr>
  </thead>
  <tbody>
  <?php
$sql = "SELECT * FROM vehicle";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  $s=0;
  while($row = mysqli_fetch_assoc($result)){
   
    $s=$s+1;
    echo"<tr>".
      "<td scope='row' id='number' class='serial'> <strong>". $s."</strong></td>".
      "<td>".$row["ownername"]."</td>".
      "<td>".$row["vehiclename"]."</td>".
      "<td>".$row["vehiclenumber"]."</td>".
      "<td>".$row["entry"]."</td>".
      "<td>". $row["exitdate"]."</td>".
      "<td>". $row["fees"]."</td>".
      "<td><img height='110px' width='160px' src=". htmlspecialchars($row["imageData"]) ." /></td>".
      "<td><button class='edit btn btn-sm btn-primary' id=".$row["sno"].">Exit</button> <button class='imgupload btn btn-sm btn-primary my-1' id=p".$row["sno"].">Image</button>       
           <button class='delete btn btn-sm btn-primary my-1' id=d".$row["sno"].">Delete</button>
           </td>"
    ."</tr>";}
}
?> 
  </tbody>
  </table>
 
</div>
</div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>let table = new DataTable('#myTable');
</script>
<script>
edits = document.getElementsByClassName('edit');
Array.from(edits).forEach((element)=>{element.addEventListener("click",(e)=>{
  // console.log("edit", );
  // tr=e.target.parentNode.parentNode;
  // exitdate=tr.getElementsByTagName("td")[6].innerText;
  // exittime=tr.getElementsByTagName("td")[7].innerText;
  // console.log(exitdate,exittime);
  // exitdateupdate.value=exitdate;
  // exittimeupdate.value=exittime;
  snoedit.value=e.target.id;
  // console.log(e.target.id);
  $('#editModal').modal('toggle')

})
})
deletes = document.getElementsByClassName('delete');
Array.from(deletes).forEach((element)=>{element.addEventListener("click",(e)=>{
  // console.log("delete", );
  sno=e.target.id.substr(1,);
  snodelete.value=sno;
  // console.log(sno);
  $('#deleteModal').modal('toggle')
})
})
//no of cars
// var data=0;
// serials = document.getElementsByClassName('serial');
// Array.from(serials).forEach((element)=>{
// console.log(element.innerText);
// data=element.innerText;
// })
// document.getElementById("noc").innerText=data;

//upload image
upload = document.getElementsByClassName('imgupload');
Array.from(upload).forEach((element)=>{element.addEventListener("click",(e)=>{
  sno=e.target.id.substr(1,);
  snoimage.value=sno;
  $('#imageModal').modal('toggle')
    
})
})
//form validation 
function validateForm() {
  let x = document.forms["firstform"]["ownername"].value;
  if (x == "") {
    alert("Owner Name must be filled out");
    return false;
  }
  let a = document.forms["firstform"]["vehiclename"].value;
  if (a == "") {
    alert("Vehicle Name must be filled out");
    return false;
  }
  let c = document.forms["firstform"]["vehiclenumber"].value;
  if (c == "") {
    alert("Vehicle Number must be filled out");
    return false;
  }
  // let b = document.forms["firstform"]["entrydate"].value;
  // if (b == "") {
  //   alert("Entry Date must be filled out");
  //   return false;
  // }
 // let e = document.forms["firstform"]["entrytime"].value;
  // if (e == "") {
  //   alert("Entry Time must be filled out");
  //   return false;}
  }

//stop the form from resubmitting by replace same url 

if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
</script>
  </body>
</html>