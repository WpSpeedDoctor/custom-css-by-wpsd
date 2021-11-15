# Custom CSS loader by WP Speed Doctor

The Custom CSS loader by WP Speed Doctor is here to assist you with building faster and more lightweight websites.

The most common mistake when WordPress users are adjusting CSS on their website is to add CSS into the Appearance/Additional CSS menu, theme options box for CSS or to single one CSS stylesheet in the child theme. That makes unnecessary bloat, loads all CSS on all pages regardless it's needed or not. It's an old-style creating CSS coming back to beginnings when speed wasn't measured and wasn't taken into account.

If you want to have top speed on mobile devices you have to load the least amount of styles on a given page. Why? Because CSS ( and even more JS ) have to be processed every time and on low-tier mobiles with slower CPU that will take significantly more rendering time. Just processing could be even 2-3 seconds longer than on top-tier mobiles or desktop computers. The best practice is to have one style that has all styles that are general for all website like styles for header, footer, header, body text, etc., and on templates and individual pages load styles that are related to a given template or page.

This plugin is here to make separate CSS files that load only on pages or pages using a separate template.

Installation:
```
1. upload and activate the plugin
2. Use the admin bar menu "Custom CSS" to create a separate CSS file for the website.
```
Once the plugin is active you can see a new menu in the admin bar (only for admin users) you can see what options are available for the given page you're currently on. All new CSS files will be created in ```/wp-content/uploads/custom-css-by-wpsd``` folder.

These files you can edit with plugins like ACEIDE or File Manager. Just place your CSS code there and it will be automatically loaded on corresponding pages.

Individual pages can have loaded a separate CSS file as a unique request or can be inlined. The inlined option is there if you need to adjust something very small, under 1kb of code. If it's larger, I recommend using a file instead of inline. As well inline CSS can be used to add CSS Critical Path.

For debugging purposes, I have included a debugging window. It will display the type and file path of files that have been loaded. If files for a given page or template wasn't created, it will show the text 'Not present'.

It's easy to come back and recognise in what file your CSS is stored. Files start with the type (inline, page, template, cpt) and then when applicable the page ID, for example, page-1.css, woocommerce-product-category.css, template-blue-background.css.  

The plugin supports Woocommerce and offers to create a general template for Woocommerce and separate Woocommerce related pages like a product, cart, checkout or product gallery.

The priority of styles are the following:
```
1. Inline
2. Page
3. Page template/ CPT template / WooCommerce template
4. Woocommerce Global
5. Global CSS
```
For example, if you have template CSS loaded and then you add individual page CSS, styles in the individual CSS file will have higher priority than template CSS styles. No more using "!important"!

Here is the video on how it works and how to use it. (video update needed, made for older version, still valid for latest version)

[![Watch the video](https://i.ytimg.com/vi_webp/1gxJ1xweiXc/maxresdefault.webp)](https://www.youtube.com/watch?v=1gxJ1xweiXc)


# Move plugin into the child theme!
In Appearance/Custom CSS loader by WP Speed Doctor menu is short description on how to copy plugin into the child theme and run it as part of the child theme. The only disadvantage is that you need to update manually after every update by copying the plugin's folder into the child theme.

Any feedback or improvement tips are welcome on email pixtweaks at protonmail.com

I hope it will make your life easier and your website faster to load on slow mobile devices. Enjoy!
