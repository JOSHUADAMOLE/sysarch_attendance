<?php session_start(); ?>
<?php include 'header.php'; ?>
<body class="hold-transition login-page" style="background:url(images/login.png); background-repeat:no-repeat;background-size:100%">
<div class="login-box">
  	<div class="login-logo">
  		<p id="date"></p>
      <p id="time" class="bold"></p>
  	</div>
  
  	<div class="login-box-body">
    	<h4 class="login-box-msg">Enter Employee ID or Use Face Recognition</h4>

    	<form id="attendance" enctype="multipart/form-data">
          <div class="form-group">
            <select class="form-control" name="method" id="method">
              <option value="manual">Manual Entry</option>
              <option value="face">Face Attendance</option>
            </select>
          </div>
          <div id="manual-entry" style="display: block;">
            <div class="form-group has-feedback">
              <input type="text" class="form-control input-lg" id="employee" name="employee" required>
              <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
            </div>
            <div class="form-group">
              <select class="form-control" name="status">
                <option value="in">Time In</option>
                <option value="out">Time Out</option>
              </select>
            </div>
          </div>
          <div id="face-recognition" style="display: none;">
            <div id="camera-preview" style="width:320px; height:240px; margin:auto;">
              <!-- Webcam preview will be shown here -->
            </div>
            <input type="hidden" id="faceImageData" name="faceImageData" />
            <div class="row">
              <div class="col-xs-4">
                <button type="button" class="btn btn-primary btn-block btn-flat" id="captureButton"><i class="fa fa-camera"></i> Capture</button>
              </div>
            </div>
            <div class="form-group">
              <select class="form-control" name="status">
                <option value="in">Time In</option>
                <option value="out">Time Out</option>
              </select>
            </div>
          </div>
      		<div class="row">
    			<div class="col-xs-4">
          			<button type="submit" class="btn btn-primary btn-block btn-flat" name="signin"><i class="fa fa-sign-in"></i> Sign In</button>
        		</div>
      		</div>
    	</form>
  	</div>
		<div class="alert alert-success alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-check"></i> <span class="message"></span></span>
    </div>
		<div class="alert alert-danger alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
    </div>
</div>
	
<?php include 'scripts.php'; ?>
<script type="text/javascript">
$(function() {
  var interval = setInterval(function() {
    var momentNow = moment();
    $('#date').html(momentNow.format('dddd').substring(0,3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));  
    $('#time').html(momentNow.format('hh:mm:ss A'));
  }, 100);

  $('#method').change(function() {
    var selectedMethod = $(this).val();
    if (selectedMethod === 'manual') {
      $('#manual-entry').show();
      $('#face-recognition').hide();
      Webcam.reset(); // Turn off the webcam when switching to manual entry
    } else {
      $('#manual-entry').hide();
      $('#face-recognition').show();
      Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
      });
      Webcam.attach('#camera-preview');
    }
  });

  // Function to capture image from webcam
  $('#captureButton').click(function() {
    Webcam.snap(function(data_uri) {
      $('#camera-preview').html('<img src="' + data_uri + '" id="capturedImage" />');
      $('#faceImageData').val(data_uri);
    });
  });

  $('#attendance').submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      type: 'POST',
      url: 'attendance.php',
      data: formData,
      contentType: false,
      processData: false,
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
