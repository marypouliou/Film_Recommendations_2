<?php

/* setting the sent info to variables */
	$target_file = $_POST['target_file'];
	$agitated_calm_val = $_POST['agitated_calm_val'];
	$happy_sad_val = $_POST['happy_sad_val'];
	$tired_wide_awake_val = $_POST['tired_wide_awake_val'];
	$scared_fearless_val = $_POST['scared_fearless_val'];
	
/* extracting the xml content and setting it to the $XML_Content variable */
	$XML_Content=simplexml_load_file($target_file);
	
/* initialisation of variables and the array that will return the content to be displayed */
	$array = array();
	$counter = 0;
	$agitated_calm_items = 0;
	$happy_sad_items = 0;
	$tired_wide_awake_items = 0;
	$scared_fearless_items = 0;
		
		
/* allocate the corresponding value of each mood to variables according to the sliders.
	Since a slider contains 2 moods in each side, the mood being on the left side has a range of 0-50 
	and the one on the right has a range of 51-100.
	Hence, the values are allocated to variables */
	
	if($agitated_calm_val >= '50'){
		$agitated_calm_val = $agitated_calm_val - 50;
		$agitated_calm_mood_name = "Calm";
	}else if($agitated_calm_val < '50'){
		$agitated_calm_val = 50 - $agitated_calm_val;
		$agitated_calm_mood_name = "Agitated";
	}
		
	if($happy_sad_val > '50'){
		$happy_sad_val = $happy_sad_val - 50;
		$happy_sad_mood_name = "Sad";
	}else if($happy_sad_val <= '50'){
		$happy_sad_val = 50 - $happy_sad_val;
		$happy_sad_mood_name = "Happy";
	}
		
	if($tired_wide_awake_val >= '50'){
		$tired_wide_awake_val = $tired_wide_awake_val - 50;
		$tired_wide_awake_mood_name = "Wide Awake";
	}else if($tired_wide_awake_val < '50'){
		$tired_wide_awake_val = 50 - $tired_wide_awake_val;
		$tired_wide_awake_mood_name = "Tired";
	}
		
	if($scared_fearless_val >= '50'){
		$scared_fearless_val = $scared_fearless_val - 50;
		$scared_fearless_mood_name = "Fearless";
	}else if($scared_fearless_val < '50'){
		$scared_fearless_val = 50 - $scared_fearless_val;
		$scared_fearless_mood_name = "Scared";
	}
		
/* calculating the total score of the user's mood rating */
	$score = $agitated_calm_val + $happy_sad_val + $tired_wide_awake_val + $scared_fearless_val;


/* if score is not equal to 0, hence not every slider indicates value 50, then the percentage of every mood is calculating
returning a number from 0 to 1 having 2 decimal digits.
If $x the variable that gives the value then the percentage is calculating as: 
							-($x-$score)/score
*/
	
	if($score != 0){	 
		$agitated_calm_percentage = number_format((float)round((-($agitated_calm_val - $score)/ $score),2),2,'.','');
		$happy_sad_percentage = number_format((float)round((-($happy_sad_val - $score)/ $score),2),2,'.','');
		$tired_wide_awake_percentage = number_format((float)round((-($tired_wide_awake_val - $score)/ $score),2),2,'.','');
		$scared_fearless_percentage = number_format((float)round((-($scared_fearless_val - $score)/ $score),2),2,'.','');
	}
		
		
/* if all the sliders inditate value 50 / condition to avoid division by 0
then set all the mood percentages be the same so as every category displays one element. then getting one randomly and setting the greatest value to it so as to display 2 (since the programms displayed are 5) */
	
	else if($score == 0){
		$random = rand(0,3);
			
		$agitated_calm_percentage = 0.80;
		$happy_sad_percentage = 0.80;
		$tired_wide_awake_percentage = 0.80;
		$scared_fearless_percentage = 0.80;
			
		switch ($random) {
			case (0):
				$agitated_calm_percentage = 0.50;
				break;
			case (1):
				$happy_sad_percentage = 0.50;
				break;
			case (2):
				$tired_wide_awake_percentage = 0.50;
				break;
			case (3):
				$scared_fearless_percentage = 0.50;
				break;
		}
	}
		
		
/* creating variables that use the get_items_to_display function passing the percentage values so as to get the number of items to be displayed*/
	
	$agitated_calm_items = get_items_to_display($agitated_calm_percentage);
	$happy_sad_items = get_items_to_display($happy_sad_percentage);
	$tired_wide_awake_items = get_items_to_display($tired_wide_awake_percentage);
	$scared_fearless_items = get_items_to_display($scared_fearless_percentage);
		
/*calculating the total items to be displayed*/
	$total_items = $agitated_calm_items + $happy_sad_items + $tired_wide_awake_items + $scared_fearless_items;
		
		
