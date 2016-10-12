<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_languages
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.language.helper');
jimport('joomla.utilities.utility');

abstract class modJdLanguageHelper {
  /**
   * Creates a link to the Active page in the given language if exists, if not redirect to the Startpage
   * @static
   * @param $lang Object the Language Object returned by jdiction::getLanguages
   * @return array With Link and Title
   */
  public static function getLink($lang) {

    $config = JFactory::getConfig();
    $sef = $config->get('sef');
    $rewrite = $config->get('sef_rewrite');
    
    $app = JFactory::getApplication();
    $uri = JURI::getInstance();
    $input = $app->input;
		$menu = $app->getMenu();
		$active = $menu->getActive();
    $link = false;

    // Load associations
    // @deprecated menu_association was used in Joomla 2.5, 3.0 useses item_associations


    

		
		$db = JFactory::getDbo();



    //restore current language
    $db->setTranslate($orig_translate);
    $db->setLanguage($orig_lang);

    $query = array();
    $query_sef = array();

    $urlquery = $uri->getQuery(true);

    foreach($urlquery as $k=>$v) {
      switch($k) {
        case 'option':
        case 'view':
        case 'id':
        case 'cid':
        case 'layout':
          $query[] = $k.'='.$v;
          if (!isset($active->query[$k]) || $active->query[$k] != $v) {
            $query_sef[] = $k.'='.$v;
          }
          break;
        case 'Itemid': // maybe different
        case 'lang': // will be changed later
          // we don't need the lang attribute
          break;
        default:
          $query[] = $k.'='.$v;
          $query_sef[] = $k.'='.$v;
          break;
      }
    }

    $path = rawurldecode($uri->toString($parts = array('path')));
    $base = $uri->base(true);
    $link = $base;
    //check if we use sef_rewrite
    if (!$rewrite) {
      $link .= '/index.php';
    }

    if ($sef) {
      $link .= '/'.$lang->sef;
    } else {
      $link .= '?lang='.$lang->sef;
    }

    $option = $app->input->get('option');
    $eName = JString::ucfirst(JString::str_ireplace('com_', '', $option));
    $cName = JString::ucfirst($eName.'HelperjDiction');
    JLoader::register($cName, JPath::clean(JPATH_COMPONENT_SITE . '/helpers/jdiction.php'));
    if (class_exists($cName)) {
      $helper = new $cName;
      $helper->targetLanguage = $lang;
    } else {
      $helper = false;
    }

    if ($menuitem) {
        if ($sef) {
					$link .= '/';

					if (!$menuitem->home) { // startpage doesn't need a link path
						$link .= $menuitem->path;
					}

					// Check if we have all ready a compiled link (by com_menu)
          if (isset($active->flink)) {
            $base = $active->flink;
          } else {
						// For Joomla < 3.2 and sites not using mod_menu (flink get generated on display)
            $base = JRoute::_($active->link);
          }

					// get path without language prefix
          $path = JString::substr($path, JString::strlen($base));

					// load route from component helper if it exists
          if ($helper) {
            $path = $helper->getRoute($path, $input, $query, $menuitem, $active);
          }

          if ($path) {
            $link .= $path;
          }

					// Append variables that are not set in the menu item
          if (!empty($query_sef)) {
            $link .= '?'.implode('&', $query_sef);
          }

				} else {
          if (!empty($query)) {
            $link .= '&'.implode('&', $query);
          }
          $link .= '&Itemid='.$menuitem->id;
        }
    } else {
      if ($sef) {
        if (!$rewrite) {
          $base .= '/index.php';
        }
        $path = substr($path, strlen($base));

        // remove language ?1/?2en/?3more
        list(,,$path) = explode('/', $path, 3);

        if ($helper) {
          $path = $helper->getRoute($path, $input, $query, $menuitem, $active);
        }

        $link .= '/'.$path;
        if (!empty($query)) {
          $link .= '?'.implode('&', $query);
        }
      } else {
        if (!empty($query)) {
          $link .= '&'.implode('&', $query);
        }
      }

    }

    if ($menuitem) {
      $result = array($link, $menuitem->title);
    } else {
      $result = array($link, '');
    }
		return $result;
	}
}
