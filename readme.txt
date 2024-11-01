=== wpDirectory ===
Contributors: obus3000
Tags: categories, articles, directory, list, business, link directory, collection, ajax
Requires at least: 2.5 (previously not tested)
Tested up to: 2.9
Stable Tag: 1.0

A clean structured list of categories, which can be easily customized with CSS. Suitable for articles, links, business directory, etc.

== Description ==

wpDirectory can show a structured list of categories on a page (home or any), easily customizable via CSS and localized compatible. This plugin can also modify the admin interface for posting articles or listings in the directory, for both contributors and admins in order to gain efficiency when posting entries.

Features:

* Category description in link title.
* Show/Hide Subcategories
* Show/Hide Amount of articles in parent and child categories.
* Show/Hide empty categories.
* Display categories on specified amount of colums with automatic reordering adjusting to user\'s resolution.
* Use hierarchy for subcategories.
* Exclude selected categories.

Special features:

* `div` powered listing, so design can be easily modified using CSS,a s you need. The parent category is inserted in a `<div>` container to mark it out with CSS as a parent.
* Parent category is showing amount of articles in subcategories. The character also include the number of articles in this parent category.
* Display the specified amount of child categories (2nd level).
* Exclude child categories articles from the parent categories archive pages.
* You can add icons for parent categories using CSS.
* User can access to article's source code.

Future Features:

* Ability to show all subcategories in listing powered by ajax.
* Show domain stats and relevant information about domain (for link directory)
* Integration with TDO miniforms

Admin interface options:

* Can prevent the listing have more than one category
* Show warning to author, if not selected any category or if selected more than one category.
* Hide unnecessary blocks from the "Write/Edit Post" page, if this is not the site administrator. It is "Comments & Pings", "Excerpt", "Password Protect" and other blocks. I.e. actually there is only a form of adding the article and the button to send it to moderation.
* Specific height of categories block on "Write/Edit Post" page.
* Custom "Terms of item publication" on "Write/Edit Post" page.

== Installation ==

1. Copy the `wpdirectory` folder in WordPress plugins directory (`/wp-content/plugins/`).
2. Copy the `categories.css` file in directory of your current THEME.
3. Activate the plugin through admin interface.
4. You can call the categories listing on any page throught this function:

        `<?php wp_directory(); ?>`

Add the following code in the `index.php` (or another file) of your THEME:

	`<?php if (function_exists('wp_directory')) wp_directory(); ?>`

5. Add the following code in the `style.css`:

	`@import 'categories.css';`

Remember to add according permissions, so you can edit this file through the Theme Editor

6. Options are optmized, however you can customize them in in admin interface at the "Options -> wpDirectory" page.

The plugin also lets you to display the list with the links to categories RSS feeds. To do this, you must:

* Create a [new page template](http://codex.wordpress.org/Pages#Creating_Your_Own_Page_Templates).
* Add the following code:

	`<?php $rssfeeds=true; ?>`
	`<?php if (function_exists('wp_directory')) wp_directory(); ?>`

* Create a new page in the admin interface and select the created template.

== Version history ==

Version history and list of changes you can see [here](http://arielbustillos.com/wpdirectory-wordpress-plugin/#version-history).