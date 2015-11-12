<?php 

if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
 *
 * @package BookLibrary
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com); 
 * Homepage: http://www.ordasoft.com
 * @version: 3.0 Free
 * @license GNU General Public license version 2 or later; see LICENSE.txt
 * */

if ($book->published == 0)
    JError::raiseError(404, _BOOKLIBRARY_RESULT_NOT_FOUND);

global $doc, $hide_js, $mainframe, $Itemid, $booklibrary_configuration, $mosConfig_live_site, $mosConfig_absolute_path, $my, $option, $database;

$doc->addStyleSheet($mosConfig_live_site . '/components/com_booklibrary/includes/booklibrary.css');
JPluginHelper::importPlugin('content');
$dispatcher = JDispatcher::getInstance();
?>

<script language="javascript" type="text/javascript">

    function review_submitbutton() {
        var form = document.review;
        // do field validation
        var rating_checked = false;
        for (c = 0;  c < form.rating.length; c++){
            if (form.rating[c].checked){
                rating_checked = true;
            } 
        }
        if (form.title.value == "") {
            alert( "<?php echo _BOOKLIBRARY_INFOTEXT_JS_REVIEW_TITLE; ?>" );
        } else if (form.comment == "") {
            alert( "<?php echo _BOOKLIBRARY_INFOTEXT_JS_REVIEW_COMMENT; ?>" );
        } else if (!rating_checked) {				
            alert( "<?php echo _BOOKLIBRARY_INFOTEXT_JS_REVIEW_RATING; ?>" );
        } else {
            form.submit();
        }
    }
    //*****************   begin add for show/hiden button "Add review" ********************
    function button_hidden( is_hide ) {
        var el  = document.getElementById('button_hidden_review');
        var el2 = document.getElementById('hidden_review');
        if(is_hide){
            el.style.display = 'none';
            el2.style.display = 'block';
        } else {
            el.style.display = 'block';
            el2.style.display = 'none';
        }
    }
    //****************   end add for show/hiden button "Add review"   *********************
</script>

<?php
// for 1.6

$cat_link_arr = array();

foreach ($cat as $category) {
    $link = sefRelToAbs('index.php?option=' . $option . '&task=showCategory&catid=' . $category->catid . '&Itemid=' . $Itemid);
    $anchor = "<a href='$link'>{$category->name}</a>";
    $cat_link_arr[] = $anchor;
}

// -- 
?>
    <?php positions_bl($params->get('view01')); ?>
<div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
<?php echo $currentcat->header; ?>
</div>


<?php positions_bl($params->get('view02')); ?>

    <?php positions_bl($params->get('view03')); ?>

