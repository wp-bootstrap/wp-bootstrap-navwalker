<?php
/**
 * Class SampleTest
 *
 * @package Wp_Bootstrap_Navwalker
 *
 * phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r -- used for returned failure messages to give some details.
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
	public function setUp() {

		parent::setUp();

		$this->walker = new WP_Bootstrap_Navwalker();

		// this is a test array of valid values that the fallback method will accept.
		$this->valid_sample_fallback_args = array(
			'container'       => 'div',
			'container_id'    => 'a_container_id',
			'container_class' => 'a_container_class',
			'menu_class'      => 'a_menu_class',
			'menu_id'         => 'a_menu_id',
			'echo'            => true,
		);

		// array of the possible linkmod typeflags.
		$this->valid_linkmod_typeflags = array(
			'dropdown-header',
			'dropdown-divider',
			'dropdown-item-text'
		);

		// array of all possible linkmods, including the valid typeflags.
		$this->valid_linkmod_classes = array_merge( $this->valid_linkmod_typeflags, array(
			'disabled',
			'sr-only',
		) );

		// array of valid font-awesome icon class starters plus some randomly
		// chosen icon classes and some variations of upper/lower case letters.
		$this->some_fontawesome_classes = array(
			'fa',
			'fas',
			'fab',
			'far',
			'fal',
			'fa-android',
			'fa-css3',
			'fa-home',
			'fa-bluetooth-b',
			'fa-chess-rook',
			'fA-home',
			'Fa-HoMe',
			'fa-HomE',
		);

		// array of valid glyphicon icon class starters plus some randomly
		// chosen icon classes and some variations of upper/lower case letters.
		$this->some_glyphicons_classes = array(
			'glyphicon',
			'glyphicon-asterisk',
			'glyphicon-ok',
			'glyphicon-file',
			'glyphicon-hand-left',
			'glyphicon-sd-video',
			'glyphicon-subscript',
			'glyphicon-grain',
			'Glyphicon-file',
			'Glyphicon-File',
			'glyphicon-File',
			'glYphiCon-fiLe',
		);

	}

	/**
	 * Test NavWalker File Exists.
	 *
	 * @access public
	 * @return void
	 */
	public function test_navwalker_file_exists() {
		$this->assertFileExists( 'class-wp-bootstrap-navwalker.php' );
	}

	/**
	 * Test Start LVL Function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_startlvl_function_exists() {

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
	public function test_start_el_function_exists() {

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
	public function test_display_element_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'display_element' ),
			'Class does not have method display_element.'
		);

	}

	/**
	 * Test Fallback function exists.
	 *
	 * @access public
	 * @return void
	 */
	public function test_fallback_function_exists() {

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
	 *
	 * @access public
	 * @return void
	 */
	public function test_fallback_function_output_loggedout() {

		// default is to echo reults, buffer.
		ob_start();
		WP_Bootstrap_Navwalker::fallback( $this->valid_sample_fallback_args );
		$fallback_output_echo = ob_get_clean();

		// empty string expected when not logged in.
		$this->assertEmpty(
			$fallback_output_echo,
			'Fallback output for logged out user created a non-empty string in echo mode.'
		);

		// set 'echo' to false and request the markup returned.
		$fallback_output_return = WP_Bootstrap_Navwalker::fallback( array_merge( $this->valid_sample_fallback_args, array(
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
	 *
	 * @access public
	 * @return void
	 */
	public function test_fallback_function_output_loggedin() {

		// make an admin user and set it to be the current user.
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );

		// default is to echo results, buffer.
		ob_start();
		WP_Bootstrap_Navwalker::fallback( $this->valid_sample_fallback_args );
		$fallback_output_echo = ob_get_clean();

		// rudimentary content test - confirm it opens a div with 2 expected
		// values and ends by closing a div.
		$match = ( preg_match( '/^(<div id="a_container_id" class="a_container_class">)(.*?)(<\/div>)$/', $fallback_output_echo ) ) ? true : false;
		$this->assertTrue(
			$match,
			'Fallback method seems to create unexpected html for logged in users in echo mode.'
		);

		// set 'echo' to false and request the markup returned.
		$fallback_output_return = WP_Bootstrap_Navwalker::fallback( array_merge( $this->valid_sample_fallback_args, array(
			'echo' => false,
		) ) );

		// return and echo should both produce the same strings.
		$this->assertEquals(
			$fallback_output_echo,
			$fallback_output_return,
			'Fallback method seems to create unexpected html for logged in users in return mode.'
		);
	}

	/**
	 * Test seporate_linkmods_and_icons_from_classes Function exists.
	 *
	 * @access public
	 * @return void
	 */
	public function test_seporate_linkmods_and_icons_from_classes_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'seporate_linkmods_and_icons_from_classes' ),
			'Class does not have method seporate_linkmods_and_icons_from_classes.'
		);

	}

	/**
	 * Test that the function catches a random assortment of glyphicon icon
	 * classes mixed with with regular classnames.
	 *
	 * @depends test_seporate_linkmods_and_icons_from_classes_function_exists
	 *
	 * @access public
	 * @return void
	 */
	public function test_seporate_linkmods_and_icons_from_classes_fontawesome() {

		$wp_bootstrap_navwalker = $this->walker;
		// since we're working with private methods we need to use a reflector.
		$reflector = new ReflectionClass( 'WP_Bootstrap_Navwalker' );

		// get a reflected method for the opener function and set to public.
		$method_open = $reflector->getMethod( 'seporate_linkmods_and_icons_from_classes' );
		$method_open->setAccessible( true );

		$icons_array     = $this->some_fontawesome_classes;
		$linkmod_classes = array();
		$icon_classes    = array();
		$chars           = range( 'a', 'z' );
		$extra_classes   = array();
		// make 10 random valid classnames with legth of 8 chars (there should
		// be no naming collisions here with this random string gen method).
		for ( $i = 0; $i < 20; $i++ ) {
			$string = '';
			$length = mt_rand( 4, 10 );
			for ( $j = 0; $j < $length; $j++ ) {
				$string .= $chars[ mt_rand( 0, count( $chars ) - 1 ) ];
			}
			$extra_classes[] = $string;
		}
		// merge the valid icon classes with the extra classes and shuffle order.
		$icons_array = array_merge( $icons_array, $extra_classes );
		shuffle( $icons_array );

		$returned_array = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( $icons_array, &$linkmod_classes, &$icon_classes, 0 ) );

		// linkmod_classes should be empty and returned_array should not.
		$this->assertTrue( ( empty( $linkmod_classes ) && ! empty( $returned_array ) ) );
		// icon_classes should no longer be empty.
		$this->assertNotTrue( empty( $icon_classes ) );
		// the number of items inside updated $icon_classes should match number of valids we started with.
		$this->assertTrue( count( $this->some_fontawesome_classes ) === count( $icon_classes ), "Seems that glyphicons classes are not catptured properly... \nvalid: \n" . print_r( $this->some_fontawesome_classes, true ) . "\nreturned: \n" . print_r( $icon_classes, true ) );
		// get the differences between the original classes and updated classes.
		$icon_differences = array_diff( $this->some_fontawesome_classes, $icon_classes );
		// should be no differences thus empty array, this being TRUE also means
		// that text was exact match in the updated array vs the original.
		$this->assertTrue( empty( $icon_differences ) );

	}

	/**
	 * Test that the function catches a random assortment of font awesome icon
	 * classes mixed with with regular classnames.
	 *
	 * @depends test_seporate_linkmods_and_icons_from_classes_function_exists
	 *
	 * @access public
	 * @return void
	 */
	public function test_seporate_linkmods_and_icons_from_classes_glyphicons() {

		$wp_bootstrap_navwalker = $this->walker;
		// since we're working with private methods we need to use a reflector.
		$reflector = new ReflectionClass( 'WP_Bootstrap_Navwalker' );

		// get a reflected method for the opener function and set to public.
		$method_open = $reflector->getMethod( 'seporate_linkmods_and_icons_from_classes' );
		$method_open->setAccessible( true );

		$icons_array     = $this->some_glyphicons_classes;
		$linkmod_classes = array();
		$icon_classes    = array();
		$chars           = range( 'a', 'z' );
		$extra_classes   = array();
		// make 10 random valid classnames with legth of 8 chars (there should
		// be no naming collisions here with this random string gen method).
		for ( $i = 0; $i < 10; $i++ ) {
			$string = '';
			$length = mt_rand( 4, 10 );
			for ( $j = 0; $j < $length; $j++ ) {
				$string .= $chars[ mt_rand( 0, count( $chars ) - 1 ) ];
			}
			$extra_classes[] = $string;
		}
		// merge the valid icon classes with the extra classes and shuffle order.
		$icons_array = array_merge( $icons_array, $extra_classes );
		shuffle( $icons_array );

		$returned_array = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( $icons_array, &$linkmod_classes, &$icon_classes, 0 ) );

		// linkmod_classes should be empty and returned_array should not.
		$this->assertTrue( ( empty( $linkmod_classes ) && ! empty( $returned_array ) ) );
		// icon_classes should no longer be empty.
		$this->assertNotTrue( empty( $icon_classes ) );
		// the number of items inside updated $icon_classes should match number of valids we started with.
		$this->assertTrue( count( $this->some_glyphicons_classes ) === count( $icon_classes ), "Seems that glyphicons classes are not catptured properly... \nvalid: \n" . print_r( $this->some_glyphicons_classes, true ) . "\nreturned: \n" . print_r( $icon_classes, true ) );
		// get the differences between the original classes and updated classes.
		$icon_differences = array_diff( $this->some_glyphicons_classes, $icon_classes );
		// should be no differences thus empty array, this being TRUE also means
		// that text was exact match in the updated array vs the original.
		$this->assertTrue( empty( $icon_differences ) );

	}

	/**
	 * Test that the function catches a random assortment of font awesome icon
	 * classes mixed with with regular classnames.
	 *
	 * @depends test_seporate_linkmods_and_icons_from_classes_function_exists
	 *
	 * @access public
	 * @return void
	 */
	public function test_seporate_linkmods_and_icons_from_classes_linkmods() {

		$wp_bootstrap_navwalker = $this->walker;
		// since we're working with private methods we need to use a reflector.
		$reflector = new ReflectionClass( 'WP_Bootstrap_Navwalker' );

		// get a reflected method for the opener function and set to public.
		$method_open = $reflector->getMethod( 'seporate_linkmods_and_icons_from_classes' );
		$method_open->setAccessible( true );

		$valid_linkmods  = $this->valid_linkmod_classes;
		$linkmod_classes = array();
		$icon_classes    = array();
		$chars           = range( 'a', 'z' );
		$extra_classes   = array();
		// make 20 random valid classnames with legth of 4 to 10 chars. There
		// should be no naming collisions here with this random string gen.
		for ( $i = 0; $i < 10; $i++ ) {
			$string = '';
			$length = mt_rand( 4, 10 );
			for ( $j = 0; $j < $length; $j++ ) {
				$string .= $chars[ mt_rand( 0, count( $chars ) - 1 ) ];
			}
			$extra_classes[] = $string;
		}
		// merge the valid icon classes with the extra classes and shuffle order.
		$linkmod_array = array_merge( $valid_linkmods, $extra_classes );
		shuffle( $linkmod_array );

		// NOTE: this is depth of 0 and meaning valid_linkmod_typeflags won't be captured.
		$returned_array = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( $linkmod_array, &$linkmod_classes, &$icon_classes, 0 ) );

		// linkmod_classes should NOT be empty and returned_array should not.
		$this->assertTrue( ( ! empty( $linkmod_classes ) && ! empty( $returned_array ) ) );
		// icon_classes should be empty.
		$this->assertTrue( empty( $icon_classes ) );

		$num_of_items_left = count( $this->valid_linkmod_classes ) - count( $linkmod_classes ) - count( $this->valid_linkmod_typeflags );
		// the number of items inside updated array should match [what we started with - minus the linkmods for inside dropdowns].
		$this->assertNotTrue(
			(bool) $num_of_items_left,
			"Seems that the linkmod classes are not catptured properly when outside of dropdowns... \nvalid: \n" . print_r( $this->valid_linkmod_classes, true ) . "\nreturned: \n" . print_r( $linkmod_classes, true )
		);
		// get the differences between the original classes and updated classes.
		$linkmod_differences = array_diff( $this->valid_linkmod_classes, $linkmod_classes, $this->valid_linkmod_typeflags );

		// should be no differences thus empty array, this being TRUE also means
		// that text was exact match in the updated array vs the original.
		$this->assertTrue( empty( $linkmod_differences ) );

		// repeat some of the above tests but this time with depth = 1 so that we catch classes intended for inside dropdowns.
		$depth             = 1;
		$linkmod_classes_d = array();
		$icon_classes_d    = array();
		$returned_array_d  = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( $linkmod_array, &$linkmod_classes_d, &$icon_classes_d, $depth ) );

		$this->assertTrue( count( $this->valid_linkmod_classes ) === count( $linkmod_classes_d ), "Seems that the linkmod classes are not catptured properly when inside dropdowns... \nvalid: \n" . print_r( $this->valid_linkmod_classes, true ) . "\nreturned: \n" . print_r( $linkmod_classes, true ) );
		$linkmod_differences_d = array_diff( $this->valid_linkmod_classes, $linkmod_classes_d );
		$this->assertTrue( empty( $linkmod_differences_d ), 'There are differences between the matched classnames and the valid classnames.' );

	}

	/**
	 * Test that the function catches all possible linkmod classes, any icon
	 * classes and leaves the other classes as-is on the array.
	 *
	 * @depends test_seporate_linkmods_and_icons_from_classes_function_exists
	 *
	 * @depends test_seporate_linkmods_and_icons_from_classes_fontawesome
	 * @depends test_seporate_linkmods_and_icons_from_classes_glyphicons
	 * @depends test_seporate_linkmods_and_icons_from_classes_linkmods
	 *
	 * @access public
	 * @return void
	 */
	public function test_seporate_linkmods_and_icons_from_classes_fulltest() {

		$wp_bootstrap_navwalker = $this->walker;
		// since we're working with private methods we need to use a reflector.
		$reflector = new ReflectionClass( 'WP_Bootstrap_Navwalker' );

		// get a reflected method for the opener function and set to public.
		$method_open = $reflector->getMethod( 'seporate_linkmods_and_icons_from_classes' );
		$method_open->setAccessible( true );

		$icons_array     = array_merge( $this->some_fontawesome_classes, $this->some_glyphicons_classes );
		$linkmod_array   = $this->valid_linkmod_classes;
		$linkmod_classes = array();
		$icon_classes    = array();
		$chars           = range( 'a', 'z' );
		$extra_classes   = array();
		// make 1000 random valid classnames with legth of 8 chars (there should
		// be no naming collisions here with this random string gen method).
		for ( $i = 0; $i < 1000; $i++ ) {
			$string = '';
			$length = mt_rand( 4, 10 );
			for ( $j = 0; $j < $length; $j++ ) {
				$string .= $chars[ mt_rand( 0, count( $chars ) - 1 ) ];
			}
			$extra_classes[] = $string;
		}
		// merge the valid icon classes with valid linkmod classes and the extra classes then shuffle order.
		$classname_array = array_merge( $icons_array, $linkmod_array, $extra_classes );
		shuffle( $classname_array );

		// need a depth of 1 to ensure that our linkmods classes for inside dropdowns are also captured.
		$depth          = 1;
		$returned_array = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( $classname_array, &$linkmod_classes, &$icon_classes, $depth ) );

		// linkmod_classes NOT should be empty and returned_array should not.
		$this->assertTrue( ( ! empty( $linkmod_classes ) && ! empty( $returned_array ) ), 'Either the linkmod array or the returned non matching classes array is empty when they shoud not be.' );
		// starting arrays should no longer be empty.
		$this->assertNotTrue( empty( $icon_classes ), 'Did not catch any icons.' );
		$this->assertNotTrue( empty( $linkmod_classes ), 'Did not catch any linkmods.' );

		// icons compair.
		$this->assertTrue( count( $icons_array ) === count( $icon_classes ), "Seems that icon classes are not catptured properly... valid: \n" . print_r( $icons_array, true ) . "returned: \n" . print_r( $icon_classes, true ) );
		$icon_differences = array_diff( $icons_array, $icon_classes );
		$this->assertTrue( empty( $icon_differences ), 'Seems that we did not catch all of the icon classes.' );
		// linkmod compair.
		$this->assertTrue( count( $linkmod_array ) === count( $linkmod_classes ), "Seems that linkmod classes are not catptured properly... valid: \n" . print_r( $linkmod_array, true ) . "returned: \n" . print_r( $linkmod_classes, true ) );
		$linkmod_differences = array_diff( $icons_array, $icon_classes );
		$this->assertTrue( empty( $linkmod_differences ), 'Seems that we did not catch all of the linkmod classes.' );
		// extra classes string matches checks.
		$returned_differences = array_diff( $returned_array, $extra_classes );
		$this->assertTrue( empty( $returned_differences ), 'The returned array minus the extra classes should be empty, likely some classes were missed or string malformation occured.' );

	}

	/**
	 * Test get_linkmod_type Function exists.
	 *
	 * @access public
	 * @return void
	 */
	public function test_get_linkmod_type_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'get_linkmod_type' ),
			'Class does not have method get_linkmod_type.'
		);

	}

	/**
	 * Test update_atts_for_linkmod_type Function exists.
	 *
	 * @access public
	 * @return void
	 */
	public function test_update_atts_for_linkmod_type_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'update_atts_for_linkmod_type' ),
			'Class does not have method update_atts_for_linkmod_type.'
		);

	}

	/**
	 * Test linkmod_element_open Function exists.
	 *
	 * @access public
	 * @return void
	 */
	public function test_linkmod_element_open_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'linkmod_element_open' ),
			'Class does not have method linkmod_element_open.'
		);

	}

	/**
	 * Test linkmod_element_close Function exists.
	 *
	 * @access public
	 * @return void
	 */
	public function test_linkmod_element_close_function_exists() {

		$wp_bootstrap_navwalker = $this->walker;

		$this->assertTrue(
			method_exists( $wp_bootstrap_navwalker, 'linkmod_element_close' ),
			'Class does not have method linkmod_element_close.'
		);

	}

	/**
	 * Tests for valid markup being used as the opener and closer sections for
	 * some different linkmod types.
	 *
	 * @access public
	 * @return void
	 */
	public function test_linkmod_elements_open_and_close_successfully() {

		$wp_bootstrap_navwalker = $this->walker;

		// since we're working with private methods we need to use a reflector.
		$reflector = new ReflectionClass( 'WP_Bootstrap_Navwalker' );

		// get a reflected method for the opener function and set to public.
		$method_open = $reflector->getMethod( 'linkmod_element_open' );
		$method_open->setAccessible( true );

		// test openers for headers and dividers.
		$header_open = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( $this->valid_linkmod_typeflags[0], 'stringOfAttributes' ) );
		$this->assertNotEmpty( $header_open, 'Got empty string for opener of ' . $this->valid_linkmod_typeflags[0] );
		$divider_open = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( $this->valid_linkmod_typeflags[1], 'stringOfAttributes' ) );
		$this->assertNotEmpty( $divider_open, 'Got empty string for opener of ' . $this->valid_linkmod_typeflags[1] );
		$text_open = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( $this->valid_linkmod_typeflags[2], 'stringOfAttributes' ) );
		$this->assertNotEmpty( $divider_open, 'Got empty string for opener of ' . $this->valid_linkmod_typeflags[2] );

		// test that that an unknown linkmod type being passed results in no output.
		$nonexistent_linkmod_type_open = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( 'nonexistentlinkmodtype', 'stringOfAttributes' ) );
		$this->assertEmpty( $nonexistent_linkmod_type_open, 'Expected empty string when using non-existent linkmod type.' );

		// get a reflected method for the closer function and set to public.
		$method_close = $reflector->getMethod( 'linkmod_element_close' );
		$method_close->setAccessible( true );

		$header_close = $method_close->invokeArgs( $wp_bootstrap_navwalker, array( $this->valid_linkmod_typeflags[0] ) );
		$this->assertNotEmpty( $header_close, 'Got empty string for closer of ' . $this->valid_linkmod_typeflags[0] );
		$divider_close = $method_close->invokeArgs( $wp_bootstrap_navwalker, array( $this->valid_linkmod_typeflags[1] ) );
		$this->assertNotEmpty( $divider_close, 'Got empty string for closer of ' . $this->valid_linkmod_typeflags[1] );
		$text_close = $method_close->invokeArgs( $wp_bootstrap_navwalker, array( $this->valid_linkmod_typeflags[2] ) );
		$this->assertNotEmpty( $divider_close, 'Got empty string for closer of ' . $this->valid_linkmod_typeflags[2] );

		// test that that an unknown linkmod type being passed results in no output.
		$nonexistent_linkmod_type_close = $method_open->invokeArgs( $wp_bootstrap_navwalker, array( 'nonexistentlinkmodtype' ) );
		$this->assertEmpty( $nonexistent_linkmod_type_close, 'Expected empty string when using non-existent linkmod type.' );

		// dropdown-header should be a span.
		$this->assertRegExp(
			'/^(<span(.*?)>)(.*?)(<\/span>)$/',
			$header_open . $header_close,
			'The opener and closer for ' . $this->valid_linkmod_typeflags[0] . ' does not seem to match expected elements.'
		);
		// dropdown-divider should be a div.
		$this->assertRegExp(
			'/^(<div(.*?)>)(.*?)(<\/div>)$/',
			$divider_open . $divider_close,
			'The opener and closer for ' . $this->valid_linkmod_typeflags[1] . ' does not seem to match expected elements.'
		);
		// dropdown-item-text should be a span.
		$this->assertRegExp(
			'/^(<span(.*?)>)(.*?)(<\/span>)$/',
			$text_open . $text_close,
			'The opener and closer for ' . $this->valid_linkmod_typeflags[2] . ' does not seem to match expected elements.'
		);
	}
}
