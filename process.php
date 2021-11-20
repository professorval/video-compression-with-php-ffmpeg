<?php
	/** 
	 * Show and Log errors 
	 */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require 'func.php';
	require 'db.php';
	require 'class.action.php';

	if ( isset($_POST['type']) && $_POST['type'] === 'image' ) {
		
		$ext = $_POST['ext'];
		$file_name = $_FILES['file']['name'];
		$file = fopen($_FILES['file']['tmp_name'], 'r');
		file_put_contents( "../files/".$file_name, $file);
		
		$uploaded_file = '../files/'.$file_name;
		$compressed_file_location = '../files/compressed_'.$file_name;

		$image_extensions = [ 'jpg', 'png', 'jpeg', 'gif', 'webp' ];
		

		//--File is an image
		if (in_array($ext, $image_extensions)) {

			echo 'File is an image. Compressing..<br>';
			compress_image($uploaded_file, $compressed_file_location, 25);

			//--Save filename to db
			$filename = 'compressed_'.$file_name;

			if ( $action->save_to_db( $filename, 'image' ) ) {
				echo 'Compressing complete..<br><br>';
				echo '<a href="files/compressed_'.$file_name.'" download >Download Compressed File</a>';
				unlink('../files/'.$file_name);
			}
			else{
				echo "An error occurred.";
			}

		}
	}


	if ( isset($_POST['type']) && $_POST['type'] === 'video' ) {


		$ext = $_POST['ext'];
		$file_name = $_FILES['file']['name'];
		$file = fopen($_FILES['file']['tmp_name'], 'r');
		file_put_contents( "../files/".$file_name, $file);

		$find = '.'.$ext;
		$uploaded_file = '../files/'.$file_name;
		
		$compressed_file_name = str_replace($find, '', $file_name);
		$compressed_file_name = 'compressed_'.$compressed_file_name.'.mp4';

		$video_extensions = [ 'mp4', '3gp', 'webm', 'mkv', 'mov' ];

		//--File is a video
		if (in_array($ext, $video_extensions)) {

			require 'ffmpeg/vendor/autoload.php';

			echo 'File is a video. Compressing...<br>';
			$ffmpeg = FFMpeg\FFMpeg::create(array(
                                            'ffmpeg.binaries' => 'ff-mpeg-exe/ffmpeg',
                                            'ffprobe.binaries' => 'ff-mpeg-exe/ffprobe',
                                            'timeout' => 3600,
                                            'ffmpeg.threads' => 1
                                        ));
			$ffmpeg->getFFMpegDriver()->listen(new \Alchemy\BinaryDriver\Listeners\DebugListener());
			$ffmpeg->getFFMpegDriver()->on('debug', function ($message) {
			    //dump_var( $message );
			});
			

		    try {


		    	$video = $ffmpeg->open( $uploaded_file );
				
				$video->filters()->resize(new FFMpeg\Coordinate\Dimension(320, 240), '', true)->synchronize();

				$format = new FFMpeg\Format\Video\X264();
				$format->setKiloBitrate(500)->setAudioChannels(2)->setAudioKiloBitrate(128);

			    if($video->save($format, '../files/'.$compressed_file_name)){	
			    	$action->save_to_db( $compressed_file_name, 'image' );

				    echo 'Compressing complete..<br><br>';
					echo '<a href="files/'.$compressed_file_name.'" download >Download Compressed File</a>';
					unlink('../files/'.$file_name);

				}

		    } 
		    catch (Exception $e) {
		    	//dump_var($e->getMessage());
		    }


		}
	}