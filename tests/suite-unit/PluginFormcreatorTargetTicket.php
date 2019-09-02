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

namespace tests\units;
use GlpiPlugin\Formcreator\Tests\CommonTestCase;
use GlpiPlugin\Formcreator\Tests\PluginFormcreatorTargetTicketDummy;

class PluginFormcreatorTargetTicket extends CommonTestCase {

   public function beforeTestMethod($method) {
      parent::beforeTestMethod($method);
      switch ($method) {
         case 'testSetTargetEntity':
         case 'testSetTargetCategory':
            $this->boolean($this->login('glpi', 'glpi'))->isTrue();
            break;
      }
   }

   public function providerGetTypeName() {
      return [
         [
            'input' => 0,
            'expected' => 'Target tickets',
         ],
         [
            'input' => 1,
            'expected' => 'Target ticket',
         ],
         [
            'input' => 2,
            'expected' => 'Target tickets',
         ],
      ];
   }

   /**
    * @dataProvider providerGetTypeName
    * @param integer $number
    * @param string $expected
    */
   public function testGetTypeName($number, $expected) {
      $output = \PluginFormcreatorTargetTicket::getTypeName($number);
      $this->string($output)->isEqualTo($expected);
   }

   public function testGetEnumDestinationEntity() {
      $output = \PluginFormcreatorTargetTicket::getEnumDestinationEntity();
      $this->array($output)->isEqualTo([
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_CURRENT      => 'Current active entity',
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_REQUESTER  => "Default requester user's entity",
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_REQUESTER_DYN_FIRST    => "First dynamic requester user's entity (alphabetical)",
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_REQUESTER_DYN_LAST      => "Last dynamic requester user's entity (alphabetical)",
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_FORM  => 'The form entity',
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_VALIDATOR    => 'Default entity of the validator',
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_SPECIFIC      => 'Specific entity',
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_USER  => 'Default entity of a user type question answer',
         \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_ENTITY    => 'From a GLPI object > Entity type question answer',
      ]);
   }

   public function testGetEnumTagType() {
      $output = \PluginFormcreatorTargetTicket::getEnumTagType();
      $this->array($output)->isEqualTo([
         \PluginFormcreatorTargetTicket::TAG_TYPE_NONE                   => __('None'),
         \PluginFormcreatorTargetTicket::TAG_TYPE_QUESTIONS              => __('Tags from questions', 'formcreator'),
         \PluginFormcreatorTargetTicket::TAG_TYPE_SPECIFICS              => __('Specific tags', 'formcreator'),
         \PluginFormcreatorTargetTicket::TAG_TYPE_QUESTIONS_AND_SPECIFIC => __('Tags from questions and specific tags', 'formcreator'),
         \PluginFormcreatorTargetTicket::TAG_TYPE_QUESTIONS_OR_SPECIFIC  => __('Tags from questions or specific tags', 'formcreator')
      ]);
   }

   public function testGetEnumDateType() {
      $output = \PluginFormcreatorTargetTicket::getEnumDueDateRule();
      $this->array($output)->isEqualTo([
         \PluginFormcreatorTargetTicket::DUE_DATE_RULE_ANSWER => __('equals to the answer to the question', 'formcreator'),
         \PluginFormcreatorTargetTicket::DUE_DATE_RULE_TICKET => __('calculated from the ticket creation date', 'formcreator'),
         \PluginFormcreatorTargetTicket::DUE_DATE_RULE_CALC => __('calculated from the answer to the question', 'formcreator'),
      ]);
   }

   public function testGetEnumLocationType() {
      $output = \PluginFormcreatorTargetTicket::getEnumLocationRule();
      $this->array($output)->isEqualTo([
         \PluginFormcreatorTargetTicket::LOCATION_RULE_NONE      => __('Location from template or none', 'formcreator'),
         \PluginFormcreatorTargetTicket::LOCATION_RULE_SPECIFIC  => __('Specific location', 'formcreator'),
         \PluginFormcreatorTargetTicket::LOCATION_RULE_ANSWER    => __('Equals to the answer to the question', 'formcreator'),
      ]);
   }

