<?php

namespace GlpiPlugin\Formcreator\Tests;
use Session;
use Html;
use DB;
use Auth;

abstract class CommonTestCase extends CommonDBTestCase
{
   protected $str = null;

   /** @var integer $debugMode save state of GLPI debug mode */
   private $debugMode = null;

   public function beforeTestMethod($method) {
      self::resetGLPILogs();
   }

   protected function resetState() {
      self::resetGLPILogs();

      // $DBvars = get_class_vars('DB');
      // $result = $this->drop_database(
      //    $DBvars['dbuser'],
      //    $DBvars['dbhost'],
      //    $DBvars['dbdefault'],
      //    $DBvars['dbpassword']
      // );

      // $result = $this->load_mysql_file($DBvars['dbuser'],
      //    $DBvars['dbhost'],
      //    $DBvars['dbdefault'],
      //    $DBvars['dbpassword'],
      //    './save.sql'
      // );
   }

   protected function resetGLPILogs() {
      // Reset error logs
      file_put_contents(GLPI_LOG_DIR."/sql-errors.log", '');
      file_put_contents(GLPI_LOG_DIR."/php-errors.log", '');
   }

   protected function setupGLPIFramework() {
      global $DB, $LOADED_PLUGINS, $AJAX_INCLUDE, $PLUGINS_INCLUDED;

      if (session_status() == PHP_SESSION_ACTIVE) {
         session_write_close();
      }
      $LOADED_PLUGINS = null;
      $PLUGINS_INCLUDED = null;
      $AJAX_INCLUDE = null;
      $_SESSION = [];
      if (is_readable(GLPI_ROOT . "/config/config.php")) {
         $configFile = "/config/config.php";
      } else {
         $configFile = "/inc/config.php";
      }
      include (GLPI_ROOT . $configFile);
      require (GLPI_ROOT . "/inc/includes.php");
      //\Toolbox::setDebugMode(Session::DEBUG_MODE);

      $DB = new DB();

      include_once (GLPI_ROOT . "/inc/timer.class.php");

      // Security of PHP_SELF
      $_SERVER['PHP_SELF'] = Html::cleanParametersURL($_SERVER['PHP_SELF']);

      if (session_status() == PHP_SESSION_ACTIVE) {
         session_write_close();
      }
      ini_set('session.use_cookies', 0); //disable session cookies
      session_start();
      $_SESSION['MESSAGE_AFTER_REDIRECT'] = [];
   }

   protected function login($name, $password, $noauto = false) {
      Session::start();
      $auth = new Auth();
      $this->disableDebug();
      $result = $auth->login($name, $password, $noauto);
      $this->restoreDebug();
      $_SESSION['MESSAGE_AFTER_REDIRECT'] = [];
      $this->setupGLPIFramework();

      return $result;
   }

   protected function disableDebug() {
      $this->debugMode = Session::DEBUG_MODE;
      if (isset($_SESSION['glpi_use_mode'])) {
         $this->debugMode = $_SESSION['glpi_use_mode'];
      }
      \Toolbox::setDebugMode(Session::NORMAL_MODE);
   }

   protected function restoreDebug() {
      \Toolbox::setDebugMode($this->debugMode);
   }

   public function afterTestMethod($method) {
      // Check logs
      $fileSqlContent = file_get_contents(GLPI_LOG_DIR."/sql-errors.log");
      $filePhpContent = file_get_contents(GLPI_LOG_DIR."/php-errors.log");

      $class = static::class;
      $class = str_replace('\\', '_', $class);
      if ($fileSqlContent != '') {
         rename(GLPI_LOG_DIR."/sql-errors.log", GLPI_LOG_DIR."/sql-errors__${class}__$method.log");
      }
      if ($fileSqlContent != '') {
         rename(GLPI_LOG_DIR."/php-errors.log", GLPI_LOG_DIR."/php-errors__${class}__$method.log");
      }

      // Reset log files
      self::resetGLPILogs();

      // Test content
      $this->variable($fileSqlContent)->isEqualTo('', 'sql-errors.log not empty');
      $this->variable($filePhpContent)->isEqualTo('', 'php-errors.log not empty');
   }

   protected function loginWithUserToken($userToken) {
      // Login as guest user
      $_REQUEST['user_token'] = $userToken;
      Session::destroy();
      self::login('', '', false);
      unset($_REQUEST['user_token']);
   }

   /**
    * Get a unique random string
    */
   protected function getUniqueString() {
      if (is_null($this->str)) {
         return $this->str = uniqid('str');
      }
      return $this->str .= 'x';
   }

   protected function getUniqueEmail() {
      return $this->getUniqueString() . "@example.com";
   }

