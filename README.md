# FrontEndTools ProcessWire Module
This module provides some useful tools for frontend development:
- Less compiler
- SCSS compiler
- CSS minifier

## Less
```php
// Get the module instance
$FrontEndTools = $modules->get("FrontEndTools");

// Set less files array
$less_files = ["one.less","two.less"];

// Set less variables
$less_vars = ["global-primary-background" => "blue"];

// Set the options (optional)
$options = ["output_file" => "main", "cache_folder" => "less-cache"];

// Compile less files and get the cache or the compiled file url
$less = $FrontEndTools->less($less_files, $less_vars, $options);

// echo <link> tag in <head> section
echo "<link rel='stylesheet' type='text/css' href='$less'>";
```

## SCSS
```php
// Get the module instance
$FrontEndTools = $modules->get("FrontEndTools");

// directory name relative to the templates folder that contains all scss files
// module will watch this folder for changes and recompile the css file
$scss_dir = "scss/";

// main scss file
// only this file will be passed to the compiler, all other files have to be included in the mail file
$source_file_name = "main.scss";

// Output file name, will be generated in /site/asses/scss/
$output_file = "main-scss";

// Get compiled css file url
$scss = $FrontEndTools->scss($scss_dir, $source_file_name, $output_file_name);

// echo <link> tag in <head> section
echo "<link rel='stylesheet' type='text/css' href='$scss'>";
```