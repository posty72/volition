<?php

include 'classes/dbClass.php';
include 'classes/uploadClass.php';
include 'classes/resizeImageClass.php';

class Model extends Dbase
{

    public $adminLoggedIn;
    public $seekLoggedIn;
    public $lanceLoggedIn;
    public $userLoggedIn;
    public $loginMsg;

    public function __construct()
    {
        parent::__construct();

        $validationPages = array('registerSeek', 'registerLance', 'editInfo', 'addImage', 'changePassword', 'contact');

        if (in_array($_GET['page'], $validationPages)) {
            include 'classes/validateClass.php';
            $this->validate = new Validate;
        }

    }

    //-----------All Users

    //Check if the user is logged in
    public function checkUserSession()
    {

        if ($_GET['page'] == 'logout') {
            unset($_SESSION['userName']);
            unset($_SESSION['accessID']);
            $this->userLoggedIn = false;
            $this->adminLoggedIn = false;
            $this->seekLoggedIn = false;
            $this->lanceLoggedIn = false;
            $this->loginMsg = 'You have successfully logged out!';
        }

        if ($_POST['loginSubmit']) {
            if ($_POST['userName'] && $_POST['userPassword']) {
                $this->userLoggedIn = $this->validateUser();

                if ($this->userLoggedIn == false) {
                    $this->loginMsg = 'Login Unsuccessful';
                }
            } else {
                $this->loginMsg = 'Enter username and password';
            }

            //End of login conditional
        } else {

            if ($_SESSION['userName']) {
                $this->userLoggedIn = true;

                if ($_SESSION['userAccess'] == 'admin') {
                    $this->adminLoggedIn = true;
                } elseif ($_SESSION['userAccess'] == 'seek') {
                    $this->seekLoggedIn = true;
                } elseif ($_SESSION['userAccess'] == 'lance') {
                    $this->lanceLoggedIn = true;
                }
            }
        }
    }

    //Get the user from the database and validate it
    public function validateUser()
    {

        //Get the user from the database
        $user = $this->getUser();

        //See if the database returned an array
        if (is_array($user)) {
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['userName'] = $user['userName'];
            $_SESSION['userAccess'] = $user['userAccess'];
            return true;
        } else {
            return false;
        }
    }

    //Deletes a user account and any portfolio images
    //Needs to be passed the user's account type
    public function processDeleteUser($mode)
    {

        $user = $this->deleteUser();

        if ($mode == 'seek') {
            $seek = $this->deleteSeek();
            $int = $this->deleteInterestsByUser($_POST['userID']);
        } elseif ($mode == 'lance') {
            $lanceInfo = $this->getLanceInfo($_POST['userID']);
            $lance = $this->deleteLance();
            $portsInfo = $this->getLancePortfolio($_POST['userID']);
            $portfolios = $this->deletePortfoliosByUser();

            if ($lanceInfo['lanceDisplayImage']) {
                @unlink('images/display/' . $lanceInfo['lanceDisplayImage']);
            }

            foreach ($portsInfo as $pi) {
                @unlink('images/uploads/thumbnails/' . $pi['portImage']);
                @unlink('images/uploads/large-thumbs/' . $pi['portImage']);
                @unlink('images/uploads/fulls/' . $pi['portImage']);
            }
        }

        if ($user['ok'] && $seek['ok'] || $user['ok'] && $lance['ok']) {
            $result['ok'] = true;
            return $result;
        }

    }

    //Deletes a user from the admin control panel
    public function processDeleteUserAsAdmin()
    {

        if ($_POST['userAccess'] == 'lance') {
            $lanceInfo = $this->getLanceInfo($_POST['userID']);
            $portsInfo = $this->getLancePortfolio($_POST['userID']);
            $result = $this->deleteLanceAsAdmin($_POST['userID']);
            $result .= $this->deletePortfolioAsAdmin($_POST['userID']);

            if ($lanceInfo['lanceDisplayImage']) {
                @unlink('images/display/' . $lanceInfo['lanceDisplayImage']);
            }

            foreach ($portsInfo as $pi) {
                @unlink('images/uploads/thumbnails/' . $pi['portImage']);
                @unlink('images/uploads/large-thumbs/' . $pi['portImage']);
                @unlink('images/uploads/fulls/' . $pi['portImage']);
            }

        } elseif ($_POST['userAccess'] == 'seek') {
            $result = $this->deleteSeekAsAdmin($_POST['userID']);
        } else {
            echo 'No user access';
        }
        if ($result['ok']) {
            $result = $this->deleteUserAsAdmin($_POST['userID']);
        } else {
            $result['ok'] = false;
        }
        return $result;
    }

