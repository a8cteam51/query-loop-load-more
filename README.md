# Query Loop Load More WordPress Plugin

> [!NOTE]  
> You can [download the latest version here](https://github.com/a8cteam51/query-loop-load-more/releases/latest/download/query-loop-load-more.zip)

This WordPress plugin adds a load more option to the Query Loop Pagination block in Gutenberg, allowing users to load more posts without refreshing the page.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/query-loop-load-more` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.

## Usage

To use this plugin, you must first add the Query Loop block into your post content, then add the Pagination block inside the Query Loop. When working with the Pagination block, you will see new options to enable load more on the block.

The load more option allows you to set a loading text parameter and also the button text. The load more button works with the alignment options and arrow options of the pagination block. 

Here is how to enable the load more option:

1. Add the Query Loop block to your post content.
2. Inside the Query Loop block, add the Pagination block.
3. In the Pagination block settings, enable the "Load More" option.
4. Set the loading text parameter and the button text.
5. Customize the alignment and arrow options if desired.

That's it! Your visitors can now load more posts by clicking the load more button without refreshing the page.

## Customization Options

- **Alignment**: Use standard block editor settings to set the alignment of the load more button
- **Button**: Choose the button option for users to click to load more posts
- **Button Text**: Customize the load more button text
- **Loading Text**: Customize the text for the post loading state
- **Infinite Scroll**: Choose this option to allow new posts to load automatically when users reach the bottom of the feed
- **Color**: Customize the color of the load more button and the loading state icon when using the infinite scroll option
- **Update URL**: Choose this option to update the browser's URL when loading more posts

## Changelog

### 1.0.17
* Fix - Select the proper paged parameter for inherited queries.
* Fix - Keep current URL parameters when fetching new posts and updating the location URL.

### 1.0.16
* Fix - Makes query loops QueryID agnostic and fixes multiple loops in a page where one exhausts before the other.

### 1.0.15
* Optimisation - Assets only load on pages using a pagination block.
* Fix - Query loops now correctly use QueryID, allows supporting multiple queries on the page.

### 1.0.14
* Fix - Fixed posts not loading on infinite scroll if site has no footer.

### 1.0.13
* Fix - Fixed link without text when inifnite scroll is enabled.

### 1.0.12
* Fix - Fixed the query pages comparison check.

### 1.0.11
* Fix - Fixed warning about _load_textdomain_just_in_time.
* ADDED - Javascript event document.qllmLoadStart when load more button is clicked.
* ADDED - Javascript event document.qllmLoadEnd when loading is completed.
* FIX - The load more button now respects the max number of pages on a query if a limit was manually set.

### 1.0.10
* Fix - Fixes load more button wrapping.

### 1.0.9
* Fix - Loading more posts for query blocks with no or zero as the queryId
* Fix - Keeping original button content after loading more posts.

### 1.0.8
* Fix - Only use intersection observer if infinite loading setting is active
* Fix - Use global query to get maximum amount of pages if query is set to inherit

### 1.0.7
* Fix - Loading posts ( multiple query blocks, loading posts from correct post and query block )
* Fix - URL related issues ( new setting for updating the URL, update paging parameter without changing base URL )
* Update - New markup, Using only one button, including for infinite scroll
* Update - Changed default behavior - not updating the URL by default - added a setting to re-enable
* Update - Added build scripts for assets

### 1.0.6
* Update - Add loading class when in loading state

### 1.0.5
* Fix - Infinite scroll would duplicate some posts due to the same AJAX call being executed twice

### 1.0.4
* Update - Version

### 1.0.3
* Fix - Loading more posts on "Inherit query from template" 

### 1.0.2
* Add - SVN deployment workflow
* Update - Version and Stable tag

### 1.0.1
* Fix - Unify GPL license
* Fix - Unify text domain and plugin slug

### 1.0.0
* Initial release.
