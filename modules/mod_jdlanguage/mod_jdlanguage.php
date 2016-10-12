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

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$jdiction = jDiction::getInstance();
$languages = $jdiction->getLanguages(true);
$current = JFactory::getLanguage();
$doc = JFactory::getDocument();
$access = JFactory::getUser()->getAuthorisedViewLevels();

$alternateLinks = array();
foreach($languages as $k=>$lang) {
  if (!in_array($lang->access, $access)) {
    unset($languages[$k]);
    continue;
  }

	//link custom translater
    list($lang->link, $lang->menutitle) = modJdLanguageHelper::getLink($lang);

    //var_dump($lang->link);
    //echo "<hr>";
    //var_dump($lang->menutitle);

	if (($lang->menutitle != '') && ($params->get('alternatetag'))) {
		$alternateLinks[substr($lang->lang_code, 0, 2)][] = $lang;
	}

	if ($lang->lang_code == $current->getTag()) {
		if ($params->get('currentlanguage')) {
			$lang->active = true;
		} else {
			unset($languages[$k]);
			continue;
		}
	} else {
		$lang->active = false;
	}
}

/**
 * We only display the full language tag if we have more then one region for a language
 */
foreach($alternateLinks as $k=>$p) {
	foreach($p as $ki=>$lang) {
		$doc->addHeadLink($lang->link, 'alternate', 'rel', array(
				'type'     => 'text/html',
				'hreflang' => ($ki > 0 ? $lang->lang_code : $k),
				'lang'     => $lang->lang_code,
				'title'    => $lang->menutitle
			));
	}
}

unset($alternateLinks);

$class_sfx	= htmlspecialchars($params->get('class_sfx'));

require JModuleHelper::getLayoutPath('mod_jdlanguage', $params->get('layout', 'default'));