    //Updates and validates the user's information
    public function processEditUserInfo()
    {

        $result = $this->validateUserForm();

        if ($result['ok']) {
            $result['msg'] = $this->updateUser($_POST['userID']);
        }

        return $result;
    }

    //Validates the user's form
    private function validateUserForm()
    {

        //Get info from the form
        extract($_POST);

        //Make the result or errors array
        $result = array();

        //Run every input that needs validation
        $result['userNameMsg'] = $this->validate->checkRequired($userName);
        $result['userEmailMsg'] = $this->validate->checkEmail($userEmail);
        $result['userAreaMsg'] = $this->validate->checkSelectField($userArea);

        //Check to make sure there are no errors
        $result['ok'] = $this->validate->checkErrorMessages($result);

        //Return the result
        return $result;
    }

    //Used by all user's to change their password
    public function processChangePassword()
    {

        extract($_POST);

        //See if new password is valid
        $result = $this->validate->checkPassword($userNewPassword, $userConfNewPassword);

        if ($result['msg']) {
            return $result;
        } else {
            $update = $this->changePassword($userPassword);
        }

        return $update;
    }

    //Sends an email to a specified address using a form
    public function processSendMessage()
    {

        $validate = $this->validateContactForm();

        if ($validate['ok'] == false) {
            return $validate;
        }

        $email = 'Josh Post <posty72@gmail.com>';

        $subject = 'Sent from Volition.net.nz via contact form';

        $message = 'Someone has sent an enquiry through the contact form.' . "\n";
        $message .= '<strong>Name:</strong>' . $_POST['name'] . "\n";
        $message .= '<br />' . "\n";
        $message .= '<strong>Email:</strong>' . $_POST['email'] . "\n";
        $message .= '<br />' . "\n";
        $message .= '<strong>Message:</strong>' . "\n";
        $message .= stripslashes(strip_tags($_POST['message']));

        $headers = 'From: ' . $_POST['name'] . ' <' . $_POST['email'] . '> ' . "\r\n" .
        'Reply-To: ' . $_POST['email'] . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=UTF-8' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        $sent = mail($email, $subject, $message, $headers);

        if ($sent) {
            $msg = 'Your message was successfully sent.';
        } else {
            $msg = 'Sorry we couldn\'t send your message. Please try again.';
        }

        return $msg;
    }

    //Validates the contact form
    private function validateContactForm()
    {

        extract($_POST);

        $result = array();

        $result['nameMsg'] = $this->validate->checkRequired($name);
        $result['emailMsg'] = $this->validate->checkEmail($email);
        $result['messageMsg'] = $this->validate->checkRequired($message);

        $result['ok'] = $this->validate->checkErrorMessages($result);

        return $result;
    }

    //-------------Seek Users

    //Validates and adds a Seek user to the database
    public function processAddSeek()
    {

        //Validate the form
        $vresult = $this->validateSeekForm();

        if ($vresult['ok'] == false) {

            return $vresult;

        }

        $aresult = $this->addUser();

        if ($aresult['id']) {

            $sresult = $this->addSeek($aresult['id']);

            return $aresult;
        } else {
            return false;
        }

    }

    //Validates and edits a Seek user's infromation within the database
    public function processEditSeekInfo()
    {

        $result = $this->validateSeekForm($_GET['page']);

        if ($result['ok']) {
            $result['msg'] = $this->updateSeek($_POST['userID']);
        }

        return $result;

    }

