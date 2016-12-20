<?php
 /**
  * This file contains the definition for the library class for ide submission plugin
  *
  * This class provides all the funtionality for the new assignment module
  */
 defined('MOODLE_INTERNAL') || die();

 define('ASSIGNSUBMISSION_IDE_FILEAREA', 'submissions_ide');

 class assign_submission_ide extends assign_submission_plugin {


     public function get_name()
     {
         return get_string('ide', 'assignsubmission_ide');
     }


     public function get_settings(MoodleQuickForm $mform)
     {

         $name = get_string('langrestrict', 'assignsubmission_ide');
         //add a language restriction setting that can be enabled or disabled
         $langs = $this->get_languages();
         $langgroup = array();
         $langgroup[] = $mform->createElement('select', 'assignsubmission_ide_langrestrict', '', $langs);
         $langgroup[] = $mform->createElement('checkbox', 'assignsubmission_ide_langrestrict_enable',
              '', get_string('enable'));

         $mform->addGroup($langgroup, 'assignsubmission_ide_lang_group', $name, ' ', false);
         $mform->addHelpButton('assignsubmission_ide_lang_group', 'langrestrict', 'assignsubmission_ide');
         $mform->disabledIf('assignsubmission_ide_langrestrict',
             'assignsubmission_ide_langrestrict_enable', 'notchecked');
     }

     public function save_settings(stdClass $data)
     {
         if(isset($data->assignsubmission_ide_langrestrict)) {
             if (in_array($data->assignsubmission_ide_langrestrict, $this->get_languages())) {
                 $this->set_config('lang', $data->assignsubmission_ide_langrestrict);
                 $this->set_config('restricted', true);
             }
         }
         return true;
     }

     public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data)
     {
         //add a new HTML QuickForm element type for this form
         $mform->registerElementType('ide', 'HTML/QuickForm/ide.php', 'HTML_QuickForm_ide');

         if(!isset($data->text)){
             $data->text = '';
         }

         if(!isset($data->lang)){
             $data->lang = 'java';
         }

         /*if($submission){
             $ide = $this->get_ide_submission($submission->id);
             if($ide){
                 $data->text = $ide->text;
                 $data->lang = $ide->lang;
             }
         }*/
         $idegroup = array();
         $idegroup['button'] = $mform->createElement('button', 'ide_run', 'Run!', null);
         if($this->get_config('restricted')) {
             $idegroup['editor'] = $mform->createElement('ide', 'IDE', $this->get_config('lang'), null);
             $data->lang = $this->get_config('lang');
         }else{
             $idegroup['editor'] = $mform->createElement('ide', 'IDE', $data->lang, null);
         }

         $mform->addGroup($idegroup, 'assignsubmission_ide_editorgroup', $data->lang, ' ', false);

         if(!$this->get_config('restricted')) {
             $mform->addElement('select', 'ide_lang', 'Language', $this->get_languages());
         }

         $mform->addElement('static', 'ide_script', '<script src="http://localhost/moodle/mod/assign/submission/ide/codeLink.js"></script>');


         return true;
     }

     public function save(stdClass $submission, stdClass $data){
         global $DB;

         $idesubmission = $this->get_ide_submission($submission->id);

         if($idesubmission){
             $idesubmission->text = $data->text;
             $idesubmission->lang = $data->lang;
             $updatestatus = $DB->update_record('assignsubmission_ide', $idesubmission);
             return $updatestatus;
         } else{
             $idesubmission = new stdClass();
             $idesubmission->text = $data->text;
             $idesubmission->lang = $data->lang;
             $idesubmission->submission = $submission->id;
             $idesubmission->assignment = $this->assignment->get_instance()->id;
             $idesubmission->id = $DB->insert_record('assignsubmission_ide', $idesubmission);
             return $idesubmission>0;
         }
     }

     public function get_editor_text($name, $submissionid)
     {
         if($name==='ide'){
             $idesubmission = $this->get_ide_submission($submissionid);
             if($idesubmission){
                 return $idesubmission->text;
             }
         }


         return '';
     }
     public function get_editor_format($name, $submissionid)
     {
         return editors_get_preferred_format();
     }

     public function view_summary(stdClass $submission, & $showviewlink)
     {

         $idesubmission = $this->get_ide_submission($submission->id);

         $text = $this->assignment->render_editor_content(ASSIGNSUBMISSION_IDE_FILEAREA,
                                                          $idesubmission->submission,
                                                          $this->get_type(),
                                                          'ide',
                                                          'assignsubmission_ide');
         $shorttext = shorten_text($text, 140);
         return $shorttext;

     }
     public function get_files($submission)
     {
         $result = array();
         $fs = get_file_storage();

         $files = $fs->get_area_files($this->assignment->get_context()->id,
             'assignsubmission_ide',
             ASSIGNSUBMISSION_IDE_FILEAREA,
             $submission->id,
             'timemodified',
             false);

         foreach ($files as $file) {
             $result[$file->get_filename()] = $file;
         }
         return $result;
     }


     public function view(stdClass $submission)
     {
         $result = '';

         $onlinetextsubmission = $this->get_ide_submission($submission->id);

         if ($onlinetextsubmission) {

             // Render for portfolio API.
             $result .= $this->assignment->render_editor_content(ASSIGNSUBMISSION_ONLINETEXT_FILEAREA,
                 $onlinetextsubmission->submission,
                 $this->get_type(),
                 'onlinetext',
                 'assignsubmission_onlinetext');
         }
         return $result;
     }

     public function delete_instance()
     {
         global $DB;
         //will throw exception on failure
         $DB->delete_records('assignsubmission_ide', array('assignment'=> $this->assignment->get_instance()->id));
     }

     public function get_languages()
     {
         return array(
             'java',
             'javascript',
             'php',
             'haskell',
             'c++',
             'objective_c',
             'c#',
         );
     }

     private function get_ide_submission($submissionid)
     {
         global $DB;
         if($DB->record_exists('assignsubmission_ide', array('submission' => $submissionid))) {
             return $DB->get_record('assignsubmission_ide', array('submission' => $submissionid));
         }else{
             return null;
         }

     }

     public function get_edit_options()
     {
         $editoroptions = array(
             'noclean' => false,
             'maxfiles' => EDITOR_UNLIMITED_FILES,
             'maxbytes' => $this->assignment->get_course()->maxbytes,
             'context' => $this->assignment->get_context(),
             'return_types' => FILE_INTERNAL || FILE_EXTERNAL
         );
         return $editoroptions;
     }

 }