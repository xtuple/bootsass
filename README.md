BootSASS theme
==============

BootSASS theme is a Drupal theme that based on usage of Compass and Bootstrap 3. It's based on BaseStrap theme and so requires Bootstrap 3 module.

Directory structure
-------------------

+ `css` — contains CSS built from SASS; content of this folder is excluded from Git as basically it's a "cache" and should be rebuilt on every SASS change
+ `fonts` — keeps custom fonts; it's recommended to keep each font (with all its styles) in the own directory;
+ `fonts/bootstrap` — keeps default Bootstrap fonts (Glyphicons);
+ `html` — keeps original html files with markup;
+ `img` — keeps all images; as all „plugins“ images are kept in subdirectories (e. g. `img/social-menu`); it's recommended to keep project images in `img/project` directory;
+ `js` — keeps Javascripts;
+ `js/bootstrap` — keeps original Bootstrap JS; this is helpful for standalone markups (`.html`) files, but shouldn't be attached to the theme (into `.info` file) as it's responsibility of Drupal to control correct versions of JS;
+ `sass` — keeps SASS files (see below files recommended structure);
+ `sass/mixins` — helpers mixins that can be useful;
+ `tpl` — keeps template files for Drupal;
+ `bootsass.info` — info file for Drupal theme;
+ `config.rb` — Compass configuration file;
+ `template.php` — Drupal theme function override file;
+ `README.md` — documentation file

SASS structure
--------------

`bootsass.screen.sass` imports all SASS files into one file and compiles into `bootsass.screen.css`. By default it imports files:

+ `params/_bootstrap.sass` — default parameters for Bootstrap (shouldn't be changed);
+ `params/_bootsass.sass` — keeps parameters overrides for Bootstrap and other parameters for exact project;
+ `bootstrap` — Bootstrap 3 itself; all parameters should be ready at this moment;
+ `common/_base.sass` — (recommended) keeps tags styles;
+ `common/_layout.sass` — (recommended) keeps layout styles; 
+ `common/_forms.sass` — (recommended) keeps forms styles;
+ `common/_bootstrap.sass` — (recommended) keeps Boostrap classes styles (overrides and extensions);
+ `common/_styles.sass` — keeps all other styles that can't be clearly categorised;

Also recommended create and import own SASS file for each content type (`ct_`) feature (e. g. `ct/_faq.sass`)

Vendors
-------

+ [Bootstrap](http://getbootstrap.com), v3.1.1
+ [SASS](http://sass-lang.org), v3.3.2
+ [Compass](http://compass-style.org), v0.12.3