    //Validates a Seek user's form
    public function validateSeekForm()
    {

        //Get info from the form
        extract($_POST);

        //Make the result or errors array
        $result = array();

        //Run every input that needs validation
        if ($_GET['page'] != 'editInfo') {
            $result['userNameMsg'] = $this->validate->checkRequired($userName);
            $result['userEmailMsg'] = $this->validate->checkEmail($userEmail);
            $result['userPasswordMsg'] = $this->validate->checkPassword($userPassword, $userConfPassword);
        }

        $result['seekCompanyMsg'] = $this->validate->checkRequired($seekCompany);
        $result['seekPhNoMsg'] = $this->validate->checkNumeric($seekPhNo);
        $result['userAreaMsg'] = $this->validate->checkSelectField($userArea);

        //Check to make sure there are no errors
        $result['ok'] = $this->validate->checkErrorMessages($result);

        //Return the result
        return $result;

    }

    //Updates both the Interests of a Seek and the interest in a Lance
    public function processAddInterest()
    {

        $i = $this->addInterest();
        $j = $this->addInterestToLance();
        if ($i['ok'] == true && $j == true) {
            $i['msg'] = 'Successfully added to your interests';
        } else {
            $i['msg'] = 'Error adding to your interests';
        }
        return $i;

    }

    //Gets a Seek's interests and returns it as an array
    //Needs a seek's userID
    public function processGetInterests($userID)
    {

        $ids = $this->getInterestsIds($userID);

        if (!is_array($ids)) {
            return false;
        }

        $intsPorts = array();

        foreach ($ids as $id) {
            $intsPorts[] = $this->getInterestsPortfolios($id['portID']);
        }

        return $intsPorts;

    }

    //---------------Lance Users

    //Adds and validates a Lance user
    public function processAddLance()
    {

        //Validate the form
        $vresult = $this->validateLanceForm();

        if ($vresult['ok'] == false) {
            return $vresult;

        }
        $iresult = $this->uploadAndResizeDisplayImage();

        if ($iresult['ok'] == false) {
            $iresult['msg'] .= 'Unable to upload/resize image';
            return $iresult;
        } else {
            $result['msg'] = 'Image uploaded/resized successfully<br />';
            $aresult = $this->addUser();
            $result .= $this->addLance($aresult['id'], $iresult['lanceDisplayImg']);
        }

        if ($result['ok'] && $aresult['id']) {

            return $result;

        } else {
            return false;
        }

    }

    //Edits and validates a Lance user's information
    public function processEditLanceInfo()
    {

        $vresult = $this->validateLanceForm();

        if ($vresult['ok'] == false) {
            return $vresult;
        }

        if ($_POST['lanceDisplayImg']) {
            $iresult = $this->uploadAndResizeDisplayImage();
        }

        if ($_POST['lanceDisplayImg']) {
            if ($iresult['ok'] == false) {
                $iresult['msg'] .= 'Unable to upload/resize your new image';
                return $iresult;
            }
        }

        if ($vresult['ok']) {
            $result = $this->updateLance($_POST['userID']);
        }

        return $result;
    }

    //Validates a Lance's register form
    public function validateLanceForm()
    {

        extract($_POST);

        $result = array();

        if ($_GET['page'] != 'editInfo') {
            $result['userNameMsg'] = $this->validate->checkRequired($userName);
            $result['userPasswordMsg'] = $this->validate->checkPassword($userPassword, $userConfPassword);
            $result['userEmailMsg'] = $this->validate->checkEmail($userEmail);
            $result['lanceDisplayImgMsg'] = $this->validate->checkRequired($_FILES['lanceDisplayImg']['name']);
        }

        $result['profTitleMsg'] = $this->validate->checkSelectField($profTitle);
        $result['userAreaMsg'] = $this->validate->checkSelectField($userArea);
        $result['ok'] = $this->validate->checkErrorMessages($result);

        return $result;

    }

