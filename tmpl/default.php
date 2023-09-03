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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die('Restricted access');
/**
 * @var $displayData array Digital sign data
 * Use
 *      echo '<pre>';
 *        print_r($displayData);
 *        echo '</pre>';
 *
 */

//echo '<pre>';
//print_r($displayData);
//echo '</pre>';
?>

<div class="ya-share2"
	 data-curtain
	<?php if(!empty($displayData['color-scheme']) && $displayData['color-scheme']!= 'classic'): ?>
		 data-color-scheme="<?php echo $displayData['color-scheme'];?>"
	<?php endif;?>
	<?php if(!empty($displayData['limit'])): ?>
	 	data-limit="<?php echo $displayData['limit'];?>"
	<?php endif;?>
	 <?php if(!empty($displayData['socials'])): ?>
	 	data-services="<?php echo $displayData['socials'];?>"
	 <?php endif;?>
	<?php if(!empty($displayData['size'])): ?>
		data-size="<?php echo $displayData['size'];?>"
	<?php endif;?>
	<?php if(!empty($displayData['lang'])): ?>
	 	data-lang="<?php echo $displayData['lang'];?>"
	 <?php endif;?>
	<?php if(!empty($displayData['shape'])): ?>
	 	data-shape="<?php echo $displayData['shape'];?>"
	 <?php endif;?>
	<?php if(!empty($displayData['pinterest_image'])): ?>
	 	data-image:pinterest="<?php echo Uri::root().'/'.$displayData['pinterest_image'];?>"
	<?php endif;?>
	<?php if(!empty($displayData['url'])): ?>
		data-url="<?php echo $displayData['url'];?>"
	<?php endif;?>
	<?php if(!empty($displayData['title'])): ?>
		data-title="<?php echo $displayData['title'];?>"
	<?php endif;?>
	<?php if(!empty($displayData['description'])): ?>
		data-description="<?php echo $displayData['description'];?>"
	<?php endif;?>
	></div>