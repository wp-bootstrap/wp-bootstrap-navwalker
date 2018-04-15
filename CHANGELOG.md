#CHANGELOG

## [3.1.0]

- Backport 2 improvements from v4.x branch:
	- Prevent error `trying to get property of non-object` when no menu is set to a location using the walker.
	- Add `$depth` as 4th parameter passed to `nav_menu_link_attributes`.

## [3.0.3]

- Revert composer autoload changes.

## [3.0.2]

- Autoload the main package file when installing through composer.

## [3.0.1]

- Made Titles on items with icons output item title instead of the icon class.

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
