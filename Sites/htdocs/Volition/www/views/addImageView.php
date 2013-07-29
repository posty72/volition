<?php

// View class which lets a lance user add an images to their personal portfolio 
class AddImageView extends View {
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        $html = '<h1>'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        if(!$this -> model -> lanceLoggedIn) {
            $html .= '<p>Sorry, but this is a restricted page</p>'."\n";
            return $html;
        }
        
        if($_POST['addImageSubmit']) {
            
            $result = $this -> model -> processAddImage();
            
            if($result['ok']) {
                header('Location: index.php?page=home');
                $html .= $result['msg'];
                return $html;
            }
            
        }
        
        $html .= $this -> displayAddImageForm($result, $_POST);
        
        return $html;
    }
    
    // Returns the form used to send information for adding an image 
    private function displayAddImageForm($result, $image) {
        
        
        if(is_array($result)) {
            extract($result);
        }
        
        extract($image);
        
        $html .= '<form id="addImageForm" method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">'."\n";
        
        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";
        $html .= '<input type="hidden" name="portID" value="'.$portID.'" />'."\n";
        $html .= '<input type="hidden" name="portImage" value="'.$portImage.'" />'."\n";
        
        $html .= '<label for="portName">Image Name</label>'."\n";
        $html .= '<input type="text" name="portName" id="portName" value="'.htmlentities(strip_tags($portName),ENT_QUOTES).'" />'."\n";
        $html .= '<div id="pNameMsg" class="error">'.$imageNameMsg.'</div>'."\n";
        
        $html .= '<label for="portDescription">Description</label>'."\n";
        $html .= '<input type="text" name="portDescription" id="portDescription" value="'.htmlentities(strip_tags($portDescription),ENT_QUOTES).'" />'."\n";
        
        
        $html .= '<div><label for="portImg">Upload New Image to your portfolio</label>'."\n";
        $html .= '<input type="file" name="portImg" />'."\n";
        
        //$pImageMsg = $uploadMsg ? $uploadMsg : $pImageMsg;
        
        $html .= '<div class="error">'.$imageImageMsg.'</div></div>'."\n";
        
        $html .= '<p><input type="submit" id="addImageSubmit" name="addImageSubmit" value="Upload" /></p>'."\n";
        $html .= '</form>'."\n";
        
        return $html;
        
        
    }
    
    
}









?>