<table  class="basictable bl_view_book my_table" width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $params->get('pageclass_sfx'); ?>">
<?php if ($book->title != '') { ?>
        <tr>
            <td nowrap="nowrap" width="20%" align="right">       
                <strong><?php echo _BOOKLIBRARY_LABEL_TITLE; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td><?php echo $book->title; ?></td>
        </tr>
        <tr>
            <td nowrap="nowrap" width="20%" align="right">       
                <strong><?php echo _BOOKLIBRARY_CATEGORIES_NAME; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
    <?php echo implode(", ", $cat_link_arr); //for J 1.6  ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" width="20%" align="right">       
                <strong><?php echo _BOOKLIBRARY_LABEL_BOOKID; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
        <?php echo $book->bookid; ?>
            </td>
        </tr>
<!-- added dewey decimal code - 20150818 - Ralph deGennaro -->
        <tr>
            <td nowrap="nowrap" width="20%" align="right">       
                <strong><?php echo _BOOKLIBRARY_LABEL_DDCCODE; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
        <?php echo $book->ddccode; ?>
            </td>
        </tr>
<?php }
if ($book->authors != '') {
    ?>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_LABEL_AUTHORS; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
        <?php echo $book->authors; ?>
            </td>
        </tr>
<?php }
if ($book->isbn != '') {
    ?>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_LABEL_ISBN; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
        <?php echo $book->isbn; ?>
            </td>
        </tr>		
<?php }
if ($book->manufacturer != '') {
    ?>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_LABEL_MANUFACTURER; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
    <?php echo $book->manufacturer; ?>
            </td>
        </tr>
<?php }
if ($book->release_Date != '') {
    ?>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_LABEL_PUB_DATE; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
    <?php echo date(str_replace("%", "", $booklibrary_configuration['date_format']), strtotime($book->release_Date)); ?>
            </td>
        </tr>
            <?php }
            if ($book->edition != '') {
                ?>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_LABEL_EDITION; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
    <?php echo $book->edition; ?>
            </td>
        </tr>
            <?php }
            if ($book->numberOfPages != '') {
                ?>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_LABEL_NUMPAGES; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
    <?php echo $book->numberOfPages; ?>
            </td>
        </tr>
            <?php
            }
            if (isset($book->user_b)) {
                ?>

        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_OWNER_NAME; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
    <?php echo $book->user_b->name; ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_OWNER_EMAIL; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
    <?php echo $book->user_b->email; ?>
            </td>
        </tr>

            <?php }
            if ($book_lang != '') {
                ?>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_LABEL_LANGUAGE; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
    <?php
    echo $book_lang;
    ?>
            </td>
        </tr>
            <?php } ?>

            <?php if ($params->get('show_price') == '1') { ?>
        <tr>
            <td nowrap="nowrap" align="right">
                <strong><?php echo _BOOKLIBRARY_LABEL_PRICE; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
        <?php //echo $book->price; ?><?php //echo $book->priceunit; ?>
    <?php
    if ($booklibrary_configuration['price_unit_show'] == '1')
        echo formatMoney($book->price, true, $booklibrary_configuration['price_format']) . " " . $book->priceunit;
    else
        echo $book->priceunit . " " . formatMoney($book->price, true, $booklibrary_configuration['price_format']);
    ?>
            </td>
        </tr>
<?php } ?>
    <tr>
        <td nowrap="nowrap" align="right">
            <strong><?php echo _BOOKLIBRARY_LABEL_RATING; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
        </td>
        <td>
            <img src="<?php echo $mosConfig_live_site; ?>/components/com_booklibrary/images/rating-<?php echo $book->rating; ?>.gif" alt="<?php echo ($book->rating) / 2; ?>" border="0" />&nbsp;
        </td>
    </tr>		
    <tr>
        <td nowrap="nowrap" align="right" valign="top">
            <strong><?php echo _BOOKLIBRARY_LABEL_PICTURE; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
        </td>
        <td>
            <?php
            //for local images
            $imageURL = $book->imageURL;
            if ($imageURL != '' && substr($imageURL, 0, 4) != "http") {
                $imageURL = $mosConfig_live_site . '' . $book->imageURL;
                ;
            }

            if ($imageURL != '') {
                echo '<img class="bl_view_book_book_image" src="' . $imageURL . '" alt="cover" border="0" height="' . $booklibrary_configuration['foto']['high'] . '" width="' . $booklibrary_configuration['foto']['width'] . '"/>';
            } else {
                echo '<img class="bl_view_book_book_image" src="' . $mosConfig_live_site . '/components/com_booklibrary/images/' . _BOOKLIBRARY_NO_PICTURE . '" alt="no-img_eng.gif" border="0" />';
            }
            ?>


            <!--************   begin add button 'buy now'   ************************-->

