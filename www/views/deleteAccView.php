<?php

// View class which lets a user remove their account from the database
class DeleteAccountView extends View
{

// Returns the HTML to be displayed by ViewClass.php
    protected function displayContent()
    {

        $html = '';

        // Test for user logged in or not
        if (!$this->model->userLoggedIn && !$_SESSION) {
            $html .= '<p>Please log in to view this page</p>' . "\n";
            return $html;
        }

        $html .= '<h1>' . $this->pageInfo['pageHeading'] . '</h1>';

        // Test for administrator priviledges
        if ($this->model->adminLoggedIn) {
            $html .= '<p>Administrators accounts cannot be deleted. If an admin account must be removed, it must be done via the database</p>' . "\n";
            return $html;
        }

        // Test whether the form has been submitted
        if ($_POST['confirm']) {

            // Set the user mode used when deleting accounts
            if ($this->model->seekLoggedIn) {
                $mode = 'seek';
            } elseif ($this->model->lanceLoggedIn) {
                $mode = 'lance';
            }

            // Run the delete user process on the model
            $result = $this->model->processDeleteUser($mode);

            if ($result['ok']) {
                header('Location:index.php?page=logout');

                return $html;
            }

        } elseif ($_POST['cancel']) {
            header('Location: index.php?page=home');
        }

        $userInfo = $this->model->getUserInfo($_SESSION['userID']);
        $html .= $this->displayDeleteAccountForm($userInfo);

        return $html;
    }

// Displays the necesary form for users to remove their sccount
    private function displayDeleteAccountForm($userInfo)
    {

        //Get user info
        extract($userInfo);

        // Determine user type
        if ($userAccess == 'seek') {
            $seekInfo = $this->model->getSeekInfo($userID);
            extract($seekInfo);
        } elseif ($userAccess == 'lance') {
            $lanceInfo = $this->model->getLanceInfo($userID);
            extract($lanceInfo);
        } else {
            $html .= '<p>Error. User access was not found</p>';
        }

        // Display HTML for corresponding user
        $html .= '<h3>' . $userInfo['userName'] . '</h3>';
        $html .= '<p>' . $userInfo['userEmail'] . '</p>';
        $html .= '<br />';

        if ($userAccess == 'seek') {
            $html .= '<p>' . $seekInfo['seekTitle'] . '</p>';
            $html .= '<p>' . $seekInfo['seekCompany'] . '</p>';
            $html .= '<br />';
        }

        $html .= '<p>Do you want to delete this account?</p>';

        $html .= '<form method="post" action="' . htmlentities($_SERVER['REQUEST_URI']) . '">';

        $html .= '<input type="hidden" name="userID" value="' . $userInfo['userID'] . '" />';

        if ($this->model->seekLoggedIn) {
            $html .= '<input type="hidden" name="seekID" value="' . $seekInfo['seekID'] . '" />';
        } elseif ($_SESSION['userAccess'] == 'lance') {

            $html .= '<input type="hidden" name="lanceID" value="' . $lanceInfo['lanceID'] . '" />';
        }

        //Submit buttons
        $html .= '<input class="deleteSubmit" type="submit" name="confirm" value="Yes" />';
        $html .= '<input class="deleteSubmit" type="submit" name="cancel" value="No" />';
        $html .= '</form>';

        return $html;
    }

}
