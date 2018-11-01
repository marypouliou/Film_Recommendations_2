<?php

	$target_dir = 'content/';
	$_FILES['fileToUpload']['name'] = 'content.xml';
	$target_file = $target_dir . $_FILES['fileToUpload']['name'];
		
		
/*	In case we want the user to upload the content

	$target_file = "";
	
// gets the file when submitted using the POST method                     
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$msg = "";
		$target_dir = 'content/';
		$target_file = $target_dir . $_FILES['fileToUpload']['name'];
		$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
	// Check if the file has been uploaded. A message is popped-up to inform the user
		if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {	  
		  $msg = $msg . "The file ". $_FILES['fileToUpload']['name']. " has been uploaded.";
		} else {
			$msg = $msg . "Sorry, there was an error uploading your file. ";
		}
	}
	
*/
?>

   <html>
	<head>
		<title>Programs - Film Recommendations</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<script type="text/javascript" src="jquery-3.2.1.js"></script>
		
			<script>
			$(document).ready(function(){
				$(function(){
					
				/* the file path that the user uploaded */
					var target_file = "<?php echo $target_file;?>";
					
					
				/* to display the content once the user uploads the file - prevents the display of null content upon file upload */
					if(target_file != ""){
						post();
						$("#file_uploaded").show(1000);
						$("#file_uploaded").fadeOut(4000);
					}
					
					
				/* if the user changes the sliders and he/she has already uploaded the xml filethen post() is called to return the corresponding content
							otherwise a message to upload it displays	 */
						
					$("#agitated_calm_range").on('change', function(){
						if(target_file != ""){
							post();
						}else if(target_file == ""){
							$("#no_content_added").hide();
							$("#no_content_added").show(1000);
							$("#no_content_added").fadeOut(4000);
						}
					});
						
					$("#happy_sad_range").on('change', function(){
						if(target_file != ""){
							post();
						}else if(target_file == ""){
							$("#no_content_added").hide();
							$("#no_content_added").show(1000);
							$("#no_content_added").hide(5000);
						}
					});
							
					$("#tired_wide_awake_range").on('change', function(){
						if(target_file != ""){
							post();
						}else if(target_file == ""){
							$("#no_content_added").hide();
							$("#no_content_added").show(1000);
							$("#no_content_added").hide(5000);
						}
					});
						
					$("#scared_fearless_range").on('change', function(){
						if(target_file != ""){
							post();
						}else if(target_file == ""){
							$("#no_content_added").hide();
							$("#no_content_added").show(1000);
							$("#no_content_added").hide(5000);
						}
					});
    			});
    			
			});
			</script>
			
			<script>
				function post(){
				/* extract the values of the 4 sliders */
					var target_file1 = "<?php echo $target_file; ?>";
					var agitated_calm_val1 = $("#agitated_calm_range").val();
					var happy_sad_val1 = $("#happy_sad_range").val();
					var tired_wide_awake_val1 = $("#tired_wide_awake_range").val();
					var scared_fearless_val1 = $("#scared_fearless_range").val();
					
					
				/*ajax to connect to the retrieve_content.php file sending the values of the emotions. 
				Then the content to be displayed will be returned having been extracted from the file uploaded. 
				The content is being returned after calculations that estimate the number of programs to be proposed per emotion
				*/
					
					$.post('retrieve_content.php',{target_file: target_file1,agitated_calm_val:agitated_calm_val1, happy_sad_val:happy_sad_val1, tired_wide_awake_val:tired_wide_awake_val1, scared_fearless_val: scared_fearless_val1 },
						
					/*handling the data returned. Split the info by the delimeter "||" and then setting the info to the image and title frames */
						function(data){
							extract_elements = data.split("||");
								
							for(i=0; i <= 4; i++){	
								$(".title_"+(i+1)+"").text(extract_elements[3*i]);
								$(".image_"+(i+1)+"").attr("src", extract_elements[(3*i)+1]);
							}
						}
					)};
			</script>
			
			<script>			
				$(document).ready(function(){
					$(function(){
						
						/*reset the values of the sliders displaying the corresponding content*/
						$("#reset_values").on('click', function(){
							$("input[type=range]").val('50');
							$(".range_value").text("");
							post();
						});
						
						/*file upload dialog pops-up */
						$("#upload_link").on('click', function(e){
							e.preventDefault();
							$("#upload:hidden").trigger('click');
    					});
					});
				});
			</script>
		
			<script>
				function range_value_text($range_group,$left_emotion,$right_emotion,$value){
					//$(".slider").on('change', function(){
						if($($range_group + " input[type=range]").val() < 50){
							$($range_group + " .range_value").text((100 -  2 * $($range_group + " input[type=range]").val()) + "% "+ $left_emotion);
						}else if($($range_group + " input[type=range]").val() > 50){
							$($range_group + " .range_value").text((2 * $($range_group + " input[type=range]").val() - 100) + "% "+ $right_emotion);
						}else if($range_group + $(".range_group_1 input[type=range]").val() == 50){
							$($range_group + " .range_value").text("No " + $left_emotion +"-"+ $right_emotion + " preference");
						}
				//	})
					}
			</script>
		
		<!--
		In case we want the user to upload the content
			<script>
				$(document).ready(function(){
					$(function(){
						/*when file is choosen, autosubmit is triggered - so as not to display the submit button */
						$('#upload').change(function() {
							$('#target').submit();
						});		
					});	
				});
			</script>
		-->
			
	</head>	
	
	<body>
		
		<!--logo-->
		<a href=""><img id="tv_logo" src="images/tv_logo.png" alt="tv_logo"></a>
		
		<p id = "title_bar">Film Recommendations Based on your Mood</p>
		
		<!-- Navigator bar-->
		<nav id="menu_navigator">
			<a id = "reset_values">Reset Moodslider </a> <!--to reset the values of the sliders-->
		<!--
			<form id="target" method="post" enctype='multipart/form-data'> //to upload the xml file
				<input id="upload" type="file" name="fileToUpload" accept=".xml"/>
			</form>
			<a href="" id="upload_link"> | Upload Content</a>​ 
		-->
		</nav>
