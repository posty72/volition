<?php

// View which allows every user's to change their password
class ChangePasswordView extends View {
    
// Returns the HTML to be displayed by ViewClass.php
    protected function displayContent(){
        
        $html = '<h1>'.$this -> pageInfo['pageHeading'].'</h1>';

        if(!$this -> model -> userLoggedIn) {
            $html .= '<p>Please log in to view this page</p>'."\n";
            return $html;
        }
        
        if($_POST['changeSubmit']) {
            
            $result = $this -> model -> processChangePassword();
            
            if($result['msg'] == 'Password successfully changed') {
                $html .= '<p>'.$result['msg'].'</p>'."\n";
                $html .= '<h3><a href="index.php?page=home">Return to Your Profile</a></h3>';
                
                return $html;
            }
            
        }
        
        if(is_array($result)){
            extract($result);
        }
        
        $html .= '<div class="error">'.$result['msg'].'</div>'."\n";
	
        $html .= '<form id="changePassword" method="post" action="'.$_SERVER['REQUEST_URI'].'" >'."\n";
        
        $html .= '<input type="hidden" name="userID" id="userID" value="'.$_SESSION['userID'].'" />';
        
        $html .= '<label for="userPassword">Current Password</label>'."\n";
        $html .= '<input type="password" name="userPassword" id="userPassword" />'."\n";
        
        $html .= '<label for="userNewPassword">New Password</label>'."\n";
        $html .= '<input type="password" name="userNewPassword" id="userNewPassword" />'."\n";
        $html .= '<br />'."\n";
        
        $html .= '<label for="userConfNewPassword">Confirm New Password</label>'."\n";
        $html .= '<input type="password" name="userConfNewPassword" id="userConfNewPassword" />'."\n";
        $html .= '<br />'."\n";
        
         $html .= '<input type="submit" name="changeSubmit" id="changeSubmit" value="Update" />'."\n";
         
         $html .= '</form>'."\n";
         
         return $html;
        
        
    }
    
    
    
    
}




?>