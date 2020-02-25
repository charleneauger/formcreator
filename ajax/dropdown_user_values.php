<?php
/**
 * ---------------------------------------------------------------------
 * Formcreator is a plugin which allows creation of custom forms of
 * easy access.
 * ---------------------------------------------------------------------
 * LICENSE
 *
 * This file is part of Formcreator.
 *
 * Formcreator is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Formcreator. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 * @copyright Copyright © 2011 - 2019 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */

include ('../../../inc/includes.php');

Session::checkRight('entity', UPDATE);

if (isset($_REQUEST['dropdown_itemtype']) && $_REQUEST['dropdown_itemtype'] == "User") {
    $itemtype = $_REQUEST['dropdown_itemtype'];

    $question = new PluginFormcreatorQuestion();
    $question->getFromDB((int) $_REQUEST['id']);
    if (!empty($question->fields['values'])) {
        $entityValue = json_decode($question->fields['values'], JSON_OBJECT_AS_ARRAY);
        $profilValue = json_decode($question->fields['values'], JSON_OBJECT_AS_ARRAY);
        $groupValue = json_decode($question->fields['values'], JSON_OBJECT_AS_ARRAY);
    } else {
        $entityValue = 0;
        $profilValue = 0;
        $groupValue = 0;
    }

    // Build the row content
    $rand = mt_rand();

    $additions['entity'] = Dropdown::show("Entity", [
        'name'  => 'show_user_entity',
        'rand'  => $rand,
        'value' => $entityValue['show_user_entity'],
        'display' => false,
    ]);

    $additions['profile'] = Dropdown::show("Profile", [
        'name'  => 'show_user_profile',
        'rand'  => $rand,
        'value' => $entityValue['show_user_profile'],
        'display' => false,
    ]);

    $additions['group'] = Dropdown::show("Group", [
        'name'  => 'show_user_group',
        'rand'  => $rand,
        'value' => $entityValue['show_user_group'],
        'display' => false,
    ]);

    echo json_encode($additions);
}