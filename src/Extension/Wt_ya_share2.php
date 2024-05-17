<?php
/**
 * @package       Content - WT Ya.share2
 * @author        Sergey Tolkachyov, info@web-tolk.ru, https://web-tolk.ru
 * @copyright     Copyright (C) 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU General Public License version 3 or later
 * @version       2.0.0
 * @since         1.0.0
 * @link          https://web-tolk.ru/en/dev/joomla-plugins/wt-ya-share2-social-share-joomla-plugin
 */

namespace Joomla\Plugin\Content\Wt_ya_share2\Extension;

use Joomla\CMS\Event\Content\AfterDisplayEvent;
use Joomla\CMS\Event\Content\AfterTitleEvent;
use Joomla\CMS\Event\Content\BeforeDisplayEvent;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route;
use Joomla\Event\SubscriberInterface;


defined('_JEXEC') or die;


final class Wt_ya_share2 extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   4.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onContentBeforeDisplay' => 'onContentBeforeDisplay',
			'onContentAfterDisplay'  => 'onContentAfterDisplay',
			'onContentAfterTitle'    => 'onContentAfterTitle',
		];
	}

	/**
	 * The display event.
	 *
	 * @param   BeforeDisplayEvent  $event  The event object
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onContentBeforeDisplay(BeforeDisplayEvent $event):void
	{
		$view = $this->getApplication()->getInput()->getString('view');
		if (
			($view === 'article' && $this->params->get('button_share_article_position', 'before_display_content') == 'before_display_content')
			||
			(($view === 'category' || $view === 'featured') && $this->params->get('button_share_category_position', 'before_display_content') == 'before_display_content')
		)
		{
			$event->addResult($this->showShareButton($event->getContext(), $event->getItem(), $event->getParams(), $event->getPage()));
		}


	}

	/**
	 * The display event.
	 *
	 * @param   AfterDisplayEvent  $event  The event object
	 *
	 * @return  void
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterDisplay(AfterDisplayEvent $event):void
	{
		$view = $this->getApplication()->getInput()->getString('view');
		if (
			($view === 'article' && $this->params->get('button_share_article_position', 'before_display_content') == 'after_display_content')
			||
			(($view === 'category' || $view === 'featured') && $this->params->get('button_share_category_position', 'before_display_content') == 'after_display_content')
		)
		{
			$event->addResult($this->showShareButton($event->getContext(), $event->getItem(), $event->getParams(), $event->getPage()));
		}
	}

	/**
	 *
	 * @param   AfterTitleEvent  $event
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function onContentAfterTitle(AfterTitleEvent $event): void
	{
		$view = $this->getApplication()->getInput()->getString('view');
		if (
			($view === 'article' && $this->params->get('button_share_article_position', 'before_display_content') == 'after_display_title')
			||
			(($view === 'category' || $view === 'featured') && $this->params->get('button_share_category_position', 'before_display_content') == 'after_display_title')
		)
		{
			$event->addResult($this->showShareButton($event->getContext(), $event->getItem(), $event->getParams(), $event->getPage()));
		}
	}

	public function showShareButton($context, $row, $params, $limitstart = 0): string
	{
		$parts = explode(".", $context);

		if ($parts[0] != 'com_content')
		{
			return '';
		}

		$category_exclude = $this->params->get('category_exclude');
		if (!is_array($category_exclude))
		{
			$category_exclude = [];
		}

		if (!in_array($row->catid, $category_exclude))
		{

				/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
				$wa = $this->getApplication()->getDocument()->getWebAssetManager();
				if ($this->params->get('old_browsers_support', 0) == '1')
				{
					$wa->registerAndUseScript('ya_share2_es5_shims', 'https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js', [], ['defer' => true]);
				}
				$wa->registerAndUseScript('ya_share2', 'https://yastatic.net/share2/share.js', [], ['defer' => true]);

			if ($context !== 'com_content.categories')
			{

				$layoutId    = $this->params->get('layout', 'default');
				$layout      = new FileLayout($layoutId, JPATH_SITE . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'wt_ya_share2' . DIRECTORY_SEPARATOR . 'tmpl');
				$curent_lang = $this->getApplication()->getLanguage()->getLocale();

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
					$article_intro_text = $row->title;
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
						'title'           => htmlspecialchars($row->title,ENT_COMPAT, 'UTF-8'),
						'description'     => htmlspecialchars($article_intro_text, ENT_COMPAT, 'UTF-8'),
						'id'              => $row->id,
					];
				}
				else
				{
					$displayData = [];
				}

				return $layout->render($displayData);
			}

		}

		return '';
	}
}
