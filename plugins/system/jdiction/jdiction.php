<?php
/**
 * jDiction plugin
 *
 * @package jDiction
 * @link http://joomla.itronic.at
 * @copyright	Copyright (C) 2011 ITronic Harald Leithner. All rights reserved.
 * @license GNU General Public License v3
 *
 * This file is part of jDiction.
 *
 * jDiction is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3 of the License.
 *
 * jDiction is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with jDiction.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
defined('_JEXEC') or die;

jimport('joomla.event.plugin');

//load jdiction framework
jimport('jdiction.jdiction');

class plgSystemjDiction extends JPlugin {

  /**
   * Construction function loading the plugin and create Logger Object if activated.
   *
   * @param object $subject
   * @param array $config
   */
  public function __construct(& $subject, $config = array()) {
		parent::__construct($subject, $config);

    $extension = JComponentHelper::getComponent('com_jdiction');
    if ($extension->params->get('debug', false)) {
      JLog::addLogger(array( 'text_file' => 'jdiction.log' ), JLog::ALL, array('jdiction'));
    }

  }
	
	/**
	 * replace database object
	 * @return void
	 */
	public function onAfterInitialise() {

		//load language file
		$lang = JFactory::getLanguage();
		$lang->load('lib_jdiction', JPATH_LIBRARIES.'/jdiction');

		$app = JFactory::getApplication();

		$jd = jDiction::getInstance();
		$jd->initialise();

    if ($app->isSite()) {
      $router = $app->getRouter();

      // attach build rules for translation on SEF
      $router->attachBuildRule(array($this, 'buildRule'), JRouter::PROCESS_AFTER);

      // attach build rules for translation on SEF
      $router->attachParseRule(array($this, 'parseRule'), JRouter::PROCESS_DURING);
    }
	}

  /**
   * Try to translates urls
   * @param $router
   * @param $uri
   */

  public function buildRule(&$router, &$uri) {
//    print_r('buildRule');
    /*
    $res =array();
    if ($uri->getVar('Itemid') == 437) {
      print_r($uri);
      jDictionMenu::loadLanguage();
      $uri->setVar('id', '38:andere-seite');
      $res = array('id'=>'38:andere-seite');
    }*/

    return array();
  }

  /**
   * Decodes translated sef urls
   * @param $router
   * @param $uri
   */
  public function parseRule(&$router, &$uri) {

    static $done = false;
    if (!$done) {
			$done = true;

			$jd = jDiction::getInstance();
			if (!$jd->getStatus()) {
				return array();
			}
			$db = JFactory::getDbo();
			$lang = JFactory::getLanguage();
			$db->setLanguage($lang->getTag());

			if ($lang->getTag() == JComponentHelper::getParams('com_languages')->get('site', 'en-GB')) {
				$db->setTranslate(false);
			} else {
				$db->setTranslate(true);
			}

			//Workaround for Joomla 3.4.0 bug where #__menu get queries before Joomla knows it language and is ready
			JFactory::getApplication()->getMenu()->__construct();

			//rewrite Menu route with translated alias
      $app = JFactory::getApplication();
      $menu = $app->getMenu()->getMenu();

      foreach($menu as &$item) {
        $item->route = '';
        if ($item->level > 1) {
          $item->route = $menu[$item->parent_id]->route.'/';
        }
        $item->route .= $item->alias;
      }

			// load jdiction helper from component
			$option = $app->input->get('option', 'CMD');
			$eName = JString::ucfirst(JString::str_ireplace('com_', '', $option));
			$cName = JString::ucfirst($eName.'HelperjDiction');
			JLoader::register($cName, JPath::clean(JPATH_SITE . '/'.$option.'/helpers/jdiction.php'));
			if (class_exists($cName)) {
				$helper = new $cName;
				$helper->targetLanguage = $jd->getCurrentLanguage();
				$helper->originalLanguage = $jd->getDefaultLanguage();
				$helper->originalTranslateStatus = $db->getTranslate();
				$helper->parseRule($router, $uri);
			}
		}

    return array();
  }
  /**
   * run after Route
   */
  public function onAfterRoute() {
    if (JFactory::getApplication()->isAdmin()) {
      $jd = jDiction::getInstance();
      $input = JFactory::getApplication()->input;
      $option = $input->get('option', null, 'cmd');
      $view = $input->get('view', null, 'cmd');
      $task = $input->get('task', null, 'cmd');
      $controller = $input->get('controller', null, 'cmd');
      if (!is_null($view) || is_null($task)) {
        if (is_null($view)) {
          $view = 'default';
        }
        $view = $jd->getView($option, $view);
      } elseif (!is_null($task)) {
        $view = $jd->getViewByTask($option, $task, $controller);
      }

      if ($view && !$view->native) {
        // Intercept the grid.id HTML Field to insert translation status
        JHtml::register('Grid.id', array($this, 'gridIdHook'));
        // Add the Toolbar in edit layout
        $jd->addToolbar();
      }
    }
  }


  /**
   * jDiction after save content method
   * Article is passed by reference, but after the save, so no changes will be saved.
   * Method is called right after the content is saved
   *
   * @param	string		$context The context of the content passed to the plugin (added in 1.6)
   * @param	object		$table A JTableContent object
   * @param	bool		$isNew If the content has just been created
   * @since	2.5
   */
  public function onContentAfterSave($context, $table, $isNew) {
    // We don't need new Items atm
    if ($isNew) {
      return;
    }

    $jd = jDiction::getInstance();

    list($ext, $view) = explode('.',$context);
    // FOF returns always the list view
    $view = $jd->getView($ext, JFactory::getApplication()->input->get('view', 'default', 'cmd'));
    if (!$view) {
      return;
    }
    $view = $view->name;

    $component = $jd->getComponent($ext);

    if (!$component) {
      return;
    }

    $jdtable = $jd->getTableByView($ext, $view);

    if (!array_key_exists($view, $component)) {
      return;
    }

    $sourcehash = $jd->getTranslationHashByView($ext, $view, $table);

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query->update('#__jd_store');
    $query->set($db->qn('state').'='.$db->q(2));
    $query->where($db->qn('state').'='.$db->q(1));
    $query->where($db->qn('idReference').'='.$db->q($table->{$jdtable->key}));
    $query->where($db->qn('referenceTable').'='.$db->q($jdtable->name));
    $query->where($db->qn('referenceOption').'='.$db->q($jdtable->component));
    $query->where($db->qn('referenceView').'='.$db->q($view));
    $query->where($db->qn('sourcehash').'!='.$db->q($sourcehash));

    $db->setQuery($query);
    return $db->execute();
  }

  /**
   * jDiction after delete content method
   * Deletes the translation upon main item deletion
   *
   * @param	string		$context The context of the content passed to the plugin (added in 1.6)
   * @param	object		$table A JTableContent object
   * @since	2.5
   */
  public function onContentAfterDelete($context, $table) {

    $jd = jDiction::getInstance();

    list($ext, $view) = explode('.',$context);
    // FOF returns always the list view
    $view = $jd->getView($ext, JFactory::getApplication()->input->get('view', 'default', 'cmd'));
    if ($view) {
      $view = $view->name;
    }
    $component = $jd->getComponent($ext);

    if (!$component) {
      return false;
    }

    $jdtable = $jd->getTableByView($ext, $view);
    if (!array_key_exists($view, $component)) {
      return false;
    }

    $key = $jdtable->key;
    $id = $table->$key;

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query->delete($db->quoteName('#__jd_store'));
    $query->where($db->qn('idReference').'='.$db->q($id));
    $query->where($db->qn('referenceTable').'='.$db->q($jdtable->name));

    $db->setQuery($query);

    return $db->execute();
  }

  public function gridIdHook() {
    //force loading of JHtmlGrid
    if (!class_exists('JHtmlGrid')) {
      @include_once(JPATH_LIBRARIES.'/joomla/html/html/grid.php');
    }
		$row = func_get_arg(0);
		$id = func_get_arg(1);
		$vars = func_get_args();
		$res = call_user_func_array('JHtmlGrid::id', $vars);
		$jd = jdiction::getInstance();
    $ext = JFactory::getApplication()->input->get('option', '', 'cmd');
    $view = $jd->getView($ext, JFactory::getApplication()->input->get('view', 'default', 'cmd'));
    if (!$view) {
      return $res;
    }
    $table = $jd->getTableByView($ext, $view->name);
    $layout = $view->layout;

    JHtml::_('behavior.framework');
    //Load interface translation
		JText::script('LIB_JDICTION_TRANSLATION');
    JText::script('JSTATUS');
    JText::script('JGLOBAL_TITLE');
    $doc = JFactory::getDocument();
    // @deprecated used for Joomla 2.5
    $tpl = (version_compare(JVERSION, '3.0', 'ge') ? '' : '_25');
    $doc->addScript(JUri::root(false).'/administrator/components/com_jdiction/assets/jdiction'.$tpl.'.js');
    //echo JUri::root(false).'/administrator/components/com_jdiction/assets/jdiction'.$tpl.'.js';die;

    $result = array();

    $languages = $jd->getLanguages();
    foreach($languages as $language) {
        //echo $language->image, "<br>";var_dump($jd->getTranslationStatus($table->name, $id, $language->lang_id));
		  $result['status'][$language->image] = $jd->getTranslationStatus($table->name, $id, $language->lang_id);
          $result[$language->image]['link'] = $jd->getTranslationLink($ext, $view->name, $layout, $id, false, $language->lang_code);//
    }
    
    //echo "<pre>"; var_dump($result); die;

	
    //echo "<pre>"; var_dump($jd); die;//$result['link']; 
		
		// create array
    if ($row == 0) {
			$table = new stdClass;
			$table->tableselector = $view->listview->tableselector;
			if (!empty($view->listview->columnselector)) {
				$table->columnselector = $view->listview->columnselector;
			}
            //var_dump($table);die;
			$first = 'var jDictionTable = '.json_encode($table).', jdiction = {}; ';
		} else {
      $first = '';
    }
    $res .= '<script>'.$first.'jdiction['.$row.']='.json_encode($result).';</script>';

		return $res;
	
	}
}