   public function getMockForItemtype($classname, $methods = []) {
      // create mock
      $mock = $this->getMockBuilder($classname)
                   ->setMethods($methods)
                   ->getMock();

      //Override computation of table to match the original class name
      // see CommonDBTM::getTable()
      $_SESSION['glpi_table_of'][get_class($mock)] = getTableForItemType($classname);

      return $mock;
   }

   protected function terminateSession() {
      if (session_status() == PHP_SESSION_ACTIVE) {
         session_write_close();
      }
   }

   protected function restartSession() {
      if (session_status() != PHP_SESSION_ACTIVE) {
         session_start();
         session_regenerate_id();
         session_id();
      }
   }

   protected function getForm($input = []) {
      if (!isset($input['name'])) {
         $input['name'] = $this->getUniqueString();
      }
      if (!isset($input['is_active'])) {
         $input['is_active'] = 1;
      }
      $form = new \PluginFormcreatorForm();
      $form->add($input);
      $this->boolean($form->isNewItem())->isFalse();
      $form->getFromDB($form->getID());

      return $form;
   }

   protected function getSection($input = [], $formInput = []) {
      $formFk = \PluginFormcreatorForm::getForeignKeyField();
      if (!isset($input[$formFk])) {
         $formId = $this->getForm($formInput)->getID();
         $input[$formFk] = $formId;
      }
      if (!isset($input['name'])) {
         $input['name'] = $this->getUniqueString();
      }
      $section = new \PluginFormcreatorSection();
      $section->add($input);
      $this->boolean($section->isNewItem())->isFalse();
      return $section;
   }

   protected function getQuestion($input = [], $sectionInput = [], $formInput = []) {
      if (!isset($input['name'])) {
         $input['name'] = 'question';
      }
      $sectionFk = \PluginFormcreatorSection::getForeignKeyField();
      if (!isset($input[$sectionFk])) {
         $sectionId = $this->getSection($sectionInput, $formInput)->getID();
         $input[$sectionFk] = $sectionId;
      }
      $defaultInput = [
         'fieldtype'                      => 'text',
         'values'                         => "",
         'required'                       => '0',
         'show_empty'                     => '0',
         'default_values'                 => '',
         'desription'                     => '',
         'row'                            => '0',
         'col'                            => '0',
         'width'                          => '4',
         'show_rule'                      => \PluginFormcreatorCondition::SHOW_RULE_ALWAYS,
         '_parameters'     => [
            'text' => [
               'range' => [
                  'range_min' => '',
                  'range_max' => '',
               ],
               'regex' => [
                  'regex' => ''
               ]
            ]
         ],
      ];
      $input = array_merge($defaultInput, $input);
      $question = new \PluginFormcreatorQuestion();
      $question->add($input);
      $question->getFromDB($question->getID());
      $question->updateParameters($input);

      return $question;
   }

   protected function getTargetTicket($input = []) {
      if (!isset($input['name'])) {
         $input['name'] = $this->getUniqueString();
      }

      $formFk = \PluginFormcreatorForm::getForeignKeyField();
      if (!isset($input[$formFk])) {
         $input[$formFk] = $this->getForm()->getID();
      }

      $targetTicket = new \PluginFormcreatorTargetTicket();
      $targetTicket->add($input);

      return $targetTicket;
   }

   protected function getTargetChange($input = []) {
      if (!isset($input['name'])) {
         $input['name'] = $this->getUniqueString();
      }

      $formFk = \PluginFormcreatorForm::getForeignKeyField();
      if (!isset($input[$formFk])) {
         $input[$formFk] = $this->getForm()->getID();
      }

      $targetChange = new \PluginFormcreatorTargetChange();
      $targetChange->add($input);

      return $targetChange;
   }

   /**
    * Tests the session has a specific message
    * this may be replaced by a custom asserter for atoum
    * @see http://docs.atoum.org/en/latest/asserters.html#custom-asserter
    *
    * @param string $message
    * @param integer $message_type
    */
   protected function sessionHasMessage($message, $message_type = INFO) {
      if (!is_array($message)) {
         $message = [$message];
      }
      $this->array($_SESSION['MESSAGE_AFTER_REDIRECT'][$message_type])
         ->containsValues($message);
   }

   protected function sessionHasNoMessage() {
      $this->boolean(isset($_SESSION['MESSAGE_AFTER_REDIRECT'][INFO]))->isFalse();
      $this->boolean(isset($_SESSION['MESSAGE_AFTER_REDIRECT'][WARNING]))->isFalse();
      $this->boolean(isset($_SESSION['MESSAGE_AFTER_REDIRECT'][ERROR]))->isFalse();
   }
}
