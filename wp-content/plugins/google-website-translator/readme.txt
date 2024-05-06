=== Prisna GWT - Google Website Translator  ===
Contributors: Prisna
Tags: translate wordpress, multilingual, translate, translation, google translate, bilingual, automatic translate, google website translator, google translator, google language translator, language translate, language translator, multi language, translate, translation
Requires PHP: 5.6
Stable tag: 1.4.9
Requires at least: 3.3
Tested up to: 6.4
License: GPL2+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily translate your WordPress site into 100+ languages to make it multilingual. A simple and complete multilingual solution for WordPress.

== Description ==

This plugin brings the power of Google's automatic translation service to translate your website into 100+ languages. A simple and complete multilingual solution for WordPress.

= Features: =
* Easy set up, including simple settings with inline help that everybody can understand.
* Include all the [3 inline](http://www.prisna.net/images/google-website-translator/inline-mode.png) and all the [4 tabbed](http://www.prisna.net/images/google-website-translator/tabbed-mode.png) styles.
* Select the available languages.
* Use it as a widget and as a shortcode.
* Practically null impact on page loads.
* Import/Export settings to easily transfer configurations.
* For feature requests, please [contact us](https://www.prisna.net/contact-us/).

= Support: =
* Create a ticket here in the WordPress support forum: [https://wordpress.org/support/plugin/google-website-translator](https://wordpress.org/support/plugin/google-website-translator)
* Or you can also ask for assistance directly from our website: [https://www.prisna.net/contact-us/](https://www.prisna.net/contact-us/)

= Advantages over similar plugins: =
There are a number of Google translation plugins in this great repository. However, most of them haven't been carefully built. Here's a list of reasons of why you should choose this plugin:

* All the settings are stored in only one record in the database; whilst other plugins use lots of records.
* It's the fastest as it doesn't load any external file; whilst other plugins load unnecessary javascript and css files.
* It includes all the options of the original [Google Website Translator](https://translate.google.com/manager/website/); whilst other plugins offer just a restricted version.
* Prisna Ltd. is a real company ([register record](https://www.prisna.net/images/prisna-limited.pdf)), with professional people working on it. 

== Installation ==

1. Go to the WordPress admin panel.
1. Click *Plugins*, then *Add New*, then *Upload Plugin*.
1. Select the downloaded zip file, install it and activate it.
1. A new entry will appear under the *Plugins* main menu: *Prisna GWT*.
1. Set options at will.
1. Either go to the *Widgets* admin page, or use the shortcode on your pages, posts, categories and any other WordPress resource.

== Frequently Asked Questions ==

= What are the supported languages? =
Afrikaans, Albanian, Amharic, Arabic, Armenian, Assamese, Aymara, Azerbaijani, Bambara, Basque, Belarusian, Bengali, Bhojpuri, Bosnian, Bulgarian, Burmese, Catalan, Cebuano, Chichewa, Chinese Simplified, Chinese Traditional, Corsican, Croatian, Czech, Danish, Dhivehi, Dogri, Dutch, English, Esperanto, Estonian, Ewe, Filipino, Finnish, French, Frisian, Galician, Georgian, German, Greek, Guarani, Gujarati, Haitian Creole, Hausa, Hawaiian, Hebrew, Hindi, Hmong, Hungarian, Icelandic, Igbo, Ilocano, Indonesian, Irish, Italian, Japanese, Javanese, Kannada, Kazakh, Khmer, Kinyarwanda, Konkani, Korean, Krio, Kurdish, Kyrgyz, Lao, Latin, Latvian, Lingala, Lithuanian, Luganda, Luxembourgish, Macedonian, Maithili, Malagasy, Malay, Malayalam, Maltese, Maori, Marathi, Meiteilon, Mizo, Mongolian, Nepali, Norwegian, Odia, Oromo, Pashto, Persian, Polish, Portuguese, Punjabi, Quechua, Romanian, Russian, Samoan, Sanskrit, Scots Gaelic, Sepedi, Serbian, Sesotho, Shona, Sindhi, Sinhala, Slovak, Slovenian, Somali, Sorani, Spanish, Sundanese, Swahili, Swedish, Tajik, Tamil, Tatar, Telugu, Thai, Tigrinya, Tsonga, Turkish, Turkmen, Twi, Ukrainian, Urdu, Uyghur, Uzbek, Vietnamese, Welsh, Xhosa, Yiddish, Yoruba and Zulu.

= How can I exclude some parts from being translated? =
You should go to:

Advanced &gt; General &gt; Exclude selector (jQuery)

Enter a *jQuery* selector to specify the area(s) you'd like to exclude from translation.

Alternatively, you can add the *notranslate* style class to the container HTML element. Or if you want to exclude just a piece of text, you should wrap it within a HTML element with the *notranslate* style class, for instance:

&lt;span class=&quot;notranslate&quot;&gt;Company name&lt;/span&gt;

= I have a lot of plugins, will this plugin slow down my website? =
This plugin has a lot of options for you to customize it in the best possible way. All these options are saved in only one database record (yes, only one!). So you can rest assured it won't slow down your website. Sometimes your website slows down when you use more and more plugins, because most likely they haven't been built carefully. If you have some understanding of code, you can search for the *add_option* function within your plugins files to check on this matter.

= Google translate is not free, do I need to pay any money to use this plugin? =
No, you don't need to pay anything. Even though Google translate isn't free anymore, Google has created a free translation widget for everybody to use. This plugin brings you that widget for you to easily use it in your WordPress powered website.

= What kind of support do you provide? =
Support includes:

* Responding to questions or problems regarding the plugin and its features.
* Fixing bugs and reported issues.
* Providing updates to ensure compatibility with new WordPress versions.

== Screenshots ==

1. General admin panel (inline).
2. General admin panel (tabbed).
3. Advanced admin panel.
4. Import/Export admin panel.
5. Go premium panel.

== Changelog ==

= 1.5 =

* 29 new languages: Assamese, Aymara, Bambara, Bhojpuri, Dhivehi, Dogri, Ewe, Guarani, Ilocano, Kinyarwanda, Konkani, Krio, Lingala, Luganda, Maithili, Meiteilon, Mizo, Odia, Oromo, Quechua, Sanskrit, Sepedi, Sorani, Tatar, Tigrinya, Tsonga, Turkmen, Twi and Uyghur.

= 1.4 =

* 13 new languages: Amharic, Corsican, Frisian, Hawaiian, Kurdish, Kyrgyz, Luxembourgish, Pashto, Samoan, Scots Gaelic, Shona, Sindhi and Xhosa.

= 1.3 =

* Ability to define a jQuery selector to exclude elements from translation.

= 1.2 =

* Ability to define a new javascript function: on before load.
* Ability to define a new javascript function: on after load.

= 1.1 =

* 29 new languages: Armenian, Bengali, Bosnian, Burmese, Cebuano, Chichewa, Hausa, Hmong, Igbo, Javanese, Kazakh, Khmer, Lao, Malagasy, Malayalam, Maltese, Maori, Marathi, Mongolian, Nepali, Punjabi, Sesotho, Sinhala, Somali, Tajik, Uzbek, Yoruba and Zulu.
* Combined flags into one single image file.
* Higher quality flags.

= 1.0 =

== Upgrade Notice ==

= 1.0 =
* Initial release.
