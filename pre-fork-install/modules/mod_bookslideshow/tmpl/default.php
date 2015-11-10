<?php
/*
 * @version 2.2 FREE
 * @package Booklibrary - property slideShow
 * @copyright 2012 OrdaSoft
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL 
 * @author 2012 Andrey Kvasnekskiy (akbet@ordasoft.com )
 * @description Book Library - property slideShow for Book Library Component
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div style ="position:relative; height:<?php echo $params->get('image_height') ?>px;" id="bookslideshow-module"<?php echo $moduleclass_sfx; ?>">
    <div id="bookslideshow-loader<?php echo $mid; ?>" class="bookslideshow-loader">
        <div id="bookslideshow<?php echo $mid; ?>" class="bookslideshow">
            <div id="slider-container<?php echo $mid; ?>" class="slider-container">
                <ul id="slider<?php echo $mid; ?>">
                    <?php
                    foreach ($slides as $slide) {
                        $db = jfactory::getDbo();
                        $query = 'SELECT imageURL from #__booklibrary WHERE id=' . $slide->id;
                        $db->setQuery($query);
                        $imageurl = $db->loadResult();

                        if (substr($imageurl, 0, 4) !== "http")
                            $imageurl = JURI::root() . $imageurl;
                        ?>
                        <li>
                                <?php if (($slide->link && $params->get('link_image', 1) == 1) || $params->get('link_image', 1) == 2) { ?>
                                <a <?php echo ($params->get('link_image', 1) == 2 ? 'class="modal"' : ''); ?> href="<?php echo ($params->get('link_image', 1) == 2 ? $slide->image : $slide->link); ?>" target="<?php echo $slide->target; ?>">
                            <?php } ?>
                                <img src="<?php echo $imageurl; ?>" alt="<?php echo $slide->alt; ?>" />
                            <?php if (($slide->link && $params->get('link_image', 1) == 1) || $params->get('link_image', 1) == 2) { ?>
                                </a>
                            <?php } ?>

                            <?php
                            if ($params->get('show_title')
                                    || ($params->get('show_desc') && !empty($slide->description))
                                    || ($params->get('show_price') && !empty($slide->price))
                            ) {
                                ?>
                                <!-- Slide description area: START -->
                                <div class="slide-desc">
                                    <div class="slide-desc-in">	
                                        <div class="slide-desc-bg"></div>
                                        <div class="slide-desc-text">
                                            <?php if ($params->get('show_title')) { ?>
                                                <div class="slide-title">
                                                <?php if ($params->get('link_title') && $slide->link) { ?><a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>"><?php } ?>
                                                    <?php echo $slide->title; ?>
                                                    <?php if ($params->get('link_title') && $slide->link) { ?></a><?php } ?>
                                                </div> 
                                                    <?php } ?>
                                                <?php if ($params->get('show_price') && !empty($slide->price)) { ?>
                                                <div class="slide-text">
                                                    <?php if ($params->get('link_price') && $slide->link) { ?>
                                                        <a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>">
                                                    <?php echo strip_tags($slide->price); ?>
                                                        </a>
                                                <?php } else { ?>
                                                    <?php echo strip_tags($slide->price); ?>
                                                    <?php } ?>
                                                </div>
                                                    <?php } ?>

                                                <?php if ($params->get('show_desc')) { ?>
                                                <div class="slide-text">
                                                    <?php if ($params->get('link_desc') && $slide->link) { ?>
                                                        <a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>">
                                                    <?php echo strip_tags($slide->description); ?>
                                                        </a>
            <?php } else { ?>
                                                        <!--         <?php echo strip_tags($slide->description); ?> -->
            <?php } ?>
                                                </div>
        <?php } ?>

                                            <div style="clear: both"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Slide description area: END -->
    <?php } ?>						
                        </li>
<?php } ?>
                </ul>
            </div>
            <div id="navigation<?php echo $mid; ?>" class="navigation-container">
                <img id="prev<?php echo $mid; ?>" class="prev-button" src="<?php echo $navigation->prev; ?>" alt="<?php echo JText::_('MOD_BOOKSLIDESHOW_PREVIOUS'); ?>" />
                <img id="next<?php echo $mid; ?>" class="next-button" src="<?php echo $navigation->next; ?>" alt="<?php echo JText::_('MOD_BOOKSLIDESHOW_NEXT'); ?>" />
                <img id="play<?php echo $mid; ?>" class="play-button" src="<?php echo $navigation->play; ?>" alt="<?php echo JText::_('MOD_BOOKSLIDESHOW_PLAY'); ?>" />
                <img id="pause<?php echo $mid; ?>" class="pause-button" src="<?php echo $navigation->pause; ?>" alt="<?php echo JText::_('MOD_BOOKSLIDESHOW_PAUSE'); ?>" />
            </div>
            <div id="cust-navigation<?php echo $mid; ?>" class="navigation-container-custom">
<?php $i = 0;
foreach ($slides as $slide) { ?>
                    <span class="load-button<?php if ($i == 0) echo ' load-button-active'; ?>"></span>
    <?php if (count($slides) == $i + $count) break; else $i++;
} ?>
            </div>
        </div>
    </div>

    <div style="clear: both"></div>
</div>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>

