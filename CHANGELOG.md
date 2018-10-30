#CHANGELOG

## [4.2.0]
- Fix typo in function name 'seporate'->'separate' (private function, no need to add back-compat).

## [4.1.0]
- Prevent error `trying to get property of non-object` when no menu is set to a location using the walker.
- Add `$depth` as 4th parameter passed to `nav_menu_link_attributes`.
- Add support for `dropdown-item-text` linkmod type.
## [4.0.3]
- Drop composer class autoload statement.

## [4.0.2]
- Fix dropdown opener having empty string for href value.
- More accurate regex matching of icon and linkmod classnames.
- Changed composer package type to `library` from `wordpress-plugin` again.
- Tests: Add unit tests for the function that separates classnames for the walker.
- Fix case sensitive matching to now match mixes of upper and lower case.

## [4.0.1]
- Fix untranslated string in fallback (this was lost in transition between v3 and v4, fixed again).

## [4.0.0]
- Added a prefix on @since tags to highlight when they refer to WP core instead of this class.
- Rework of `start_lvl()` and `start_el()` based on latest `Walker_Nav_Menu` Class from WP core.
	- Whitespace preservation method improvements.
	- Added `nav_menu_item_args` filter and `nav_menu_item_title` brought in at WP 4.4.0
	- Use `the_title` filter prior to `nav_menu_item_title`.
- Added a labelled-by attribute to dropdowns for accessibility.
- Links inside dropdown have `.dropdown-item` instead of `.nav-link`.
- Remove `<span class="carat">` after parent dropdown items.
- Support `echo` arg in fallback menu. props: @toddlevy
- Add `.active` to parent when a child is current page. props: @zyberspace
- Fix to correct output of dropdown atts and styles when depth passed to wp_nav_menu is <= 1. props: @chrisgeary92
- Move icon output to a local var instead of modifying and clearing a global object.
- Reassign filtered classes back to $classes array so that updated classes can be accessed later if needed. props: @lf-jeremy
- Update to work with Bootstrap v4.
	- Added `.nav-item` and `.nav-link` to `<li>` and `<a>` respectively.
- Dropped support for using title attribute to add link modifiers and icons.
- Added support for link modifiers and icons through WP Menu Builder 'classes' input.
	- Icon support is for Font Awesome 4/5 and Glyphicons icons.
- Added unit tests for the `fallback` method.
- Added code to handle icon-only menus.

## [3.0.0]

- Fix untranslated string in fallback.
- Instruct screenreaders to ignore icons when present.
- Added basic unit tests and travis config.
- Swapped to IF statements with curly braces.
- Adds `$depth` arg for nav_menu_css_class filter.
- Fix sanitization function used for class output in fallback.
- Changed composer package type to `library` from `wordpress-plugin`.

## [2.0.5] - 2016-011-15

- Fixed all reported issues by WP Enforcer.
- Fixed several Code Climate issues.

## [2.0.4]

- Updated fallback function to accept args array from `wp_nav_menu`.

## [2.0.3]

- Included a fallback function that adds a link to the WordPress menu manager if no menu has been assigned to the theme location.

## [2.0.2]

- Small tweak to ensure carets are only displayed on top level dropdowns.

## [2.0.1]

- Added missing active class to active menu items.

## [2.0.0]

- Class was completly re-written using the latest Wordpress 3.6 walker class.
- Now full supports Bootstrap 3.0+
- Tested with wp_debug & the Theme Check plugin.


---
<small>All notable changes to this project will be documented in this file. Please read [Keep a Change Log](http://keepachangelog.com) for more information. This project adheres to [Semantic Versioning](http://semver.org).</small>
