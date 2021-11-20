<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="content-language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" type="image/svg" href="includes/images/icon.png">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<title></title>
	<style type="text/css">
		body, html{
		height: 100%;
		font-family: 'Poppins', sans-serif;
	}
	.container-fluid{
	    height: 100vh;
	    background-size: contain;
	    background-position: center;
	}
	.form-container{
	    background-color: rgba(255, 255, 255, 0.8);
		padding: 30px 20px 10px 20px;
		width: 450px;
		box-shadow: 2px 2px 3px 3px #aaa;
		box-sizing: border-box;
		color: #666;
	}
	.logo{
		display: block;
		width: 130px;
		margin: 10px auto 30px auto;
		transition: 2s;
		border: solid 1px #ddd;
		padding: 5px;
	}
	.button:hover{
	    border-radius: 10px;
	    transition: .7s;
	    color: #FFF;
	}
	#notification-area{
		position: fixed;
		left: 10px;
		bottom: 10px;
	}
	#ajax_result{
		margin: 5px auto 15px auto;
		border: solid #eee 1px;
		box-shadow: 1px 1px 1px 1px #eee;
		border-left: solid 6px;
		padding: 5px;
		max-width: 100%;
		overflow-x: auto;
		display: none;
	}
	#progress{
		width: 300px;
		max-width: 100%;
		border: solid #eee 1px;
		box-shadow: 1px 1px 1px 1px #eee;
		border-left: solid 6px #28a745;
		max-width: 75%;
		display: none;
		padding: 5px;
		color: #28a745;
	}
	#email_section{
		display: none;
	}
	@media only screen and (max-width: 768px) {
		.form-container{
			padding: 10px;
			max-width: 95%;
		}
	}
	</style>
