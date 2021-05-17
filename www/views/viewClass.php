<?php

// Collects information from the rigth pages and displays the information
abstract class View
{

    // Set global variables for use in index.php
    protected $pageInfo;
    protected $model;

    public function __construct($info, $model)
    {
        $this->pageInfo = $info;
        $this->model = $model;

    }

    // Set the page order and display it
    public function displayPage()
    {

        $this->model->checkUserSession();
        $html = $this->displayHeader();
        $html .= $this->displayContent();
        $html .= $this->displayFooter();

        return $html;
    }

    // Display the content returned from the other views
    abstract protected function displayContent();

    // Display information at the top of the document across all pages
    private function displayHeader()
    {

        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
        $html .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $html .= '<head>' . "\n";

        //Meta tags
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"  />' . "\n";
        $html .= '<meta name="description" content="' . $this->pageInfo['pageDescription'] . '" />' . "\n";
        $html .= '<meta name="keywords" content="jobs, freelance, work, design, freedom, choice, new zealand, quality" />' . "\n";
        $html .= '<link rel="icon" type="image/png" href="images/volition-icon.png" />' . "\n";

        //Stylesheets
        $html .= '<!-- Stylesheets -->' . "\n";
        $html .= '<link rel="stylesheet" type="text/css" href="css/styles.css" />' . "\n";
        $html .= '<!--[if lt IE 9]>' . "\n";
        $html .= '<link rel="stylesheet" type="text/css" href="css/ie.css" />' . "\n";
        $html .= '<![endif]-->' . "\n";

        //Scripts
        //$html .= '<script type="text/javascript" src="js/window.js"></script>'."\n";

        //Page title
        $html .= '<title>Volition | ' . $this->pageInfo['pageTitle'] . '</title>' . "\n";
        $html .= '</head>' . "\n";
        $html .= '<body>' . "\n";

        //Header
        $html .= '<div id="header">' . "\n";

        //Control buttons

        $html .= '<div id="headerImg"><a href="index.php?page=home"></a></div>' . "\n";

        if ($this->model->userLoggedIn) {
            $html .= '<p class="controlButton"><a href="index.php?page=logout">Logout</a></p>' . "\n";
            $html .= '<p class="userControl">You are logged in as ' . $_SESSION['userName'] . '</p>' . "\n";
        } else {
            $html .= '<p class="controlButton"><a href="index.php?page=login">Login</a></p>' . "\n";
            $html .= '<p class="controlButton"><a href="index.php?page=register">Register</a></p>' . "\n";
        }

        $html .= '<form id="searchForm" method="post" action="index.php?page=search">';
        if ($_POST['searchInput']) {
            $html .= '<input type="text" name="searchInput" id="searchInput" value="' . $_POST['searchInput'] . '" />';
        } else {
            $html .= '<input type="text" name="searchInput" id="searchInput" value="Search..." />';
        }
        $html .= '<input type="submit" class="submit" name="searchSubmit" id="searchSubmit" value="Go" />';
        $html .= '</form>';

        //Navbar
        $html .= $this->displayNav();

        $html .= '</div>' . "\n";
        //Content tags
        $html .= '<div id="container">' . "\n";
        $html .= '<div id="content" >' . "\n";

        return $html;

    }

    // Display footer at the bottom of every page
    private function displayFooter()
    {

        $html = '</div>' . "\n";
        $html .= '</div>' . "\n";
        $html .= '<div id="footer">' . "\n";
        $html .= '<ul>' . "\n";
        $html .= '<li>&copy; Volition 2012</li>' . "\n";
        $html .= '<li><a href="index.php?page=home">Home</a></li>' . "\n";
        $html .= '<li><a href="index.php?page=home">My Profile</a></li>' . "\n";
        if (!$this->model->userLoggedIn) {
            $html .= '<li><a href="index.php?page=register">Register</a></li>' . "\n";
            $html .= '<li><a href="index.php?page=login">Login</a></li>' . "\n";
        }
        $html .= '<li><a href="index.php?page=browse">Browse</a></li>' . "\n";
        $html .= '<li><a href="index.php?page=about">About</a></li>' . "\n";
        $html .= '<li><a href="index.php?page=contact">Contact</a></li>' . "\n";
        $html .= '</ul>' . "\n";
        $html .= '</div>' . "\n";
        $html .= '<script type="text/javascript" src="js/search.js"></script>';
        $html .= '</body>' . "\n";
        $html .= '</html>' . "\n";

        return $html;
    }

    // Display the navbar on every page
    private function displayNav()
    {

        $links = array(
            'home',
            'browse',
            'about',
            'contact',
        );

        //Navbar
        $html = '<div id="navbar">' . "\n";
        $html .= '<ul>' . "\n";

        // Loop through the links
        foreach ($links as $link) {

            $html .= '<li><a href="index.php?page=' . $link . '"';

            if ($link == $_GET['page']) {
                $html .= ' class="active"';
            }

            $html .= '>';

            if ($this->model->userLoggedIn && $link == 'home') {
                $html .= 'My Profile';
            } else {
                $html .= ucfirst($link);
            }

            $html .= '</a></li>' . "\n";

        }

        $html .= '</ul>' . "\n";
        $html .= '</div>' . "\n";

        return $html;
    }

}
