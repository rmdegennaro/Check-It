<?php

/*
 * @version 3.0 FREE 
 * @package Booklibrary - property slideShow
 * @copyright 2012 OrdaSoft
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author 2012 Andrey Kvasnekskiy (akbet@ordasoft.com )
 * @description Booklibrary - property slideShow for Booklibrary Component
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL 

 */

// no direct access
defined('_JEXEC') or die('Restricted access');
if (defined("DS") != true) {
    define('DS', DIRECTORY_SEPARATOR);
}
require_once (dirname(__FILE__) . DS . 'helper.php');
// Include the syndicate functions only once
$slides = modBOOKSlideShowHelper ::getImagesFromBookSlideShow($params);
$app = JFactory::getApplication();
if ($slides == null) {
    $app->enqueueMessage(JText::_('MOD_BOOKSLIDESHOW_NO_CATEGORY_OR_ITEMS'), 'notice');
    return;
}

if (version_compare(JVERSION, '3.0', 'lt')) {
    JHTML::_('behavior.mootools');
} else {
    JHtml::_('behavior.framework');
}

if ($params->get('link_image', 1) == 2) {
    JHTML::_('behavior.modal');
    JHTML::_('behavior.mootools-uncompressed');
}
$document = JFactory::getDocument();
$document->addScript('modules/mod_bookslideshow/assets/slider.js');

if (!is_numeric($width = $params->get('image_width')))
    $width = 240;
if (!is_numeric($height = $params->get('image_height')))
    $height = 180;
if (!is_numeric($max = $params->get('count_book')))
    $max = 20;
if (!is_numeric($count = $params->get('visible_images')))
    $count = 3;
if (!is_numeric($spacing = $params->get('space_between_images')))
    $spacing = 3;
$moduleclass_sfx = $params->get('moduleclass_sfx');
if ($count > count($slides))
    $count = count($slides);
if ($count < 1)
    $count = 1;
if ($count > $max)
    $count = $max;
$mid = $module->id;
$slider_type = $params->get('slider_type', 0);
switch ($slider_type) {
    case 2:
        $slide_size = $width;
        $count = 1;
        break;
    case 1:
        $slide_size = $height + $spacing;
        break;
    case 0:
    default:
        $slide_size = $width + $spacing;
        break;
}

$animationOptions = modBOOKSlideShowHelper::getAnimationOptions($params);
$showB = $params->get('show_buttons', 1);
$showA = $params->get('show_arrows', 1);
if (!is_numeric($preload = $params->get('preload')))
    $preload = 800;
$moduleSettings = "{id: '$mid', slider_type: $slider_type, slide_size: $slide_size, visible_slides: $count, show_buttons: $showB, show_arrows: $showA, preload: $preload}";
$js = "window.addEvent('domready',function(){var Slider$mid = new BookSlideShow($moduleSettings,$animationOptions)});";
$js = "(function($){ " . $js . " })(document.id);";
$document->addScriptDeclaration($js);

$css = JURI::base() . 'modules/mod_bookslideshow/assets/style.css';
$document->addStyleSheet($css);

$css = modBOOKSlideShowHelper::getStyleSheet($params, $mid);
$document->addStyleDeclaration($css);

$navigation = modBOOKSlideShowHelper::getNavigation($params, $mid);

require(JModuleHelper::getLayoutPath('mod_bookslideshow'));