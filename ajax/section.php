<?php
include ('../../../inc/includes.php');

Session::checkRight("config", "w");

if(empty($_REQUEST['section_id'])) {
   $section_id    = 0;
   $name          = '';
} else {
   $section_id    = (int) $_REQUEST['section_id'];
   $section       = new PluginFormcreatorSection();
   $section->getFromDB($section_id);
   $name          = $section->fields['name'];
}

echo '<form name="form_section" method="post" action="' . $GLOBALS['CFG_GLPI']['root_doc'] . '/plugins/formcreator/front/section.form.php">';
echo '<table class="tab_cadre_fixe">';
echo '<tr><th colspan="2">';
if(0 == $section_id) {
   echo  __('Add a section', 'formcreator');
} else {
   echo  __('Edit a section', 'formcreator');
}
echo '</th></tr>';
echo '<tr class="line0">';
echo '<td width="20%">' . __('Title', 'formcreator') . ' <span style="color:red;">*</span></td>';
echo '<td width="70%"><input type="text" name="name" style="width:100%;" value="' . $name . '"></td>';
echo '<tr class="line1">';
echo '<td colspan="2" class="center">';
echo '<input type="hidden" name="id" value="' . $section_id . '" />';
echo '<input type="reset" name="reset" class="submit_button" onclick="resetAll()"
         value="' . __('Cancel', 'formcreator') . '" /> &nbsp; ';
echo '<input type="hidden" name="plugin_formcreator_forms_id" value="' . (int) $_REQUEST['form_id'] . '" />';
if(0 == $section_id) {
   echo '<input type="submit" name="add" class="submit_button" value="' . __('Add') . '" />';
} else {
   echo '<input type="submit" name="update" class="submit_button" value="' . __('Edit') . '" />';
}
echo '</td>';
echo '</tr>';
echo '</table>';
Html::closeForm();
