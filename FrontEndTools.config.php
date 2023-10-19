<?php

/**
 *  FrontEndTools Config
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://www.kraetivan.dev
 */

class FrontEndToolsConfig extends ModuleConfig {

  public function getInputfields() {
    $inputfields = parent::getInputfields();

    $this_module = $this->modules->get("FrontEndTools");

    $wrapper = new InputfieldWrapper();

    // --------------------------------------------------------- 
    // Compilers 
    // --------------------------------------------------------- 
    $compiler_set = $this->wire('modules')->get("InputfieldFieldset");
    $compiler_set->label = "Compiler";
    $wrapper->add($compiler_set);

    $f = $this->wire('modules')->get("InputfieldRadios");
    $f->attr('name', 'compiler');
    $f->label = 'Enable Less/SCSS Compiler';
    $f->options = array('1' => "Yes", '2' => "No");
    $f->required = true;
    $f->defaultValue = "1";
    $f->optionColumns = 1;
    $f->columnWidth = "100%";
    $f->description = "If enabled, will automatically detect changes and compile.";
    $compiler_set->add($f);

    $f = $this->wire('modules')->get("InputfieldText");
    $f->attr('name', 'last_compile_time');
    $f->label = 'Last Compile Time';
    $f->columnWidth = "100%";
    $f->collapsed = "8";
    $f->description = "User to detect scss changes";
    $compiler_set->add($f);

    $html = '
        <h3 class="uk-text-bold">Less</h3>
        <div class="uk-form-label">Get the module instance</div>
        <code>$FrontEndTools = $modules->get("FrontEndTools");</code>

        <div class="uk-form-label">Set less files array </div>
        <code>$less_files = ["one.less","two.less"];</code>

        <div class="uk-form-label">Set less variables</div>
        <code>$less_vars = ["global-primary-background" => "blue"];</code>

        <div class="uk-form-label">Set the options (optional)</div>
        <code> $options = ["output_file" => "main", "cache_folder" => "less-cache"];</code>

        <div class="uk-form-label">Add link tag to your website head section</div>
        <code>' . htmlspecialchars('<link rel="stylesheet" type="text/css" href="<?= $FrontEndTools->less($less_files, $less_vars, $options); ?>">') . '</code>

        <h3 class="uk-text-bold">SCSS</h3>
        <code>' . htmlspecialchars('<link rel="stylesheet" type="text/css" href="<?= $FrontEndTools->scss($scss_dir, $source_file_name, $output_file_name); ?>">') . '</code>
      ';
    $f = $this->wire('modules')->get("InputfieldMarkup");
    $f->attr('name', 'compilers_markup');
    $f->label = 'How to use';
    $f->value = $html;
    $f->columnWidth = "80%";
    $f->collapsed = "8";
    $compiler_set->add($f);

    // Add Set
    $inputfields->add($compiler_set);


    // --------------------------------------------------------- 
    // Uikit 
    // --------------------------------------------------------- 

    // Get Less Files
    $get_uikit_less_files = glob($this_module->path() . "uikit/src/less/components/[!_]*.less");
    $uikit_less_files = [];
    foreach ($get_uikit_less_files as $file) {
      $file_name = basename($file);
      $uikit_less_files[$file_name] = $file_name;
    }

    // Get uikit JS Files
    $get_uikit_js_files =  glob($this_module->path() . "uikit/dist/js/components/[!.]*.js");
    $uikit_js_files = [];
    foreach ($get_uikit_js_files as $file) {
      $file_name = basename($file);
      if (substr($file_name, -7) === ".min.js") {
        $uikit_js_files[$file_name] = $file_name;
      }
    }


    // Uikit SET
    $uikit_set = $this->wire('modules')->get("InputfieldFieldset");
    $uikit_set->label = "UIkit";
    $wrapper->add($uikit_set);

    $uikit_how_to = '
      <code>$FrontEndTools = $modules->get("FrontEndTools");</code> <br />
      Render uikit preload styles tags <br />
      <code>$FrontEndTools->uikit_preload();</code> <br />
      Render uikit styles tags <br />
      <code>$FrontEndTools->uikit_stylesheet($less_files = [], $less_variables = [], $options = []);</code> <br />
      Render uikit scripts tags <br />
      <code>$FrontEndTools->uikit_scripts();</code>
    ';

    /**
     * Uikit
     */
    $f = $this->wire('modules')->get("InputfieldRadios");
    $f->attr('name', 'uikit');
    $f->label = 'UIkit Theme';
    $f->options = array('custom' => "Custom", 'theme' => "Theme");
    $f->required = true;
    $f->defaultValue = "custom";
    $f->optionColumns = 1;
    $f->columnWidth = "100%";
    $f->description = 'Load custom uikit components of theme.';
    $uikit_set->add($f);

    /**
     * UIkit Less Files
     */
    $f = $this->wire('modules')->get("InputfieldCheckboxes");
    $f->attr('name', 'uikit_less_files');
    $f->label = 'Less Files';
    $f->description = 'Select uikit less components to include.';
    $f->options = $uikit_less_files;
    $f->optionColumns = 1;
    $f->optionWidth = "200px";
    $f->showIf = "uikit=custom";
    $uikit_set->add($f);

    /**
     * UIkit JS Files
     */
    $f = $this->wire('modules')->get("InputfieldCheckboxes");
    $f->attr('name', 'uikit_js_files');
    $f->label = 'Uikit JS';
    $f->description = 'Uikit js components to include.';
    $f->options = $uikit_js_files;
    $f->optionColumns = 1;
    $f->optionWidth = "200px";
    $f->showIf = "uikit=custom";
    $uikit_set->add($f);

    $f = $this->wire('modules')->get("InputfieldMarkup");
    $f->attr('name', 'uikit_how_to');
    $f->label = 'How to';
    $f->value = $uikit_how_to;
    $f->columnWidth = "80%";
    $f->collapsed = "8";
    $uikit_set->add($f);


    // Add Set
    $inputfields->add($uikit_set);


    // Return 
    // ========================================================= 
    return $inputfields;
  }
}
