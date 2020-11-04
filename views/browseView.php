<?php

// View class which shows all portfolio images if a user is logged in
class BrowseView extends View {
    
    public $portfolios;
    public $interests;
    
    // Displays information from all users portfolios
    protected function displayContent() {
        
        $html = '<h1>'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        if(!$this -> model -> userLoggedIn) {
            
            $html .= '<p class="links" >Please <a href="index.php?page=login">Login</a> or <a href="index.php?page=register">Register</a> to have full access to this page</p>'."\n";
            
        }
        
        if($this -> model -> seekLoggedIn) {
            $this -> interests = $this -> model -> processGetInterests($_SESSION['userID']);
            
            if(is_array($this -> interests)) {
                $html .= $this -> displayInterests();
            } 
        }
        
        $this -> portfolios = $this -> model -> getPortfolios();
        
        $html .= $this -> displayPortfolioImages();
        
        return $html;
        
    }
    
    // Displays each lancer's portfolio images
    private function displayPortfolioImages() {
        
        if(!$this -> portfolios['msg']) {
            
            $html = '<div id="columns">'."\n";
            foreach($this -> portfolios as $portfolio) {
    
                $html .= '<div class="item">'."\n";
                $html .= '<img src="images/uploads/thumbnails/'.$portfolio['portImage'].'" alt="'.htmlentities($portfolio['portName']).' image"/>'."\n";
                $html .= '<h4>'.stripslashes($portfolio['portName']).'</h4>'."\n";
                $html .= '<p>'.stripslashes($portfolio['portDescription']).'</p>'."\n";
                if($this -> model -> userLoggedIn) {
                    $html .= '<p><a href="index.php?page=profile&amp;portfolio='.$portfolio['portID'].'">View Profile</a></p>'."\n";
                }
                
                if($portfolio['userID'] == $_SESSION['userID']) {
                    $html .= '<p><a class="deletelinks" href="index.php?page=deletePortfolio&amp;id='.$portfolio['portID'].'">Delete</a></p>'."\n";
                $html .= '<p><a class="deletelinks" href="index.php?page=editPortfolio&amp;id='.$portfolio['portID'].'">Edit</a></p>'."\n";
                }
                
                $html .= '</div>'."\n";
            }
            
            $html .= '</div>'."\n";
            
        } else {
            $html .= '<h3>There are no images to show</h3>';
        }
        return $html;
        
    }
    
    private function displayInterests() {
        
        $html = '<h2>Your Interests</h2>';
        
        $html .= '<div id="poi-columns">'."\n";
        
        foreach($this -> interests as $interest) {

            $html .= '<div class="large-item">'."\n";
            $html .= '<img src="images/uploads/large-thumbs/'.$interest['portImage'].'" alt="Portfolio Name"/>'."\n";
            $html .= '<h4>'.stripslashes($interest['portName']).'</h4>'."\n";
            $html .= '<p>'.stripslashes($interest['portDescription']).'</p>'."\n";
            $html .= '<p><a href="index.php?page=profile&amp;id='.$interest['userID'].'&amp;img='.$interest['portImage'].'&amp;name='.$portfolio['portName'].'&amp;portfolio='.$portfolio['portID'].'">View Profile</a></p>'."\n";
            
            $html .= '</div>'."\n";
        }
        
        $html .= '</div>'."\n";
        return $html;
        
        
        
    }
    
}







?>