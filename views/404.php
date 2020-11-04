<?php

// View class which shows the error 404 for missing pages
class ErrorView extends View {
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        $html .= '<h1>Error 404 - Page Not Found</h1>';
        $html .= '<p>This page does not exist. Please <a class="links" href="index.php?page=home">return to the home page.</a></p>';
        
        return $html;
    }
    
    
    
    
}







?>