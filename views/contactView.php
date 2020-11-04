<?php

// Shows the contact information of the so user's can contact administrators
    
// Returns the HTML to be displayed by ViewClass.php
    protected function displayContent() {
        
        $html = '<h1>'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<div id="contact">'."\n";
        $html .= '<h3>Phone</h3>'."\n";
        $html .= '<p>05050056145</p>'."\n";
        $html .= '<h3>Email</h3>'."\n";
        $html .= '<p>admin@mail.com</p>'."\n";
        $html .= '</div>'."\n";
        
        if($_POST['submit']) {
            $sendMessage = $this -> model -> processSendMessage();
            if(is_array($sendMessage)) {
                extract($sendMessage);
            }
        }
        
        $html .= '<div id="feedback">'."\n";
        
        $html .= '<h4>Please send of feedback on our site and ways you think we can improve. Don\'t be gentle.</h4>'."\n";
        
        if(!is_array($sendMessage)) {
            $html .= '<p>'.$sendMessage.'</p>';
        }
        
        $html .= '<form id="feedback-form" method="post" action="">'."\n";
        
        $html .= '<label for="name">Name</label>'."\n";
        $html .= '<input type="text" name="name" id="name" value="'.$_POST['name'].'" />'."\n";
        $html .= '<p class="error">'.$nameMsg.'</p>';
        
        $html .= '<label for="email">Email</label>'."\n";
        $html .= '<input type="text" name="email" id="email" value="'.$_POST['email'].'" />'."\n";
        $html .= '<p class="error">'.$emailMsg.'</p>';
        
        $html .= '<label for="message">Please type your message</label>'."\n";
        $html .= '<textarea name="message" id="message" rows="7" cols="45">'.$_POST['message'].'</textarea>'."\n";
        $html .= '<p class="error">'.$messageMsg.'</p>';
        
        $html .= '<input type="submit" name="submit" id="submit" value="Send" />'."\n";
        
        $html .= '</form>'."\n";
        
        $html .= '</div>'."\n";
        
        return $html;
    }
}






?>