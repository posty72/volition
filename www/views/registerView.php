<?php

// View class which lets a person choose what kind of user they are
class RegisterView extends View
{

// Returns the HTML to be displayed by ViewClass.php
    protected function displayContent()
    {

        $html = '<div id="register">' . "\n";

        $html .= '<h1>' . $this->pageInfo['pageHeading'] . '</h1>';

        if ($this->model->userLoggedIn) {
            header('Location: index.php?page=home');
        }

        $html .= '<h3 for="user-type">Choose your user type</h3>' . "\n";

        $html .= '<p class="form-description">Don\'t know which one to choose? Please <a href="index.php?page=about">visit our about page</a> to find out more.</p>' . "\n";
        $html .= '<br />' . "\n";

        $html .= '<h2 class="buttons"><a href="index.php?page=registerSeek">Seek</a></h2>';

        $html .= '<h2 class="buttons"><a href="index.php?page=registerLance">Lance</a></h2>';

        $html .= '</div>' . "\n";

        return $html;

    }

}
