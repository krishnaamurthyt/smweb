<?php
	require ('../ScreensHelper.php');
	require ('..\Constants.php');

	$datas = json_decode($_REQUEST['data'],true);

	$inputResult = json_decode($_REQUEST['data'],true);
	
	$media_name = $_REQUEST['media_name'];
	$path = json_decode($_REQUEST['path'],true);
	//$duration_name = $_REQUEST['duration_name'];
	//$audio_name = $_REQUEST['audio_name'];


 //assigning values to array
	$inputResult['media_name'] = $media_name;
	$inputResult['path'] = $path;

	$response = json_encode($datas);


	get_data($datas);
	
	
	
	function get_data($inputResult)
	{
		
			
			require '..\database.php';
				$conn = mysqli_connect($server,$username,$password);
						mysqli_select_db($conn,$database);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 

		$screen=$GLOBALS['path']; 

  $list = implode(',', $screen);  
   $channelsQuery = "SELECT path,ip from screens where ch_id IN ($list) "; 

        $result = $conn->query($channelsQuery);

       if ($result->num_rows > 0) {

        
        while($row = $result->fetch_assoc()) 
        {
          $directory = $row['path'];
          $ip = $row['ip'];
          
          try{

        if(pingScreen($ip))
          {

			if(isset($_FILES["fileName"]['name']))
            {
               $info = pathinfo($_FILES["fileName"]['name']);
               $ext = $info['extension']; 
           
             
               $newname = "DNDM-".$GLOBALS['media_name'].".".$ext; 

                $target_file = $directory.$newname;

		    $src = $_FILES['fileName']['tmp_name'];
    	
			  if(!file_exists (copy_resource_temp_loc))
                 {
                
                   $isCreatedNewDir = mkdir(copy_resource_temp_loc,0777, true);
                 }


                 $tempFile = copy_resource_temp_loc.$newname;

                  $isCopied = copy($src, $tempFile ); 
          
                 rename($tempFile, $target_file);
		
				}
				

          //audio

				if(isset($_FILES["AudiofileName"]['name']))
				{
				$audio_info = pathinfo($_FILES["AudiofileName"]['name']);
				$audio_ext = $audio_info['extension']; 
				$newname = "DNDM-".$GLOBALS['media_name'].".".$audio_ext;

				$target_file = $directory.$newname;

		    	$src = $_FILES['AudiofileName']['tmp_name'];
    	
			  if(!file_exists (copy_resource_temp_loc))
                 {
                
                   $isCreatedNewDir = mkdir(copy_resource_temp_loc,0777, true);
                 }


                 $tempFile = copy_resource_temp_loc.$newname;

                  $isCopied = copy($src, $tempFile ); 
          
                 rename($tempFile,$directory.$newname);
		
				}
			
				

			  //json 
				$file_name1 = $GLOBALS['media_name'].'.txt';

				file_put_contents($directory.$file_name1, json_encode($inputResult));
				// file_put_contents("../json/image/".$file_name1, json_encode($insData));
		}
				
          }catch(Exception $e){
            echo 'Inside exception in pushing';
             
          }

        }
       }

       echo "New record created successfully";

   	


			$conn->close();
		
	}
	
	
	
?>