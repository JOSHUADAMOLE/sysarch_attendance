<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>Attendance and Payroll System</title>
  	<!-- Tell the browser to be responsive to screen width -->
  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  	<!-- Font Awesome -->
  	<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  	<!-- Theme style -->
  	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  	<!--[if lt IE 9]>
  	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  	<![endif]-->

  	<!-- Google Font -->
  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  	<!-- Include Webcam.js -->
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

  	<style type="text/css">
  		.mt20{
  			margin-top:20px;
  		}
  		.result{
  			font-size:20px;
  		}
      .bold{
        font-weight: bold;
      }
  	</style>
</head>
<body>
  <div class="container mt20">
    <h1>Manual Entry or Face Attendance</h1>
  </div>

  <script language="JavaScript">
	$(function() {
	var interval = setInterval(function() {
		var momentNow = moment();
		$('#date').html(momentNow.format('dddd').substring(0,3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));  
		$('#time').html(momentNow.format('hh:mm:ss A'));
	}, 100);

	// Function to toggle webcam based on method selection
	function toggleWebcam() {
		var selectedMethod = $('#method').val();
		if (selectedMethod === 'face') {
		Webcam.attach('#camera-preview');
		} else {
		Webcam.reset();
		}
	}

	// Toggle webcam when method selection changes
	$('#method').change(function() {
		toggleWebcam();
	});

	// Toggle webcam initially based on initial method selection
	toggleWebcam();

	// Function to capture image from webcam
	$('#captureButton').click(function() {
		Webcam.snap(function(data_uri) {
		$('#camera-preview').html('<img src="' + data_uri + '" id="capturedImage" />');
		$('#faceImageData').val(data_uri);
		});
	});

	$('#attendance').submit(function(e){
		e.preventDefault();
		var attendance = $(this).serialize();
		$.ajax({
		type: 'POST',
		url: 'attendance.php',
		data: attendance,
		dataType: 'json',
		success: function(response){
			if(response.error){
			$('.alert').hide();
			$('.alert-danger').show();
			$('.message').html(response.message);
			}
			else{
			$('.alert').hide();
			$('.alert-success').show();
			$('.message').html(response.message);
			$('#employee').val('');
			}
		}
		});
	});
	});
	</script>
</body>
</html>
