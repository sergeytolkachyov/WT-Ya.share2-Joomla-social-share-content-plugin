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

defined('_JEXEC') || die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Content\Wt_ya_share2\Extension\Wt_ya_share2;

return new class () implements ServiceProviderInterface {
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function register(Container $container)
	{
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				$subject = $container->get(DispatcherInterface::class);
				$config  = (array) PluginHelper::getPlugin('content', 'wt_ya_share2');
				$plugin = new Wt_ya_share2($subject, $config);
				$plugin->setApplication(Factory::getApplication());
				return $plugin;
			}
		);
	}
};