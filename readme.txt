=== Custom Post View Generator ===

Contributors: Marco Constâncio
Tags: custom field, custom post, view
Requires at least: 3.0
Tested up to: 3.1.2
Stable Tag: trunk

Create views allowing the user to display of data of custom fields from custom post types without the any theme modification.

== Description ==

Create views allowing the user to display of data of custom fields from custom post types without the any theme modification.

The plugin allows administrator the ability to create views by simply selecting a custom post type, the custom fields and their respective types on the administration page and each time someone views a custom post from that type, the data from the custom fields will be displayed on the frontend.

This plugin was only tested with custom post types created by the following plugins: **Content Types** By Brian S. Reed (http://www.scottreeddesign.com/), **Custom Content Type Manager** By Everett Griffiths (http://code.google.com/p/wordpress-custom-content-type-manager/), **Ultimate Post Type Manager** by XYDAC (http://posttypemanager.wordpress.com/), so this it will only work in conjuction with one these plugins.

PLEASE READ THE Frequently Asked Questions FOR MORE DETAILED INFORMATION.

== Installation ==

1. Install the plugin either by uploading the contents of
custom-post-view-generator.zip to the '/wp-content/plugins/' directory
or by using the 'Add New' in 'Plugins' menu in WordPress
1. Activate the plugin through the 'Plugins' menu in WordPress

= Instructions =

BEFORE USING THIS PLUGIN YOU MUST CREATE CUSTOM POST TYPE USING ONE OF THE FOLLOWING PLUGINS:

* **Content Types** By Brian S. Reed (http://www.scottreeddesign.com/)
* **Custom Content Type Manager** By Everett Griffiths (http://code.google.com/p/wordpress-custom-content-type-manager/)
* **Ultimate Post Type Manager** by XYDAC (http://posttypemanager.wordpress.com/)

USAGE:

1. Go to 'Page Views' on the 'CTP View Generator'
1. Select a Custom Post Type in the top drop-down box **Post type** and drag the a desired field on **Available fields** area to the left side.
1. After dragging the field, a grey box will be displayed allowing to setup the display properties of that field. Write the desired label for that custom field, select the appropriate type (you can either test each one, or check the *Fields Info* section in **Other Notes** page), select available output options and press **Save Layout**.
1.  OPTIONAL: You can also change the field order by dragging the grey box(es) up and down and even select a diferent view template on the **Template** drop-down box and press **Save Layout** to save the changes.

== Screenshots ==

1. Frontent View.
2. Administration Page.


== Frequently Asked Questions ==

= How does this plugin works ? =

Without any theme modification, the only data that will be displayed, when viewing a custom post type, will be the post content (the content inserted in the post content editor) since the theme doesn't know existence of additional fields. So the plugin works by replacing the post content with the data of the selected custom fields in the administration panel (and the post content if the administrator selects it) and it does this by using a wordpress filter function (**add_filter**) making the changes on-the-fly without any file modification.

= So this will work with any custom post type/custom post fields ? =

No. The custom post fields can store information in endless ways and it is impossible automaticly determine the type of certain custom post field. There also endless ways to store information about existing custom post types, so it is also impossible determine the existing custom post type and its fields wihout adding the necessary code to extract that information. Currently this plugins only works with custom post types created by plugins specified in the description/installation pages.

= There are no custom post types in the 'Post Type' in the 'Post Views' page. Why ? =

Assuming that you created custom post types, most likelly you created those custom post types using other plugin other the ones that were recommended in the description/installation pages and if read the second answer on this F.A.Q. you will understand why. **NOTE FOR DEVELOPERS:** If you are familiar the custom post type plugin that you are using or you created the custom post type youself and know how extract the its information, you can extend this plugin support by creating the appropiate file in the 'pluginscode' folder. Use 'cpvg_plugincode_manual' file as a template, rename it and add the php extension.

= Is it possible change/add types options in the 'Post Views' page ? =

Yes. All the code relative to field types in this plugin are located in the 'fieldtypes' folder and can be easily changed. To create a new filetype you can use 'cvpg_fieldtype_manual' file as a template, rename it and add the php extension. The most simple filetype that you can analyse is the 'date' file type.

= Are you going to add suport for more custom post type plugins ? =

Depends. The custom post type plugins that this plugin supports are very complete, and my opinion, the best plugins for creating custom post types, so it might not be worth it to add more, especially since some of them might take too much work. If someone sends me code for 'plugincode' or 'fieldtypes' folders I most likelly add it and I might add support for other plugins but at the moment, I not thinking of adding support for more plugins.

= Is it possible change/add templates ? =

Yes. All the code relative to templates in this plugin are located in the 'templates' folder. To create a new template you can use 'template_manual' file as a template, rename it and add the php extension.

== Fields Info ==

When selecting wich custom fields are going to be displayed, you need to select the appropriate type. Here is a list of the custom fields created by the recomended plugins and their appropriate types in this plugin.

= Content Types By Brian S. Reed =

* **Checkboxes** - Mutiple Values (Serialized)
* **Single Line of Text** - Text
* **Mutiple Lines of Text** - Text
* **Date** - Date
* **Select** - Text
* **Select Multiple** - Mutiple Values (Vertical Bar)
* **Select an Image** - Image (WP Attachment)
* **Select a Color** - Color (Web)

= Custom Content Type Manager By Everett Griffiths =

* **Checkbox** - Boolean
* **Color Selector** - Color/Text (Depends on what the user Writes)
* **Date** - Text (The plugin saves the formated date when the post is saved.)
* **Dropdown** - Text
* **Image** - Image (WP Attachment)
* **Media Field** - Hiperlink (WP Attachment), Image (WP Attachment), Audio, Video
* **Multiselect** - Multiple Values (JSON)
* **Relation** - Hyperlink (WP Attachment)
* **Text** - Text
* **TextArea** - Text
* **Wysiwyg** - Text

= Ultimate Post Type Manager by XYDAC =

* **Image** - Single Image Url
* **Link** - Hiperlink (URL)
* **Text Area** - Text
* **Rich Text Area** - Text
* **Check Box** - Mutiple Values (Vertical Bar)
* **Gallery** - Multiple Image Urls (Comma)
* **Text** - Text
* **Radio Button** - Mutiple Values (Vertical Bar)
* **Combo Box** - Text

== Changelog ==

= 0.1 =
First version of the plugin.

= 0.1.1 =
Fixed issue wich resulted warnings in php versions before 5.3.

= 0.1.2 =
Fixed bug that result incompatibility with other plugins that are using the add_filter function.
Fixed issue wich resulted warnings with content types plugins.

= 0.1.3 =
Fixed bug that resulted in shortcode not being processed.
Fixed error in template.
Added another template.