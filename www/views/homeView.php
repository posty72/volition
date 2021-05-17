<?php

// View class which shows either the callout, profile page or administrator control panel, dependant on user
class HomeView extends View
{

    // Set global variables
    private $userInfo;
    private $adminUsers;
    private $adminLance;
    private $adminSeek;
    private $lanceInfo;
    private $lancePortfolio;
    private $seekInfo;
    private $interests;

    // Returns the HTML to be used in the ViewClass
    protected function displayContent()
    {

        if ($this->model->userLoggedIn) {

            //Get general user info
            $this->userInfo = $this->model->getUserInfo($_SESSION['userID']);

            if ($this->model->adminLoggedIn) {
                //Display Admin home page

                if (!$_POST['sortBy']) {
                    $sortBy = 'ASC';
                } else {
                    $sortBy = $_POST['sortBy'];
                }

                $this->adminUsers = $this->model->getAllUsers($sortBy);
                $html = $this->displayAdminProfile();

            } elseif ($this->model->seekLoggedIn) {
                //Get seek user info
                $this->seekInfo = $this->model->getSeekInfo($_SESSION['userID']);
                $this->interests = $this->model->processGetInterests($_SESSION['userID']);

                $html = $this->displaySeekProfile();

            } elseif ($this->model->lanceLoggedIn) {
                //Get lance user info
                $this->lancePortfolio = $this->model->getLancePortfolio($_SESSION['userID']);
                $this->lanceInfo = $this->model->getLanceInfo($_SESSION['userID']);
                //Display lance home page
                $html = $this->displayLanceProfile();

            } else {
                //Show an error
                $html = '<h2>There has been an error getting your page. Please logout and try again.</h2>' . "\n";
            }

            return $html;
        } else {
            //Display callout
            $html = $this->displayCallout();
            return $html;
        }

    }

