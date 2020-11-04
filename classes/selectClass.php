<?php

class Select {

	public function createSelect($selectName, $selectOpts, $defVal='', $className='') {

		//Initionalise select tag and pass in name/id/class
		$html = '<select name="'.$selectName.'" id="'.$selectName.'" ';
		if($className) {
			$html .= 'class="'.$className.'"';
		}
		$html .= '>'."\n";

		//Set selected option

		$html .= '<option value="'.$defVal.'" selected="selected">'.$defVal.'</option>'."\n";

		

		//Loop through option tags
		foreach($selectOpts as $option) {
			if ($selectedVal && $selectedVal == $option) {
				$html .= '<option value="'.$option.'" selected="selected">'.$option.'</option>'."\n";
			}
			else {
				$html .= '<option value="'.$option.'">'.$option.'</option>'."\n";
			}
		}

		//Close tag and return to html
		$html .= '</select>'."\n";
		return $html;

	}

	//Create area options function
	public function fillAreaOptions() {
		$areaOpts = array (
		"Northland",
		"Auckland",
		"Waikato",
		"Taranaki",
		"Bay of Plenty",
		"Hawke's Bay",
		"Manawatu",
		"Wellington",
		"Nelson Bays/Marlborough",
		"Canterbury",
		"Otago",
		"Southland");
		return $areaOpts;
	}

}






?>