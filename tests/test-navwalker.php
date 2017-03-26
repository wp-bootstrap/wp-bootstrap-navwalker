<?php
/**
 * Class SampleTest
 *
 * @package Wp_Bootstrap_Navwalker
 */


/**
 * Test_WP_Bootstrap_NavWalker class.
 *
 * @extends WP_UnitTestCase
 */
class Test_WP_Bootstrap_NavWalker extends WP_UnitTestCase {

	/**
	 * setUp function.
	 *
	 * @access public
	 * @return void
	 */
	function setUp() {

		parent::setUp();

		$this->walker = new WP_Bootstrap_Navwalker();

	}

	/**
	 * Test NavWalker File Exists.
	 *
	 * @access public
	 * @return void
	 */
	function test_navwalker_file_exists() {
        $this->assertFileExists('wp-bootstrap-navwalker.php');
    }

	/**
	 * Test Start LVL Function.
	 *
	 * @access public
	 * @return void
	 */
	function test_startlvl_function() {

		$WP_Bootstrap_Navwalker = new WP_Bootstrap_Navwalker();

		$this->assertTrue(
			method_exists($WP_Bootstrap_Navwalker, 'start_lvl'),
				'Class does not have method start_lvl.'
			);

	}

	/**
	 * Test Start El Function.
	 *
	 * @access public
	 * @return void
	 */
	function test_start_el_function() {

		$WP_Bootstrap_Navwalker = new WP_Bootstrap_Navwalker();

		$this->assertTrue(
			method_exists($WP_Bootstrap_Navwalker, 'start_el'),
				'Class does not have method start_el.'
			);

	}

	/**
	 * Test for Display Element.
	 *
	 * @access public
	 * @return void
	 */
	function test_display_element_function() {

		$WP_Bootstrap_Navwalker = new WP_Bootstrap_Navwalker();

		$this->assertTrue(
			method_exists($WP_Bootstrap_Navwalker, 'display_element'),
				'Class does not have method display_element.'
			);

	}

	/**
	 * Test Fallback Function exists.
	 *
	 * @access public
	 * @return void
	 */
	function test_fallback_function() {

		$WP_Bootstrap_Navwalker = new WP_Bootstrap_Navwalker();

		$this->assertTrue(
			method_exists($WP_Bootstrap_Navwalker, 'fallback'),
				'Class does not have method fallback.'
			);

	}
}
