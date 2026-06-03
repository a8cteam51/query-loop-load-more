=== Query Loop Load More ===
Contributors: automattic, wpspecialprojects, tommusrhodus, npagazani, geoffguillain, tiagonoronha, nateallen, glynnquelch, dhansondesigns, mlaetitia, fmfernandes, robrobsn, kimclow
Tags: block editor, query loop, gutenberg, full-site-editing, load more
Requires at least: 6.2
Tested up to: 6.9
Stable tag: 1.0.18
Requires PHP: 8.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Short Description: Adds a load more option to the Query Loop Pagination block, allowing users to load more posts without a page refresh.

== Description ==

This WordPress plugin adds a load more option to the Query Loop Pagination block in Gutenberg, allowing users to load more posts without refreshing the page. It replaces the traditional `Previous` `Next` and numbered pagination, with a customizable, `Load More` button.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/query-loop-load-more` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.

== Instructions ==

To use this plugin, you must first add the Query Loop block into your post content, then add the Pagination block inside the Query Loop. When working with the Pagination block, you will see new options to enable load more on the block.
The load more option allows you to set a loading text parameter and also the button text. The load more button works with the alignment options and arrow options of the pagination block.
Here is how to enable the load more option:

1. Add the Query Loop block to your post content.
2. Inside the Query Loop block, add the Pagination block.
3. In the Pagination block settings, enable the "Load More" option.
4. Set the loading text parameter and the button text.
5. Customize the alignment and arrow options if desired.

That's it! Your visitors can now load more posts by clicking the load more button without refreshing the page.

== Customization Options ==

- **Alignment**: Use standard block editor settings to set the alignment of the load more button
- **Button**: Choose the button option for users to click to load more posts
- **Button Text**: Customize the load more button text
- **Loading Text**: Customize the text for the post loading state
- **Infinite Scroll**: Choose this option to allow new posts to load automatically when users reach the bottom of the feed
- **Color**: Customize the color of the load more button and the loading state icon when using the infinite scroll option
- **Update URL**: Choose this option to update the browser's URL when loading more posts


== Frequently Asked Questions ==

**How can I download the plugin file?**

You can download the file from the [WordPress.org plugin library here.](https://wordpress.org/plugins/query-loop-load-more/)

**Where and how can I submit a bug report or feature request?**

- You can share your feedback by creating an issue on our [public repo here](https://github.com/a8cteam51/query-loop-load-more/issues/new/choose). We can’t guarantee the turnaround time for bug fixes, but we can guarantee that all issues will be triaged.
- The plugin is open source, so feel free to work on your desired functionality changes and create a PR with the fix.

**How do I edit the text of the block?**

- In the block settings, you can replace the default text `Load More` with whatever text you’d like.

**How do I change the color?**

- In the current version, the button background color pulls from your theme’s button color.

== Screenshots ==

1. **Block Editor - Load More button**
2. **Block Editor - Loading state animation**
3. **Frontend - Posts loading with button**
4. **Frontend - Infinite scroll post**


== Changelog ==

= 1.0.18 =
* Fix - Updated composer.json PHP requirement from 8.3 to 8.0 to match plugin requirements

= 1.0.17 =
* Fix - potential issue infinite scroll not triggering if button is at the bottom of the viewport
* Fix - Page parameter for inherited queries
* Fix - Page parameter for custom query page when not set
* Fix - Keep current URL parameters when fetching new posts and updating the location URL
* Update - Changed event listener of manual load more buttons to be attached to the document instead of each individual button

= 1.0.16 =
* Fix - Makes query loops QueryID agnostic and fixes multiple loops in a page where one exhausts before the other.

= 1.0.15 =
* Optimisation - Assets only load on pages using a pagination block.
* Fix - Query loops now correctly use QueryID, allows supporting multiple queries on the page.

= 1.0.14 =
* Fix - Fixed posts not loading on infinite scroll if site has no footer.

= 1.0.13 =
* Fix - Fixed link without text when inifnite scroll is enabled.

= 1.0.12 =
* Fix - Fixed the query pages comparison check.

= 1.0.11 =
* Fix - Fixed warning about _load_textdomain_just_in_time.
* ADDED - Javascript event document.qllmLoadStart when load more button is clicked.
* ADDED - Javascript event document.qllmLoadEnd when loading is completed.
* FIX - The load more button now respects the max number of pages on a query if a limit was manually set.

= 1.0.10 =
* Fix - Fixes load more button wrapping.

= 1.0.9 =
* Fix - Loading more posts for query blocks with no or zero as the queryId
* Fix - Keeping original button content after loading more posts. Fixes pagination arrow

= 1.0.8 =
* Fix - Only use intersection observer if infinite loading setting is active
* Fix - Use global query to get maximum amount of pages if query is set to inherit

= 1.0.7 =
* Fix - Loading posts ( multiple query blocks, loading posts from correct query block )
* Fix - URL related issues ( new setting for updating the URL, update paging parameter without changing base URL )
* Update - Using only one button, including infinite scroll
* Update - Changed default behavior - not updating the URL by default - added a setting to re-enable
* Update - Build scripts

= 1.0.6 =
* Update - Add loading class when in loading state

= 1.0.5 =
* Fix - Infinite scroll would duplicate some posts due to the same AJAX call being executed twice

= 1.0.4 =
* Update - Version

= 1.0.3 =
* Fix - Loading more posts on "Inherit query from template"

= 1.0.2 =
* Add - SVN deployment workflow
* Update - Version and Stable tag

= 1.0.1 =
* Fix - Unify GPL license
* Fix - Unify text domain and plugin slug

= 1.0.0 =
Initial release.