    // Display the administrator control panel
    private function displayAdminProfile()
    {

        $html = '<div id="user-profile">' . "\n";

        $html .= '<h1>Administrator Control Panel</h1>' . "\n";
        $html .= '<h2>' . $_SESSION['userName'] . '</h2>' . "\n";

        $html .= '<div id="adminUsers">' . "\n";

        $html .= '<h3>Your Users</h3>' . "\n";
        //Show all users

        $html .= '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '" >';
        $html .= '<label for="sortBy">Sort By</label>';

        $html .= '<select name="sortBy" id="sortBy">';

        if ($_POST['sortBy'] == 'ASC') {
            $html .= '<option value="ASC">Oldest First</option>';
            $html .= '<option value="DESC">Newest First</option>';
        } elseif ($_POST['sortBy'] == 'DESC') {
            $html .= '<option value="DESC">Newest First</option>';
            $html .= '<option value="ASC">Oldest First</option>';
        } else {
            $html .= '<option value="ASC">Oldest First</option>';
            $html .= '<option value="DESC">Newest First</option>';
        }
        $html .= '</select>';

        $html .= '<input type="submit" class="submit name="sort" value="Go" />';

        $html .= '</form>';
        $html .= '<br />' . "\n";

        if ($_POST['adminDelete']) {

            $html .= '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">' . "\n";
            $html .= '<input type="hidden" name="userID" value="' . $_POST['userID'] . '" />' . "\n";
            $html .= '<input type="hidden" name="userAccess" value="' . $_POST['userAccess'] . '" />' . "\n";
            $html .= '<input type="hidden" name="userName" value="' . $_POST['userName'] . '" />' . "\n";
            $html .= '<label for="confirm">Are you sure you want to delete user ' . htmlentities(stripslashes($_POST['userName'])) . '?</label>' . "\n";
            $html .= '<input type="submit" class="submit" name="deleteYes" value="Yes" />' . "\n";
            $html .= '<input type="submit" class="submit" name="deleteNo" value="No" />' . "\n";
            $html .= '</form>';

        }

        if ($_POST['deleteYes']) {

            $deleteUser = $this->model->processDeleteUserAsAdmin();

            if ($deleteUser['ok']) {
                $html .= '<p>' . htmlentities($_POST['userName']) . ' was deleted.</p>' . "\n";
                if ($deleteUser['pok']) {
                    $html .= '<p>Lance\'s portfolios also deleted.</p>' . "\n";
                }
                $html .= '<p><a href="index.php">Reload page</a></p>' . "\n";
                return $html;
            }
        }

        if ($_POST['no']) {
            $html .= '<p>The user was not deleted</p>';
            $html .= '<p><a href="index.php">Reload page</a></p>';
            return $html;
        }

        $html .= '<p><strong>Seek Users</strong></p>' . "\n";

        //Loop through all users
        $html .= '<table border="1">' . "\n";
        $html .= '<tr>' . "\n";
        $html .= '<td>UserID</td>' . "\n";
        $html .= '<td>userName</td>' . "\n";
        $html .= '<td>userAccess</td>' . "\n";
        $html .= '<td>userEmail</td>' . "\n";
        $html .= '<td>userArea</td>' . "\n";
        $html .= '</tr>' . "\n";

        foreach ($this->adminUsers as $user) {
            if ($user['userAccess'] == 'seek') {
                $html .= '<tr>' . "\n";
                $html .= '<td>' . $user['userID'] . '</td>' . "\n";
                $html .= '<td>' . $user['userName'] . '</td>' . "\n";
                $html .= '<td>' . $user['userAccess'] . '</td>' . "\n";
                $html .= '<td>' . $user['userEmail'] . '</td>' . "\n";
                $html .= '<td>' . $user['userArea'] . '</td>' . "\n";

                $html .= '<td>';
                $html .= '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">';
                $html .= '<input type="hidden" name="userID" value="' . $user['userID'] . '" />';
                $html .= '<input type="hidden" name="userAccess" value="' . $user['userAccess'] . '" />';
                $html .= '<input type="hidden" name="userName" value="' . $user['userName'] . '" />';
                $html .= '<input type="submit" class="submit" name="adminDelete" value="Delete User" />';
                $html .= '</form>';
                $html .= '</td>';
            }

            $html .= '</tr>' . "\n";
        }

        $html .= '</table>' . "\n";

        $html .= '<p><strong>Lance Users</strong></p>' . "\n";
        //Loop through all users
        $html .= '<table border="1">' . "\n";
        $html .= '<tr>' . "\n";
        $html .= '<td>UserID</td>' . "\n";
        $html .= '<td>userName</td>' . "\n";
        $html .= '<td>userAccess</td>' . "\n";
        $html .= '<td>userEmail</td>' . "\n";
        $html .= '<td>userArea</td>' . "\n";
        $html .= '</tr>' . "\n";

        foreach ($this->adminUsers as $user) {
            $html .= '<tr>' . "\n";
            if ($user['userAccess'] == 'lance') {
                $html .= '<td>' . $user['userID'] . '</td>' . "\n";
                $html .= '<td>' . stripslashes($user['userName']) . '</td>' . "\n";
                $html .= '<td>' . stripslashes($user['userAccess']) . '</td>' . "\n";
                $html .= '<td>' . stripslashes($user['userEmail']) . '</td>' . "\n";
                $html .= '<td>' . stripslashes($user['userArea']) . '</td>' . "\n";

                $html .= '<td><a href="index.php?page=profile&amp;id=' . $user['userID'] . '&amp;portfolio=' . $user['portID'] . '">View Users Profile</a></td>' . "\n";

                $html .= '<td>';
                $html .= '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">';
                $html .= '<input type="hidden" name="userID" value="' . $user['userID'] . '" />';
                $html .= '<input type="hidden" name="userAccess" value="' . $user['userAccess'] . '" />';
                $html .= '<input type="hidden" name="userName" value="' . $user['userName'] . '" />';
                $html .= '<input type="submit" class="submit" name="adminDelete" value="Delete User" />';
                $html .= '</form>';
                $html .= '</td>';
            }

            $html .= '</tr>' . "\n";
        }
        $html .= '</table>' . "\n";

        $html .= '<a href="index.php?page=changePassword">Change Password</a>' . "\n";

        $html .= '</div>' . "\n";
        $html .= '</div>' . "\n";

        return $html;

    }

