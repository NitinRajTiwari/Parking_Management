<?php
$sno="";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['snoimage'])) {
    $sno = $_POST['snoimage'];
}
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="upload.css">
  </head>
<body>
  <h1 class="photo">UPLOAD VEHICLE PHOTO</h1>
<video id="video" width="640" height="480" autoplay></video>
<canvas id="canvas" width="640" height="480"></canvas>
<br>
<button id="snap">Snap Photo</button>

<form id="form1" runat="server">
<input type="hidden" id="sno" name="sno" value="<?php echo isset($sno) ? $sno :''; ?>">
    <input type='hidden' id='img_val' name='img_val' value='' />
</form>



<script>
// Grab elements, create settings, etc.
var video = document.getElementById('video');

// Get access to the camera!
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        video.srcObject = stream;
        video.play();
    });
}

// Elements for taking the snapshot
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var video = document.getElementById('video');

// Trigger photo take
document.getElementById("snap").addEventListener("click", function() {
	context.drawImage(video, 0, 0, 640, 480);
	var dataUrl = canvas.toDataURL();
	document.getElementById('img_val').value = dataUrl;
	var fd = new FormData(document.forms["form1"]);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', '', true);
  xhr.onload = function() {
		if (xhr.status === 200) {
			window.location.href = "index.php";
		}
	};
	xhr.send(fd);
});
</script>

</body>
</html>
<?php
$serial="";
$img_val="";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['img_val'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "parking";
    
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    
    $serial = $_POST['sno'];
    $img_val = $_POST['img_val'];
    $sql = "UPDATE `vehicle` SET
    `imageData`='$img_val' WHERE sno=$serial";

if (mysqli_query($conn, $sql)) {
      echo "image uploaded";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    mysqli_close($conn);
}

?>