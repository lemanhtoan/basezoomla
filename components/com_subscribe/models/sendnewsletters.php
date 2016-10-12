<?php

/**
 * @version     1.0.0
 * @package     com_subscribe
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Phong Tranh <phongtranh68@gmail.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Subscribe records.
 */
class SubscribeModelSendnewsletters extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				
			);
		}
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{


		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');
		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');
		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		

		$this->setState('list.ordering', $list['ordering']);
		$this->setState('list.direction', $list['direction']);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery() {
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);	return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;
		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value))
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}
		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_SUBSCRIBE_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in an specified format (YYYY-MM-DD)
	 *
	 * @param string Contains the date to be checked
	 *
	 */
	private function isValidDate($date)
	{
		return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
	}

	public function sendMail() {
        //$blog_articles = $this->getBlogArticles(43);
        //echo '<pre>'; print_r($blog_articles['de-DE']); die;
		//echo JRoute::_('index.php?option=com_subscribe&task=sendnewsletters.sendmail'); die;
        // Check time to sent
        $times = $this->getTime2Send();
        $current_time = date('Y-m-d H:i:s'); echo $current_time;
        $dt1 = new Datetime($current_time);
        $session = JFactory::getSession();
        $send_mail = false; 
        $send_newsleter_id = 0;
        foreach($times as $time) {
            $dt2 = new Datetime(date('Y-m-d H:i:s', strtotime($time->send_time)));
            $dt_left = date_diff($dt1, $dt2);
            //var_dump($dt_left->format('%Y%m%d%H%i'));
            $dt_left = (int)$dt_left->format('%Y%m%d%H%i');

            if($dt_left > 0 && $dt_left <= 1) {
                $send_newsleter_id = $time->id;
                if(!$this->checkSent($send_newsleter_id)) {
                    $send_mail = true;
                    break;
                }
            }
        }
        //$send_mail = true;
        //var_dump($send_mail);
        $count_success = 0;
        if($send_mail == true) {
            $app = JFactory::getApplication();
            $mailfrom = $app->get('mailfrom');
            $params = $this->getModuleParams();
            $params_setting = $params[0];
            $params_setting_arr = json_decode($params_setting, true);

            $mail_list = $this->getSubscribeEmails();
            // Begin mail
            foreach($mail_list as $mail) {
            	// Mail body
            	$mail_body = $this->parepareMailContent($send_newsleter_id, $mail->email, $mail->language);
                if(!$mail_body) {
                    $mail_body = '';
                }
                $mailer = JFactory::getMailer();
                $mailer->isHTML(true);
                $mailer->addRecipient($mail->email);
                $mailer->addReplyTo($params_setting_arr['reply_to_email'], $params_setting_arr['reply_to_name']);
                $mailer->setSender(array($mailfrom, $params_setting_arr['email_sendor_name']), array($mailfrom, $params_setting_arr['email_sendor_name']));
                $mailer->setSubject($params_setting_arr['email_subject']);
                $mailer->setBody($mail_body);
                $sent = $mailer->Send();
                if($sent) {
                    $count_success++;
                }
            }
            if($count_success > 0) {
                $update_data = new stdClass();
                $update_data->is_sent = 1;
                $update_data->id = $send_newsleter_id;
                JFactory::getDbo()->updateObject('#__send_newsletter', $update_data, 'id');
                //var_dump($count_success);
                die;
            }
        }
        die;
    }

    public function getBlogArticles($send_newsleter_id) {
        $list_by_lang = array();

        // Get list blog id
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('blog_articles')
                ->from('#__send_newsletter')
                ->where('id = '.intval($send_newsleter_id));
        $db->setQuery($query);
        $list_id = $db->loadResult();

        // Get all selected blog
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('ExtractValue(value, "//name") name, 
                    ExtractValue(value, "//description") description, 
                    ExtractValue(value, "//content") content, 
                    (select lang_code from #__languages where lang_id = idLang) language, 
                    (select image from zfg_blog where id = idReference) image, 
                    (select created_date from zfg_blog where id = idReference) create_date')
            ->from('#__jd_store')
            ->where('idReference IN ('.$list_id.') AND referenceView = "blog"')
            ->order('idReference ASC');
        $db->setQuery($query);
        $blogs = $db->loadObjectList();
        $count_blogs = count($blogs);
        for($i = 0; $i < $count_blogs; $i++) {
            switch($blogs[$i]->language) {
                case 'en-GB': $list_by_lang['en-GB'][] = $blogs[$i]; break;
                case 'de-DE': $list_by_lang['de-DE'][] = $blogs[$i]; break;
                case 'fr-FR': $list_by_lang['fr-FR'][] = $blogs[$i]; break;
                case 'it-IT': $list_by_lang['it-IT'][] = $blogs[$i]; break;
            }
        }

        return $list_by_lang;
    }

    public function getSubscribeEmails() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('email, language')
            ->from('#__subscribe')
            ->where('state = 1');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function getModuleParams() {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('params')
            ->from('#__modules')
            ->where('module = "mod_setting" AND published = 1')
            ->union("SELECT params from #__modules WHERE module=\"mod_social\"");
        $db->setQuery($query);
        $results = $db->loadObjectList(); 
        $result = array();
        foreach($results as $r) {
            $result[] = $r->params;
        }
        return $result;
    }

    public function parepareMailContent($send_newsleter_id, $email, $lang_code) {
        // Get blog articles
        $blog_articles = $this->getBlogArticles($send_newsleter_id);
        $blog_articles = $blog_articles[$lang_code];
        // Language tag
        //$lang = JFactory::getLanguage();
        
        $lang_tag = str_replace('-', '_', $lang_code);
        // Get module params
        $params = $this->getModuleParams();
        $params_setting = $params[0];
        $params_social = $params[1];
        $params_setting_arr = json_decode($params_setting, true);
        $params_social_arr = json_decode($params_social, true);
        $email_template_content = $params_setting_arr['email_template_content_'.$lang_tag];
        $image_facebook = $params_social_arr['image_facebook'];
        $link_facebook = $params_social_arr['link_facebook'];
        $image_twitter = $params_social_arr['image_twitter'];
        $link_twitter = $params_social_arr['link_twitter'];
        // Replace tag
        $start = strpos($email_template_content, '<p>[blog_post_list_begin]</p>');
        $end = strpos($email_template_content, '<p>[blog_post_list_end]</p>');
        $postContent = '';
        $homepage_link = JUri::base();
        $lang_prefix = '';
        switch($lang_code) {
            case 'en-GB': $lang_prefix = 'en'; break;
            case 'de-DE': $lang_prefix = 'de'; break;
            case 'fr-FR': $lang_prefix = 'fr'; break;
            case 'it-IT': $lang_prefix = 'it'; break;
            default: $lang_prefix = 'en';

        }
        if (false !== $start && false !== $end) {
            $end += 27;
            $postContentTemplate = substr($email_template_content, $start, $end - $start);
            $replaces = array(
                '<p>[blog_post_list_begin]</p>' => '',
                '<p>[blog_post_list_end]</p>' => '',
            );
            foreach ($blog_articles as $post) {
                $post->name = empty($post->name) ? '&nbsp;' : $post->name;
                $post->description = empty($post->description) ? '&nbsp;' : $post->description;
                $post->content = empty($post->content) ? '&nbsp;' : $post->content;

                $link = $homepage_link.$lang_prefix.'/blog.html';
                $replaces['[name]'] = '<a target="_blank" style="font-size: 18px; text-decoration: none; text-transform: uppercase; color: #333; vertical-align: top; line-height: 25px; display: block; margin-bottom: 10px;" href="'.$link.'">'.$post->name.'</a>';
                $replaces['[link]'] = '<a target="_blank" href="'.$link.'" title="'.$post->name.'">'.$post->name.'</a>';
                $replaces['[create_date]'] = '<span class="date" style="text-transform: uppercase">'.date('F', strtotime($post->create_date)).'<br />'.date('d', strtotime($post->create_date)).'</span>';
                $replaces['[image]'] = '';
                if ($post->image) {
                    $replaces['[image]'] = '<a class="thumb" style="display: block; margin-bottom: 10px;" href="'.$link.'"><img src="'.$homepage_link.$post->image.'" style="max-width: 100%;"/></a>';
                }
                $replaces['[readmore]'] = '<a target="_blank" class="read-more" style="display: inline-block; padding: 5px 25px; color: #333; border: 1px solid #333; border-radius: 20px; text-decoration: none;" href="'.$link.'">Read more</a>';

                $replaces['[description]'] = $post->content;
                $content = str_replace(array_keys($replaces), array_values($replaces), $postContentTemplate);
                $postContent .= '<p></p>' . $content . '<p></p>';
            }

            $postContentTemplate = str_replace($postContentTemplate, $postContent, $email_template_content);

            $email_template_content = str_replace(array('[viewall]'), array('<a target="_blank" class="view-all" style="text-align: center; display: block;margin-top: 50px;" href="'.$homepage_link.$lang_prefix.'/blog.html'.'"><span style="background: #333; display: inline-block; line-height: 50px; width: 400px; border-radius: 30px; font-size: 25px; color: #dfdfdf;">View all</span></a>'), $postContentTemplate);

            $email_template_content = str_replace(array('[base_url]'), array($homepage_link), $email_template_content);

            $email_template_content = str_replace(array('[Facebook]'), array('<a href="'.$link_facebook.'" title="Facebook"><img src="'.$homepage_link.$image_facebook.'" alt="Facebook" /></a>'), $email_template_content);

            $email_template_content = str_replace(array('[Twitter]'), array('<a href="'.$link_twitter.'" title="Twitter"><img src="'.$homepage_link.$image_twitter.'" alt="Twitter" /></a>'), $email_template_content);

            $email_template_content = str_replace(array('[unsubscribe]'), array('<a style="text-decoration: none; color: #fff" href="http://'. $_SERVER['HTTP_HOST'] . JRoute::_('index.php?option=com_subscribe&task=sendnewsletters.unsubscribe&code='.$email) .'" title="Twitter">Unsubscribe</a>'), $email_template_content);

            return $email_template_content;
        }
        return false;
    }

    public function getTime2Send() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('id, send_time')
            ->from('#__send_newsletter')
            ->order('id DESC');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    public function checkSent($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('id')
            ->from('#__send_newsletter')
            ->where('id = '.$id.' AND is_sent = 0');
        $db->setQuery($query);
        $result = $db->loadColumn();
        if(count($result) > 0) {
            return false;
        }
        return true;
    }

    public function unSubscribe($email) {
    	$db = JFactory::getDbo();
    	$update_data = new stdClass();
	    $update_data->state = 0;
	    $update_data->email = $db->escape($email);
	    if(JFactory::getDbo()->updateObject('#__subscribe', $update_data, 'email')) {
	    	return true;
	    }
	    return false;
    }

    public function reSubscribe($email) {
    	$db = JFactory::getDbo();
    	$update_data = new stdClass();
	    $update_data->state = 1;
	    $update_data->email = $db->escape($email);
	    if(JFactory::getDbo()->updateObject('#__subscribe', $update_data, 'email')) {
	    	return true;
	    }
	    return false;
    }

}
