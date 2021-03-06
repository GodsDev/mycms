# MYCMSPROJECTSPECIFIC
XYZ web
(Folder *dist* is an instant seed of new project.)

# Stack

Linux, Apache (mod_rewrite, mod_header, ssl...)
PHP 7.0
MySQL
PHP libraries
- xml
- mbstring
- mysql

```sh
apt install libapache2-mod-php7.0 apache2 mysql-server git composer php-xml php-mbstring php7.0-mysql
```

Git flow (master,develop,feature,release,fix,hotfix)


# Content



# Web analytics

Gtag version may be used only after https://github.com/googleanalytics/autotrack is updated to work with it.
I.e. probably when it will be out of beta.
script/autotrack.V.V.V.js and script/autotrack.V.V.V.js.map are manually taken from current (v2.4.1) https://github.com/googleanalytics/autotrack repository.

@todo - as GA events

* Production: UA-XYZ
* Test: UA-39642385-1

# MyCMS dist deployment
* Folder `/dist` contains initial *distribution* files for a new project using MyCMS, therefore copy it to your new project folder.
* Replace the string `mycmsprojectnamespace` with your project namespace.
* Replace the string `MYCMSPROJECTSPECIFIC` with other website specific information (Brand, Twitter address, phone number, database name, name of icon in manifest.json etc.).
* Default *admin.php* credentials are *john* / *Ew7Ri561*   - MUST be deleted after the real admin account is set up.
* Change `define('MYCMS_SECRET', 'u7-r!!T7.&&7y6ru');` //16-byte random string, unique per project in `conf/config.php`
* Delete this section after the changes above are made

# Deployment

Create database with `Collation=utf8_general_ci`

Create `conf/config.local.php` based on `config.local.dist.php` including the name of the database created above

Create `phinx.yml` based on `phinx.dist.yml` including the name of the database created above

Under construction mode may be turned on (for non admin IP adresses i.e. not in `$debugIpArray`) by adding
```php
define('UNDER_CONSTRUCTION', true);
```
to `conf/config.local.php`. 

Note: Management má iPhone a Mac - testovat na Apple prostředí!

## Ad firewall

If the web will be running behind firewall hence REMOTE_ADDR would contain only firewall IP, then the original REMOTE_ADDR should be passed in another HTTP header, e.g. CLIENT_IP.
So that trusted IPs for debugging may be used.
For this deployment scenarion only (because otherwise it would be a vulnerability) uncomment `isset($_SERVER['HTTP_CLIENT_IP']) ? in_array($_SERVER['HTTP_CLIENT_IP'], $debugIpArray) :` line in `index.php` and `api\*\index.php`.


## `build.sh` runs the following commands

`composer update`

Note: All changes in database (structure) SHOULD be made by phinx migrations. Create your local `phinx.yml` as a copy of `phinx.dist.yml` to make it work, where you set your database connection into *development* section. 
```bash
vendor/bin/phinx migrate -e development # or production or testing
```

## reCAPTCHA

Paste this snippet at the end of the <form> where you want the reCAPTCHA widget to appear:
```html
<div class="g-recaptcha" data-sitekey="................"></div>
```

# CMS notes

## Agenda
Agenda is an item in the admin.php left menu that refers to a set of rows in database. (All tables can be also accessed from the bottom of the page.)

