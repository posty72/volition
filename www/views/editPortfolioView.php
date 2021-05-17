<?php

// View class which shows an individual lances information and portfolio images
class EditPortfolioView extends View
{

    // Returns the HTML to be used on the ViewClass
    protected function displayContent()
    {

        $html .= '<h1>' . $this->pageInfo['pageHeading'] . '</h1>';

        $portfolio = $this->model->getLancePortfolioByID($_GET['id']);

        if (!$this->model->userLoggedIn || $this->model->seekLoggedIn) {
            $html .= '<p>Sorry, you do not have acces to this page.</p>';
            return $html;
        }

        if ($_POST) {
            $update = $this->model->editImage();
        }

        if ($update == 'ok' && !$this->model->adminLoggedIn) {
            header('Location: index.php?page=home');
        } elseif ($update == 'bad' && !$this->model->adminLoggedIn) {
            $imageImageMsg = 'Item not updated';
        } elseif ($update == 'ok' && $this->model->adminLoggedIn) {
            header('Location: index.php?page=profile&portfolio=' . $_POST['portID']);
        }

        $html .= '<img src="images/uploads/large-thumbs/' . $portfolio['portImage'] . '" alt="' . $portfolio['portName'] . '" />' . "\n";

        $html .= '<form id="addImageForm" method="post" action="' . $_SERVER['REQUEST_URI'] . '" enctype="multipart/form-data">' . "\n";

        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />' . "\n";
        $html .= '<input type="hidden" name="portID" value="' . htmlentities(stripslashes($portfolio['portID']), ENT_QUOTES) . '" />' . "\n";
        $html .= '<input type="hidden" name="portImage" value="' . htmlentities(stripslashes($portfolio['portID']), ENT_QUOTES) . '" />' . "\n";

        $html .= '<label for="portName">Image Name</label>' . "\n";

        if ($_POST) {
            $html .= '<input type="text" name="portName" id="portName" value="' . htmlentities(stripslashes($_POST['portName']), ENT_QUOTES) . '" />' . "\n";
        } else {
            $html .= '<input type="text" name="portName" id="portName" value="' . htmlentities(stripslashes($portfolio['portName']), ENT_QUOTES) . '" />' . "\n";
        }
        $html .= '<div id="pNameMsg" class="error">' . $imageNameMsg . '</div>' . "\n";

        $html .= '<label for="portDescription">Description</label>' . "\n";

        if ($_POST) {
            $html .= '<input type="text" name="portDescription" id="portDescription" value="' . htmlentities(stripslashes($_POST['portDescription']), ENT_QUOTES) . '" />' . "\n";
        } else {
            $html .= '<input type="text" name="portDescription" id="portDescription" value="' . htmlentities(stripslashes($portfolio['portDescription']), ENT_QUOTES) . '" />' . "\n";
        }

        $html .= '<div class="error">' . $imageImageMsg . '</div>' . "\n";

        $html .= '<p><input type="submit" id="addImageSubmit" name="addImageSubmit" value="Upload" /></p>' . "\n";
        $html .= '</form>' . "\n";

        return $html;

    }

}
