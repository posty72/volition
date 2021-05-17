<?php

/* All functions within the database class (except the constructor) follow this formula

//Set a variable containing the query
$qry = "SELECT userName FROM users";

// Run the query through the database
$rs = $this -> db -> query($qry);

// Check the result and return it
if($rs)....

 */

// include '../config.php';

class Dbase
{

    private $db;

    //Instantiate the database connection
    public function __construct()
    {

        try {

            $this->db = new mysqli(
                getenv('DBHOST'),
                getenv('DBUSER'),
                getenv('DBPASS'),
                getenv('DBNAME')
            );

            if (mysqli_connect_errno()) {
                throw new Exception('Unable to establish database connection');
            }

        } catch (Exception $e) {
            die($e->getMessage());
        }

    }

    //Retrieves the current page's information from the database
    public function getPageInfo($page)
    {

        //Set up the query
        $qry = "SELECT pageName, pageTitle, pageHeading, pageDescription FROM pages WHERE pageName = '$page'";

        //Run the query
        $rs = $this->db->query($qry);

        if ($rs) {

            if ($rs->num_rows > 0) {
                $pageInfo = $rs->fetch_assoc();
                return $pageInfo;
            }

        } else {
            return false;
        }

    }

    //-----------------All Users

    //Retrieves a user from the database to intiniate a login
    public function getUser()
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        //Get info from the form
        extract($_POST);
        $userPassword = sha1($userPassword);

        //Set up the Query
        $qry = "SELECT userID, userName, userPassword, userAccess FROM users WHERE userName = '$userName' AND userPassword = '$userPassword'";

        //Run the query
        $rs = $this->db->query($qry);

        //Check the result
        if ($rs) {

            if ($rs->num_rows > 0) {
                $user = $rs->fetch_assoc();
                return $user;
            }

        } else {
            return false;
        }