   public function testGetEnumUrgencyRule() {
      $output = \PluginFormcreatorTargetTicket::getEnumUrgencyRule();
      $this->array($output)->isEqualTo([
         \PluginFormcreatorTargetTicket::URGENCY_RULE_NONE      => 'Urgency from template or Medium',
         \PluginFormcreatorTargetTicket::URGENCY_RULE_SPECIFIC  => 'Specific urgency',
         \PluginFormcreatorTargetTicket::URGENCY_RULE_ANSWER    => 'Equals to the answer to the question',
      ]);
   }

   public function testGetEnumAssociateRule() {
      $output = \PluginFormcreatorTargetTicket::getEnumAssociateRule();
      $this->array($output)->isEqualTo([
         \PluginFormcreatorTargetTicket::ASSOCIATE_RULE_NONE      => 'None',
         \PluginFormcreatorTargetTicket::ASSOCIATE_RULE_SPECIFIC  => 'Specific asset',
         \PluginFormcreatorTargetTicket::ASSOCIATE_RULE_ANSWER    => 'Equals to the answer to the question',
      ]);
   }

   public function testGetEnumCategoryRule() {
      $output = \PluginFormcreatorTargetTicket::getEnumCategoryRule();
      $this->array($output)->isEqualTo([
         \PluginFormcreatorTargetTicket::CATEGORY_RULE_NONE      => 'Category from template or none',
         \PluginFormcreatorTargetTicket::CATEGORY_RULE_SPECIFIC  => 'Specific category',
         \PluginFormcreatorTargetTicket::CATEGORY_RULE_ANSWER    => 'Equals to the answer to the question',
      ]);
   }

