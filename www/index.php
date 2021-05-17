<?php

session_start();

//Include the View Class
include 'views/viewClass.php';
//Include the Model Class
include 'classes/modelClass.php';

class PageSelector
{

    public function run()
    {

        if (!$_GET['page']) {
            $_GET['page'] = 'home';
        }

        //Instantiate the modelClass
        $model = new Model;

        $pageInfo = $model->getPageInfo($_GET['page']);

        switch ($_GET['page']) {

            case 'home':
                include 'views/homeView.php';
                $view = new HomeView($pageInfo, $model);
                break;
            case 'login':
            case 'logout':
                include 'views/loginoutView.php';
                $view = new LoginoutView($pageInfo, $model);
                break;
            case 'register':
                include 'views/registerView.php';
                $view = new RegisterView($pageInfo, $model);
                break;
            case 'registerSeek':
                include 'views/registerSeekView.php';
                $view = new RegisterSeekView($pageInfo, $model);
                break;
            case 'registerLance':
                include 'views/registerLanceView.php';
                $view = new RegisterLanceView($pageInfo, $model);
                break;
            case 'editInfo':
                include 'views/editInfoView.php';
                $view = new EditInfoView($pageInfo, $model);
                break;
            case 'changePassword':
                include 'views/changePasswordView.php';
                $view = new ChangePasswordView($pageInfo, $model);
                break;
            case 'deleteAcc':
                include 'views/deleteAccView.php';
                $view = new DeleteAccountView($pageInfo, $model);
                break;
            case 'addImage':
                include 'views/addImageView.php';
                $view = new AddImageView($pageInfo, $model);
                break;
            case 'browse':
                include 'views/browseView.php';
                $view = new BrowseView($pageInfo, $model);
                break;
            case 'deletePortfolio':
                include 'views/deletePortfolioView.php';
                $view = new DeletePortfolioView($pageInfo, $model);
                break;
            case 'editPortfolio':
                include 'views/editPortfolioView.php';
                $view = new EditPortfolioView($pageInfo, $model);
                break;
            case 'profile':
                include 'views/profileView.php';
                $view = new ProfileView($pageInfo, $model);
                break;
            case 'about':
                include 'views/aboutView.php';
                $view = new AboutView($pageInfo, $model);
                break;
            case 'contact':
                include 'views/contactView.php';
                $view = new ContactView($pageInfo, $model);
                break;
            case 'search':
                include 'views/searchView.php';
                $view = new SearchView($pageInfo, $model);
                break;
            default:
                include 'views/404.php';
                $view = new ErrorView($pageInfo, $model);
                break;
        }

        echo $view->displayPage();

    }

}

$pageSelect = new PageSelector();
$pageSelect->run();