        return false;

    }

    //Inserts a user to the database
    public function addUser()
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);

        $qry = "INSERT INTO users VALUES (NULL, '$userName', SHA1('$userPassword'), '$userAccess', '$userEmail', '$userArea')";

        $rs = $this->db->query($qry);

        if ($rs) {

            if ($this->db->affected_rows > 0) {

                $selId = "SELECT userID FROM users ORDER BY userID DESC LIMIT 1";

                $id = $this->db->query($selId);

                $userId = $id->fetch_assoc();

                $aresult['id'] = $userId['userID'];
                return $aresult;
            } else {
                return false;
            }

        } else {
            return false;
        }

        return false;

    }

    //Removes a user from the database
    public function deleteUser()
    {

        $qry = "DELETE FROM users WHERE userID = " . $_POST['userID'];

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows) {
                $result['msg'] = 'User successfully deleted';
                $result['ok'] = true;
            } else {
                $result['msg'] = 'No user deleted';
                $result['ok'] = false;
            }
        } else {
            return false;
        }

        return $result;

    }

    //Updates any user's infromation on the database
    //Needs a userID
    public function updateUser($userID)
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);

        $qry = "UPDATE users SET userName = '$userName', userEmail = '$userEmail', userArea = '$userArea' WHERE userID = '$userID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {

                $i['ok'] = true;
                return $i;

            } else {
                $i['msg'] = 'Nothing was changed.';
                return $i;
            }
        } else {
            return false;
        }

    }

    public function search($term)
    {

        $qry = "SELECT userName, userArea, lanceDisplayName, profTitle, portName, portDescription, portImage
                FROM users, lance, portfolio
                WHERE (userName LIKE '%" . $term . "%' OR userArea LIKE '%" . $term . "%' OR lanceDisplayName LIKE '%" . $term . "%' OR portName LIKE '%" . $term . "%' OR portDescription LIKE '%" . $term . "%' OR profTitle LIKE '%" . $term . "%')
                AND portfolio.userID = users.userID
                AND lance.userID = users.userID";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($rs->num_rows > 0) {

                $results = array();

                while ($row = $rs->fetch_assoc()) {
                    $results[] = $row;
                }

            } else {
                $results = false;
            }

            return $results;
        } else {
            return false;
        }

    }

    //Retrieves a user's infromation from the database
    //Needs a userID
    public function getUserInfo($userID)
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }
        $qry = "SELECT userID, userName, userAccess, userEmail, userArea FROM users WHERE '$userID' = users.userID";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($rs->num_rows > 0) {
                $userInfo = $rs->fetch_assoc();
                return $userInfo;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Updates a user's password
    //Needs a userID
    public function changePassword($userID)
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);
        $userPassword = sha1($userPassword);
        $userNewPassword = sha1($userNewPassword);

        $qry = "SELECT userPassword FROM users WHERE userID = '$userID'";
        $rs = $this->db->query($qry);
        $curPass = $rs->fetch_assoc();

        $result = array();

        if ($curPass['userPassword'] == $userPassword) {

            $query = "UPDATE users SET userPassword = '$userNewPassword' WHERE userID = '$userID'";
            $result = $this->db->query($query);

            if ($this->db->affected_rows > 0) {

                $update['msg'] = 'Password successfully changed';

            } else {
                $update['msg'] = 'Error updating password';
            }

        } else {
            $update['msg'] = 'Your password is incorrect';
        }
        return $update;
    }

    //Retrieves all portfolio's from the database
    public function getPortfolios()
    {

        $qry = "SELECT portID, portName, portDescription, portImage, userID FROM portfolio ORDER BY portName DESC";

        $rs = $this->db->query($qry);

        if ($rs) {

            if ($rs->num_rows > 0) {

                $portfolio = array();

                while ($row = $rs->fetch_assoc()) {
                    $portfolio[] = $row;
                }
                return $portfolio;
            } else {
                $portfolio['msg'] = 'Error. Query did not return any results.';
                return $portfolio;
            }

        } else {
            return false;
        }

    }

    //-----------------Admin

    //Retrieves all users information from the database
    public function getAllUsers($sortBy)
    {

        $qry = "SELECT userID, userName, userPassword, userAccess, userEmail, userArea, portID, portName FROM users LEFT JOIN portfolio USING(userID) GROUP BY users.userID ORDER BY userID $sortBy";
        $rs = $this->db->query($qry);

        if ($rs) {
            if ($rs->num_rows > 0) {

                $users = array();

                while ($row = $rs->fetch_assoc()) {
                    $users[] = $row;
                }

                return $users;
            }
        } else {
            return false;
        }

    }

    //Deletes a user from the database when an admin is logged in
    //Needs a userID
    public function deleteUserAsAdmin($userID)
    {

        $qry = "DELETE FROM users WHERE userID = '$userID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['ok'] = false;
            }
        } else {
            return false;
        }
        return $result;
    }

    //Deletes a Lance from the database as an admin
    //Needs a userID
    public function deleteLanceAsAdmin($userID)
    {

        $qry = "DELETE FROM lance WHERE userID = '$userID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['ok'] = false;
            }
        } else {
            return false;
        }
        return $result;
    }

    //Deletes a Seek from the database as an admin
    //Needs a userID
    public function deleteSeekAsAdmin($userID)
    {

        $qry = "DELETE FROM seek WHERE userID = '$userID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['ok'] = false;
            }
        } else {
            return false;
        }
        return $result;
    }

    //Deletes all Portfolio's from the database as an admin
    //Needs a userID
    public function deletePortfolioAsAdmin($userID)
    {

        $qry = "DELETE FROM portfolio WHERE userID = '$userID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $result['pok'] = true;
            } else {
                $result['pok'] = false;
            }
        } else {
            return false;
        }
        return $result;
    }

    //-----------------Seekers

    //Inserts a Seek user
    //Needs a userID
    public function addSeek($userId)
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);

        //Set up query
        $qry = "INSERT INTO seek VALUES (NULL, '$seekTitle', '$seekCompany', '$seekPhNo', '$seekAddress', NULL, '$userId')";

        //Run the query
        $rs = $this->db->query($qry);

        if ($rs) {

            if ($this->db->affected_rows > 0) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

        return false;

    }

    //Deletes a Seek user
    public function deleteSeek()
    {

        $qry = "DELETE FROM seek WHERE seekID = " . $_POST['seekID'];

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows) {
                $result['ok'] = true;
            } else {
                $result['msg'] = 'No user deleted';
                $result['ok'] = false;
            }
        } else {
            return false;
        }

        return $result;

    }

    //Retrieves a Seek user's info
    //Needs a userID
    public function getSeekInfo($userID)
    {

        $qry = "SELECT seekID, seekTitle, seekCompany, seekPhNo, seekAddress, seekInterest FROM seek WHERE '$userID' = seek.userID";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($rs->num_rows > 0) {
                $seekInfo = $rs->fetch_assoc();
                return $seekInfo;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Updates a Seek user's infromation
    //Needs a userID
    public function updateSeek($userID)
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);
        $userPassword = sha1($userPassword);

        $qry = "UPDATE seek SET seekTitle = '$seekTitle', seekCompany = '$seekCompany', seekPhNo = '$seekPhNo', seekAddress = '$seekAddress' WHERE userID = '$userID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {

                $i = 'User updated on database';
                return $i;

            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //Inserts a seek into the Interests database
    public function addInterest()
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);

        $qry = "INSERT INTO interests VALUES (NULL, '$portID', '$userID')";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $i['ok'] = true;
                return $i;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //Inserts an Interest to the Lance table
    public function addInterestToLance()
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);
        $qry = "SELECT userID FROM portfolio WHERE '$portID' = portfolio.portID";
        $rs = $this->db->query($qry);
        if ($rs) {
            if ($rs->num_rows > 0) {
                $r = $rs->fetch_assoc();
                $qry = "SELECT MAX(lanceInterest) AS maxInt FROM `lance` ";
                $rs = $this->db->query($qry);
                $r = $rs->fetch_assoc();
                extract($r);
                $r = $maxInt + 1;
                $rs = false;
                $qry = "UPDATE lance SET lance.lanceInterest = '$r'";
                $rs = $this->db->query($qry);
                if ($rs) {
                    if ($this->db->affected_rows > 0) {
                        return true;
                    }
                } else {
                    return false;
                }

            } else {
                return false;
            }
        } else {
            return false;
        }
        return false;
    }

    //Retrieves the Interests from the database
    public function getInterestsIds($userID)
    {

        $qry = "SELECT interestID, portID FROM interests WHERE userID = '$userID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($rs->num_rows > 0) {

                $interest = array();
                //$interest = $rs -> fetch_assoc();
                while ($row = $rs->fetch_assoc()) {
                    $interest[] = $row;
                }

                return $interest;

            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //Retrieves a Seek user's Interests
    //Needs a portID
    public function getInterestsPortfolios($portfolio)
    {

        $qry = "SELECT portID, portName, portDescription, portImage, userID FROM portfolio WHERE portID = '$portfolio'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($rs->num_rows > 0) {

                $port = $rs->fetch_assoc();

                return $port;

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Deletes an Interest
    //Needs a portID and userID
    public function deleteInterest($portID, $seekID)
    {

        $qry = "DELETE FROM interests WHERE portID = '$portID' AND userID = '$seekID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $msg = 'Interest was removed';
            } else {
                $msg = 'Interest was not removed';
            }
            return $msg;
        } else {
            return false;
        }

    }

    public function deleteInterestsByUser($userID)
    {

        $qry = "DELETE FROM interests WHERE interests.userID = $userID";

        $rs = $this->db->query($qry);

        if ($rs) {
            return true;
        } else {
            return false;
        }

    }

    //-----------------Lancers

    //Inserts a Lance user
    //Needs a userID and displayImg
    public function addLance($userId, $displayImg)
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);

        //Set up query
        $qry = "INSERT INTO lance VALUES (NULL, '$lanceDisplayName', '$displayImg', '$profTitle', '$lanceExp', '$lanceBio', NULL, '$lanceSite', '$userId')";

        //Run the query
        $rs = $this->db->query($qry);

        if ($rs) {

            if ($this->db->affected_rows > 0) {

                $result['ok'] = true;
                return $result;

            } else {
                return false;
            }

        } else {
            return false;
        }

        return false;

    }

    //Deletes a Lance user
    public function deleteLance()
    {

        $qry = "DELETE FROM lance WHERE lanceID = " . $_POST['lanceID'];

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['msg'] = 'No user deleted';
                $result['ok'] = false;
            }
        } else {
            return false;
        }

        return $result;

    }

    //Updates a Lance user
    public function updateLance($userID)
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);
        $userPassword = sha1($userPassword);

        $qry = "UPDATE lance SET lanceDisplayName = '$lanceDisplayName', profTitle = '$profTitle', lanceExp = '$lanceExp', lanceBio = '$lanceBio', lanceSite = '$lanceSite' WHERE userID = '$userID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {

                $i['ok'] = true;
                return $i;

            } else {
                $i['msg'] = 'Nothing was changed.';
                return $i;
            }
        } else {
            return false;
        }

    }

    //Retrieves the Lance's information
    public function getLanceInfo($userID)
    {

        $qry = "SELECT lanceID, lanceDisplayName, lanceDisplayImage, profTitle, lanceExp, lanceBio, lanceInterest, lanceSite FROM lance WHERE '$userID' = userID";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($rs->num_rows > 0) {
                $lanceInfo = $rs->fetch_assoc();
                return $lanceInfo;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //Inserts a Portfolio image
    public function addImage($portImg, $userID)
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }
        extract($_POST);

        $qry = "INSERT INTO portfolio VALUES (NULL, '$portName', '$portDescription', '$portImg', '$userID')";

        $rs = $this->db->query($qry);

        if ($rs) {

            if ($this->db->affected_rows > 0) {
                $i = 'Portfolio updated';
                return $i;
            } else {
                return false;
            }

        } else {
            return false;
        }

        return false;

    }

    //Retrieves a Lance user's portfolio's
    //Needs a userID
    public function getLancePortfolio($userID)
    {

        $qry = "SELECT portID, portName, portDescription, portImage FROM portfolio WHERE '$userID' = portfolio.userID";

        $rs = $this->db->query($qry);

        if ($rs) {

            if ($rs->num_rows > 0) {

                $portfolio = array();
                while ($row = $rs->fetch_assoc()) {

                    $portfolio[] = $row;

                }

                return $portfolio;

            } else {
                $portfolio['msg'] = 'No images found';
                return $portfolio;
            }

        } else {
            return false;
        }

    }

    //Retrieves a Portfolio from the database
    //Needs a portID
    public function getLancePortfolioByID($portID)
    {

        $qry = "SELECT portID, portName, portImage, portDescription, userID FROM portfolio WHERE '$portID' = portID";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($rs->num_rows > 0) {
                $portfolio = $rs->fetch_assoc();
                return $portfolio;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //Deletes a portfolio from the database
    public function deletePortfolio()
    {

        $qry = "DELETE FROM portfolio WHERE portID = " . $_POST['portID'];
        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $result['msg'] = 'Image was successfully deleted';
                $result['ok'] = true;
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //Updates a Portfolio's information
    //Needs all portfolio info stored in an array
    public function editImage()
    {

        if (!get_magic_quotes_gpc()) {
            $this->sanitizeInput();
        }

        extract($_POST);

        $qry = "UPDATE portfolio SET portName = '$portName', portDescription = '$portDescription' WHERE portID = '$portID'";

        $rs = $this->db->query($qry);

        if ($rs) {
            if ($this->db->affected_rows > 0) {
                $result = 'ok';
            } else {
                $result = 'bad';
            }
        } else {
            return false;
        }
        return $result;

    }

    //Deletes a Portfolio
    public function deletePortfoliosByUser()
    {

        $qry = "DELETE FROM portfolio WHERE portfolio.userID = " . $_POST['userID'];
        $rs = $this->db->query($qry);
        if ($rs) {
            if ($this->affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['ok'] = false;
            }
        } else {
            return false;
        }

    }

    //Sanitizes the input of all data going into the database
    private function sanitizeInput()
    {

        foreach ($_POST as &$post) {
            $post = $this->db->real_escape_string($post);
            $post = strip_tags($post);
        }

    }
}