<?php
//show button 'buy now'
if ($params->get('show_input_buy_now')) {
    if ($book->URL != '') {
        $database->setQuery("SELECT URL FROM #__booklibrary WHERE id=" . $book->id . ";");
        $direct_url = $database->loadResult();
?>

                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <a class="bl_view_book_buy_now my_btn my_btn-success my_btn-large" href="<?php echo $direct_url; ?>" target="blank">
                            <!--<img src="<?php echo $mosConfig_live_site; ?>/components/com_booklibrary/images/amazon/buy_now.png" alt="Button Buy now" border="0" height="27" width="82" />-->
                        <img src="<?php echo $mosConfig_live_site; ?>/components/com_booklibrary/images/basket.png" alt="Button Buy now" border="0" height="27" width="82" />
                        Buy now</a>

        <?php
        }
    }
    ?>
            <!--************************   end add button 'buy now'   ******************-->
<!--************   begin add button 'buy now vm'   ************************-->

  
<!--************************   end add button 'buy now vm'   ******************-->
        </td>
    </tr>
    <!--************   begin add to cart'   ************************-->

    <!--************   end add to cart'   ************************-->
<?php
if ($params->get('show_ebooksrequest') && $book->ebookURL != null) {

    ?>
        <tr>
            <td align="right" >
                <strong><?php echo _BOOKLIBRARY_LABEL_EBOOK; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td align ="left">
                <?php
                $j = 0;
                $endFile='';
		foreach($book->ebookURL as $efile) {
                    $endFile = pathinfo($efile->location, PATHINFO_EXTENSION);
                        if (strstr($efile->location,"http")) {
                            echo "<a href='".$efile->location."' target='new' >"._BOOKLIBRARY_LABEL_EBOOK_DOWNLOAD.++$j."</a>"; 
			}  
			else  {
                            echo "<a href='".$mosConfig_live_site.$efile->location."' target='new' >"._BOOKLIBRARY_LABEL_EBOOK_DOWNLOAD.++$j.'.'.$endFile."</a><br>"; 
			} 

                } ?>
            </td>
        </tr>	
        <?php
    }
    ?>
    <?php
    //lend out?? 
    if ($params->get('show_lendstatus') && $params->get('show_lendrequest')) {
        $data1 = JFactory::getDBO();
        $query = "SELECT  b.lend_from , b.lend_until  FROM #__booklibrary_lend  AS b LEFT JOIN #__booklibrary AS c ON b.fk_bookid = c.id WHERE  c.id=" . $book->id . " AND c.published='1' AND c.approved='1' AND b.lend_return IS NULL";

        $data1->setQuery($query);
        $rents1 = $data1->loadObjectList();
        ?>

        <?php
        if (count($rents1) == 0) {
            ?>
            <?php
        } else {
            echo '<tr><td align="right"><strong>' . _BOOKLIBRARY_LABEL_LEND_FROM_UNTIL . ':&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</strong></td>' . "<br />";
            echo '<td>';
            for ($a = 0; $a < count($rents1); $a++) {

                $from_until = date(str_replace("%", "", $booklibrary_configuration['date_format']), strtotime($rents1[$a]->lend_from)) .
                        "&nbsp;/&nbsp;" .
                        date(str_replace("%", "", $booklibrary_configuration['date_format']), strtotime($rents1[$a]->lend_until))
                        . "<br />";
                print_r($from_until);
            }
            echo '</td>';
        }
        if ($params->get('lend_save')) {

            $available = true;
        } else {

            $available = false;
        }
        ?>
        <br>

            <?php }
            if ($book->comment != '') {
                ?>
        <tr>
            <td align="right" valign="top">
                <strong><?php echo _BOOKLIBRARY_LABEL_COMMENT; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
            </td>
            <td>
                <?php
                JPluginHelper::importPlugin('content');
                $dispatcher = JDispatcher::getInstance();
                positions_bl($params->get('viewdescription'));
                $err_state = ini_get('display_errors');
                ini_set('display_errors', 'Off');
                $idesc = $book->comment; //load the desc into a variable
                $plug_row->text = $idesc; // load the var into plugin_row object
                $results = $dispatcher->trigger('onPrepareContent', array(&$plug_row, &$plug_params), true); //run mambot onPrepareContent on plug_row object
                $idesc = $plug_row->text; //get new content from plug_row object to value
                echo $idesc; //echo new content out

                ini_set('display_errors', $err_state);
                ?>
            </td>
        </tr>
            <?php if ($book->owneremail != '' && $booklibrary_configuration['owner']['show']) { ?>
            <tr>
                <td align="right">
                    <strong><?php echo _BOOKLIBRARY_LABEL_BOOKOWNER; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
                </td>
                <td>
        <?php echo $book->ownername; ?>
                </td>
            </tr>
                    <?php } ?>
        <tr>
<?php } ?>
    </tr>	
