<?php

// View class which shows results from a search
class SearchView extends View
{

    // Returns the HTML to be displayed by ViewClass.php
    protected function displayContent()
    {

        $html .= '<h1>' . $this->pageInfo['pageTitle'] . '</h1>';

        if (!$_POST) {
            $html .= '<p>Please try your search again</p>';
            return $html;
        }

        $searchResults = $this->displaySearchResults();

        $html .= $searchResults;

        return $html;
    }

    // Displays the results from the database
    protected function displaySearchResults()
    {

        $rs = $this->model->search($_POST['searchInput']);

        $html .= '<div id="columns">';

        if (!$rs) {
            $html .= '<p>No results found</p>';
            return $html;
        }

        foreach ($rs as $r) {
            $html .= '<div class="item">';
            if (!$r['lanceDisplayName']) {
                $html .= '<h3>' . $r['userName'] . '</h3>';
            } else {
                $html .= '<h3>' . $r['lanceDisplayName'] . '</h3>';
            }
            $html .= '<img src="images/uploads/thumbnails/' . $r['portImage'] . '" alt="' . $r['portName'] . ' image"/>' . "\n";
            $html .= '<h4>' . $r['portName'] . '</h4>' . "\n";
            $html .= '<p>' . stripslashes($r['portDescription']) . '</p>' . "\n";
            if ($this->model->userLoggedIn) {
                $html .= '<p><a href="index.php?page=profile&amp;portfolio=' . $r['portID'] . '">View Profile</a></p>' . "\n";
            }

            if ($portfolio['userID'] == $_SESSION['userID']) {
                $html .= '<p><a class="deletelinks" href="index.php?page=deletePortfolio&amp;id=' . $r['portID'] . '">Delete</a></p>' . "\n";
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;

    }

}
