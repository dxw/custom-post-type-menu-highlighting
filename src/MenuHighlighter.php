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
        $currentMarkerClass = 'current_page_parent';

        if (is_tax()) {
            // Prevent Taxonomy archives from highlighting the blog index page.
            $classes = $this->removeDefaultCurrentMarker($classes, $currentMarkerClass);

            $this_type_class = 'tax-type-' . get_queried_object()->taxonomy;
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
