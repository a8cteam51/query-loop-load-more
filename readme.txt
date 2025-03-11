=== Query Loop Load More ===
Contributors: wpspecialprojects, tommusrhodus
Tags: gutenberg, editor, block editor, load more, query loop
Requires at least: 6.2
Tested up to: 6.7.2
Stable tag: 1.0.7
Requires PHP: 8.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds a load more option to the Query Loop Pagination block in Gutenberg, allowing users to load more posts without refreshing the page.

== Description ==

Adds a load more option to the Query Loop Pagination block in Gutenberg, allowing users to load more posts without refreshing the page.

To use this plugin, you must first add the Query Loop block into your post content, then add the Pagination block inside the Query Loop. When working with the Pagination block, you will see new options to enable load more on the block.

The load more option allows you to set a loading text parameter and also the button text. The load more button works with the alignment options and arrow options of the pagination block.

Here is how to enable the load more option:

* Add the Query Loop block to your post content.
* Inside the Query Loop block, add the Pagination block.
* In the Pagination block settings, enable the "Load More" option.
* Set the loading text parameter and the button text.
* Customize the alignment and arrow options if desired.
* That's it! Your visitors can now load more posts by clicking the load more button without refreshing the page.

== Changelog ==

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
