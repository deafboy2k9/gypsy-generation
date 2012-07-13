=== BP Xtra Signup ===
Contributors: travel-junkie
Donate link: http://shabushabu.eu/donation/
Tags: buddypress, registration, eula, newsletter, tos, username, check, ajax, password, strength
Requires at least: WP 3.0, BP 1.2.6
Tested up to: WP 3.0.1, BP 1.3bleeding
Stable tag: 1.6

Adds extra signup options to the registration page of a BuddyPress installation.

== Description ==

This plugin lets you add a Terms of Service checkbox and, optionally, a Mailchimp signup checkbox to your BuddyPress registration page.
Additionally, an ajax username availability check, a password strength meter, email check and date of birth check can be activated.

== Installation ==

1. Upload the files to wp-content/plugins/bp-xtra-signup

2. Activate the plugin in your admin backend

4. Go to BuddyPress->BP Xtra Signup and change the options to your liking

That's it ... enjoy!

== Frequently Asked Questions ==

= What about feature requests? =

You can register on our [support site](http://shabushabu.eu/membership-options/) and leave a comment in our support forums.

= But you charge for support, right? =

Yes, we do. It's not a lot, though, and you can obviously download the plugin for free.

== Screenshots ==

1. Admin Panel
2. Register Page

== Changelog ==

= v1.0 =
* initial release

= v1.1 =
* Changed: TOS display is optional now
* Added: slight code improvements

= v1.2 =
* NEW: Optional email confirmation on registration (THX to Francesco Laffi)
* Bugfix: Newsletter selection is repaired

= v1.3 =
* NEW: Ajax username availability (THX to Brajesh Singh)
* NEW: Password strength meter
* Changed: Newsletter checkbox is unchecked by default due to legal issues in certain countries

= v1.3.1 =
* NEW: Alternative username suggestion if desired username is taken already
* Added: Support for WP MS when checking for usernames

= v1.4 =
* NEW: Date of Birth check on registration
* Added: Various filters and hooks
* Added: Some admin UX enhancements

= v1.5 =
* NEW: Ajax email check
* NEW: Comparison of email values
* NEW: JS files get concatinated
* Added: Minified/dev js and css files

= v1.5.1 =
* Bugfix: IE Compatibility by substituting JSON.parse with jQuery.parseJSON (THX James Smith)

= v1.5.2 =
* Changed: TOS link and TOS text option have been combined

= v1.5.3 =
* Bugfix: Admin help has been fixed

= v1.5.4 =
* Bugfix: Fixed problems when translating the plugin

= v1.6 =
* General housekeeping

== Upgrade Notice ==

= v1.5.2 =
Go to the plugin settings page and manually add the link to your TOS to the 'Checkbox Text' option

= v1.5 =
Go to the plugin settings page to activate the ajax email check

= v1.4 =
Go to the plugin settings page to activate the date of birth check

= v1.3 =
Go to the plugin settings page and activate the password strength meter and ajax username availability if needed

= v1.2 =
Visit the plugin admin page to activate email confirmation

= v1.0 =
Slight code improvements over the beta version