    //Uploads a display image to the server and resizes it
    private function uploadAndResizeDisplayImage()
    {

        $imgPath = 'images/display';

        if (!$_FILES['lanceDisplayImg']) {
            return false;
        }

        $fileTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/pjpeg');
        $upload = new Upload('lanceDisplayImg', $fileTypes, $imgPath);

        $returnFile = $upload->isUploaded();

        if (!$returnFile) {
            $result['uploadMsg'] = $upload->msg;
            $result['ok'] = false;
            return $result;
        }

        $fileName = basename($returnFile);
        $filePath = $imgPath . '/' . $fileName;

        $imgInfo = getimagesize($returnFile);

        if ($imgInfo[0] > 300 || $imgInfo[1] > 300) {
            $resizeObj = new ResizeImage($filePath, 300, $imgPath, '');
            if (!$resizeObj->resize()) {
                echo 'Unable to resize image to 300 pixels';
            }
        }

        if (file_exists($filePath)) {
            $result['lanceDisplayImg'] = basename($filePath);
            $result['ok'] = true;
            return $result;
        } else {
            return false;
        }
    }

    //Adds and validates an image to the database
    public function processAddImage()
    {

        $vresult = $this->validateAddImage();

        if ($vresult['ok'] == false) {
            return $vresult;
        }

        $iresult = $this->uploadAndResizeImage();

        if ($iresult['ok'] == false) {
            $iresult['msg'] = $iresult['uploadMsg'];
        } else {
            $iresult['msg'] .= $this->addImage($iresult['portImg'], $_SESSION['userID']);
        }

        return $iresult;

    }

    //Validates an image form
    public function validateAddImage()
    {

        $result['imageNameMsg'] = $this->validate->checkRequired($_POST['portName']);
        $result['imageImageMsg'] = $this->validate->checkRequired($_FILES['portImg']['name']);

        $result['ok'] = $this->validate->checkErrorMessages($result);

        return $result;

    }

    //Uploads a portfolio image to the server and resizes it to three configurations
    public function uploadAndResizeImage()
    {

        $imgPath = 'images/uploads/fulls';
        $thumbImgPath = 'images/uploads/thumbnails';
        $largeThumbImgPath = 'images/uploads/large-thumbs';

        if (!$_FILES['portImg']['name']) {
            return false;
        }

        $fileTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/pjpeg');
        $upload = new Upload('portImg', $fileTypes, $thumbImgPath);

        $returnFile = $upload->isUploaded();

        if (!$returnFile) {
            $result['uploadMsg'] = $upload->msg;
            $result['ok'] = false;
            return $result;
        }

        $fileName = basename($returnFile);
        $fullPath = $imgPath . '/' . $fileName;
        $thumbPath = $thumbImgPath . '/' . $fileName;
        $largeThumbPath = $largeThumbImgPath . '/' . $fileName;

        copy($returnFile, $fullPath);
        copy($returnFile, $largeThumbPath);

        if (!file_exists($thumbPath) && !file_exists($largeThumbPath)) {
            return false;
        }

        $imgInfo = getimagesize($returnFile);

        if ($imgInfo[0] > 300 || $imgInfo[1] > 300) {
            $resizeObj = new ResizeImage($thumbPath, 300, $thumbImgPath, '');
            if (!$resizeObj->resize()) {
                echo 'Unable to resize image to 300 pixels';
            }
        }

        if ($imgInfo[0] > 500 || $imgInfo[1] > 500) {
            $resizeObj1 = new ResizeImage($largeThumbPath, 500, $largeThumbImgPath, '');
            if (!$resizeObj1->resize()) {
                echo 'Unable to resize image to 500 pixels';
            }
        }

        if ($imgInfo[0] > 1000 || $imgInfo[1] > 1000) {
            $resizeObj2 = new ResizeImage($fullPath, 1000, $imgPath, '');
            if (!$resizeObj2->resize()) {
                echo 'Unable to resize image to 1000 pixels';
            }
        }

        if (file_exists($largeThumbPath) && file_exists($thumbPath) && file_exists($fullPath)) {
            $result['portImg'] = basename($thumbPath);
            $result['ok'] = true;
            return $result;
        } else {
            return false;
        }

    }

    //Deletes a Lance's portfolio as well as removing their images from the server
    public function processDeletePortfolio()
    {

        $result = $this->deletePortfolio();

        if ($result['ok']) {
            @unlink('images/uploads/thumbnails/' . $_POST['portImage']);
            @unlink('images/uploads/large-thumbs/' . $_POST['portImage']);
            @unlink('images/uploads/fulls/' . $_POST['portImage']);
        }

        return $result;

    }

}
