<?php
/**
 *  FrontEndTools
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link https://www.kraetivan.dev
*/

class FrontEndTools extends WireData implements Module {

  public static function getModuleInfo() {
    return array(
      'title' => 'FrontEndTools',
      'version' => 100,
      'summary' => 'Tools for building front-ends, preprocessors, minifiers etc...',
      'icon' => 'css3',
      'author' => "Ivan Milincic",
      "href" => "https://kreativan.dev",
      'singular' => true,
      'autoload' => false
    );
  }

  /* =========================================================== 
    Compile Less
  =========================================================== */

  /**
   * Main less parser method
   * @param array $less_files - array of file paths
   * @param array $less_files -  array of less variables ["my_variable" => "100px"]
   * @param string $output_file - output file name
   */
  public function less($less_files, $variables = [], $output_file = "main") {
    $css_file_path = $this->config->paths->assets."less/{$output_file}.css";
    if($this->compiler == "1" || !file_exists($css_file_path)) {
      return $this->compileLess($less_files, $variables, $output_file);
    } else {
      return $this->config->urls->assets."less/{$output_file}.css";
    }
  }

  /**
   *  Parse Less
   *  @param array $less_files - array of less file paths
   *  @param array $variables - array of less variables ["my_variable" => "100px"]
   */
  public function compileLess($less_files, $variables = [], $output_file = "less") {

    // load less.php if it is not already loaded
    // a simple require_once does not work properly
    require_once("less.php/lib/Less/Autoloader.php");
    Less_Autoloader::register();

    // create less folder
    $output_dir = $this->config->paths->assets . "less/";
    if(!is_dir($output_dir)) $this->files->mkdir($output_dir);
    
    $output_file_name = "{$output_file}.css";
    $css_file_path = $output_dir . $output_file_name;
    $root_url = "http://" . $this->config->httpHost . $this->config->urls->root;
    $cache_folder = $this->config->paths->assets . "less-cache/";
    $cache_url = $this->config->urls->assets . "less-cache/";

    $less_array = [];
    foreach($less_files as $file) $less_array[$file] = $root_url;

    $options = [
      'cache_dir' => $cache_folder,
      'compress'=> true,
    ];

    $css_file_name = Less_Cache::Get($less_array, $options, $variables);
    $compiled = file_get_contents($cache_folder . $css_file_name);
    file_put_contents($css_file_path, $compiled);

    return $cache_url . $css_file_name;

  }

  /* =========================================================== 
    Sass Compiler
  =========================================================== */

  /**
   *  Trigger scss compile
   *  Put compiled string in a file
   *  return url to the compiled css file
   */
  public function scss($scss_dir = "", $source_file = "style.scss", $output_file = "main-scss") { 

    if($scss_dir == "") $scss_dir = $this->config->paths->templates . "scss/";
    $main_scss_path = $this->config->paths->assets . "scss/{$output_file}.css";
    $main_scss_url = $this->config->urls->assets . "scss/{$output_file}.css";

    // if compiler is off, just return css file url
    if($this->compiler != "1") return $main_scss_url."?{$this->last_compile_time}";

    if($this->needsCompile($scss_dir) || !file_exists($main_scss_path)) {
      $compiled_scss = $this->compileSCSS($scss_dir, $source_file);
      $this->files->filePutContents($main_scss_path, $compiled_scss);
      $this->saveModule($this, ["last_compile_time" => time()]);
    }

    return $main_scss_url."?{$this->last_compile_time}";

  }

  /**
   *  Compile scss file content and return css string
   *  @param string $folder - main scss folder
   *  @param string $file - main scss file name
   *  @return string
   */
  public function compileSCSS($scss_dir = "", $scss_file = "style.scss") {
    if($scss_dir == "") $scss_dir = $this->config->paths->templates . "scss/";
    $scss_file_path = "{$scss_dir}{$scss_file}";
    if (!file_exists($scss_file_path)) return false;
    require_once("scss.php/scss.inc.php");
    $scss = new \ScssPhp\ScssPhp\Compiler();
    $scss->addImportPath($scss_dir);
    $scss_string = file_get_contents($scss_file_path);
    $compiled_scss = $scss->compile($scss_string);
    return $this->minifyCSS($compiled_scss);
  }

  /**
   * Check if there is a file chananges
   * in specified dir and subdirs
   * @param string $folder
   * @return bool
   */
  public function needsCompile($folder) {
    $root_files = glob($folder. "*");
    foreach($root_files as $file) {
      if(is_dir($file)) {
        $files = glob($file."/*");
        foreach($files as $f) {
          $last_time = $this->last_compile_time;
          $this_time = filemtime($f);
          if($this_time > $last_time) return true;
        }
      } elseif ($file != "." && $file != "..") {
        $last_time = $this->last_compile_time;
        $this_time = filemtime($file);
        if($this_time > $last_time) return true;
      }
    }
    return false;
  }

  /* =========================================================== 
    Minify
  =========================================================== */

  /**
   *  Minify CSS
   *  @param $css_string
   *  @return string
   */
  public function minifyCSS($css_string) {
    require_once("minify/src/Minify.php");
    require_once("minify/src/Converter.php");
    require_once("minify/src/CSS.php");
    $minifier = new \MatthiasMullie\Minify\CSS();
    $minifier->add($css_string);
    return $minifier->minify();
  }


}