    // Display a profile page specific to seek users
    private function displaySeekProfile()
    {

        //echo '$_SESSION['userID']';

        //$userInfo = $this -> model -> getSeekInfo($_SESSION['userID']);

        $html = '<div id="user-profile">' . "\n";

        $html .= '<h1>My Profile</h1>' . "\n";
        $html .= '<h2>' . $this->userInfo['userName'] . '</h2>' . "\n";

        $html .= '<div id="userInfo" >' . "\n";

        $html .= '<h3>Your Information</h3>' . "\n";

        $html .= '<ul>' . "\n";
        $html .= '<li><strong>Email</strong>' . $this->userInfo['userEmail'] . '</li>' . "\n";
        $html .= '<li><strong>Company Name</strong>' . $this->seekInfo['seekCompany'] . '</li>' . "\n";
        $html .= '<li><strong>Location</strong>' . $this->userInfo['userArea'] . '</li>' . "\n";
        $html .= '<li><strong>Phone Number</strong>' . $this->seekInfo['seekPhNo'] . '</li>' . "\n";
        if ($this->seekInfo['seekAddress']) {
            $html .= '<li><strong>Company Address</strong>' . $this->seekInfo['seekAddress'] . '</li>' . "\n";
        }
        $html .= '</ul>' . "\n";

        $html .= '<a href="index.php?page=editInfo">Edit Info</a>' . "\n";
        $html .= '<a href="index.php?page=changePassword">Change Password</a>' . "\n";

        $html .= '</div>' . "\n";

        if (is_array($this->interests)) {

            $html .= '<div id="interests">';

            $html .= '<h2>Your Interests</h2>';

            if ($_POST['removeInterestSubmit']) {
                $reInt = $this->model->deleteInterest($_POST['portID'], $_POST['seekID']);
                $html .= '<h4>' . $reInt . '</h4>';
                $html .= '<p><a href="index.php?page=home">View Interests</a></p>';
                return $html;
            }

            $html .= '<div id="poi-columns">' . "\n";

            foreach ($this->interests as $interest) {

                $html .= '<div class="large-item">' . "\n";
                $html .= '<img src="images/uploads/large-thumbs/' . $interest['portImage'] . '" alt="Portfolio Name"/>' . "\n";
                $html .= '<h4>' . $interest['portName'] . '</h4>' . "\n";
                $html .= '<p>' . $interest['portDescription'] . '</p>' . "\n";
                $html .= '<p><a href="index.php?page=profile&amp;id=' . $interest['userID'] . '&amp;img=' . $interest['portImage'] . '&amp;name=' . $portfolio['portName'] . '&amp;portfolio=' . $portfolio['portID'] . '">View Profile </a></p><br />';
                $html .= '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">' . "\n";
                $html .= '<input type="hidden" name="portID" value="' . $interest['portID'] . '" />' . "\n";
                $html .= '<input type="hidden" name="seekID" value="' . $_SESSION['userID'] . '" />' . "\n";
                $html .= '<input type="submit" class="incogSubmit" name="removeInterestSubmit" value="Remove from Interests"/>' . "\n";
                $html .= '</form>' . "\n";

                $html .= '</div>' . "\n";
            }
            $html .= '</div>' . "\n";
        }

        $html .= '<p id="deleteAcc"><a href="index.php?page=deleteAcc">Delete your account</a></p>' . "\n";

        $html .= '</div>' . "\n";
        $html .= '</div>' . "\n";

        return $html;

    }

