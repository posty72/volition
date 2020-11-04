<?php

// Include the select class
include 'classes/selectClass.php';


// View class which shows allows people to register as a lance user
class RegisterLanceView extends View {
    
// Returns the HTML to be displayed by ViewClass.php
    protected function displayContent() {
        
        
        $html .= '<h1>'.$this -> pageInfo['pageHeading'].'</h1>';
        
        if($this -> model -> userLoggedIn) {
            $html .= '<h3>You are already logged in. Please return to your <a href"index.php?page=home">Profile</a></h3>';
            return $html;
        }
        
        if($_POST['lanceRegisterSubmit']) {

            $result = $this -> model -> processAddLance();
            
            if($result['ok']) {
                $html .= '<p>User successfully created</p>'."\n";
                $html .= '<p class="controlButton"><a href="index.php?page=login">Login</a></p>'."\n";
                $html .= '</div>';
                return $html;
            } else {
                $html .= '<p class="error">User was not successfully created</p>'."\n";
                $html .= '<p>'.$result['msg'].'</p>';
            }

        }

        $html .= $this -> displayLanceForm($result);
        

        return $html;
        
    }

    // Displays the form used t register as a lance user
    private function displayLanceForm($result) {

        if(is_array($result)) {
            extract($result);
        }        

        $select = new Select;
        $areaOpts = $select -> fillAreaOptions();
        
        $html = '<form id="register" method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data" >'."\n";
        
        $html .= "\t".'<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";
        $html .= "\t".'<input type="hidden" name="lanceDisplayImg" value="'.$lanceDisplayImg.'" />'."\n";
        
        $html .= "\t".'<label for="userName">Username</label>'."\n";
        $html .= "\t".'<input type="text" name="userName" id="userName" value="'.$_POST['userName'].'"/>'."\n";
        $html .= "\t".'<div class="error">'.$userNameMsg.'</div>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<label for="userEmail">Email</label>'."\n";
        $html .= "\t".'<input type="text" name="userEmail" id="userEmail" value="'.$_POST['userEmail'].'"/>'."\n";
        $html .= "\t".'<div class="error">'.$userEmailMsg.'</div>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<label for="userPassword">Password</label>'."\n";
        $html .= "\t".'<input type="password" name="userPassword" id="userPassword" />'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<label for="userConfPassword">Confirm Password</label>'."\n";
        $html .= "\t".'<input type="password" name="userConfPassword" id="userConfPassword" />'."\n";
        $html .= "\t".'<div class="error">'.$userPasswordMsg.'</div>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<input type="hidden" name="userAccess" id="userAccess" value="lance"/>'."\n";
        
        $html .= "\t".'<label for="lanceDisplayName">Choose a display name</label>'."\n";
        $html .= "\t".'<input type="text" name="lanceDisplayName" id="lanceDisplayName" value="'.$_POST['lanceDisplayName'].'" />'."\n";
        $html .= "\t".'<p class="form-description">Only if different from username (recommended)</p>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<label for="lanceDisplayImg">Choose a display image</label>'."\n";
        $html .= "\t".'<input type="file" name="lanceDisplayImg" id="lanceDisplayImg" />'."\n";
        
        $lanceDisplayImgMsg = $uploadMsg ? $uploadMsg : $lanceDisplayImgMsg;
        
        $html .= "\t".'<div class="error">'.$lanceDisplayImgMsg.'</div>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<label for="profTitle">Area of expertise</label>'."\n";
        $html .= "\t".'<select name="profTitle" id="profTitle">'."\n";
        
        if($_POST['profTitle']) {
            $profession = $_POST['profTitle'];
        } else {
            $profession = 'Please Select...';
        }
        
        $html .= "\t"."\t".'<option>'.$profession.'</option>'."\n";
        $html .= "\t"."\t".'<option>Web Design/Development</option>'."\n";
        $html .= "\t"."\t".'<option>Graphic Design</option>'."\n";
        $html .= "\t"."\t".'<option>Film and Media</option>'."\n";
        $html .= "\t"."\t".'<option>Photography</option>'."\n";
        $html .= "\t"."\t".'<option>Interactive Design</option>'."\n";
        $html .= "\t"."\t".'<option>Event Management</option>'."\n";
        $html .= "\t"."\t".'<option>Music</option>'."\n";
        $html .= "\t"."\t".'<option>Artist</option>'."\n";
        
        $html .= "\t".'</select>'."\n";
        $html .= "\t".'<div class="error">'.$profTitleMsg.'</div>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<label for="lanceExp">Previous experience</label>'."\n";
        $html .= "\t".'<textarea name="lanceExp" id="lanceExp" rows="7" cols="15">'.$_POST['lanceExp'].'</textarea>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<label for="lanceBio">Write something about yourself</label>'."\n";
        $html .= "\t".'<textarea name="lanceBio" id="lanceBio" rows="7" cols="15">'.$_POST['lanceBio'].'</textarea>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<label for="lanceSite">Link to your website</label>'."\n";
        $html .= "\t".'<input type="text" name="lanceSite" id="lanceSite" value="'.$_POST['lanceSite'].'" />'."\n";
        
        if($_POST['userArea']) {
            $userArea = $_POST['userArea'];
        } else {
            $userArea = 'Please Select...';
        }

        $html .= "\t".'<label for="userArea">Area</label>'."\n";
        $area = ($area == "") ? $userArea : $area;
        $html .= $select -> createSelect("userArea", $areaOpts, $area);
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<div class="error">'.$userAreaMsg.'</div>'."\n";
        $html .= "\t".'<br />'."\n";
        
        $html .= "\t".'<input type="submit" name="lanceRegisterSubmit" id="lanceRegisterSubmit" value="Create User" />'."\n";
        
        $html .= '</form>'."\n";
        
        return $html;
    }
    
}





?>