   public function testGetItem_User() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicGetItem_User();
      $this->object($output)->isInstanceOf(\Ticket_User::class);
      $this->boolean($output->isNewItem())->isTrue();
   }

   public function testGetItem_Group() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicGetItem_Group();
      $this->object($output)->isInstanceOf(\Group_Ticket::class);
      $this->boolean($output->isNewItem())->isTrue();
   }

   public function testGetItem_Supplier() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicGetItem_Supplier();
      $this->object($output)->isInstanceOf(\Supplier_Ticket::class);
      $this->boolean($output->isNewItem())->isTrue();
   }

   public function testGetItem_Item() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicGetItem_Item();
      $this->object($output)->isInstanceOf(\Item_Ticket::class);
      $this->boolean($output->isNewItem())->isTrue();
   }

   public function testGetItem_Actor() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicGetItem_Actor();
      $this->object($output)->isInstanceOf(\PluginFormcreatorTargetTicket_Actor::class);
      $this->boolean($output->isNewItem())->isTrue();
   }

   public function testGetCategoryFilter() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicGetCategoryFilter();
      $this->array($output)->isEqualTo([
         'OR' => [
            'is_request'  => 1,
            'is_incident' => 1
         ]
      ]);
   }

   public function testGetTaggableFields() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicGetTaggableFields();
      $this->array($output)->isEqualTo([
         'name',
         'content',
      ]);
   }

   public function testGetTargetItemtypeName() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicGetTargetItemtypeName();
      $this->string($output)->isEqualTo(\Ticket::class);
   }

   /**
    * Tests that deleting a target ticket of a form also deletes relations between tickets and generated tickets
    *
    * @covers PluginFormcreatorTargetTicket::pre_deleteItem
    */
   public function testDeleteLinkedTickets() {
      global $CFG_GLPI;

      // Disable notification to avoid output to console
      $CFG_GLPI['use_notifications'] = '0';

      // setup the test
      $ticket = new \Ticket();
      $ticket->add([
         'name'               => 'ticket',
         'content'            => 'help !',
         'users_id_recipient' => '0',
      ]);
      $this->boolean($ticket->isNewItem())->isFalse();

      $form = new \PluginFormcreatorForm();
      $formFk = \PluginFormcreatorForm::getForeignKeyField();
      $form->add([
         'name' => 'a form'
      ]);
      $this->boolean($form->isNewItem())->isFalse();

      $targetTicket_1 = new \PluginFormcreatorTargetTicket();
      $targetTicket_1->add([
         'name'      => 'target 1',
         $formFk     => $form->getID(),
      ]);
      $this->boolean($targetTicket_1->isNewItem())->isFalse();

      $targetTicket_2 = new \PluginFormcreatorTargetTicket();
      $targetTicket_2->add([
         'name'      => 'target 2',
         $formFk     => $form->getID(),
      ]);
      $this->boolean($targetTicket_2->isNewItem())->isFalse();

      $targetTicketFk = \PluginFormcreatorTargetTicket::getForeignKeyField();
      $item_targetticket_1 = new \PluginFormcreatorItem_TargetTicket();
      $item_targetticket_1->add([
         $targetTicketFk   => $targetTicket_1->getID(),
         'link'            => \Ticket_Ticket::LINK_TO,
         'itemtype'        => \Ticket::class,
         'items_id'        => $ticket->getID(),
      ]);
      $this->boolean($item_targetticket_1->isNewItem())->isFalse();

      $item_targetticket_2 = new \PluginFormcreatorItem_TargetTicket();
      $item_targetticket_2->add([
         $targetTicketFk   => $targetTicket_1->getID(),
         'link'            => \Ticket_Ticket::LINK_TO,
         'itemtype'        => \PluginFormcreatorTargetTicket::class,
         'items_id'        => $targetTicket_2->getID(),
      ]);
      $this->boolean($item_targetticket_2->isNewItem())->isFalse();

      // delete the target ticket
      $targetTicket_1->delete(['id' => $targetTicket_1->getID()]);

      // Check the linked ticket or target ticket are deleted
      $this->boolean($item_targetticket_1->getFromDB($item_targetticket_1->getID()))->isFalse();
      $this->boolean($item_targetticket_2->getFromDB($item_targetticket_2->getID()))->isFalse();
   }

   /**
    * @engine inline
    *
    * @return void
    */
   public function  testSetTargetEntity() {
      $form = $this->getForm();
      $formFk = \PluginFormcreatorForm::getForeignKeyField();
      $targetTicket = $this->getTargetTicket([
         $formFk => $form->getID(),
      ]);

      // Use a dummy class to access protected methods
      $instance = new PluginFormcreatorTargetTicketDummy();
      $instance->getFromDB($targetTicket->getID());

      // Test current entity of the requester
      $entity = new \Entity();
      $entityId = $entity->import([
         'entities_id' => '0',
         'name' => $this->getUniqueString()
      ]);
      $targetTicket->update([
         'id' => $targetTicket->getID(),
         '_skip_checks' => true,
         'destination_entity' => \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_CURRENT,
         'destination_entity_value' => '0',
      ]);
      $instance->getFromDB($targetTicket->getID());
      $formAnswer = new \PluginFormcreatorFormAnswer();
      $formAnswer->add([
         'plugin_formcreator_forms_id' => $form->getID(),
         'entities_id' => $entityId,
      ]);
      $formAnswer->getFromDB($formAnswer->getID());
      $requesterId = \Session::getLoginUserID();
      $output = $instance->publicSetTargetEntity([], $formAnswer, $requesterId);
      $this->integer((int) $output['entities_id'])->isEqualTo($entityId);

      // Test requester's entity
      $targetTicket->update([
         'id' => $targetTicket->getID(),
         '_skip_checks' => true,
         'destination_entity' => \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_REQUESTER,
         'destination_entity_value' => '0',
      ]);
      $instance->getFromDB($targetTicket->getID());
      $formAnswer->add([
         'plugin_formcreator_forms_id' => $form->getID(),
         'entities_id' => $entityId,
      ]);
      \Session::changeActiveEntities($entityId);
      $requesterId = \Session::getLoginUserID();
      $output = $instance->publicSetTargetEntity([], $formAnswer, $requesterId);
      $this->integer((int) $output['entities_id'])->isEqualTo(0);

      // Test requester's first entity (alphanumeric order)
      $targetTicket->update([
         'id' => $targetTicket->getID(),
         '_skip_checks' => true,
         'destination_entity' => \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_REQUESTER_DYN_FIRST,
         'destination_entity_value' => '0',
      ]);
      $instance->getFromDB($targetTicket->getID());
      $entityId = $entity->import([
         'entities_id' => '0',
         'name' => $this->getUniqueString(),
      ]);
      $user = new \User();
      $user->add([
         'name' => $this->getUniqueString(),
         'password' => 'passwd',
         'password2' => 'passwd',
         '_profiles_id' => '3', // Admin
         '_entities_id' => $entityId,
      ]);
      $entity = new \Entity();
      $profileUser = new \Profile_User();
      $profileUser->add([
         \User::getForeignKeyField() => $user->getID(),
         \Profile::getForeignKeyField() => 4, // Super admin
         \Entity::getForeignKeyField() => 0,
      ]);
      $formAnswer->add([
         'plugin_formcreator_forms_id' => $form->getID(),
         'entities_id' => 0,
      ]);
      $this->boolean($this->login($user->fields['name'], 'passwd'))->isTrue();
      $requesterId = \Session::getLoginUserID();
      $output = $instance->publicSetTargetEntity([], $formAnswer, $requesterId);
      $this->integer((int) $output['entities_id'])->isEqualTo($entityId);

      // Test requester's last entity (alphanumeric order)
      $targetTicket->update([
         'id' => $targetTicket->getID(),
         '_skip_checks' => true,
         'destination_entity' => \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_REQUESTER_DYN_LAST,
         'destination_entity_value' => '0',
      ]);
      $instance->getFromDB($targetTicket->getID());
      $formAnswer->add([
         'plugin_formcreator_forms_id' => $form->getID(),
         'entities_id' => $entityId,
      ]);
      $this->boolean($this->login($user->fields['name'], 'passwd'))->isTrue();
      $requesterId = \Session::getLoginUserID();
      $output = $instance->publicSetTargetEntity([], $formAnswer, $requesterId);
      $this->integer((int) $output['entities_id'])->isEqualTo(0);

      // Test specific entity
      $this->boolean($this->login('glpi', 'glpi'))->isTrue();
      $entityId = $entity->import([
         'entities_id' => '0',
         'name' => $this->getUniqueString(),
      ]);
      $targetTicket->update([
         'id' => $targetTicket->getID(),
         '_skip_checks' => true,
         'destination_entity' => \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_SPECIFIC,
         'destination_entity_value' => "$entityId",
      ]);
      $instance->getFromDB($targetTicket->getID());
      $formAnswer->add([
         'plugin_formcreator_forms_id' => $form->getID(),
         'entities_id' => 0,
      ]);
      $requesterId = \Session::getLoginUserID();
      $output = $instance->publicSetTargetEntity([], $formAnswer, $requesterId);
      $this->integer((int) $output['entities_id'])->isEqualTo($entityId);

      // Test form's entity
      $entityId = $entity->import([
         'entities_id' => '0',
         'name' => $this->getUniqueString(),
      ]);
      $targetTicket->update([
         'id' => $targetTicket->getID(),
         '_skip_checks' => true,
         'destination_entity' => \PluginFormcreatorTargetTicket::DESTINATION_ENTITY_FORM,
         'destination_entity_value' => '0',
      ]);
      $form->update([
         'id' => $form->getID(),
         'entities_id' => $entityId,
      ]);
      $instance->getFromDB($targetTicket->getID());
      $formAnswer->add([
         'plugin_formcreator_forms_id' => $form->getID(),
         'entities_id' => 0,
      ]);
      $requesterId = \Session::getLoginUserID();
      $output = $instance->publicSetTargetEntity([], $formAnswer, $requesterId);
      $this->integer((int) $output['entities_id'])->isEqualTo($entityId);
   }

   public function providerPrepareTemplate() {
      $question = $this->getQuestion([
         'fieldtype' => 'textarea',
         '_parameters' => [
            'textarea' => [
               'range' => [
                  'range_min' => '',
                  'range_max' => '',
               ],
               'regex' => [
                  'regex' => ''
               ],
            ],
         ],
      ]);
      $this->boolean($question->isNewItem())->isFalse();
      $section = new \PluginFormcreatorSection();
      $section->getFromDB($question->fields[\PluginFormcreatorSection::getForeignKeyField()]);
      $form = new \PluginFormcreatorForm();
      $form->getFromDB($section->fields[\PluginFormcreatorForm::getForeignKeyField()]);
      $formAnswerId = $form->saveForm([
         'validation_required' => 0,
         'formcreator_field_' . $question->getID() => 'foo',
      ]);
      $formAnswer = new \PluginFormcreatorFormAnswer();
      $formAnswer->getFromDB($formAnswerId);
      $sectionName = $section->fields['name'];
      $questionTag = '##question_' . $question->getID() . '##';
      $answerTag = '##answer_' . $question->getID() . '##';
      $eolSimple = "\r\n";
      // 2 expected values
      // 0 : Rich text mode disabled
      // 1 : Rich text mode enabled
      return [
         [
            'template' => '##FULLFORM##',
            'form_answer' => $formAnswer,
            'expected' => [
               0 => 'Form data' . $eolSimple
                  . '=================' . $eolSimple
                  . $eolSimple
                  . $eolSimple . \Toolbox::addslashes_deep($sectionName) . $eolSimple
                  . '---------------------------------' . $eolSimple
                  . '1) ' . $questionTag . ' : ' . $answerTag . $eolSimple . $eolSimple,
               1 => '&lt;h1&gt;Form data&lt;/h1&gt;'
                  . '&lt;h2&gt;' . \Toolbox::addslashes_deep($sectionName) . '&lt;/h2&gt;'
                  . '&lt;div&gt;&lt;b&gt;1) ' . $questionTag . ' : &lt;/b&gt;' . $answerTag . '&lt;/div&gt;',
            ],
         ],
      ];
   }

   /**
    * @dataProvider providerPrepareTemplate
    */
   public function testPrepareTemplate($template, $formAnswer, $expected) {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $output = $instance->publicPrepareTemplate($template, $formAnswer);
      $this->string($output)->isEqualTo($expected[0]);

      $output = $instance->publicPrepareTemplate($template, $formAnswer, true);
      $this->string($output)->isEqualTo($expected[1]);
   }

   public function testExport() {
      $instance = $this->newTestedInstance();

      // Try to export an empty item
      $output = $instance->export();
      $this->boolean($output)->isFalse();

      // Prepare an item to export
      $instance = $this->getTargetTicket();
      $instance->getFromDB($instance->getID());

      // Export the item without the ID and with UUID
      $output = $instance->export(false);

      // Test the exported data
      $fieldsWithoutID = [
         'name',
         'target_name',
         'type',
         'content',
         'due_date_rule',
         'due_date_question',
         'due_date_value',
         'due_date_period',
         'urgency_rule',
         'urgency_question',
         'validation_followup',
         'destination_entity',
         'destination_entity_value',
         'tag_type',
         'tag_questions',
         'tag_specifics',
         'category_rule',
         'category_question',
         'associate_rule',
         'associate_question',
         'location_rule',
         'location_question',
      ];
      $extraFields = [
         '_tickettemplate',
         '_actors',
         '_ticket_relations',
      ];

      $this->array($output)
         ->hasKeys($fieldsWithoutID + $extraFields + ['uuid'])
         ->hasSize(1 + count($fieldsWithoutID) + count($extraFields));

      // Export the item without the UUID and with ID
      $output = $instance->export(true);
      $this->array($output)
         ->hasKeys($fieldsWithoutID + $extraFields + ['id'])
         ->hasSize(1 + count($fieldsWithoutID) + count($extraFields));
   }

   public function testImport() {
      $form = $this->getForm();
      $uuid = plugin_formcreator_getUuid();
      $input = [
         'name' => $this->getUniqueString(),
         'content' => $this->getUniqueString(),
         'due_date_rule' => \PluginFormcreatorTargetTicket::DUE_DATE_RULE_NONE,
         'due_date_question' => '0',
         'due_date_value' => '',
         'due_date_period' => '',
         'urgency_rule' => \PluginFormcreatorTargetTicket::URGENCY_RULE_NONE,
         'urgency_question' => '0',
         'location_rule' => \PluginFormcreatorTargetTicket::LOCATION_RULE_NONE,
         'location_question' => '0',
         'validation_followup' => '1',
         'destination_entity' => '0',
         'destination_entity_value' => '',
         'tag_type' => \PluginFormcreatorTargetTicket::TAG_TYPE_NONE,
         'tag_questions' => '0',
         'tag_specifics' => '',
         'category_rule' => \PluginFormcreatorTargetTicket::CATEGORY_RULE_NONE,
         'category_question' => '0',
         'associate_rule' => \PluginFormcreatorTargetTicket::ASSOCIATE_RULE_NONE,
         'associate_question' => '0',
         'uuid' => $uuid,
      ];

      $linker = new \PluginFormcreatorLinker();
      $targetTicketId = \PluginFormcreatorTargetTicket::import($linker, $input, $form->getID());
      $this->integer($targetTicketId)->isGreaterThan(0);

      unset($input['uuid']);

      $this->exception(
         function() use($linker, $input, $form) {
            \PluginFormcreatorTargetTicket::import($linker, $input, $form->getID());
         }
      )->isInstanceOf(\GlpiPlugin\Formcreator\Exception\ImportFailureException::class)
         ->hasMessage('UUID or ID is mandatory'); // passes

      $input['id'] = $targetTicketId;
      $targetTicketId2 = \PluginFormcreatorTargetTicket::import($linker, $input, $form->getID());
      $this->integer((int) $targetTicketId)->isNotEqualTo($targetTicketId2);
   }

   public function providerSetTargetCategory() {
      $dataSet = [];

      $instance = new PluginFormcreatorTargetTicketDummy();
      $question = $this->getQuestion([
         'fieldtype' => 'dropdown',
         'values' => json_encode([
            'itemtype' => 'ITILCategory',
            'show_ticket_categories' =>'both',
            'show_ticket_categories_depth' => '0',
            'show_ticket_categories_root' => '0',
         ]),
      ]);
      $form = new \PluginFormcreatorForm();
      $form->getByQuestionId($question->getID());
      $fields = $form->getFields();
      $instance->add([
         'plugin_formcreator_forms_id' => $form->getID(),
         'category_rule' => \PluginFormcreatorTargetTicket::CATEGORY_RULE_ANSWER,
         'category_question' => $question->getID(),
      ]);
      $input = [
         'formcreator_field_' . $question->getID() => '42',
      ];
      foreach ($fields as $id => $field) {
         $field->parseAnswerValues($input);
      }
      $formAnswer = new \PluginFormcreatorFormAnswer();
      $this->disableDebug();
      $formAnswer->saveAnswers(
         $form,
         $input,
         $fields
      );
      $this->restoreDebug();

      $dataSet[] = [
         'instance' => $instance,
         'data' => [
         ],
         'formanswer' => $formAnswer,
         'expected' => [
            'itilcategories_id' => '42',
         ],
      ];

      // $instance = new PluginFormcreatorTargetTicketDummy();
      // $question = $this->getQuestion([
      //    'fieldtype' => 'dropdown',
      //    'values' => json_encode([
      //       'itemtype' => 'ITILCategory',
      //       'show_ticket_categories' =>'both',
      //       'show_ticket_categories_depth' => '0',
      //       'show_ticket_categories_root' => '0',
      //    ]),
      // ]);
      // $form = new \PluginFormcreatorForm();
      // $form->getByQuestionId($question->getID());
      // $fields = $form->getFields();
      // $instance->add([
      //    'plugin_formcreator_forms_id' => $form->getID(),
      //    'category_rule' => \PluginFormcreatorTargetTicket::CATEGORY_RULE_SPECIFIC,
      //    'category_question' => '41',
      // ]);
      // $input = [
      //    'formcreator_field_' . $question->getID() => '42',
      // ];
      // foreach ($fields as $id => $field) {
      //    $field->parseAnswerValues($input);
      // }
      // $formAnswer = new \PluginFormcreatorFormAnswer();
      // $this->disableDebug();
      // $formAnswer->saveAnswers(
      //    $form,
      //    $input,
      //    $fields
      // );
      // $this->restoreDebug();

      // $dataSet[] = [
      //    'instance' => $instance,
      //    'data' => [
      //    ],
      //    'formanswer' => $formAnswer,
      //    'expected' => [
      //       'itilcategories_id' => '41',
      //    ],
      // ];

      return $dataSet;
   }

   /**
    *
    */
   public function testSetTargetCategory() {
      $dataSet = [];

      $instance = new PluginFormcreatorTargetTicketDummy();
      // $question = $this->getQuestion([
      //    'fieldtype' => 'dropdown',
      //    'values' => json_encode([
      //       'itemtype' => 'ITILCategory',
      //       'show_ticket_categories' =>'both',
      //       'show_ticket_categories_depth' => '0',
      //       'show_ticket_categories_root' => '0',
      //    ]),
      // ]);
      // $form = new \PluginFormcreatorForm();
      // $form->getByQuestionId($question->getID());
      // $fields = $form->getFields();
      // $instance->add([
      //    'name' => 'foo',
      //    'plugin_formcreator_forms_id' => $form->getID(),
      //    'category_rule' => \PluginFormcreatorTargetTicket::CATEGORY_RULE_ANSWER,
      //    'category_question' => $question->getID(),
      // ]);
      // $input = [
      //    'formcreator_field_' . $question->getID() => '42',
      // ];
      // foreach ($fields as $id => $field) {
      //    $field->parseAnswerValues($input);
      // }
      // $formAnswer = new \PluginFormcreatorFormAnswer();
      // $this->disableDebug();
      // $formAnswer->saveAnswers(
      //    $form,
      //    $input,
      //    $fields
      // );
      // $this->restoreDebug();

      // $dataSet[] = [
      //    'instance' => $instance,
      //    'data' => [
      //    ],
      //    'formanswer' => $formAnswer,
      //    'expected' => [
      //       'itilcategories_id' => '42',
      //    ],
      // ];
      // $data = [];
      // $expected = [
      //    'itilcategories_id' => '42',
      // ];

      // $output = $instance->publicSetTargetCategory($data, $formAnswer);
      // $this->integer((int) $output['itilcategories_id'])->isEqualTo($expected['itilcategories_id']);
   }

   public function testSetTargetAssociatedItem() {
      $instance = new PluginFormcreatorTargetTicketDummy();
      $question = $this->getQuestion([
         'fieldtype' => 'glpiselect',
         'values' => \Computer::class,
      ]);
      $form = new \PluginFormcreatorForm();
      $form->getByQuestionId($question->getID());

      $computer = new \Computer();
      $computer->add([
         'name' => $this->getUniqueString(),
         'entities_id' => '0',
      ]);
      $this->boolean($computer->isNewItem())->isFalse();
      $formAnswer = new \PluginFormcreatorFormAnswer;
      $formAnswer->add([
         \PluginFormcreatorForm::getForeignKeyField() => $form->getID(),
         'name' => $form->fields['name'],
         'requester_d' => 2, // glpi user id
         'status' => '101',
      ]);
      $this->boolean($formAnswer->isNewItem())->isFalse();
      $answer = new \PluginFormcreatorAnswer();
      $answer->add([
         \PluginFormcreatorFormAnswer::getForeignKeyField() => $formAnswer->getID(),
         \PluginFormcreatorQuestion::getForeignKeyField() => $question->getID(),
         'answer' => $computer->getID(),
      ]);
      $this->boolean($answer->isNewItem())->isFalse();
      $instance->add([
         'name' => '',
         'target_name' => '',
         \PluginFormcreatorForm::getForeignKeyField() => $form->getID(),
         'content' => '##FULLFORM',
         'associate_rule' => \PluginFormcreatorTargetTicket::ASSOCIATE_RULE_ANSWER,
         'associate_question' => $question->getID(),
      ]);
      $this->boolean($instance->isNewItem())->isFalse();
      $output = $instance->publicSetTargetAssociatedItem([], $formAnswer);
      $this->array($output['items_id']['Computer'])->hasSize(1);
      $this->integer((int) $output['items_id']['Computer'][$computer->getID()])->isEqualTo($computer->getID());
   }
}