</table>
<form id="adminForm" action="<?php echo sefRelToAbs("index.php?option=com_booklibrary&task=lend_request&Itemid=$Itemid"); ?>" method="post" name="book" id="book">
    <table class="bl_view_book_land_request basictable" >
        <tr>
            <td >
<?php if ($params->get('show_lendrequest') && $params->get('show_lendstatus')) { ?>
                    <div class="my_btn my_btn-primary bl_view_book_land_request_button">
                        <input type="submit" name="submit" value="<?php echo _BOOKLIBRARY_LABEL_BUTTON_LEND_REQU; ?>" 
                               class="button bl_view_book_land_request_button" onclick="document.book.submit()"/>	
                    </div>		
                    <?php
                }
                ?>
            </td>
            <td align="right">
<?php
// displays back button
mosHTML::BackButton($params, $hide_js);
?>
            </td>
        </tr>
    </table>
    <input type="hidden" name="bid[]" value="<?php echo $book->id; ?>" />
</form>
    <?php
    //sow the reviews

    if ($params->get('show_reviews')) {
        $reviews = $book->getReviews();
        //print_r($reviews);exit;
        ?>
    <br />
    <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
        <?php echo _BOOKLIBRARY_LABEL_REVIEWS; ?>
    </div>
    <table  class="bl_bl_view_book_reviews_table basictable" width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="front-end-reviews contentpane<?php echo $params->get('pageclass_sfx'); ?>">
    <?php
    if ($reviews != null && count($reviews) > 0) {
        for ($m = 0, $n = count($reviews); $m < $n; $m++) {
            $review = $reviews[$m];
         //   if ($review->published) {
                ?>
                    <tr class="line-1">
                        <td colspan="3" class="col-1">
                            <strong><?php echo $review->title; ?></strong>
                        </td>
                    </tr>

                    <tr class="line-2">	
                        <td class="col-1"><?php echo data_transformer($review->date); ?></td>	
                    </tr>

                    <tr class="line-3">
                        <td class="col-2">
                <?php $help = $review->getReviewFrom();
                echo $help['name']; ?>
                        </td>
                    </tr>

                    <tr class="line-4">	
                        <td class="col-3">
                            <img src="<?php echo $mosConfig_live_site; ?>/components/com_booklibrary/images/rating-<?php echo $review->rating; ?>.gif" alt="<?php echo ($review->rating) / 2; ?>" border="0" align="left"/>
                        </td>
                    </tr>

                    <tr class="line-5">
                        <td colspan="3" class="col-1">
                <?php echo $review->comment; ?>
                        </td>
                    </tr>

                    <tr style="background:transparent !important;">
                        <td style="background:transparent !important;">&nbsp;</td>
                    </tr>
            <?php //}
        }
    } ?>
    </table>     
    <?php positions_bl($params->get('view04')); ?>
            <?php
            if ($params->get('show_inputreviews')) {
                ?>
        <!--***********   begin add for show/hiden button "Add review"   ***********************-->
        <div id ="button_hidden_review" class="my_btn my_btn-primary bl_view_book_add_review_button" style="<?php if (isset($_REQUEST['err_msg'])) {
                    echo 'display:none';
                } ?>">
            <input type="submit" name="submit" value="<?php echo _BOOKLIBRARY_LABEL_BUTTON_ADD_REVIEW; ?>" class="button bl_view_book_add_review_button" onclick="javascript:button_hidden(true)"/>
        </div>
        <!--***********   end add for show/hiden button "Add review"   ************************-->

        <div id="hidden_review" style="<?php if (isset($_REQUEST['err_msg'])) {
                    echo 'display:block';
                } else {
                    echo 'display:none';
                } ?>">
            <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
                            <?php 
                            echo _BOOKLIBRARY_LABEL_ADDREVIEW; ?>
            </div>

            <form id="adminForm" action="<?php echo sefRelToAbs("index.php?option=com_booklibrary&task=review&id=" . $_REQUEST['id'] . "&catid=" . $_REQUEST['catid'] . "&Itemid=" . $Itemid); ?>" method="post" name="review">
                <table  class="my_table bl_view_book_add_review_table basictable" width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $params->get('pageclass_sfx'); ?>">
                    <tr>
                        <td style="text-align:left;padding:0px;display:inline-block;width:125px;">
        <?php echo _BOOKLIBRARY_LABEL_REVIEW_TITLE; ?>:&nbsp;
                        </td>				
                        <td style="text-align:left;padding:5px 0px 0px 0px;display:inline-block;width:230px;">					
                            <input class="inputbox" type="text" name="title" size="80" value="<?php if (isset($_REQUEST["title"])) {
            echo $_REQUEST["title"];
        } ?>" />
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align:left;padding:0px;display:inline-block;width:125px;">
                            <?php echo _BOOKLIBRARY_LABEL_REVIEW_COMMENT; ?>
                        </td>
                        <td style="text-align:left;padding:5px 0px 0px 0px;display:inline-block;width:230px;"> 			
                            <textarea align= "top" name="comment" id="comment" cols="60" rows="10" style="width:400;height:100;" value="<?php if (isset($_REQUEST["comment"])) {
                        echo $_REQUEST["comment"];
                    } ?>"/></textarea>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align:left;padding:0px;display:inline-block;width:125px;">
        <?php echo _BOOKLIBRARY_LABEL_REVIEW_RATING; ?>
                        </td>
                        <td style="text-align:left;padding:5px 0px 0px 0px;display:inline-block;width:230px;">  
                            <?php
                            $k = 0;
                            while ($k < 11) {
                                ?>
                                <input type="radio" name="rating" value="<?php echo $k; ?>" <?php if (isset($_REQUEST["rating"]) && $_REQUEST["rating"] == $k) {
                        echo "CHECKED";
                    } ?> alt="Rating" />
                                <img src="<?php echo $mosConfig_live_site; ?>/components/com_booklibrary/images/rating-<?php echo $k; ?>.gif" alt="<?php echo ($k) / 2; ?>" border="0" /><br />
                                <?php
                                $k++;
                            }
                            ?>
                        </td>
                    </tr>
                    <!--*********************************   begin add antispam guest   *****************************-->
                   
                    <!--****************************   end add antispam guest   ******************************-->



                    <tr>
                        <td class="bl_suggestion_save">
                            <!-- save review button-->
                            <input class="button my_btn my_btn-success" type="button" value="<?php echo _BOOKLIBRARY_LABEL_BUTTON_SAVE; ?>" onclick="review_submitbutton()"/>						
                        </td>
                        <td class="bl_suggestion_hide">
                            <!-- hifde review button-->
                            <input class="button my_btn my_btn-info" type="button" value="<?php echo _BOOKLIBRARY_LABEL_BUTTON_REVIEW_HIDE; ?>" onclick="javascript:button_hidden(false);"/>						
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;

                        </td>
                    </tr>
                </table>

                <input type="hidden" name="fk_bookid" value="<?php echo $book->id; ?>" />
               
            </form>


        </div> <!-- end <div id="hidden_review"> -->
        <br />
        <br />
        <?php
    }
}


positions_bl($params->get('view05'));
?>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>		
