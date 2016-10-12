<?php

/**
 * @version     1.0.0
 * @package     com_blog
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      SD3 - JAV <sd3@qsoftvietnam.com> - http://www.qsoftvietnam.com
 */
defined('_JEXEC') or die;

class BlogFrontendHelper {
    
	static $user_type = null;
    static public $translation_data = array();
    
	/**
	* Get category name using category ID
	* @param integer $category_id Category ID
	* @return mixed category name if the category was found, null otherwise
	*/
	public static function getCategoryNameByCategoryId($category_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('title')
			->from('#__categories')
			->where('id = ' . intval($category_id));

		$db->setQuery($query);
		return $db->loadResult();
	}

    static public function getTranslationForCurrentLanguage( $table, $referenceOption = '', $idReferences = '', $originalData = array() , $opt = 0 )
    {
        if ( $opt == 0 ) {
            //using cache
            if ( isset(self::$translation_data[$table . '_' . $idReferences]) )
            {
                return self::$translation_data[$table . '_' . $idReferences];
            }
        }
        
        $db = JFactory::getDbo();
        $current_language = JFactory::getLanguage ()->getTag ();
        
        $idLang = 0;
        $whereString = $db->quoteName ( 'lang_code' ) . ' = "' . $current_language . '" ';
        $query = $db->getQuery ( true )->select ( '*' )
            ->from( $db->quoteName ( '#__languages', 'l' ) )
            ->where ( $whereString );  
        $db->setQuery($query);
        $languageRow = $db->loadObject();
        if ( is_object($languageRow) && property_exists($languageRow, 'lang_id') )
        {
            $idLang = intval($languageRow->lang_id);
        }
        
        $whereCondition = array();
        array_push($whereCondition,$db->quoteName ( 'referenceTable' ) . ' = "#__' . $table . '" ');
        if ( !$referenceOption ) $referenceOption = 'com_' . $table;
        array_push($whereCondition,$db->quoteName ( 'referenceOption' ) . ' = "' . $referenceOption . '" ');
        array_push($whereCondition,$db->quoteName ( 'idLang' ) . ' = "' . $idLang . '" ');
        if ( $idReferences ) array_push($whereCondition,$db->quoteName ( 'idReference' ) . ' IN (' . $idReferences . ') ');
        $whereString = implode(' AND ', $whereCondition); 
        
        $query = $db->getQuery ( true )->select ( '*' )
            ->from( $db->quoteName ( '#__jd_store', 'store' ) )
            
            ->where ( $whereString );    

		
        $db->setQuery($query);

        $result = $db->loadObjectList();
        $translated_texts = array();
		
		$idReferences = explode(",", $idReferences);

		
		if ($table == 'blog') {
			$array_sort_order_post = array();
			foreach($idReferences as $idReference) {
				foreach($result as $rs) {
					if ($rs->idReference == $idReference) {
						array_push($array_sort_order_post, $rs);
					}
				}
			}
			$result = $array_sort_order_post;
		}
		

        foreach ( $result as $item )
        {
            $idReference = $item->idReference;
            if ( $item->value ) 
            {
                //echo "<pre>"; var_dump($item->value);die;
                $xml = simplexml_load_string($item->value);
                if ( $xml !== false )
                {
                    $xml = (array) $xml;
                    foreach ( $xml as $k => $v )
                    {
                        $v = (string) $v;
                        //var_dump($k, $v, isset($originalData[$k]); echo "<br/>";
                        if ( !isset( $translated_texts[$idReference] ) ) $translated_texts[$idReference] = array();    
                        $default_value = "";
                        $origin = array();
                        if ( isset($originalData[$idReference]) && $originalData[$idReference] )
                        {
                            $origin = $originalData[$idReference];
                            $default_value = (isset($origin[$k]) && $origin[$k]) ? $origin[$k] : '';
                        }
                        $translated_texts[$idReference][$k] = $v ? $v : $default_value;
                    }
                }
            }   
        }

		
        foreach ( $originalData as $idReference => $items )
        {
            if ( $items ) 
            {
                foreach ( $items as $k => $v )
                {
                    if ( !isset( $translated_texts[$idReference] ) ) $translated_texts[$idReference] = array();    
                    if ( !isset( $translated_texts[$idReference][$k] ) ) $translated_texts[$idReference][$k] = $v;
                }        
            }
        }
        self::$translation_data[$table . '_' . $idReferences] = $translated_texts;
        //var_dump($translated_texts); die;
        return $translated_texts;
    }
}
