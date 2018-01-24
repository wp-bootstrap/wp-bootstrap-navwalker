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
	 * The setUp function.
	 *
	 * @access public
	 * @return void
	 */
	function setUp() {

		parent::setUp();

		$this->walker = new WP_Bootstrap_Navwalker();

		// this is a test array of valid values that the fallback method will accept.
		$this->sample_fallback_args = array(
			'container'       => 'div',
			'container_id'    => 'a_container_id',
			'container_class' => 'a_container_class',
			'menu_class'      => 'a_menu_class',
			'menu_id'         => 'a_menu_id',
			'echo'			  => true,
		);
	}

	/**
	 * Test NavWalker File Exists.
	 *
	 * @access public
	 * @return void
	 */
	function test_navwalker_file_exists() {
		$this->assertFileExists( 'class-wp-bootstrap-navwalker.php' );
	}

	/**
	 * Test Start LVL Function.
	 *
	 * @access public
	 * @return void
	 */
	function test_startlvl_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'start_lvl' ),
			'Class does not have method start_lvl.'
		);

	}

	/**
	 * Test Start El Function.
	 *
	 * @access public
	 * @return void
	 */
	function test_start_el_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'start_el' ),
			'Class does not have method start_el.'
		);

	}

	/**
	 * Test for Display Element.
	 *
	 * @access public
	 * @return void
	 */
	function test_display_element_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'display_element' ),
			'Class does not have method display_element.'
		);

	}

	/**
	 * Test Fallback Function exists.
	 *
	 * @access public
	 * @return void
	 */
	function test_fallback_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'fallback' ),
			'Class does not have method fallback.'
		);

	}

	/**
	 * Test Fallback method output for logged out users.
	 *
	 * Expects that for logged out users both echo and return requests should
	 * produce empty strings.
	 */
	function test_fallback_function_output_loggedout() {

		// default is to echo reults, buffer.
		ob_start();
		WP_Bootstrap_Navwalker::fallback( $this->sample_fallback_args );
		$fallback_output_echo = ob_get_clean();

		// empty string expected when not logged in.
		$this->assertEmpty(
			$fallback_output_echo,
			'Fallback output for logged out user created a non-empty string in echo mode.'
		);

		// set 'echo' to false and request the markup returned.
		$fallback_output_return = WP_Bootstrap_Navwalker::fallback( array_merge( $this->sample_fallback_args, array(
			'echo' => false,
		) ) );

		// return and echo should result in the same values (both empty).
		$this->assertEquals(
			$fallback_output_echo,
			$fallback_output_return,
			'Fallback output for logged out user created a non-empty string in return mode.'
		);
	}

	/**
	 * Test Fallback method output for logged in users.
	 *
	 * Expects strings to be produced with html markup and that they match when
	 * requesting either a return or defaulting to echo.
	 */
	function test_fallback_function_output_loggedin() {

		// make an admin user and set it to be the current user.
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );

		// default is to echo results, buffer.
		ob_start();
		WP_Bootstrap_Navwalker::fallback( $this->sample_fallback_args );
		$fallback_output_echo = ob_get_clean();

		// rudimentary content test - confirm it opens a div with 2 expected
		// values and ends by closing a div.
		$match = ( preg_match('/^(<div id="a_container_id" class="a_container_class">)(.*?)(<\/div>)$/', $fallback_output_echo ) ) ? true : false;
		$this->assertTrue(
			$match,
			'Fallback method seems to create unexpected html for logged in users in echo mode.'
		);

		// set 'echo' to false and request the markup returned.
		$fallback_output_return = WP_Bootstrap_Navwalker::fallback( array_merge( $this->sample_fallback_args, array(
			'echo' => false,
		) ) );

		// return and echo should both produce the same strings.
		$this->assertEquals(
			$fallback_output_echo,
			$fallback_output_return,
			'Fallback method seems to create unexpected html for logged in users in return mode.'
		);
	}

}
