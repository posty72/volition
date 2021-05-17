<?php

// View class which allows a user to edit their information
include 'classes/selectClass.php';

class EditInfoView extends View
{

    // Returns the HTML for the ViewClass
    protected function displayContent()
    {

        $html = '';
        if (!$this->model->userLoggedIn) {
            $html .= '<p>Please log in to view this page</p>' . "\n";
            return $html;
        }
        $html .= '<h1>' . $_SESSION['userName'] . '</h1>' . "\n";

        if ($_POST['seekEditSubmit']) {

            $result = $this->model->processEditSeekInfo();

            if ($result['ok']) {
                header('Location: index.php?page=home');
            } else {
                $html .= $this->result['msg'];
            }
        } elseif ($_POST['lanceEditSubmit']) {

            $result = $this->model->processEditLanceInfo();

            if ($result['ok']) {
                header('Location: index.php?page=home');
            } else {
                $html .= $this->result['msg'];
            }

        } elseif ($_POST['userEditSubmit']) {

            $uresult = $this->model->processEditUserInfo();

            if ($uresult['ok']) {
                header('Location: index.php?page=home');
            } else {
                $html .= $this->uresult['msg'];
            }

        }

        $html .= '<p>' . $result['msg'] . '</p><br />' . "\n";

        $html .= $this->displayEditUserForm($uresult);

        if ($this->model->seekLoggedIn) {
            $html .= $this->displayEditSeekForm($result);
            return $html;
        } elseif ($this->model->lanceLoggedIn) {
            $html .= $this->displayEditLanceForm($result);
            return $html;
        }

        return $html;

    }

    // Displays a form to edit general user information
    private function displayEditUserForm($result)
    {

        if ($_POST['userEditSubmit']) {
            extract($_POST);
        } else {
            //Get the info to be changed
            $userInfo = $this->model->getUserInfo($_SESSION['userID']);
            //And extract it from their arrays
            extract($userInfo);
        }

        if (is_array($result)) {
            extract($result);
        }

        $select = new Select;
        $areaOpts = $select->fillAreaOptions();

        $html = '<div class="editPage">';
        $html .= '<form id="register" method="post" action="' . $_SERVER['REQUEST_URI'] . '">' . "\n";

        $html .= '<input type="hidden" name="userAccess" id="userAccess" value="' . $userAccess . '"/>' . "\n";
        $html .= '<input type="hidden" name="userID" id="userID" value="' . $userID . '"/>' . "\n";

        $html .= '<p>Username</p>' . "\n";
        $html .= '<input type="text" name="userName" id="userName" value="' . $userName . '" />' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<p>Email</p>' . "\n";
        $html .= '<p class="form-description">' . $userEmail . '</p>' . "\n";
        $html .= '<input type="text" name="userEmail" id="userEmail" value="' . $userEmail . '" />' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="userArea">Area</label>' . "\n";
        $area = ($area == "") ? $userArea : $area;
        $html .= $select->createSelect("userArea", $areaOpts, $area);
        $html .= '<br />' . "\n";

        $html .= '<br />' . "\n";

        $html .= '<input type="submit" name="userEditSubmit" id="userEditSubmit" value="Update Info" />' . "\n";

        $html .= '</form>' . "\n";
        $html .= '</div>';

        return $html;

    }

