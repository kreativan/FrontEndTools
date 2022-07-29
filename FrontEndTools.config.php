<?php
/**
 *  FrontEndTools Config
 *  @author Ivan Milincic <kreativan.dev@gmail.com>
 *  @link http://www.kraetivan.dev
*/

class FrontEndToolsConfig extends ModuleConfig {

	public function getInputfields() {
		$inputfields = parent::getInputfields();

		$wrapper = new InputfieldWrapper();

    //  Compilers
    // ===========================================================

    $compiler_set = $this->wire('modules')->get("InputfieldFieldset");
		$compiler_set->label = "Compiler";
		$wrapper->add($compiler_set);

      $f = $this->wire('modules')->get("InputfieldRadios");
      $f->attr('name', 'compiler');
      $f->label = 'Enable Less/SCSS Compiler';
      $f->options = array('1' => "Yes",'2' => "No");
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
      $compiler_set->add($f);

      $html = '
        <code>$FrontEndTools = $modules->get("FrontEndTools");</code><br /> 
        <code>'.htmlspecialchars('<link rel="stylesheet" type="text/css" href="<?= $FrontEndTools->less($less_files_array, $less_vars, "main-css"); ?>">').'</code>
        <br />
        <code>'.htmlspecialchars('<link rel="stylesheet" type="text/css" href="<?= $FrontEndTools->scss($scss_dir, $source_file_name, $output_file_name); ?>">').'</code>
      ';
      $f = $this->wire('modules')->get("InputfieldMarkup");
      $f->attr('name', 'compilers_markup');
      $f->label = 'How to use';
      $f->value = $html;
      $f->columnWidth = "80%";
      $f->collapsed = "8";
      $compiler_set->add($f);

    $inputfields->add($compiler_set);

		// render fields
		return $inputfields;


	}

}
