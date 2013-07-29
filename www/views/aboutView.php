<?php

// View class which shows a the about page
class AboutView extends View {
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        $html = '<h1>'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<div id="about-content">'."\n";
        
        $html .= '<div class="item">'."\n";
        $html .= '<h2>What is Volition?</h2>'."\n";
        $html .= '<p>Volition is place where people can come to show off their professional work and view other people\'s. It could be defined as an online portfolio. A place where people can come and look at a range of freelancers work and then get in contact if they are interested in openly employing.</p>'."\n";
        $html .= '</div>'."\n";
        
        $html .= '<div class="item">'."\n";
        $html .= '<h2>What is the difference between a \'Lance\' and a \'Seek\'?</h2>'."\n";
        $html .= '<p>Volition has two knids of users. Seek\'s are people who are looking for somebody that can produce high quality work, and are able to see Lance\'s profile to get information in order to contact them. A Lance is someone who can go on and upload their work and information to show to the world.</p>'."\n";
        $html .= '</div>'."\n";
        
        
        $html .= '<div class="item">'."\n";
        $html .= '<h2>How much does this service cost?</h2>'."\n";
        $html .= '<p>Volition is a free service, and always will be.</p>'."\n";
        $html .= '</div>'."\n";
        
        
        $html .= '<div class="item">'."\n";
        $html .= '<h2>Who can see my information?</h2>'."\n";
        $html .= '<p>Only people who have an account can log in see other users information. Volition doesn\'t take personal information such as names and addresses.</p>'."\n";
        $html .= '</div>'."\n";
        
        $html .= '</div>'."\n";
        
        return $html;
    }
    
    
    
    
}





?>