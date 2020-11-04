<?php


// View class which shows an individual lances information and portfolio images
class ProfileView extends View {
    
    public $lanceInfo;
    public $lancePortfolio;
    public $userInfo;
    
    // Returns the HTML to be displayed by ViewClass.php
    protected function displayContent() {
        
        
        $portfolio = $this -> model -> getLancePortfolioByID($_GET['portfolio']);
        
        $this -> lancePortfolio = $this -> model -> getLancePortfolio($portfolio['userID']);
        $this -> lanceInfo = $this -> model -> getLanceInfo($portfolio['userID']);
        $this -> userInfo = $this -> model -> getUserInfo($portfolio['userID']);
        
        if($this -> model -> adminLoggedIn && !$_GET['portfolio']) {
            $this -> lanceInfo = $this -> model -> getLanceInfo($_GET['id']);
            $this -> userInfo = $this -> model -> getUserInfo($_GET['id']);
        }
        //print_r($this -> lanceInfo);
        $html = '';
        
        if($this -> model -> userLoggedIn) {
            if($this -> lanceInfo['lanceDisplayName']) {
                $html .= '<h1>'.$this -> lanceInfo['lanceDisplayName'].'</h1>'."\n";
            } else {
                $html .= '<h1>'.$this -> userInfo['userName'].'</h1>'."\n";
            }
        } else {
            
            $html .= '<h1>Error. Restricted Access.</h1>';
            
        }
        
        
        if(!$this -> model -> userLoggedIn) {
            $html .= '<p class="links" >Please either <a href="index.php?page=login">Login</a> or <a href="index.php?page=register">Register</a> to have access to this page</p>'."\n";
            return $html;
        }
        
        if(!$portfolio['userID'] && !$this -> model -> adminLoggedIn) {
            header('Location: index.php?page=browse');
        }
        
        //print_r($this -> userInfo);
        
        if(!$this -> userInfo) {
            $html .= '<h1>Error</h1>';
            $html .= '<h3>The user could not be found</h3>';
            $html .= '<a href="index.php">Return</a>';
            return $html;
        }
        
        
        
        if($_POST['interestsSubmit']) {
            $interest = $this -> model -> processAddInterest();
            $html .= $interest['msg'];
        }
        
        if($_GET['portfolio']) {
            
            
            $html .= '<div id="profilePortfolio">';
            $html .= '<img src="images/uploads/fulls/'.$portfolio['portImage'].'" alt="'.$portfolio['portName'].'" />';
            
            if($_SESSION['userAccess'] == 'seek') {
                
                $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'" />';
                $html .= '<input type="hidden" name="userID" id="userID" value="'.$_SESSION['userID'].'" />';
                $html .= '<input type="hidden" name="portID" id="portID" value="'.$_GET['portfolio'].'" />'."\n";
                $html .= '<input type="submit" name="interestsSubmit" id="interestsSubmit" value="Interested" />'."\n";
                if($interest['msg'] == 'Successfully added to your interests') {
                    $html .= '<img src="images/tick.png" alt="Added Successfully" />';
                }
                $html .= '</form>';
                
            }
        
        
        
            $html .= '<h2>'.$portfolio['portName'].'</h2>';
            $html .= '</div>';
        }
        
        $html .= $this -> displayLanceProfile();
        
        return $html;
    }
    
    
    // Returns the HTML which displays a lance users information
    private function displayLanceProfile() {
        
        
        $html = '<div id="user-profile">'."\n";
        
        $html .= '<div id="userInfo" >'."\n";
        
        
        $html .= '<h3>Info</h3>'."\n";
        
        
        $html .= '<ul>'."\n";
        $html .= '<li><strong>Email</strong>'.$this -> userInfo['userEmail'].'</li>'."\n";
        
        
        $html .= '<li><strong>Area of Expertise</strong>'.$this -> lanceInfo['profTitle'].'</li>'."\n";
        $html .= '<li><strong>Location</strong>'.$this -> userInfo['userArea'].'</li>'."\n";
        
        if($this -> lanceInfo['lanceBio']) {
            $html .= '<li><strong>Bio.</strong>'.stripslashes($this -> lanceInfo['lanceBio']).'</li>'."\n";
        }
        
        if($this -> lanceInfo['lanceSite']) {
            $html .= '<li class="userSite"><strong>Site</strong><a href="'.$this -> lanceInfo['lanceSite'].'">'.$this -> lanceInfo['lanceSite'].'</a></li>'."\n";
        }
        
        $html .= '</ul>'."\n";
        
        
        $html .= '</div>'."\n";
        
        $html .= '<div id="displayPic">'."\n";
        
        
        $html .= '<h3>Display Pic</h3>'."\n";
        
        
        $html .= '<img src="images/display/'.$this -> lanceInfo['lanceDisplayImage'].'" alt="Display Picture" width="300px" />'."\n";
        $html .= '</div>'."\n";
        
        if($this -> lanceInfo['lanceExp'] || $this -> lanceInfo['lanceRating']) {
        
            $html .= '<div id="userWork">'."\n";
        
            if($this -> lanceInfo['lanceDisplayName']) {
                $html .= '<h3>'.$this -> lanceInfo['lanceDisplayName'].' Work</h3>'."\n";
            } else {
                $html .= '<h3>'.$this -> userInfo['userName'].' Work</h3>'."\n";
            }
            
            $html .= '<ul>'."\n";
            if($this -> lanceInfo['lanceExp']) {
                $html .= '<li><strong>Previous Experience</strong>'."\n";
                $html .= '<p>'.stripslashes($this -> lanceInfo['lanceExp']).'</p>'."\n";
                $html .= '</li>'."\n";
                
            }
            
            $html .= '</ul>'."\n";
            
            $html .= '</div>'."\n";
        
        }
        
            $html .= '<div id="portfolio">'."\n";
            
            $html .= '<h3>Portfolio Images</h3>'."\n";
        
        if(!$this -> lancePortfolio['msg']) {
            
            $html .= '<div id="columns">'."\n";
            
            foreach($this -> lancePortfolio as $portfolio) {
            
                $html .= '<div class="item">'."\n";
                $html .= '<img src="images/uploads/thumbnails/'.$portfolio['portImage'].'" alt="'.$portfolio['portName'].'"/>'."\n";
                $html .= '<h4>'.$portfolio['portName'].'</h4>'."\n";
                $html .= '<p>'.$portfolio['portDescription'].'</p>'."\n";
                $html .= '<a href="index.php?page=profile&amp;portfolio='.$portfolio['portID'].'">View Profile</a>'."\n";
                
                if($this -> model -> adminLoggedIn) {
                    $html .= '<a href="index.php?page=deletePortfolio&amp;id='.$portfolio['portID'].'">Delete</a>';
                    $html .= '<a class="deletelinks" href="index.php?page=editPortfolio&amp;id='.$portfolio['portID'].'">Edit</a>'."\n";
                }
                
                $html .= '</div>'."\n";
                
            }
            
            $html .= '</div>'."\n";
        } else {
            $html .= '<p class="error">'.$this -> lancePortfolio['msg'].'</p>'."\n";
        }
            
            
        $html .= '</div>'."\n";
        $html .= '</div>'."\n";
        
        return $html;
        
    }
    
    
}













?>