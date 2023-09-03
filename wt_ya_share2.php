<?php

/**
 * @package       Content - WT Ya.share2
 * @author        Sergey Tolkachyov, info@web-tolk.ru, https://web-tolk.ru
 * @copyright     Copyright (C) 2022 Sergey Tolkachyov. All rights reserved.
 * @license       GNU General Public License version 3 or later
 * @version       1.0.2
 * @since         1.0.0
 * @link          https://web-tolk.ru/en/dev/joomla-plugins/wt-ya-share2-social-share-joomla-plugin
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Version;


defined('_JEXEC') or die;


class PlgContentWt_ya_share2 extends CMSPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Displays the voting area if in an article
	 *
	 * @param   string    $context  The context of the content being passed to the plugin
	 * @param   object   &$row      The article object
	 * @param   object   &$params   The article params
	 * @param   integer   $page     The 'page' number
	 *
	 * @return  mixed  html string containing code for the votes if in com_content else boolean false
	 *
	 * @since   1.6
	 */
	public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
	{
		if ((new Version())->isCompatible('4.0') == true)
		{
			$view = Factory::getApplication()->getInput()->getString('view');
		}
		else
		{
			$view = Factory::getApplication()->input->getString('view');
		}
		if ($view === 'article' && $this->params->get('button_share_article_position', 'before_display_content') == 'before_display_content')
		{
			return $this->showShareButton($context, $row, $params, $limitstart = 0);
		}

		if ($view === 'category' && $this->params->get('button_share_category_position', 'before_display_content') == 'before_display_content')
		{
			return $this->showShareButton($context, $row, $params, $limitstart = 0);
		}

	}

	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $row         The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterDisplay($context, $row, $params, $limitstart = 0)
	{
		if ((new Version())->isCompatible('4.0') == true)
		{
			$view = Factory::getApplication()->getInput()->getString('view');
		}
		else
		{
			$view = Factory::getApplication()->input->getString('view');
		}
		if ($view === 'article' && $this->params->get('button_share_article_position', 'before_display_content') == 'after_display_content')
		{
			return $this->showShareButton($context, $row, $params, $limitstart = 0);
		}

		if ($view === 'category' && $this->params->get('button_share_category_position', 'before_display_content') == 'after_display_content')
		{
			return $this->showShareButton($context, $row, $params, $limitstart = 0);
		}
	}

	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $row         The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterTitle($context, $row, $params, $limitstart = 0)
	{
		if ((new Version())->isCompatible('4.0') == true)
		{
			$view = Factory::getApplication()->getInput()->getString('view');
		}
		else
		{
			$view = Factory::getApplication()->input->getString('view');
		}

		if ($view === 'article' && $this->params->get('button_share_article_position', 'before_display_content') == 'after_display_title')
		{
			return $this->showShareButton($context, $row, $params, $limitstart = 0);
		}

		if ($view === 'category' && $this->params->get('button_share_category_position', 'before_display_content') == 'after_display_title')
		{
			return $this->showShareButton($context, $row, $params, $limitstart = 0);
		}
	}

	public function showShareButton($context, &$row, &$params, $limitstart = 0)
	{
		$parts = explode(".", $context);

		if ($parts[0] != 'com_content')
		{
			return false;
		}

		$category_exclude = $this->params->get('category_exclude');
		if (!is_array($category_exclude))
		{
			$category_exclude = array();
		}

		if (!in_array($row->catid, $category_exclude))
		{
			if ((new Version())->isCompatible('4.0') == true)
			{
				/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
				$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
				if ($this->params->get('old_browsers_support', 0) == '1')
				{
					$wa->registerAndUseScript('ya_share2_es5_shims', 'https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js', [], ['defer' => true]);
				}
				$wa->registerAndUseScript('ya_share2', 'https://yastatic.net/share2/share.js', [], ['defer' => true]);

			}
			else
			{
				if ($this->params->get('old_browsers_support', 0) == '1')
				{
					Factory::getDocument()->addScript('https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js', [
						'version'  => 'auto',
						'relative' => false
					], [
						'defer' => true
					]);

				}
				else
				{
					Factory::getDocument()->addScript('https://yastatic.net/share2/share.js', [
						'version'  => 'auto',
						'relative' => false,
					],
						[
							'defer' => true
						]);
				}

			}


			if ($context !== 'com_content.categories')
			{

				$layoutId    = $this->params->get('layout', 'default');
				$layout      = new FileLayout($layoutId, JPATH_SITE . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'wt_ya_share2' . DIRECTORY_SEPARATOR . 'tmpl');
				$curent_lang = Factory::getLanguage()->getLocale();

				//Article intro text
				if (!empty($row->introtext))
				{
					(int) $intro_text_max_lenght = $this->params->get('article_intro_text_max_chars', 100);

					$article_intro_text = trim(strip_tags(html_entity_decode($row->introtext, ENT_QUOTES, 'UTF-8')));
					$article_intro_text = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '   '), ' ', $article_intro_text);
					if ($intro_text_max_lenght > 3)
					{
						$intro_text_max_lenght = $intro_text_max_lenght - 3; // For '...' in the end of string
					}
					$article_intro_text = mb_substr($article_intro_text, 0, $intro_text_max_lenght, 'utf-8');
					$article_intro_text = $article_intro_text . '...';

				}
				else
				{
					$article_intro_text = '';
				}
				if (!empty($this->params->get('socials')))
				{
					$displayData = [
						'color-scheme'    => $this->params->get('color-scheme', 'classic'),
						'socials'         => implode(',', $this->params->get('socials')),
						'limit'           => $this->params->get('limit', 3),
						'size'            => $this->params->get('size', 's'),
						'lang'            => isset($curent_lang[8]) ? $curent_lang[8] : '',
						'shape'           => $this->params->get('shape', 'normal'),
						'pinterest_image' => $this->params->get('pinterest_image'),
						'url'             => Route::_('index.php?option=com_content&view=article&id=' . $row->id . '&catid=' . $row->catid, '', '', 1),
						'title'           => $row->title,
						'description'     => $article_intro_text,
						'id'              => $row->id,
					];
				}
				else
				{
					$displayData = [];
				}


				return $layout->render($displayData);
			}

			return false;

		}

	}

}
