<?php

describe(Dxw\CustomPostTypeMenuHighlighter\MenuHighlighter::class, function () {
    beforeEach(function () {
        $this->menuHighlighter = new \Dxw\CustomPostTypeMenuHighlighter\MenuHighlighter();
    });

    it('is registerable', function () {
        expect($this->menuHighlighter)->toBeAnInstanceOf(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the filter', function () {
            allow('add_filter')->toBeCalled();
            expect('add_filter')->toBeCalled()->once();
            expect('add_filter')->toBeCalled()->once()->with('nav_menu_css_class', [$this->menuHighlighter, 'addHighlightClass'], 10, 2);
            $this->menuHighlighter->register();
        });
    });

    describe('->addHighlightClass', function () {
        context('on a taxonomy archive page', function () {
            beforeEach(function () {
                allow('is_tax')->toBeCalled()->andReturn(true);
            });
            context('but this is not the selected parent', function () {
                it('removes the current_page_parent class if it is already there', function () {
                    $classes = [
                        'current_page_parent',
                        'foo',
                        'bar',
                        'tax-type-taxonomy-one'
                    ];
                    $item = (object) [];
                    allow('get_queried_object')->toBeCalled()->andReturn((object) [
                        'taxonomy' => 'taxonomy-two'
                    ]);
    
                    $result = $this->menuHighlighter->addHighlightClass($classes, $item);
                    expect($result)->toEqual([1=> 'foo', 2=> 'bar', 3=> 'tax-type-taxonomy-one']);
                });
            });
            context('and this is the selected parent', function () {
                it('adds the current_page_parent marker class', function () {
                    $classes = [
                        'foo',
                        'bar',
                        'tax-type-taxonomy-one'
                    ];
                    $item = (object) [];
                    allow('get_queried_object')->toBeCalled()->andReturn((object) [
                        'taxonomy' => 'taxonomy-one'
                    ]);
    
                    $result = $this->menuHighlighter->addHighlightClass($classes, $item);
                    expect($result)->toEqual(['foo', 'bar', 'tax-type-taxonomy-one', 'current_page_parent']);
                });
            });
        });
    });
});
