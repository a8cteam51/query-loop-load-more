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

## Changelog

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