</head>
<body>
	<div class="container-fluid h-100">
	    <div class="row align-items-center h-100">

	    	<div id="notification-area">
				<div id="ajax_result"></div>
				<div id="progress">Progress:&nbsp;<span id="upload_progress"></span></div>
			</div>

        	<div class="mx-auto form-container">
	        	<h5 class="form-title">Image Compressor</h5>
	        	<hr>
	            <form id="image_form" enctype="multipart/form-data">


 					<div class="form-group" id="image_file_section">
                        <input type="file" id="image_file" class="form-control">
                    </div>

                    <div class="form-group ">
                        <button class="btn btn-success" id="image_upload">Upload Image</button>
                    </div>
                    
                </form>
	        </div>

	        <div class="mx-auto form-container">
	        	<h5 class="form-title">Video Compressor</h5>
	        	<hr>
	            <form id="video_form" enctype="multipart/form-data">

 					<div class="form-group" id="video_file_section">
                        <input type="file" id="video_file" class="form-control">
                    </div>

                    <div class="form-group ">
                        <button class="btn btn-info" id="video_upload">Upload Video</button>
                    </div>
                    
                </form>
	        </div>

	    </div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/f082d7e5bc.js" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<script>
		function show_ajax_result_container() {
			$('#ajax_result').fadeIn('slow');
		}
		
		function show_notice( msg, type ) {

			if (type === 'error') {
				$('#ajax_result').css('color', '#dc3545');
				$('#ajax_result').css('border-left-color', '#dc3545');
				$('#ajax_result').html(msg);
				show_ajax_result_container();
				return false;
			}

			if (type === 'success') {
				$('#ajax_result').css('color', '#28a745');
				$('#ajax_result').css('border-left-color', '#28a745');
				$('#ajax_result').html(msg);
				show_ajax_result_container();
			}

		}

		//--Do nothing until page loads
  		$(window).on('load', function () {


			$('#image_upload').on('click', function(){

				const file = $('#image_file')[0].files[0];
				const upload_btn = $('#video_upload, #image_upload');

				//--Is a file selected?
	    		if ( !file ) {
	    			show_notice('No image selected!', 'error');
	    			return false;
	    		}
	    		else{

						var fileToUploadEXT = $('#image_file').val().split('.').pop().toLowerCase();
						var extn = [ 'jpg','png','jpeg','gif', 'webp' ];
					
						if($.inArray(fileToUploadEXT, extn) == -1){

							show_notice('Sorry, file format not supported.', 'error');
	    					return false;

						} 
						else{
							
							show_notice('Processing image...', 'success');

							var formData = new FormData();    
						 	formData.append( 'file', file );
						 	formData.append( 'ext', fileToUploadEXT );
						 	formData.append( 'type', 'image' );


						 	$('#upload').attr('disabled', 'true');
						    $.ajax({
						       url : 'app/process.php',
						       type : 'POST',
						       data : formData,
						       processData: false,
						       contentType: false,
						        success: function (data) {
						            
						            show_notice( data, 'success' );
						 			upload_btn.attr('disabled', 'false');
						 			upload_btn.removeAttr('disabled');
						 			
					          	},
								error: function(XMLHttpRequest, textStatus, errorThrown) {
									if (XMLHttpRequest.readyState === 0) {
										console.log( 'A network connection is not available!' );
									}
						 		},

						 		// Custom XMLHttpRequest
						        // For handling the progress of the upload
							    xhr: function () {
							      var myXhr = $.ajaxSettings.xhr();
							      if (myXhr.upload) {
							        	myXhr.upload.addEventListener('progress', function (e) {
							          	if (e.lengthComputable) {
							            
								            $('#progress').fadeIn('slow');
								          	
								          	let uploaded_percent_ = (e.loaded / e.total)*100;
								          	let uploaded_percent = uploaded_percent_.toFixed(0); 

								            $('#upload_progress').html(uploaded_percent+'%');
								        }
							        }, false);
							      }
							      return myXhr;
							    },
							    complete: function () {
							    	$('#image_file').val('');
							    }
						 	});
						 	return false;
	    					

						}
				}
			})


			$('#video_upload').on('click', function(){

				const file = $('#video_file')[0].files[0];
				const upload_btn = $('#video_upload, #image_upload');

				//--Is a file selected?
	    		if ( !file ) {
	    			show_notice('No video selected!', 'error');
	    			return false;
	    		}
	    		else{

					var fileToUploadEXT = $('#video_file').val().split('.').pop().toLowerCase();
					var extn = [ 'mp4', 'webm', 'mkv', 'm4v', 'mov', '3gp' ];
				
					if($.inArray(fileToUploadEXT, extn) == -1){

						show_notice('Sorry, file format not supported.', 'error');
    					return false;

					} 
					else{
						
						show_notice('Processing video...', 'success');

						var formData = new FormData();    
					 	formData.append( 'file', file );
					 	formData.append( 'ext', fileToUploadEXT );
						formData.append( 'type', 'video' );

					 	upload_btn.attr('disabled', 'true');
					    $.ajax({
					       url : 'app/process.php',
					       type : 'POST',
					       data : formData,
					       processData: false,
					       contentType: false,
					        success: function (data) {
					            
					            show_notice( data, 'success' );
					 			upload_btn.attr('disabled', 'false');
					 			upload_btn.removeAttr('disabled');
					 			
				          	},
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								if (XMLHttpRequest.readyState === 0) {
									console.log( 'A network connection is not available!' );
								}
					 		},

					 		// Custom XMLHttpRequest
					        // For handling the progress of the upload
						    xhr: function () {
						      var myXhr = $.ajaxSettings.xhr();
						      if (myXhr.upload) {
						        	myXhr.upload.addEventListener('progress', function (e) {
						          	if (e.lengthComputable) {
						            
							            $('#progress').fadeIn('slow');
							          	
							          	let uploaded_percent_ = (e.loaded / e.total)*100;
							          	let uploaded_percent = uploaded_percent_.toFixed(0); 

							            $('#upload_progress').html(uploaded_percent+'%');
							        }
						        }, false);
						      }
						      return myXhr;
						    },
						    complete: function () {
						    	$('#video_file').val('');
						    }

						  });
					 	return false;

					}
				}
			})


		})

	
	</script>
</body>
</html>