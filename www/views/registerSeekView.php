<?php
// Include the select class
include 'classes/selectClass.php';

// View class which shows let's people register as a seek user
class RegisterSeekView extends View
{

// Returns the HTML to be displayed by ViewClass.php
    protected function displayContent()
    {

        $html .= '<h1>' . $this->pageInfo['pageHeading'] . '</h1>' . "\n";

        if ($this->model->userLoggedIn) {
            $html .= '<h3>You are already logged in. Please return to your <a href"index.php?page=home">Profile</a></h3>' . "\n";
            return $html;
        }

        if ($_POST['seekRegisterSubmit']) {

            $result = $this->model->processAddSeek();

            if ($result['id']) {
                $html .= '<p>User successfully created</p>' . "\n";
                $html .= '<p class="controlButton"><a href="index.php?page=login">Login</a></p>' . "\n";
                return $html;
            } else {
                $html .= '<p class="error">User was not successfully created</p>' . "\n";
            }

        }

        $html .= $this->displaySeekForm($result);

        return $html;

    }

    // Displays the form to get a seek user's information
    private function displaySeekForm($result)
    {

        if (is_array($result)) {
            extract($result);
        }

        $select = new Select;
        $areaOpts = $select->fillAreaOptions();

        $html .= '<form id="register" method="post" action="' . $_SERVER['REQUEST_URI'] . '">' . "\n";

        $html .= '<label for="userName">Username</label>' . "\n";
        $html .= '<input type="text" name="userName" id="userName" value="' . $_POST['userName'] . '"/>' . "\n";
        $html .= '<div class="error">' . $userNameMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="userEmail">Email</label>' . "\n";
        $html .= '<input type="text" name="userEmail" id="userEmail" value="' . $_POST['userEmail'] . '"/>' . "\n";
        $html .= '<div class="error">' . $userEmailMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="userPassword">Password</label>' . "\n";
        $html .= '<input type="password" name="userPassword" id="userPassword" />' . "\n";
        $html .= '<div class="error">' . $userPasswordMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="userConfPassword">Confirm Password</label>' . "\n";
        $html .= '<input type="password" name="userConfPassword" id="userConfPassword" />' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<input type="hidden" name="userAccess" id="userAccess" value="seek"/>' . "\n";

        $html .= '<label for="seekTitle">Display Name</label>' . "\n";
        $html .= '<input type="text" name="seekTitle" id="seekTitle" value="' . $_POST['seekTitle'] . '"/>' . "\n";
        $html .= '<p class="form-description">Only if different from username</p>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="seekCompany">Company Name</label>' . "\n";
        $html .= '<input type="text" name="seekCompany" id="seekCompany" value="' . $_POST['seekCompany'] . '"/>' . "\n";
        $html .= '<div class="error">' . $seekCompanyMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="seekPhNo">Phone Number</label>' . "\n";
        $html .= '<input type="text" name="seekPhNo" id="seekPhNo" value="' . $_POST['seekPhNo'] . '"/>' . "\n";
        $html .= '<div class="error">' . $seekPhNoMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<label for="seekAddress">Mailing Address</label>' . "\n";
        $html .= '<textarea name="seekAddress" id="seekAddress" rows="7" cols="25" >' . $_POST['seekAddress'] . '</textarea>' . "\n";
        $html .= '<br />' . "\n";

        if ($_POST['userArea']) {
            $postArea = $_POST['userArea'];
        } else {
            $postArea = 'Please Select...';
        }

        $html .= '<label for="userArea">Area</label>' . "\n";
        $area = ($area == "") ? $postArea : $area;
        $html .= $select->createSelect("userArea", $areaOpts, $area);
        $html .= '<br />' . "\n";

        $html .= '<div class="error">' . $userAreaMsg . '</div>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<input type="submit" name="seekRegisterSubmit" id="seekRegisterSubmit" value="Create User" />' . "\n";

        $html .= '</form>' . "\n";

        return $html;
    }

}
