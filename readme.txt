=== uCare - Support Ticket System ===
Contributors: smartcat
Donate link: https://smartcatdesign.net
Tags: support,ticket,helpdesk,system,email,customer service,agent,tech,technical,desk,help,support ticket,zendesk
Requires at least: 4.6
Tested up to: 4.8.1
Stable tag: 1.3.4
License: GPLv2 or later

If you have customers, then you need uCare. An intelligent support ticket help desk for your customers featuring usergroups,agents,ticket status,filtering,searching all in one responsive app. The most robust support ticket system for WordPress.

== Description ==

[Live demo](https://ucaresupport.com/demo/ "Demo")
Demo username: demoagent
Demo password: demoagent

uCare is the ultimate WordPress plugin to create a fully-featured support ticket help desk system for your business. It is integrated with WooCommerce and Easy Digital Downloads.
With unique user roles for your support admins, agents and customers, you can provide your customers with a unique and impressive support experience
which is aimed at increasing customser satisfaction. 

uCare allows you to work on customer support tickets directly from the backend or the frontend. You can view, manage and comment on tickets, as well as update ticket status directly from the WordPress
dashboard. Additionally, you and the support agents will be able to use the beautifully designed page to work on tickets.

This plugin creates it's own page that runs completely separate from your theme, meaning this is the only plugin that has zero impact on your
site speed, as none of the assets get loaded to your site, unless the user is on the help desk page. It also uses a tabbed-system for viewing tickets. Meaning, you can open multiple tickets and work on them at the same time.
The front-end is fully using Ajax, no loading, everything updates while you're on the page.

The frontend app is fully responsive, and is coded in a way to use as little resource as possible, ensuring it always runs super fast.

= Features =
- **Reporting:** A reports view allowing you to track your ticket activity and your agent productivity
- **Multiple users & groups:** Support Admin, Support Agent & Support User ( customer )
- **Works with ANY theme**. The support system app is 100% compatible with any theme, and runs separately from your theme files
- **Fast & Lightweight**. 100% Ajax-ified, loads data on the fly to prevent impacting your site speed
- **Ticket search & filter**
- **Ticket status & priority**
- Product & invoice number for E-commerce products
- **Assign tickets** to agents
- **Integrated with WooCommerce and Easy Digital Downloads**
- **Tabbed view:** Load and work on multiple tickets at a time
- **Frontend & Backend:** Work on tickets from the WP dashboard, or from the frontend app
- **Notification system:** Automated & customizable email notifications for customers and agents
- **Welcome screen** to first-time users. Your customers will find this easy to use and part of a great customer service.
- **Auto-close tickets**. If turned on, the plugin will automatically close tickets after X number of days of no customer interaction.
- **Event logging**. If turned on, the plugin will log all events so you can have a full view of everything that is happening in the system.

= Translation =
The plugin lists all translatable strings in /languages/ucare.pot file allowing you to translate the plugin into your language.
The following translations are currently bundled:
- Arabic
- English
- French ( Provided by [Philippe Chiorboli](https://www.guerni.fr/ "Philippe Chiorboli") )
If you have created a translation for this plugin, please contact us to get it bundled with the plugin.

== Screenshots ==

1. Help desk dashboard ( Ticket list )
2. Create ticket view
3. Single ticket view
4. Automated email notifications

== Installation ==
1. Download the plugin, then upload the zipped file to your site from the WordPress dashboard plugin uploader menu
2. OR - Upload the unpacked folder folder via FTP into /wp-content/plugins

== Frequently Asked Questions ==

= How do I access the support system ? =

uCare automatically creates a page called *Support*. You can access the help desk by going to yourwebsite.com/support. You can find this page in your page list.

= Where is the plugin documentation ? =

uCare documentation [can be accessed by clicking here] (https://ucaresupport.com/documentation/) We are constantly adding documentation to this plugin.

= Does uCare work with WordPress user roles ? =

Yes, uCare is programmed to work right away with WordPress default user roles, as well as WooCommerce and Easy Digital Downloads. This allows you to restrict access to the support system only to your customers, or leave it open for anyone to register. Your choice! 

= How can I add agents to my support system ? =

Adding agents is as simple as creating a new user in WordPress from Users - Add New. You can select one of the user roles Support Agent or Support Admin. [Click here for more details on this] (https://ucaresupport.com/kb/user-roles-capabilities/)

= What type of reporting is available with uCare ? =

The plugin comes with a reporting tool built right into your WordPress dashboard. This tool allows you to view how many tickets your support system receives daily/weekly/monthly or within a custom date range. The reporting also allows you to see your agent's productivity as well as who is solving the most tickets.

= Does this plugin support Email Piping ? =

Email piping is currently planned to be released in Q3 of 2017.

= Does this plugin have notifications for users and agents ? =

Yes, uCare is built with a notification system and templates for your customers and agents. Each notification can be customized fully from the Email Templates tab.

== Changelog ==

= 1.3.4 =
- Fix div being injected in the header 

= 1.3.3 =
- Bug fix for error occuring on un-install
- Logout redirect fix

= 1.3.2 =
- Fixed readme file structure

= 1.3.1 =
- Added French/Arabic translations

= 1.3.0 =
- Added extension licensing support
- Added public functions for registering extension licenses in core
- Added new email notifications for agents
    - Ticket Assigned
    - Customer Reply
- Added check for minimum PHP version 5.5
- Deprecated use of Components
- Moved Options under root namespace
- Added options to configure primary and secondary fonts
- New look for settings page
- Added new public functions for logging and developer mode
- Added open ticket count to WordPress admin bar
- Ticket response text input now auto expands when typing long replies
- Added support quick link widget to display on site
- Added new email notification to send to site admin when a ticket is created
- Fixed issue where quick editor would not update in WordPress admin
- Added ability to create categories for support tickets

= 1.2.1 =
- Added system log with level and tag filtering under reports view
- Tweaks and re-enabled cron that deletes abandoned stale tickets
- Added ability to filter out stale tickets from WordPress admin and front end
- Added filters to WordPress admin for ticket product and status
- Fixed permission issue preventing Email Templates and Support Ticket custom post types from being able to be deleted in bulk
- Prefixed plugin menu pages in WordPress admin
- Front end link back to WordPress admin now links to top level Support System menu page
- Colorized reports Y-axes to match their respective lines
- Added ability to disable system logging and clear existing log entries
- Added ability to set the maximum number of days before a ticket is marked as stale
- Fixed issue where multiple migrations would run at once
- Auto close tickets is now available

= 1.2.0 =
- Added reports menu page with overview of total tickets opened/closed and per agent totals
- Added cron job to mark tickets as stale after a set number of days and option to delete tickets 24 hours later
- Added new email and default template to notify users of ticket inactivity
- Added ability to filter unassigned tickets from front-end
- Fixed issue where updating a ticket overwrites its created date
- Fixed issue where password reset link would not work if permalinks are not set
- Prep for extensions support
- Restructured admin menu configuration
- Added branding to admin settings

= 1.1.2 =
- Fix issue with DB query showing up in the admin dashboard for some users

= 1.1.1 =
- Added confirmation for deleting images and comments
- Gravtar of assigned agent in ticket list
- Cross-browser CSS fixes
- Added tracking for ticket closing.
- Added Forgot Password option to Login screen

= 1.1.0 =
- Added ability to upload images to a ticket
- Updated notifications
- Responsive fixes to the app layout
- Added statistics bar
- Bug fixes
- Ability for Customer to close their own tickets
- Ability to upload custom image for the login page
- Statistics widget
- Customizable widget areas on the login screen & tickets list view

= 1.0.1 = Bug fixes
- Fixed scope issue which was causing conflicts with some plugins

= 1.0.0 = initial release

== Requirements == 
- PHP 5.6 or higher
