=== Volunteer Spotlight ===
Contributors: Muhammad Mehdi
Developer: Muhammad Mehdi
Designer: Muhammad Mehdi
Tags: volunteer, spotlight, slider, shortcode, nonprofit
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 1.3.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Showcase your amazing volunteers with beautiful spotlight cards displayed in a responsive slider.

== Description ==

Volunteer Spotlight lets you create beautiful, professional volunteer highlight cards and display them in a smooth slider on any page or post using a simple shortcode.

**Features:**

* Custom Post Type for managing volunteers
* Easy-to-use admin interface
* Beautiful card design with photo, name, designation, and description
* Responsive Swiper.js slider
* Shortcode with customizable options
* Fully responsive and mobile-friendly
* Lightweight — assets only load where the shortcode is used

== Installation ==

1. Upload the `volunteer-spotlight` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Vol. Spotlight → Add Spotlight** to add your first volunteer
4. Use the shortcode `[volunteer_spotlight]` on any page or post

== Shortcode Usage ==

Basic usage:
`[volunteer_spotlight]`

With options:
`[volunteer_spotlight limit="6" autoplay="true" speed="5000" order="DESC"]`

**Attributes:**

* `limit` — Number of volunteers to show (default: -1, all)
* `autoplay` — Enable auto-rotation (default: true)
* `speed` — Autoplay delay in ms (default: 4000)
* `order` — Sort order, ASC or DESC (default: DESC)

== Frequently Asked Questions ==

= How do I add a volunteer photo? =
When editing a spotlight, use the "Set featured image" option in the right sidebar.

= Can I display the slider on multiple pages? =
Yes! Use the shortcode `[volunteer_spotlight]` on as many pages as you like.

= How do I change the slider speed? =
Use the speed attribute: `[volunteer_spotlight speed="6000"]` (value in milliseconds).

== Changelog ==

= 1.3.0 =
* Hardcoded inline CSS and JS for zero file-path dependency (fixes 404 on server)
* Read More button now links to the volunteer's post permalink
* Enhanced card shadow to match reference design
* Added rounded corners to photo section (left side)
* Fixed CSS specificity with !important to override Elementor/theme styles
* Improved Swiper conflict handling with duplicate handle check

= 1.2.0 =
* Replaced emojis and SVG icons with Font Awesome
* Improved CSS enqueuing and priority
* Fixed plugin structure for easier activation
* Minor UI and layout improvements

= 1.0.0 =
* Initial release
* Custom Post Type for volunteers
* Swiper.js slider with navigation and pagination
* Responsive card design
* Shortcode with configurable options