<!--	<p id = "file_uploaded"><?php echo $msg;?></p> -->
		<div id="main_container">
<!--			<p id = "no_content_added">*Please upload an XML file first, providing the content</p> //warning when file is not uploaded and the user tries to change the sliders' values -->
			
			<!--Slider containers. It contains the 4 sliders with their labels-->
			<div id = "ranges">
				<div class = "range_group_1">
					<p class = "left_emotion">Agitated</p>
					<input type="range" min="0" max="101" value="50" class="slider" id="agitated_calm_range" onchange= "range_value_text('.range_group_1','Agitated','Calm',this.value)";>
					<p class = "range_value"></p>
					<p class = "right_emotion">Calm</p>
				</div>	
				<div class = "range_group_2">
					<p class = "left_emotion">Happy</p>
					<input type="range" min="0" max="101" value="50" class="slider" id="happy_sad_range" onchange= "range_value_text('.range_group_2','Happy','Sad',this.value)";>
					<p class = "range_value"></p>
					<p class = "right_emotion">Sad</p>
				</div>
				<div class = "range_group_3">
					<p class = "left_emotion">Tired</p>
					<input type="range" min="0" max="101" value="50" class="slider" id="tired_wide_awake_range" onchange= "range_value_text('.range_group_3','Tired','Wide Awake',this.value)";>
					<p class = "range_value"></p>
					<p class = "right_emotion">Wide Awake</p>
				</div>
				<div class = "range_group_4">
					<p class = "left_emotion">Scared</p>
					<input type="range" min="0" max="101" value="50" class="slider" id="scared_fearless_range" onchange= "range_value_text('.range_group_4','Scared','Fearless',this.value)";>
					<p class = "range_value"></p>
					<p class = "right_emotion">Fearless</p>
				</div>
			</div>
		
			<!--Programs. It displays the images and titles of the programs proposed-->
			<div id="program_div">
				<?php 
					for ($x = 1; $x <= 5; $x++) { ?>
						<div class = "program_subdiv">
							<img src="images/tv_logo.png" class="image_<?php echo $x; ?>" alt="program">
							<p class="title_<?php echo $x; ?>">Program Title</p>
						</div>
				<?php } ?>
				
			</div>	
		</div>
	</body>
</html>
