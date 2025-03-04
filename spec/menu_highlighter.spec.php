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
			context('but this menu item is not the selected parent for this taxonomy', function () {
				it('removes the current-menu-ancestor class if it is already there', function () {
					$classes = [
						'current-menu-ancestor',
						'foo',
						'bar',
						'tax-type-taxonomy-one'
					];
					$item = (object) [];
					allow('get_queried_object')->toBeCalled()->andReturn((object) [
						'taxonomy' => 'taxonomy-two'
					]);

					$result = $this->menuHighlighter->addHighlightClass($classes, $item);
					expect($result)->toEqual([1 => 'foo', 2 => 'bar', 3 => 'tax-type-taxonomy-one']);
				});
			});
			context('and this menu item is the selected parent for this taxonomy', function () {
				it('adds the current-menu-ancestor marker class', function () {
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
					expect($result)->toEqual(['foo', 'bar', 'tax-type-taxonomy-one', 'current-menu-ancestor']);
				});
			});
		});
		context('the menu item is the blog index page', function () {
			context('and the post type is default post', function () {
				it('does not remove the current page parent marker if it exists', function () {
					$classes = [
						'foo',
						'bar',
						'current-menu-ancestor'
					];
					$item = (object) [
						'object_id' => 123
					];
					allow('is_tax')->toBeCalled()->andReturn(false);
					allow('get_post_type')->toBeCalled()->andReturn('post');
					allow('get_option')->toBeCalled()->andReturn(123);

					$result = $this->menuHighlighter->addHighlightClass($classes, $item);
					expect($result)->toEqual(['foo', 'bar', 'current-menu-ancestor']);
				});
			});
			context('and the post type is not default post', function () {
				it('does remove the current page parent marker if it exists', function () {
					$classes = [
						'foo',
						'bar',
						'current-menu-ancestor'
					];
					$item = (object) [
						'object_id' => 123
					];
					allow('is_tax')->toBeCalled()->andReturn(false);
					allow('get_post_type')->toBeCalled()->andReturn('custom');
					allow('get_option')->toBeCalled()->andReturn(123);

					$result = $this->menuHighlighter->addHighlightClass($classes, $item);
					expect($result)->toEqual(['foo', 'bar']);
				});
			});
		});

		context('we are viewing a custom post type', function () {
			context('and this menu item has been marked as the parent for this custom post type', function () {
				it('adds the current page parent class to the classes', function () {
					$classes = [
						'foo',
						'bar',
						'post-type-custom'
					];
					$item = (object) [
						'object_id' => 123
					];
					allow('is_tax')->toBeCalled()->andReturn(false);
					allow('get_post_type')->toBeCalled()->andReturn('custom');
					allow('get_option')->toBeCalled()->andReturn(456);

					$result = $this->menuHighlighter->addHighlightClass($classes, $item);
					expect($result)->toEqual(['foo', 'bar', 'post-type-custom', 'current-menu-ancestor']);
				});
			});
		});
	});
});
