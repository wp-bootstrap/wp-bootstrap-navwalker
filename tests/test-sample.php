<?php
/**
 * Class SampleTest
 *
 * @package Wp_Bootstrap_Navwalker
 */

/**
 * @group post
 * @group navmenus
 * @group taxonomy
 * @group walker
 * https://develop.svn.wordpress.org/trunk/tests/phpunit/tests/walker.php
 */
class WP_Test_Bootstrap_NavWalker extends WP_UnitTestCase {
	
	function setUp() {
		$this->walker = new WP_Bootstrap_Navwalker();
		parent::setUp();
	}
	
	function test_single_item() {
		$items = array( (object) array( 'id' => 1, 'parent' => 0 ) );
		$output = $this->walker->walk( $items, 0 );
		$this->assertEquals( 1, $this->walker->get_number_of_root_elements( $items ) );
		$this->assertEquals( '<li>1</li>', $output );
	}
	
	function test_single_item_flat() {
		$items = array( (object) array( 'id' => 1, 'parent' => 0 ) );
		$output = $this->walker->walk( $items, -1 );
		$this->assertEquals( 1, $this->walker->get_number_of_root_elements( $items ) );
		$this->assertEquals( '<li>1</li>', $output );
	}
	
	function test_single_item_depth_1() {
		$items = array( (object) array( 'id' => 1, 'parent' => 0 ) );
		$output = $this->walker->walk( $items, 1 );
		$this->assertEquals( 1, $this->walker->get_number_of_root_elements( $items ) );
		$this->assertEquals( '<li>1</li>', $output );
	}
	
}