    // Displays a form to edit a seek users information
    private function displayEditSeekForm($result)
    {

        if ($_POST['seekEditSubmit']) {
            extract($_POST);
        } else {
            //Get the info to be changed
            $userInfo = $this->model->getUserInfo($_SESSION['userID']);
            $seekInfo = $this->model->getSeekInfo($_SESSION['userID']);
            //And extract it from their arrays
            extract($userInfo);
            extract($seekInfo);
        }

        if (is_array($result)) {
            extract($result);
        }

        $html = '<div class="editPage">';
        $html .= '<form id="register" method="post" action="' . $_SERVER['REQUEST_URI'] . '">' . "\n";

        $html .= '<input type="hidden" name="userAccess" id="userAccess" value="' . $userAccess . '"/>' . "\n";
        $html .= '<input type="hidden" name="userID" id="userID" value="' . $userID . '"/>' . "\n";
        $html .= '<input type="hidden" name="seekID" id="seekID" value="' . $seekID . '"/>' . "\n";

        $html .= '<label for="seekTitle">Display Name</label>' . "\n";
        $html .= '<input type="text" name="seekTitle" id="seekTitle" value="' . $seekTitle . '"/>' . "\n";
        $html .= '<p class="form-description">Only if different from username</p>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="seekCompany">Company Name</label>' . "\n";
        $html .= '<input type="text" name="seekCompany" id="seekCompany" value="' . $seekCompany . '"/>' . "\n";
        $html .= '<div class="error">' . $seekCompanyMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="seekPhNo">Phone Number</label>' . "\n";
        $html .= '<input type="text" name="seekPhNo" id="seekPhNo" value="' . $seekPhNo . '"/>' . "\n";
        $html .= '<div class="error">' . $seekPhNoMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="seekAddress">Mailing Address</label>' . "\n";
        $html .= '<textarea name="seekAddress" id="seekAddress" rows="7" cols="25" >' . $seekAddress . '</textarea>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<br />' . "\n";

        $html .= '<input type="submit" name="seekEditSubmit" id="seekEditSubmit" value="Update Info" />' . "\n";

        $html .= '</form>' . "\n";
        $html .= '</div>';

        return $html;

    }

    // Displays the form specific to editing a lance users information
    private function displayEditLanceForm($result)
    {

        if ($_POST['lanceEditSubmit']) {
            extract($_POST);
        } else {
            $userInfo = $this->model->getUserInfo($_SESSION['userID']);
            $lanceInfo = $this->model->getLanceInfo($_SESSION['userID']);
            extract($userInfo);
            extract($lanceInfo);
        }

        if (is_array($result)) {
            extract($result);
        }

        $html = '<div class="editPage">';

        $html .= '<form id="register" method="post" action="' . $_SERVER['REQUEST_URI'] . '" enctype="multipart/form-data" >' . "\n";

        $html .= '<input type="hidden" name="userAccess" id="userAccess" value="' . $userAccess . '"/>' . "\n";
        $html .= '<input type="hidden" name="userID" id="userID" value="' . $userID . '"/>' . "\n";
        $html .= '<input type="hidden" name="lanceID" id="lanceID" value="' . $lanceID . '" />' . "\n";

        $html .= '<label for="lanceDisplayName">Choose a display name</label>' . "\n";
        $html .= '<input type="text" name="lanceDisplayName" id="lanceDisplayName" value="' . $lanceDisplayName . '" />' . "\n";
        $html .= '<p class="form-description">Only if different from username (recommended)</p>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="profTitle">Area of expertise</label>' . "\n";
        $html .= '<select name="profTitle" id="profTitle">' . "\n";

        $html .= '<option>' . $profTitle . '</option>' . "\n";
        $html .= '<option>Web Design/Development</option>' . "\n";
        $html .= '<option>Graphic Design</option>' . "\n";
        $html .= '<option>Film and Media</option>' . "\n";
        $html .= '<option>Photography</option>' . "\n";
        $html .= '<option>Interactive Design</option>' . "\n";
        $html .= '<option>Event Management</option>' . "\n";
        $html .= '<option>Music</option>' . "\n";
        $html .= '<option>Artist</option>' . "\n";

        $html .= '</select>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="lanceExp">Previous experience</label>' . "\n";
        $html .= '<textarea name="lanceExp" id="lanceExp" rows="7" cols="15">' . htmlentities(stripslashes($lanceExp), ENT_QUOTES) . '</textarea>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="lanceBio">Write something about yourself</label>' . "\n";
        $html .= '<textarea name="lanceBio" id="lanceBio" rows="7" cols="15">' . htmlentities(stripslashes($lanceBio), ENT_QUOTES) . '</textarea>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="lanceSite">Link to your website</label>' . "\n";
        $html .= '<input type="text" name="lanceSite" id="lanceSite" value="' . htmlentities(stripslashes($lanceSite), ENT_QUOTES) . '" />' . "\n";

        $html .= '<div class="error">' . $userAreaMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<input type="submit" name="lanceEditSubmit" id="lanceEditSubmit" value="Update User" />' . "\n";

        $html .= '</form>' . "\n";
        $html .= '</div>';

        return $html;

    }

}
