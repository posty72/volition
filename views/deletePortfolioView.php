<?php

// View class which allows a user to delete their portfolio
class DeletePortfolioView extends View {
    
    // Returns the HTML for the ViewClass
    protected function displayContent() {
        
        $html .= '<h1>'.$this -> pageInfo['pageHeading'].'</h1>';
        
        if(!$this -> model -> userLoggedIn || $this -> model -> seekLoggedIn) {
            $html .= '<p>Sorry, you do not have acces to this page.</p>';
            return $html;
        }
        
        if($_POST['confirm']) {
            $result = $this -> model -> processDeletePortfolio();
            $html .= '<p>'.$result['msg'].'</p>';
            return $html;
        } elseif($_POST['cancel']) {
            header('Location: index.php?page=home');
        }
        
        $portfolio = $this -> model -> getLancePortfolioByID($_GET['id']);
        $html .= $this -> displayDeletePortfolioForm($portfolio);
        
        return $html;
    }
    
    // Displays the form used to delete a protfolio
    private function displayDeletePortfolioForm($portfolio) {
        
        $html  = '<div id="deletePortfolio">';
        $html .= '<img src="images/uploads/fulls/'.$portfolio['portImage'].'" alt="'.$portfolio['portName'].'" />';
        $html .= '<h2>'.$portfolio['portName'].'</h2>';
        $html .= '<p>'.$portfolio['portDescription'].'</p>';
        $html .= '</div>';
        
        $html .= '<form id="deletePortfolioForm" method="post" action="'.$_SERVER['REQUEST_URI'].'" >';
        $html .= '<input type="hidden" name="portID" value="'.$portfolio['portID'].'" />';
        $html .= '<input type="hidden" name="portImage" value="'.$portfolio['portImage'].'" />';
        $html .= '<input type="submit" name="cancel" value="Cancel" />';
        $html .= '<input type="submit" name="confirm" value="Delete" />';
        $html .= '</form>';
        
        return $html;
    }
    
}










?>