    // Display a profile view specific to lance users
    private function displayLanceProfile()
    {

        $html = '<div id="user-profile">' . "\n";

        $html .= '<h1>My Profile</h1>' . "\n";
        $html .= '<h2>' . $this->userInfo['userName'] . '</h2>' . "\n";

        $html .= '<div id="userInfo" >' . "\n";

        $html .= '<h3>Your Information</h3>' . "\n";

        $html .= '<ul>' . "\n";
        $html .= '<li><strong>Email</strong>' . $this->userInfo['userEmail'] . '</li>' . "\n";

        if ($this->lanceInfo['lanceDisplayName']) {
            $html .= '<li><strong>Display Name</strong>' . $this->lanceInfo['lanceDisplayName'] . '</li>' . "\n";
        }

        $html .= '<li><strong>Profession</strong>' . stripslashes($this->lanceInfo['profTitle']) . '</li>' . "\n";
        $html .= '<li><strong>Location</strong>' . stripslashes($this->userInfo['userArea']) . '</li>' . "\n";

        if ($this->lanceInfo['lanceBio']) {
            $html .= '<li><strong>Bio.</strong>' . stripslashes($this->lanceInfo['lanceBio']) . '</li>' . "\n";
        }

        if ($this->lanceInfo['lanceSite']) {
            $html .= '<li><strong>Site</strong>' . $this->lanceInfo['lanceSite'] . '</li>' . "\n";
        }

        $html .= '</ul>' . "\n";

        $html .= '<a href="index.php?page=editInfo">Edit</a>' . "\n";

        $html .= '</div>' . "\n";

        $html .= '<div id="displayPic">' . "\n";
        $html .= '<h3>Your Profile\'s Picture</h3>' . "\n";
        $html .= '<img src="images/display/' . $this->lanceInfo['lanceDisplayImage'] . '" alt="Display Picture" width="300px" />' . "\n";
        $html .= '</div>' . "\n";

        if ($this->lanceInfo['lanceExp'] || $this->lanceInfo['lanceRating']) {

            $html .= '<div id="userWork">' . "\n";

            $html .= '<h3>Your Work</h3>' . "\n";

            $html .= '<ul>' . "\n";
            if ($this->lanceInfo['lanceExp']) {
                $html .= '<li><strong>User Experience</strong>' . "\n";
                $html .= '<p>' . stripslashes($this->lanceInfo['lanceExp']) . '</p>' . "\n";
                $html .= '</li>' . "\n";

            }
            if ($this->lanceInfo['lanceRating']) {
                $html .= '<li><strong>User Rating</strong>' . $this->lanceInfo['lanceRating'] . '</li>' . "\n";
            }

            $html .= '</ul>' . "\n";

            $html .= '<a href="index.php?page=editInfo">Edit</a>' . "\n";

            $html .= '</div>' . "\n";

        }

        $html .= '<div id="portfolio">' . "\n";

        $html .= '<h3>Portfolio Images</h3>' . "\n";

        if (!$this->lancePortfolio['msg']) {

            $html .= '<div id="columns">' . "\n";

            foreach ($this->lancePortfolio as $portfolio) {

                $html .= '<div class="item">' . "\n";
                $html .= '<img src="images/uploads/thumbnails/' . $portfolio['portImage'] . '" alt="' . $portfolio['portName'] . '"/>' . "\n";
                $html .= '<h4>' . $portfolio['portName'] . '</h4>' . "\n";
                $html .= '<p>' . stripslashes($portfolio['portDescription']) . '</p>' . "\n";
                $html .= '<a class="deletelinks" href="index.php?page=deletePortfolio&amp;id=' . $portfolio['portID'] . '">Delete</a>' . "\n";
                $html .= '<a class="deletelinks" href="index.php?page=editPortfolio&amp;id=' . $portfolio['portID'] . '">Edit</a>' . "\n";
                $html .= '</div>' . "\n";

            }

            $html .= '</div>' . "\n";
        } else {
            $html .= '<p class="error">' . $this->lancePortfolio['msg'] . '</p>' . "\n";
        }

        $html .= '<p><a href="index.php?page=addImage">Add an Image</a></p>' . "\n";

        $html .= '</div>' . "\n";

        $html .= '<p id="deleteAcc"><a href="index.php?page=deleteAcc">Delete your account</a></p>' . "\n";

        $html .= '</div>' . "\n";

        return $html;

    }

    // Display the callout for when user's aren't logged in
    private function displayCallout()
    {

        $html = '<div id="callout">' . "\n";

        $html .= '<img src="images/callout-img.jpg" alt="Join Today!" />' . "\n";

        $html .= '<div class="buttons">' . "\n";
        $html .= '<ul>' . "\n";
        $html .= '<li><a href="index.php?page=login">Login</a></li>' . "\n";
        $html .= '<li><a href="index.php?page=register">Register</a></li>' . "\n";
        $html .= '</ul>' . "\n";
        $html .= '</div>' . "\n";

        $html .= '</div>' . "\n";

        return $html;

    }

}