Examples of settings:
```php
$AGENDAS = array(
    'division' => array('column' => 'division_' . $tmp),
    'page' => array('table' => 'content', 'where' => 'type="page"', 'column' => "\0CONCAT(code,'|',page_$tmp)"),
    'news' => array('table' => 'content', 'where' => 'type="news"', 'column' => 'content_' . $tmp, 'prefill' => array('type' => 'news')),
    'slide' => array('table' => 'content', 'where' => 'type="slide"', 'column' => 'content_' . $tmp, 'prefill' => array('type' => 'event')),
    'event' => array('table' => 'content', 'where' => 'type="event"', 'column' => "\0CONCAT(page_$tmp,'|',content_$tmp)", 'prefill' => array('type' => 'event')),
);

$AGENDAS = array(
    'category' => array('path' => 'path'),
    'press' => array('table' => 'content', 'where' => 'type="press"', 'prefill' => array('type' => 'press')),
    'testimonial' => array('table' => 'content', 'where' => 'type="testimonial"', 'column' => 'description_' . DEFAULT_LANGUAGE, 'prefill' => array('type' => 'testimonial')),
    'claim' => array('table' => 'content', 'where' => 'type="claim"', 'column' => 'description_' . DEFAULT_LANGUAGE, 'prefill' => array('type' => 'claim')),
    'perex' => array('table' => 'content', 'where' => 'type="perex"', 'column' => 'description_' . DEFAULT_LANGUAGE, 'prefill' => array('type' => 'perex'))
);

$AGENDAS = [
    'item' => [
        'column' => 'name',  # Name of column where the value is displayed from
        'where' => 'active="1"' # Filter on displayed columns
    ],
    'consumption' => [
        'column' => 'created',
        'where' => 'active="1"'
    ],
    'amount' => [
        'column' => 'created',
        'where' => 'active="1"'
    ],
];
```
(TODO: explain more examples.)



## Asset folder structure
* `assets/career/` - pro média spojené s pracovními příležitostmi
* `assets/news/` - pro obrázky novinek
* `assets/products/` - pro obrázky produktů
* `assets/product-sheet-cs/` - pro CS verze PDF produktů
* `assets/product-sheet-en/` - pro EN verze PDF produktů
* `assets/section-bg/` - background of some product sections
* `assets/testimonials/` - logos of companies with testimonial
* `assets/videos/` - videos
* `assets/slides/` - pro slidy
* `assets/references/` - logos of companies with reference
* `images` - other miscelaneous images (logos, page headers, etc.)

Note: assets expects only ONE sub-level.

### admin.php expects
* scripts\bootstrap.js
* scripts\admin-specific.js
* scripts\ie10-viewport-bug-workaround.js
* scripts\summernote.js
* styles\bootstrap.css
* styles\bootstrap-datetimepicker.css
* styles\font-awesome.css
* styles\ie10-viewport-bug-workaround.css
* styles\summernote.css
* fonts\fa*.*

## Admin UI
Add protected functions to Admin.php according to MyAdmin.php in order to add menu relevant for the application, such as Translations, FriendlyURL, Divisions and products, etc.

# Debugging

```php
// to write out a variable to `Tracy`
\Tracy\Debugger::barDump($mixedVar, 'Info why to show it');

// to log a variable value into its error level log
\Tracy\Debugger::log($stringVar, \Tracy\ILogger::DEBUG); // Note: \Tracy\ILogger::DEBUG equals 'debug'

// to throw an exception
throw new \Exception('Exception description');
```

`$debugIpArray` in `config.php` contains IPs for Tracy.

## REST API

Note: header("Content-type: application/json"); in outputJSON hides Tracy

# Error handling

If your IP is among `$debugIpArray` you will see an Exception on screen. Otherwise, you will get "nice" Tracy 500 Internal server error.

Logs are in folder `log`:
* `exception.log` contains fatal errors by Tracy\Debugger
* `error.log` contains recoverable erros by Tracy\Debugger
* `debug.log` contains debug info by Tracy\Debugger
* `backyard-error.log.YYYY-MM.log` by PSR-3 logger implemented in GodsDev\Backyard\BackyardError
* `sqlYYYY-MM-DD.sql` contains content changes by CMS as SQL statements with timestamp

# Templating

`latte` templates are used.


# Visual style

Pages have view-TEMPLATE class in <body/> to allow for exceptions.

Convert SASS to CSS by
```sh
sass styles/index.sass styles/index.css  # made also by build.sh
```

When changing index.css, index.js or admin.js, update `PAGE_RESOURCE_VERSION` in `config.php` in order to force cache reload these resources.

# TODO

## TODO lokalizace


## TODO CMS


## TODO SEO


## TODO vizualizace


## TODO other
* 1812 až friendlyUrl součástí MyCMS, tak v .latte zrušit {dirname($_SERVER['SCRIPT_NAME'])} a dát místo toho applicationDir
* 190611 add article and search page types including controller tests
* 190611 Make SASS to CSS conversion automatic (e.g. gulp)
