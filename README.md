### Assets:

Assets is a assets manager for PHP.

### Getting Started:

* Create composer.json file in root directory of your application:

```json
{
    "require": {
        "php": ">=5.4.0",
        "nanjingboy/assets": "*"
    },
    "config": {
        "bin-dir": "bin/"
    }
}
```
* Install it via [composer](https://getcomposer.org/doc/00-intro.md)

### Dependencies:

If you don't use coffeeScript, sass or less, you don't have to install coffeeScript, sass or less compiler.

* [CoffeeScript](http://coffeescript.org/)
* [Sass](http://sass-lang.com/)
* [Less](http://lesscss.org/)
* [uglifyjs](https://github.com/mishoo/UglifyJS2)
* [uglifycss](https://github.com/fmarcia/UglifyCSS)

### Config:
```php
<?php
return array(
    'path' => array(
        'serverRoot' => string,
        'javascripts' => string, // relative to serverRoot path
        'stylesheets' => string, // relative to serverRoot path
        'images' => string, // relative to serverRoot path
        'fonts' => string // relative to serverRoot path
    ),
    'precompile' => array() // the file's extension must be js or css, and it's relative to root javascripts(stylesheets) path
);
```

### Api:

* [image-url](https://github.com/nanjingboy/assets/blob/master/test/app/assets/stylesheets/home.scss#L23)
* [font-url](https://github.com/nanjingboy/assets/blob/master/test/app/assets/stylesheets/home.scss#L17)
* [javascript_include_tag](https://github.com/nanjingboy/assets/blob/master/assets.php#L4)
* [stylesheet_include_tag](https://github.com/nanjingboy/assets/blob/master/assets.php#L21)

### Console Line:

```shell
$ bin/assets

assets version 0.1.0

Usage:
  [options] command [arguments]

Options:
  --help           -h Display this help message.
  --quiet          -q Do not output any message.
  --verbose        -v|vv|vvv Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug.
  --version        -V Display this application version.
  --ansi              Force ANSI output.
  --no-ansi           Disable ANSI output.
  --no-interaction -n Do not ask any interactive question.

Available commands:
  clean        Remove old compiled assets
  help         Displays help for a command
  list         Lists commands
  precompile   Compile all the assets named in $config["precompile"]["files"]
```


### Example:

Get a example from [test](https://github.com/nanjingboy/assets/tree/master/test)

### License:
MIT