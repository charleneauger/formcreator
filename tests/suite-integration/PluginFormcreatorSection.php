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
 * @copyright Copyright © 2011 - 2020 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */
namespace tests\units;
use GlpiPlugin\Formcreator\Tests\CommonTestCase;

class PluginFormcreatorSection extends CommonTestCase {

   private $form = null;

   private $sectionData = null;

   public function beforeTestMethod($method) {
      parent::beforeTestMethod($method);

      switch ($method) {
         case 'testAdd':
         case 'testUpdate':
         case 'testDelete':
            $this->login('glpi', 'glpi');
            $this->form = new \PluginFormcreatorForm();
            $this->form->add([
               'entities_id'           => $_SESSION['glpiactive_entity'],
               'name'                  => $method . ' ' . $this->getUniqueString(),
               'description'           => 'form description',
               'content'               => 'a content',
               'is_active'             => 1,
               'validation_required'   => 0
            ]);
            break;

         case 'testDuplicate':
            $this->login('glpi', 'glpi');
            break;
      }
   }

   public function testAdd() {
      $instance = new \PluginFormcreatorSection();
      $instance->add([
         'plugin_formcreator_forms_id' => $this->form->getID(),
         'name'                        => $this->getUniqueString()
      ]);
      $this->boolean($instance->isNewItem())->isFalse();
   }

   public function testUpdate() {
      $instance = new \PluginFormcreatorSection();
      $instance->add([
         'plugin_formcreator_forms_id' => $this->form->getID(),
         'name'                        => $this->getUniqueString()
      ]);
      $this->boolean($instance->isNewItem())->isFalse();

      $success = $instance->update([
            'id'     => $instance->getID(),
            'name'   => 'section renamed'
      ]);
      $this->boolean($success)->isTrue();
   }

   public function testDelete() {
      $instance = new \PluginFormcreatorSection();
      $instance->add([
         'plugin_formcreator_forms_id' => $this->form->getID(),
         'name'                        => $this->getUniqueString()
      ]);
      $this->boolean($instance->isNewItem())->isFalse();

      $success = $instance->delete([
            'id' => $instance->getID()
      ], 1);
      $this->boolean($success)->isTrue();
   }
}
