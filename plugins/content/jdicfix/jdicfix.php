<?php
defined('JPATH_BASE') or die;
jimport('joomla.plugin.plugin');
//die("Rrr");
class PlgContentJdicfix extends JPlugin
{

	public function onContentAfterSave($context, $article, $isNew)
	{

       //blog
       if($context == "com_blog.blog") {
	   if ( !$isNew )
       {
         // update
         $request = $article;   
         //echo "<pre>"; var_dump($request); die; 
         $lang = $request->language;
            switch ($lang) {
                case "en-GB":
                    $langId = 1;
                    break;
                case "fr-FR":
                    $langId = 2;
                    break;
                case "it-IT":
                    $langId = 3;
                    break;
                case "de-DE":
                    $langId = 4;
                    break;
         }
         $xml = '<?xml version="1.0"?><jDiction><name><![CDATA[]]></name><description><![CDATA[]]></description><content><![CDATA[]]></content></jDiction>';
                                          
         if ( isset($request) )
         {
            $xml = '<?xml version="1.0"?><jDiction><name><![CDATA[' . $request->name .']]></name><description><![CDATA[' . $request->description .']]></description><content><![CDATA[' . $request->content .']]></content></jDiction>';
         }
         $db = JFactory::getDbo();    
		 $query = $db->getQuery(true);
		 $fields = array(
		    $db->quoteName('value') . ' = ' . $db->quote($xml), // change here value
		    $db->quoteName('modified') . ' = "' . $db->quote(date("Y-m-d H:i:s")) . '"',
		 );

		 // Conditions for which records should be updated.
		 $conditions = array(
		    $db->quoteName('idLang') . ' = ' . intval($langId), 
		    $db->quoteName('idReference') . ' = ' . intval($request->id),
		    $db->quoteName('referenceTable') . ' = "' . "#__blog" . '"',
		    $db->quoteName('referenceOption') . ' = "com_blog"',
            $db->quoteName('referenceView') . ' = "blog"',
            $db->quoteName('referenceLayout') . ' = "edit"',
		 );
         //var_dump($xml); die("fff");
		 $query->update($db->quoteName('#__jd_store'))->set($fields)->where($conditions);
		 //echo $query->dump(); die("d");
         $db->setQuery($query);
		 $result = $db->execute();
       }
       else 
       {   
            $db = JFactory::getDbo();    	
    		$request = $article;    
            $lang = $request->language;
            switch ($lang) {
                case "en-GB":
                    $langId = 1;
                    break;
                case "fr-FR":
                    $langId = 2;
                    break;
                case "it-IT":
                    $langId = 3;
                    break;
                case "de-DE":
                    $langId = 4;
                    break;
            }
                
			$xml = '<?xml version="1.0"?><jDiction><name><![CDATA[]]></name><description><![CDATA[]]></description><content><![CDATA[]]></content></jDiction>';
                                          
			if ( isset($request) )
			{
                $xml = '<?xml version="1.0"?><jDiction><name><![CDATA[' . $request->name .']]></name><description><![CDATA[' . $request->description .']]></description><content><![CDATA[' . $request->content .']]></content></jDiction>';
			}
            
			$referTable = '#__blog';
				//insert new
				$query = $db->getQuery(true);
				$columns = array('idLang', 'idReference', 'referenceTable', 'value', 'modified_by', 'referenceOption' , 'referenceView', 'referenceLayout');
				$values = array(intval($langId), intval($request->id), $db->quote($referTable), $db->quote($xml), $db->quote(date("Y-m-d H:i:s")), $db->quote('com_blog') , "'blog'", "'edit'");
				$query
				    ->insert($db->quoteName('#__jd_store'))
				    ->columns($db->quoteName($columns))
				    ->values(implode(',', $values));
				$db->setQuery($query);
				$db->execute();
    		}         
	   }   


       //slideshow
       if($context == "com_slideshow.slideshow") {
       if ( !$isNew )
       {
         // update
         $request = $article;   
         //echo "<pre>"; var_dump($request); die; 
         $lang = $request->language;
            switch ($lang) {
                case "en-GB":
                    $langId = 1;
                    break;
                case "fr-FR":
                    $langId = 2;
                    break;
                case "it-IT":
                    $langId = 3;
                    break;
                case "de-DE":
                    $langId = 4;
                    break;
         }
        
         $xml = '<?xml version="1.0"?><jDiction><image><![CDATA[]]></image><m_image><![CDATA[]]></m_image><m2_image><![CDATA[]]></m2_image><m3_image><![CDATA[]]></m3_image><m4_image><![CDATA[]]></m4_image><m5_image><![CDATA[]]></m5_image><m6_image><![CDATA[]]></m6_image><m7_image><![CDATA[]]></m7_image><m8_image><![CDATA[]]></m8_image><m9_image><![CDATA[]]></m9_image><title><![CDATA[]]></title><title><![CDATA[]]></title><alt><![CDATA[]]></alt><description><![CDATA[]]></description></jDiction>';
                                          
         if ( isset($request) )
         {
            $xml = '<?xml version="1.0"?><jDiction><image><![CDATA[' . $request->image .']]></image><m_image><![CDATA[' . $request->m_image .']]></m_image><m2_image><![CDATA[' . $request->m2_image .']]></m2_image><m3_image><![CDATA[' . $request->m3_image .']]></m3_image><m4_image><![CDATA[' . $request->m4_image .']]></m4_image><m5_image><![CDATA[' . $request->m5_image .']]></m5_image><m6_image><![CDATA[' . $request->m6_image .']]></m6_image><m7_image><![CDATA[' . $request->m7_image .']]></m7_image><m8_image><![CDATA[' . $request->m8_image .']]></m8_image><m9_image><![CDATA[' . $request->m9_image .']]></m9_image><title><![CDATA[' . $request->title .']]></title><alt><![CDATA[' . $request->alt .']]></alt><description><![CDATA[' . $request->description .']]></description></jDiction>';
         }
         $db = JFactory::getDbo();    
         $query = $db->getQuery(true);
         $fields = array(
            $db->quoteName('value') . ' = ' . $db->quote($xml), // change here value
            $db->quoteName('modified') . ' = "' . $db->quote(date("Y-m-d H:i:s")) . '"',
         );

         // Conditions for which records should be updated.
         $conditions = array(
            $db->quoteName('idLang') . ' = ' . intval($langId), 
            $db->quoteName('idReference') . ' = ' . intval($request->id),
            $db->quoteName('referenceTable') . ' = "' . "#__slideshow" . '"',
            $db->quoteName('referenceOption') . ' = "com_slideshow"',
            $db->quoteName('referenceView') . ' = "slideshow"',
            $db->quoteName('referenceLayout') . ' = "edit"',
         );
         //var_dump($xml); die("fff");
         $query->update($db->quoteName('#__jd_store'))->set($fields)->where($conditions);
         //echo $query->dump(); die("d");
         $db->setQuery($query);
         $result = $db->execute();
       }
       else 
       {    //die("fff");
            $db = JFactory::getDbo();       
            $request = $article;    
            $lang = $request->language;
            switch ($lang) {
                case "en-GB":
                    $langId = 1;
                    break;
                case "fr-FR":
                    $langId = 2;
                    break;
                case "it-IT":
                    $langId = 3;
                    break;
                case "de-DE":
                    $langId = 4;
                    break;
            }
                
            $xml = '<?xml version="1.0"?><jDiction><image><![CDATA[]]></image><m_image><![CDATA[]]></m_image><m2_image><![CDATA[]]></m2_image><m3_image><![CDATA[]]></m3_image><m4_image><![CDATA[]]></m4_image><m5_image><![CDATA[]]></m5_image><m6_image><![CDATA[]]></m6_image><m7_image><![CDATA[]]></m7_image><m8_image><![CDATA[]]></m8_image><m9_image><![CDATA[]]></m9_image><title><![CDATA[]]></title><title><![CDATA[]]></title><alt><![CDATA[]]></alt><description><![CDATA[]]></description></jDiction>';
                                          
            if ( isset($request) )
            {
                $xml = '<?xml version="1.0"?><jDiction><image><![CDATA[' . $request->image .']]></image><m_image><![CDATA[' . $request->m_image .']]></m_image><m2_image><![CDATA[' . $request->m2_image .']]></m2_image><m3_image><![CDATA[' . $request->m3_image .']]></m3_image><m4_image><![CDATA[' . $request->m4_image .']]></m4_image><m5_image><![CDATA[' . $request->m5_image .']]></m5_image><m6_image><![CDATA[' . $request->m6_image .']]></m6_image><m7_image><![CDATA[' . $request->m7_image .']]></m7_image><m8_image><![CDATA[' . $request->m8_image .']]></m8_image><m9_image><![CDATA[' . $request->m9_image .']]></m9_image><title><![CDATA[' . $request->title .']]></title><alt><![CDATA[' . $request->alt .']]></alt><description><![CDATA[' . $request->description .']]></description></jDiction>';
            }
            
            $referTable = '#__slideshow';
                //insert new
                $query = $db->getQuery(true);
                $columns = array('idLang', 'idReference', 'referenceTable', 'value', 'modified_by', 'referenceOption' , 'referenceView', 'referenceLayout');
                $values = array(intval($langId), intval($request->id), $db->quote($referTable), $db->quote($xml), $db->quote(date("Y-m-d H:i:s")), $db->quote('com_slideshow') , "'slideshow'", "'edit'");
                $query
                    ->insert($db->quoteName('#__jd_store'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
            }         
       }
       
       //content - about us
       if($context == "com_content.article") {
       if ( !$isNew )
       {
         // update
         $request = $article;   
         //echo "<pre>"; var_dump($request); die; 
         $lang = $request->language;
            switch ($lang) {
                case "en-GB":
                    $langId = 1;
                    break;
                case "fr-FR":
                    $langId = 2;
                    break;
                case "it-IT":
                    $langId = 3;
                    break;
                case "de-DE":
                    $langId = 4;
                    break;
         }
        
         $xml = '<?xml version="1.0"?><jDiction><title><![CDATA[]]></title><alias><![CDATA[]]></alias><articletext><![CDATA[]]></articletext></jDiction>';                           
         if ( isset($request) )
         {
            $xml = '<?xml version="1.0"?><jDiction><title><![CDATA[' . $request->title .']]></title><alias><![CDATA[' . $request->alias .']]></alias><articletext><![CDATA[' . $request->articletext .']]></articletext></jDiction>';
         }
         $db = JFactory::getDbo();    
         $query = $db->getQuery(true);
         $fields = array(
            $db->quoteName('value') . ' = ' . $db->quote($xml), // change here value
            $db->quoteName('modified') . ' = "' . $db->quote(date("Y-m-d H:i:s")) . '"',
         );

         // Conditions for which records should be updated.
         $conditions = array(
            $db->quoteName('idLang') . ' = ' . intval($langId), 
            $db->quoteName('idReference') . ' = ' . intval($request->id),
            $db->quoteName('referenceTable') . ' = "' . "#__content" . '"',
            $db->quoteName('referenceOption') . ' = "com_content"',
            $db->quoteName('referenceView') . ' = "article"',
            $db->quoteName('referenceLayout') . ' = "edit"',
         );
         //var_dump($xml); die("fff");
         $query->update($db->quoteName('#__jd_store'))->set($fields)->where($conditions);
         //echo $query->dump(); die("d");
         $db->setQuery($query);
         $result = $db->execute();
       }
       else 
       {    //die("fff");
            $db = JFactory::getDbo();       
            $request = $article;    
            $lang = $request->language;
            switch ($lang) {
                case "en-GB":
                    $langId = 1;
                    break;
                case "fr-FR":
                    $langId = 2;
                    break;
                case "it-IT":
                    $langId = 3;
                    break;
                case "de-DE":
                    $langId = 4;
                    break;
            }
                
            $xml = '<?xml version="1.0"?><jDiction><title><![CDATA[]]></title><alias><![CDATA[]]></alias><articletext><![CDATA[]]></articletext></jDiction>';                           
             if ( isset($request) )
             {
                $xml = '<?xml version="1.0"?><jDiction><title><![CDATA[' . $request->title .']]></title><alias><![CDATA[' . $request->alias .']]></alias><articletext><![CDATA[' . $request->articletext .']]></articletext></jDiction>';
             }
            
            $referTable = '#__content';
                //insert new
                $query = $db->getQuery(true);
                $columns = array('idLang', 'idReference', 'referenceTable', 'value', 'modified_by', 'referenceOption' , 'referenceView', 'referenceLayout');
                $values = array(intval($langId), intval($request->id), $db->quote($referTable), $db->quote($xml), $db->quote(date("Y-m-d H:i:s")), $db->quote('com_content') , "'article'", "'edit'");
                $query
                    ->insert($db->quoteName('#__jd_store'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
            }         
       }

    }

}
?>