<?php

namespace Helpie\Features\Components\Search\Views;

use \Helpie\Includes\Translations as Translations;

if (!class_exists('\Helpie\Features\Components\Search\Views\Search_Results_View')) {
    class Search_Results_View
    {
        public function __construct($pagination_view)
        {
            $this->pagination_view = $pagination_view;
        }

        public function get($viewProps)
        {
            $html = $this->content_header_html($viewProps['collection']['content_area_class']);
            $html .= $this->content_body_html($viewProps);
            $html .= $this->content_footer_html();

            return $html;
        }

        protected function content_header_html($content_area_class)
        {
            $html = "<div id='primary' class='helpie-primary content-area " . $content_area_class . "'>";
            $html .= "<main id='main' class='site-main' role='main'>";
            $html .= "<div class='wrapper'>";
            $html .= "<div class='helpie-main-section'>";
            $html .= "<div class='wrapper'>";
            $html .= "<div class='helpie-main-content-area'>";
            $html .= "<div class='helpie-search-listing'>";

            return $html;
        }

        protected function content_body_html($viewProps)
        {
            $itemsProps = $viewProps['items'];
            $collectionProps = $viewProps['collection'];

            $html = $this->get_header_html($collectionProps);

            if (isset($itemsProps) && !empty($itemsProps)) {
                $html .= '<div class="ui divided items">';
                foreach ($itemsProps as $item) {
                    $html .= $this->get_single_item($item, $collectionProps);
                }
                $html .= '</div>';

                $html .= $this->pagination_view->get_view($collectionProps);
            }
            wp_reset_query();

            // Appending password protect Modal Html to Search Page html
            $pp_controller = new \Helpie\Features\Services\Password_Protect\Controller();
            $html .= $pp_controller->get_Modal();

            return $html;
        }

        public function get_header_html($collectionProps)
        {
            if ($collectionProps['total_posts'] >= 1) {
                if (empty($collectionProps['search_query'])) {
                    return '<h2>' . $collectionProps['no_query_text'] . '</h2>';
                }

                $html = '<h2>' . $collectionProps['total_posts'] . ' ' . Translations::get('results_found_for') . ' ';
                $html .= '&lsquo;' . $collectionProps['search_query'] . '&rsquo; </h2>';
            } else {
                $html = '<h2> ' . $collectionProps['empty_search_result_label'] . ' </h2>';
            }

            return $html;
        }

        public function get_single_item($itemProps, $collectionProps)
        {
            $attr1 = "data-post-id='" . $itemProps['id'] . "'";
            $attr2 = "data-term-id='" . $itemProps['category_id'] . "'";
            $data_attrs = $attr1 . " " . $attr2;
            $classes = "item helpie-element" . " ";
            $classes .= $itemProps['lock_class'];
            $id = "search-item-post-id-" . $itemProps['id'];

            $html = '<div id="' . $id . '" class="' . $classes . '" ' . $data_attrs . '>';

            if ($collectionProps['featured_image_show']) {
                $html .= $this->get_image_html($itemProps);
            }

            $html .= '<div class="middle aligned item-content">';
            $html .= $this->get_anchor_html($itemProps);
            if ($itemProps['is_password_permitted']) {
                if ($collectionProps['meta_data_show']) {
                    $html .= $this->get_meta_html($itemProps);
                }
                if ($collectionProps['description_show']) {
                    $html .= $this->get_description_html($itemProps, $collectionProps);
                }
                if ($collectionProps['tags_show']) {
                    $html .= $this->get_article_tags($itemProps['tags']);
                }
            }
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }

        protected function get_image_html($itemProps)
        {
            $html = '<div class="ui middle aligned tiny image">';
            $html .= $itemProps['image'];
            $html .= '</div>';

            return $html;
        }

        protected function get_anchor_html($itemProps)
        {
            $href = ($itemProps['link']) ? 'href = "' . $itemProps['link'] . '"' : '';

            $html = '<span  class="header">' . $itemProps['icon'];
            $html .= '<a ' . $href . ' class="item-title">' . $itemProps['title'] . '</a>';
            $html .= ' <span class="item-in">' . Translations::get('in') . '</span>';
            $html .= ' <span class="item-cat_name">' . $itemProps['category'] . '</span>';
            $html .= '</span>';

            return $html;
        }

        protected function get_meta_html($itemProps)
        {
            $html = '<div class="meta">';
            $html .= '<span class="date">';
            $html .= '<i class="calendar alternate outline icon"></i> <span class="meta-value">' . $itemProps['date'] . '</span>';
            $html .= '</span>  ';

            if (isset($itemProps['page_views']) && !empty($itemProps['page_views'])) {
                $html .= '<span class="like">';
                $html .= '<i class="eye icon"></i><span class="meta-value">' . $itemProps['page_views'] . '</span>';
                $html .= '</span>';
            }

            $html .= '</div>';

            return $html;
        }

        protected function get_description_html($itemProps, $collectionProps)
        {
            $content = substr($itemProps['content'], 0, $collectionProps['description_length']) . '...';
            $html = '<div class="description">';
            $html .= '<p>' . $content . '</p>';
            $html .= '</div>';

            return $html;
        }

        protected function get_article_tags($tags)
        {
            $html = '';

            if (!isset($tags) || empty($tags)) {
                return $html;
            }

            $html .= "<div class='extra'>";
            foreach ($tags as $tag) {
                if (stripos($tag, 'strong') !== false) {
                    $html .= "<div class='ui blue label'>" . $tag . "</div>";
                } else {
                    $html .= "<div class='ui basic label'>" . $tag . "</div>";
                }
            }
            $html .= "</div>";

            return $html;
        }

        protected function content_footer_html()
        {
            return "</div></div></div>"; // .helpie-search-listing  -> .helpie-main-content-area -> .wrapper;
        }
    }
}