/* if $total_items < 5, then add the elements missing to the emotion that has the most items. */
	
	if( $total_items < 5){
		$max = max($agitated_calm_items,$happy_sad_items,$tired_wide_awake_items,$scared_fearless_items);
		foreach( array('agitated_calm_items','happy_sad_items','tired_wide_awake_items','scared_fearless_items') as $v) {
			if ($$v == $max) {
				switch ($$v) {
					case ($agitated_calm_items):
						$agitated_calm_items = $agitated_calm_items + (5-$total_items);
						$total_items = $agitated_calm_items + $happy_sad_items + $tired_wide_awake_items + $scared_fearless_items;
						break;	
					case ($happy_sad_items):
						$happy_sad_items = $happy_sad_items + (5-$total_items);
						$total_items = $agitated_calm_items + $happy_sad_items + $tired_wide_awake_items + $scared_fearless_items;
						break;
					case ($tired_wide_awake_items):
						$tired_wide_awake_items = $tired_wide_awake_items + (5-$total_items);
						$total_items = $agitated_calm_items + $happy_sad_items + $tired_wide_awake_items + $scared_fearless_items;
						break;
					case ($scared_fearless_items):
						$scared_fearless_items = $scared_fearless_items + (5-$total_items);
						$total_items = $agitated_calm_items + $happy_sad_items + $tired_wide_awake_items + $scared_fearless_items;
						break;		
				}
			}
		}
	}
		
/* content extraction from the XML uploaded */
	foreach ($XML_Content->programme as $programme) {
		
		//Get the desired elements
		$name = $programme->name;
		$image = $programme->image_path;
		$mood = $programme->mood;
		
	/* According to the $mood value extracted, its number of items to be displayed is checked.
	If they are more than 0 then the name, url image and mood name extracted from the file are saved in the array: $array
	Each value is separated by the next one by "||". The delimeter was used for handling the data when they return back to javascript so as to set the content that will be displayed in the image and title frames.
	*/		
		
		switch ($mood) {
			case ($agitated_calm_mood_name):
				if($agitated_calm_items > 0){
					$array[$counter] = array();
					$array[$counter]['name'] = $name."||";
					$array[$counter]['image'] = $image."||";
					$array[$counter]['mood'] = $mood."||";
					$agitated_calm_items -= 1;
					$counter = $counter + 1;
				}
				break;	
			case ($happy_sad_mood_name):
				if($happy_sad_items > 0){
					$array[$counter] = array();
					$array[$counter]['name'] = $name."||";
					$array[$counter]['image'] = $image."||";
					$array[$counter]['mood'] = $mood."||";
					$happy_sad_items -= 1;
					$counter = $counter + 1;
				}
				break;
			case ($tired_wide_awake_mood_name):
				if($tired_wide_awake_items > 0){
					$array[$counter] = array();
					$array[$counter]['name'] = $name."||";
					$array[$counter]['image'] = $image."||";
					$array[$counter]['mood'] = $mood."||";
					$tired_wide_awake_items -= 1;
					$counter = $counter + 1;
				}
				break;
			case ($scared_fearless_mood_name):
				if($scared_fearless_items > 0){
					$array[$counter] = array();
					$array[$counter]['name'] = $name."||";
					$array[$counter]['image'] = $image."||";
					$array[$counter]['mood'] = $mood."||";
					$scared_fearless_items -= 1;
					$counter = $counter + 1;
				}
				break;		
		}	
				
	}
		
		
/* returning the values extracted */
		
	$len=count($array);
		
	for ($i=0;$i<$len;$i++){
		echo $array[$i]['name'];
		echo $array[$i]['image'];
		echo $array[$i]['mood'];
	}
		
		
		
/* The function checks the percentage of each mood returning the items to be displayed. 
	The range [0,1] has been divited into sub ranges.
	[0.01 - 0.20] that retuns 4 items to be displayed
	[0.21,0.40] that retuns 3 items to be displayed
	[0.41,0.60] that retuns 2 items to be displayed
	[0.61,0.90] that retuns 1 items to be displayed
	[0.91,1]  that retuns 0 items to be displayed
	Finally, if $mood_percentage equals to 0, then 5 items to be displayed are returned since only that mood has been rated by the  user */
		
	function get_items_to_display($mood_percentage){
		switch ($mood_percentage) {
			case ($mood_percentage == 0.00):
				$items_to_display = 5;
				break;
			case ($mood_percentage >= 0.01 && $mood_percentage <= 0.20):
				$items_to_display = 4;
				break;
			case ($mood_percentage >= 0.21 && $mood_percentage <= 0.40):
				$items_to_display = 3;
				break;
			case ($mood_percentage >= 0.41 && $mood_percentage <= 0.60):
				$items_to_display = 2;
				break;
			case ($mood_percentage >= 0.61 && $mood_percentage <= 0.90):
			$items_to_display = 1;	
				break;			
			case ($mood_percentage >= 0.91 && $mood_percentage <= 1):
				$items_to_display = 0;
				break;
		}
		return $items_to_display;
	}

?>
