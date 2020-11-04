<?php

// View class which shows the login page, or logs the user out
class LoginoutView extends View {
    
// Returns the HTML to be displayed by ViewClass.php
    protected function displayContent() {
        
        $html = '';
        
        
        if($this -> model -> userLoggedIn) {
            header('Location: index.php?page=home');
        } else {
            $html .= $this -> displayLoginForm();
        }
        
        return $html;
        
    }

    // Displays the login form
    private function displayLoginForm() {
        
        $html = '<div>'."\n";
        $html .= '<h1>Login</h1>'."\n";
        
        $html .= '<form id="login" method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
        
        $html .= '<label for="userName">Username</label>'."\n";
        $html .= '<input type="text" name="userName" id="userName" value="'.htmlentities(stripslashes($_POST['userName'])).'"/>'."\n";
        $html .= '<br />'."\n";
        
        $html .= '<label for="userPassword">Password</label>'."\n";
        $html .= '<input type="password" name="userPassword" id="userPassword" />'."\n";
        $html .= '<br />'."\n";
        $html .= '<p>'.$this -> model -> loginMsg.'</p>';
        $html .= '<input type="submit" name="loginSubmit" id="loginSubmit" value="Login" />'."\n";
        
        $html .= '</form>'."\n";
        
        $html .= '</div>'."\n";

        return $html;
    }



}


?>