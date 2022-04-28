<?php

namespace Dxw\CustomPostTypeMenuHighlighter;

class MenuHighlighter implements \Dxw\Iguana\Registerable
{
    public function register() : void
    {
        add_filter('nav_menu_css_class', [$this, 'addHighlightClass'], 10, 2);
    }

    public function addHighlightClass(array $classes, object $item) : array
    {
        $currentMarkerClass = 'current-menu-ancestor';

        if (is_tax()) {
            // Prevent Taxonomy archives from highlighting the blog index page.
            $classes = $this->removeDefaultCurrentMarker($classes, $currentMarkerClass);

            $this_type_class = 'tax-type-' . get_queried_object()->taxonomy;
            if (in_array($this_type_class, $classes)) {
                array_push($classes, $currentMarkerClass);
            };
        } else {
            $post_type = get_post_type();
            // Prevent CPT from highlighting the blog index page;
            // run only if the post type is a CPT - don't mess with posts.
            if ('post' !== $post_type && get_option('page_for_posts') === $item->object_id) {
                $classes = $this->removeDefaultCurrentMarker($classes, $currentMarkerClass);
            }
    
            $this_type_class = 'post-type-' . $post_type;
            if (in_array($this_type_class, $classes)) {
                array_push($classes, $currentMarkerClass);
            };
        }
        return $classes;
    }

    private function removeDefaultCurrentMarker(array $classes, string $currentMarkerClass) : array
    {
        return array_filter(
            $classes,
            function ($element) use ($currentMarkerClass) {
                return ($element !== $currentMarkerClass);
            }
        );
    }
}
