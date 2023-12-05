<?php

namespace Helpie\Features\Components\Search\Views;

if (!class_exists('\Helpie\Features\Components\Search\Views\Pagination_View')) {
    class Pagination_View
    {

        public function get_view($collectionProps)
        {
            $total_posts = $collectionProps['total_posts'];
            $posts_per_page = $collectionProps['posts_per_page'];
            $range = $collectionProps['range'];
            $current_page = $collectionProps['current_page'];

            $total_pages = ceil($total_posts / $posts_per_page);

            if ($total_posts > $posts_per_page) {
                $start = $this->get_start_range($current_page, $range, $total_pages);
                $end = $this->get_end_range($current_page, $range, $total_pages);

                $page_nav = '<div class="ui pagination menu">';
                if ($current_page > 1) {
                    $page_nav .= $this->get_first_page_link($current_page);
                }
                for ($page_number = $start; $page_number <= $end; $page_number++) {
                    $page_nav .= $this->get_middle_page_links($page_number, $current_page);
                }
                if ($current_page < $total_pages) {
                    $page_nav .= $this->get_last_page_link($current_page);
                }
                $page_nav .= '</div>';

                return $page_nav;
            }
        }

        protected function get_start_range($current_page, $range, $total_pages)
        {
            if ($total_pages > $range) {
                $start = ($current_page <= $range) ? 1 : ($current_page - $range);
            } else {
                $start = 1;
            }

            return $start;
        }

        protected function get_end_range($current_page, $range, $total_pages)
        {
            if ($total_pages > $range) {
                $end = ($total_pages - $current_page >= $range) ? ($current_page + $range) : ($total_pages);
            } else {
                $end = $total_pages;
            }

            return $end;
        }

        protected function get_first_page_link($current_page)
        {
            $previous_page = $current_page - 1;
            $page_query_value = 'p' . $previous_page;

            $page_nav = '';
            $page_nav .= '<a class="item pre" href="' . add_query_arg('page', $page_query_value) . '"';
            $page_nav .= '><i class="angle left icon"></i></a>';

            return $page_nav;
        }

        protected function get_middle_page_links($page_number, $current_page)
        {
            $page_query_value = 'p' . $page_number;
            $active = '';

            if ($page_number == $current_page) {
                $active = 'active ';
            }

            $page_nav = '';
            $page_nav .= '<a class="' . $active . 'item" href="' . add_query_arg('page', $page_query_value) . '">';
            $page_nav .= $page_number;
            $page_nav .= '</a>';

            return $page_nav;
        }

        protected function get_last_page_link($current_page)
        {
            $next_page = $current_page + 1;
            $page_query_value = 'p' . $next_page;

            $page_nav = '';
            $page_nav .= '<a class="item nxt" href="' . add_query_arg('page', $page_query_value) . '"';
            $page_nav .= '><i class="angle right icon"></i></a>';

            return $page_nav;
        }
    } // END CLASS
}
