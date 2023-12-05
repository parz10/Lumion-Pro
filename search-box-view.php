<?php

namespace Helpie\Features\Components\Search\Views;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Helpie\Features\Components\Search\Views\Search_Box_View')) {
    class Search_Box_View
    {
        public function __construct()
        {
            $this->settings = new \Helpie\Includes\Settings\Getters\Settings();
        }

        public function get()
        {
            $search_page_id = get_option('helpdesk_search_page_id');
            $action = get_permalink($search_page_id);
            $label = $this->settings->styles->get_search_placeholder_text();

            $html = $this->get_search_abstract($action, $label, 'autocomplete');
            return $html;
        }

        public function get_search_abstract($action, $label, $id)
        {
            $html = "<form role='custom_search' id='helpie-search-form' name='helpie-search-form' method='get' class='search-form' action='" . $action . "'>";
            $html .= "<span class='main-search pauple-helpie-search-box '>";
            $html .= '<input id="' . $id . '"  type="text" name="custom_search" placeholder="' . $label . '">';

            $html .= "<button class='input-group-addon' type='submit' form='helpie-search-form' value='Submit'>";
            $html .= "<span class=''><i class='fa fa-search fa-fw'></i></span>";
            $html .= "</button>";

            $html .= "<div class='helpie-autocomplete-suggestions-container'></div>";
            $html .= "</span>";
            $html .= "</form>";

            return $html;
        }
    } // Class Ends
}
