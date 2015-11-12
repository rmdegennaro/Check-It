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
    
if ($cat_name != "")
    $category_url_part = "task=$cat_name";
else
    $category_url_part = "task=showCategory&amp;catid=$catid";
$session = JFactory::getSession();
$arr = $session->get("array", "default");
global $doc, $hide_js, $Itemid, $mosConfig_live_site, $mosConfig_absolute_path, $my, $database;
global $limit, $total, $limitstart, $Itemid, $task, $paginations, $mainframe, $booklibrary_configuration, $option;
$acl = JFactory::getACL();
$user = Jfactory::getUser();
// for 1.6
$doc->addStyleSheet($mosConfig_live_site . '/components/com_booklibrary/includes/booklibrary.css');
$doc->addStyleSheet($mosConfig_live_site . '/components/com_booklibrary/includes/custom.css');

        $userid = $my->id;
        $query = "SELECT u.id, u.name AS username FROM #__users AS u WHERE u.id = " . $userid;
        $database->setQuery($query);
        $ownerslist = $database->loadObjectList();
        foreach ($ownerslist as $owner) {
            $username = $owner->username;
        }
?>
  <!--  <div class="componentheading<?php// echo $params->get('pageclass_sfx'); ?>">
        <?php// echo _BOOKLIBRARY_NOTHING_FOUND; ?>
    </div>-->
    <script type="text/javascript">
        function lend_request_submitbutton() {
            var form = document.userForm;
            <!-- change form requirements - 20150819 - Ralph deGennaro -->
            if (form.lendeecode.value == "") {
                alert( "<?php echo _BOOKLIBRARY_INFOTEXT_JS_LEND_REQ_LENDEECODE; ?>" );
            } else if ((form.lend_until.value == "")) {  
                alert( "<?php echo _BOOKLIBRARY_INFOTEXT_JS_LEND_REQ_UNTIL; ?>" );
            } else {
                form.submit();
            }
        }
        function isValidEmail(str) {
            return (str.indexOf("@") > 1);
        }
                
        function allreordering(){
            if(document.orderForm.direction.value=='asc')
                document.orderForm.direction.value='desc';
            else document.orderForm.direction.value='asc';

            document.orderForm.submit();
        }
    </script>
    <?php
    if (!isset($_REQUEST['userId']) || $_REQUEST['userId'] == $my->id) {
        if (JRequest::getVar('option') == "com_simplemembership") {
            $user = Jfactory::getuser();
            $db = Jfactory::getDBO();
            $query = "SELECT * FROM #__booklibrary_lend AS b WHERE fk_userid = '$user->id'";
            $db->setQuery($query);
            $current_user_rent_history_array = $db->loadObjectList();
            $check_for_show_rent_history = 0;
            $option = 'com_booklibrary';
            if (isset($current_user_rent_history_array)) {
                foreach ($current_user_rent_history_array as $temp)
                    if ($temp->fk_userid == $user->id)
                        $check_for_show_rent_history = 1;
            }
            if ($booklibrary_configuration['cb_mybook']['show'] == '1' && checkAccessBL($booklibrary_configuration['cb_mybook']['registrationlevel'], 'NORECURSE', userGID_BL($my->id), $acl))
                echo " <span class='books_button'><a class='my_btn my_btn-primary' href='" . JRoute::_('index.php?option='.$option.'&task=show_my_books&tab=showmybooks&Itemid='.$Itemid. $username . '&user=' . $userid . '&is_show_data=1' . '#tabs-2') . "'>" . _BOOKLIBRARY_LABEL_CBBOOKS_TT . "</a></span>";

             if(($booklibrary_configuration['cb_edit']['show'])=='1' && checkAccessBL($booklibrary_configuration['cb_mybook']['registrationlevel'], 'NORECURSE', userGID_BL($my->id), $acl))
                echo " <span class='books_button'><a class='my_btn my_btn-primary' href='" . JRoute::_('index.php?option='.$option.'&task=show_my_books&Itemid='.$Itemid) . "'>" . _BOOKLIBRARY_LABEL_CBEDIT . "</a></span>";
            
            if (($booklibrary_configuration['cb_history']['show'])) {
                $params->def('show_history', 1);
                $i = checkAccessBL($booklibrary_configuration['cb_history']['registrationlevel'], 'NORECURSE', userGID_BL($my->id), $acl);
                if ($i) {
                    $params->def('show_history_registrationlevel', 1);
                }
                if ($params->get('show_history')) {
                    if ($params->get('show_history_registrationlevel')) {
                        if ($check_for_show_rent_history != 0) {
                            echo " <span class='books_button'>
										<a class='my_btn my_btn-primary' href='" . JRoute::_('index.php?option=com_booklibrary&task=rent_history_books&name=' .
                                    $user->name . '&user=' . $user->id . '&is_show_data=1') . "'>" .
                            _BOOKLIBRARY_MY_LEND_HISTORY . "</a></span>";
                        }
                        //echo "<div style=\" border:1px solid black; padding: 10px; text-align:center; \">you dont have rent_history_lable</div>";
                    }
                }
            }
            else
                $query = "SELECT * FROM #__booklibrary_lend_request AS b WHERE b.status=0";
            $db->setQuery($query);
            $current_user_rent_request_array = $db->loadObjectList();
            $check_for_show_rent_request = 0;
            if (isset($current_user_rent_request_array))
                foreach ($current_user_rent_request_array as $temp)
                    $check_for_show_rent_request = 1;

            if (($booklibrary_configuration['cb_rent']['show'])) {
                $params->def('show_rent', 1);
                $i = checkAccessBL($booklibrary_configuration['cb_rent']['registrationlevel'], 'NORECURSE', userGID_BL($my->id), $acl);
                if ($i) {
                    $params->def('show_rent_registrationlevel', 1);
                }

                /* 	if($params->get('show_rent'))
                  {
                  if($params->get('show_rent_registrationlevel'))
                  {
                  if($check_for_show_rent_request!=0)
                  {
                  echo" <span class='books_button'><a class='my_btn my_btn-primary' href='".
                  JRoute::_('index.php?option=com_booklibrary&task=rent_requests_cb_books&is_show_data=1') .
                  "'>" . _BOOKLIBRARY_LEND_REQUESTS ."</a></span>" ;
                  }
                  }
                  } */
            }
            ?>

        <?php
        }
    }
    ?>
    <?php positions_bl($params->get('singleuser01')); ?>
        <?php positions_bl($params->get('singlecategory01')); ?>
    <!--<div class="componentheading<?php// echo $params->get('pageclass_sfx'); ?>"> 
        <?php
//         if ($params->get('wrongitemid')) {
//             echo $currentcat->header;
//         } else {
//             $parametrs = $mainframe->getParams();
//             echo $parametrs->toObject()->page_title;
//         }
        ?>
    </div>-->
    <?php positions_bl($params->get('singleuser02')); ?>
    <?php positions_bl($params->get('singlecategory02')); ?>
 
    <?php positions_bl($params->get('singlecategory03')); ?>               
    <?php
    if (strpos($currentcat->header, _BOOKLIBRARY_LABEL_SEARCH) && count($rows) == 0) {
        ?>
        <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
        <?php echo _BOOKLIBRARY_NOTHING_FOUND; ?>
        </div>
        <?php
        mosHTML::BackButton($params, $hide_js);
        return;
    }
    if ($params->get('show_search')) {
        ?>
        <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">

            <table  class="basictable bl_bl_single_category_list_table_with_search" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="60%">&nbsp;
                    </td>
                    <td width="40%" nowrap align="right">
                        <?php
                     //   if ($params->get('search_fieldshow'))
                            echo HTML_booklibrary::displaySimpleSearch();
                         echo '<div class="bl_bl_single_category_top_advanced_search my_btn my_btn-info">        
                        <a href="' . $mosConfig_live_site . '/index.php?option=com_booklibrary&task=show_search&catid=0&Itemid=$Itemid"> Advanced search</a>
                        </div>';                        
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    //print_r($rows);exit;
    $total = count($rows);
    if ($total + 1 > 0) {

        $view_type = $params->get('view_type');
        if (isset($_REQUEST['viewtype'])) {
            $view_type_selected = mosGetParam($_REQUEST, 'viewtype');
            $session = JFactory::getSession();
            $session->set('view_type_selected', $view_type_selected);
        } else {
            $session = JFactory::getSession();
            $view_type_selected = $session->get('view_type_selected');
        }

        $is_show_view_type = false;
        //add view type selecting
        if ($view_type == 2) {
            $redirect_url = $_SERVER["REQUEST_URI"];
            $pos = strpos($redirect_url, "viewtype=");
            if ($pos != FALSE) {
                $redirect_url1 = str_replace("viewtype=1", "viewtype=0", $redirect_url);
                $redirect_url2 = str_replace("viewtype=0", "viewtype=1", $redirect_url);
            } else {
                $redirect_url1 = $_SERVER["REQUEST_URI"] . '&amp;viewtype=0';
                $redirect_url2 = $_SERVER["REQUEST_URI"] . '&amp;viewtype=1';
            }
            $is_show_view_type = true;
        }
        //print_r($sort_arr);//exit;
        /* $sort_arr['field'] = $params->get('sort_arr_field');
          $sort_arr['direction'] = $params->get('sort_arr_direction'); */
        //print_r($sort_arr);exit;
        // $sort_arr['field'] = $sort_arr['field'];
        // $sort_arr['direction'] = $sort_arr['direction'];
        ?>
        <?php positions_bl($params->get('singleuser03')); ?>
                <?php positions_bl($params->get('singlecategory04')); ?>
        <table width="100%" class="basictable bl_all_books_list_top_table_order_by">
            <tr>
                <?php
                //if( $is_show_view_type ) {
                ?>
                <!--<td>
                   <div id="viewtype">
                   <a href="<?php echo $redirect_url1; ?>"  ><?php echo _BOOKLIBRARY_CONFIG_VIEW_TYPE_LIST; //exit;   ?></a>
                   &nbsp;|&nbsp;<a href="<?php echo $redirect_url2; ?>" style="active" ><?php echo _BOOKLIBRARY_CONFIG_VIEW_TYPE_GALLERY; ?></a>
                   </div>
                </td>-->
    <?php if (JRequest::getVar('option') != "com_simplemembership") {
           if ($params->get('header') != 'Lend Request') { ?>
           <td >
              <div id="ShowOrderBy" style="text-align:right" >
                <form class="bl_bl_books_gallery_sort_by" id="adminForm" method="POST" action="<?php echo sefRelToAbs($_SERVER["REQUEST_URI"]);?>" name="orderForm">
          <input type="hidden" id="direction" name="direction" value="<?php echo $sort_arr['direction']; ?>" >
          <a title="Click to sort by this column." onclick="javascript:allreordering();return false;" href="#">
              <img alt="" src="<?php echo $mosConfig_live_site; ?>/components/com_booklibrary/images/sort_<?php
            if ($sort_arr['direction'] == false) {
                echo 'asc';
            } else {
                echo $sort_arr['direction'];
            }
            ?>.png" />
          </a>
        <?php echo _BOOKLIBRARY_LABEL_ORDER_BY; ?>
                <select size="1" class="inputbox" onchange="javascript:document.orderForm.direction.value='asc'; document.orderForm.submit();" id="field" name="field">
                    <option value="authors" <?php if ($sort_arr['field'] == "authors") echo 'selected="selected"'; ?> > <?php echo _BOOKLIBRARY_LABEL_AUTHORS; ?></option>
                    <option value="title" <?php if ($sort_arr['field'] == "title") echo 'selected="selected"'; ?> > <?php echo _BOOKLIBRARY_LABEL_TITLE; ?></option>
                    <option value="rating" <?php if ($sort_arr['field'] == "rating") echo 'selected="selected"'; ?> > <?php echo _BOOKLIBRARY_LABEL_RATING; ?></option>
                    <option value="hits" <?php if ($sort_arr['field'] == "hits") echo 'selected="selected"'; ?> > <?php echo _BOOKLIBRARY_LABEL_HITS; ?></option>
                </select>       
              </form>
          </div>
      </td>       
    <?php }
          } ?>
    <?php } ?>
        </tr>
    </table>                
    <?php positions_bl($params->get('singleuser04')); ?>
    <?php positions_bl($params->get('singlecategory05')); ?>

    <?php
    $available = false;
    if ($view_type == 0 || ($view_type == 2 && ($view_type_selected == 0 || !isset($view_type_selected)))) {
        ?>
        <div id="list" class="bl_bl_books"">
            <table  width="100%" border="0" cellspacing="0" cellpadding="0" class="bl_bl_books_list my_table my_table-bordered my_table-hover">
                <tr>
                    <td height="20" width="50" class="sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>">
        <?php echo _BOOKLIBRARY_LABEL_COVER; ?>
                    </td>
                    <td height="20" class="sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>">
        <?php echo _BOOKLIBRARY_LABEL_TITLE; ?>
                    </td>
                    <td height="20" class="sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>">
                    <?php echo _BOOKLIBRARY_LABEL_AUTHORS; ?>
                    </td>
                    <?php
                    if ($params->get('show_rating')) {
                        ?>
                        <td height="20" class="sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>">
                        <?php echo _BOOKLIBRARY_LABEL_RATING; ?>
                        </td>
                        <?php
                    }
                    if ($params->get('hits')) {
                        ?>
                        <td width="30" height="20" class="sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>" align="right">
                        <?php echo _BOOKLIBRARY_LABEL_HITS; ?>
                        </td>
                        <?php
                    }
                    if ($params->get('search_request')) {
                        ?>
                        <td height="20" class="sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>" align="right">
                        <?php echo _BOOKLIBRARY_LABEL_CATEGORY; ?>
                        </td>
                        <?php
                    }
                    if ($params->get('show_lendstatus') && $params->get('show_lendrequest')) {
                        ?>
                        <td height="20" class="sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>">	
                        <?php echo _BOOKLIBRARY_LABEL_LEND_CB; ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                $k = 0;
//****************************************   add my perenos */

                foreach ($rows as $row) {
//****************************************   add my perenos */
                    $cat_id = @(isset($row->category_id)) ? $row->category_id : $catid;
                    $link = 'index.php?option=com_booklibrary&task=view&id=' . $row->id . '&catid=' . $cat_id . '&Itemid=' . $Itemid;
                    ?>
                    <tr class="<?php echo $tabclass[$k]; ?>" >
                        <td class="bl_table_book_img" width="26%" style="padding:7px;text-align:center;">
                            <?php
                            $book = $row;
                            //for local images
                            $imageURL = $book->imageURL;
                            ?>	<a href="<?php echo sefRelToAbs($link); ?>" class="category<?php echo $params->get('pageclass_sfx'); ?>">
                            <?php
                            if ($imageURL != '' && substr($imageURL, 0, 4) != "http") {
                                $imageURL = $mosConfig_live_site . '' . $book->imageURL;
                                ;
                            }

                            if ($imageURL != '') {
                                if (version_compare(JVERSION, '3.0', 'lt'))
                                    echo '<img src="' . $imageURL . '" alt="cover" border="0" height="50" />';
                                else
                                    echo '<img src="' . $imageURL . '" alt="cover" border="0" height="50"  />'; ///дописано свойство width="100
                            } else {
                                echo '<img src="' . $mosConfig_live_site . '/components/com_booklibrary/images/' . _BOOKLIBRARY_NO_PICTURE . '" alt="no-img_eng.gif" border="0" />';
                            }
                            ?> </a>
                        </td>
                        <td  width="45%">
                            <a href="<?php echo sefRelToAbs($link); ?>" class="category<?php echo $params->get('pageclass_sfx'); ?>">
            <?php echo $row->title; ?> 
                            </a> 
                            <!--<?php
            if ($params->get('item_description')) {
                ?>
                                            <br /> 
                                <?php echo $row->description; ?>
                                <?php
                            }
                            ?>-->
                        </td>
                        <td  width="55%" class="bl_single_category_table_td_authors"> 				
                        <?php echo $row->authors; ?> 
                        </td>
                        <?php
                        if ($params->get('show_rating')) {
                            if ($row->rating == 0 && $row->rating2 != 0)
                                $row->rating = $row->rating2;
                            ?>
                            <td align="right">
                                <img src="<?php echo $mosConfig_live_site; ?>/components/com_booklibrary/images/rating-<?php echo $row->rating; ?>.gif" alt="<?php echo ($row->rating) / 2; ?>" align="left" border="0" />&nbsp;&nbsp;
                            </td>
                            <?php
                        }
                        if ($params->get('hits')) {
                            ?>
                            <td align="left" class="bl_single_category_table_td_hits">
                            <?php echo $row->hits; ?>
                            </td>
                            <?php
                        }
                        if ($params->get('search_request')) {
                            $link1 = 'index.php?option=com_booklibrary&task=showCategory&catid=' . $row->category_id . '&Itemid=' . $Itemid;
                            ?>
                            <td align="right">
                                <a href="<?php echo sefRelToAbs($link1); ?>" class="category<?php echo $params->get('pageclass_sfx'); ?>">
                <?php echo $row->category; ?>
                                </a>
                            </td>
                            <?php
                        }

                        if ($params->get('show_lendstatus') && $params->get('show_lendrequest')) {
                            $data1 = JFactory::getDBO();
                            $query = "SELECT  b.lend_from , b.lend_until  FROM #__booklibrary_lend  AS b LEFT JOIN #__booklibrary AS c ON b.fk_bookid = c.id WHERE  c.id=" . $row->id . " AND c.published='1' AND c.approved='1' AND b.lend_return IS NULL";

                            $data1->setQuery($query);
                            $rents1 = $data1->loadObjectList();
                            ?>
                            <td align="center">
                                <?php
                                if (count($rents1) == 0) {
                                    ?>
                                    <?php
                                    echo "<img src='" . $mosConfig_live_site . "/components/com_booklibrary/images/ok.png' alt='Available' name='image' border='0' align='middle' />";
                                } else {
                                    echo _BOOKLIBRARY_LABEL_LEND_FROM_UNTIL . "<br />";
                                    for ($a = 0; $a < count($rents1); $a++) {
                                        $from_until = substr($rents1[$a]->lend_from, 0, 10) .
                                                "&nbsp;/&nbsp;" .
                                                substr($rents1[$a]->lend_until, 0, 10) . "\n";
                                        print_r($from_until);
                                    }
                                }
                                if ($params->get('lend_save')) {

                                    $available = true;
                                } else {

                                    $available = false;
                                }
                                ?>
                                <br>
                            </td>
                    <?php } ?>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>

            </table>
          
        </div>
        <?php positions_bl($params->get('singleuser05')); ?>
        <?php positions_bl($params->get('singlecategory06')); ?>

    <?php }
    ?>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="basictable bl_bl_view_book_lent_request_for_paginator_table">
        <tr>
            <td colspan="6" align="center">
                <div id="paginator-custom" class="my_pagination my_pagination-centered my_pagination-small">
                    <?php
                    if ($pageNav != null && $pageNav->total > $pageNav->limit) {
                        echo $pageNav->getPagesLinks(); //for 1.6
                    }
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <?php
    if ($params->get('show_lendstatus') && $params->get('show_lendrequest') && $params->get('lend_save')) {
        ?>

        <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
        <?php echo _BOOKLIBRARY_LABEL_LEND_INFORMATIONS; ?>
            <input type="hidden"  name="bookid" id="bookid"  value="<?php echo $row->id ?>"  maxlength="80" />

        </div>
        <form class="a" id="adminForm" action="<?php echo sefRelToAbs("index.php"); ?>" name="userForm" method="post">
            <table  class="basictable bl_view_book_lend_request_table_lend_information_first my_table" 
                    width="100%" border="0" cellspacing="0" cellpadding="0">			
                <tr>
                    <td style="text-align:left;padding:10px 0px 0px 0px;display:inline-block;width:125px;text-transform:capitalize;">
                        <?php
                        // change from request name to lendeecode - 20150819 - Ralph degennaro
                        echo _BOOKLIBRARY_LABEL_LENDEECODE;
                        global $my;
                        ?>:&nbsp;
                    </td>
                    <td style="text-align:left;padding:0px;display:inline-block;width:230px;">
                        <!-- // change from user_name to lendeecode - 20150819 - Ralph degennaro -->
                        <input style="position:relative;top:10px;" class="inputbox" type="text" name="lendeecode" size="38" maxlength="80" value="<?php if ($my->name != '') echo $my->name ?>" />       
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td style="text-align:left;padding:10px 0px 0px 0px;display:inline-block;width:125px;text-transform:capitalize;">
        <!-- // remove display of email - 20150819 - Ralph degennaro -->
        <!-- // <?php echo _BOOKLIBRARY_LABEL_LEND_REQUEST_EMAIL; ?>:&nbsp; -->
                    </td>
                    <td style="text-align:left;padding:0px;display:inline-block;width:230px;">
                        <!-- // hide inputbox so formatting not changed - 20150819 - Ralph degennaro -->
                        <input style="position:relative;top:10px;" class="inputbox" type="hidden" name="user_email" size="38" maxlength="80" value="<?php if ($my->name != '') echo $my->email ?>" />
                    </td>
                    <td></td>
                </tr>

            </table>                     
            <script>
                window.onload = function ()
                {
                    var today = new Date();
                    var date = today.toLocaleFormat("<?php echo $booklibrary_configuration['date_format'] ?>");
                    document.getElementById('lend_from').value = date;
                    document.getElementById('lend_until').value = date;
                }; 
            </script>
            <table  class="basictable bl_view_book_lend_request_table_lend_information_second my_table" width="100%" border="0" cellspacing="0" cellpadding="0">  			
                <tr>
                    <td style="text-align:left;padding:0px;display:inline-block;width:125px;">
        <?php echo _BOOKLIBRARY_LABEL_LEND_REQUEST_MAILING; ?>:&nbsp;					
                    </td>
                    <td style="text-align:left;padding:0px;display:inline-block;width:230px;"> 	
                        <textarea align= "top" name="user_mailing" id="user_mailing" cols="60" rows="10" style="width:210px;height:100px;" value=""></textarea>
                    </td>
                    <td></td>
                </tr>
                <tr>	
                    <td style="text-align:left;padding:0px;display:inline-block;width:125px;">
        <?php echo _BOOKLIBRARY_LABEL_LEND_REQUEST_FROM; ?>:&nbsp;
                    </td>
                    <td style="text-align:left;padding:0px;display:inline-block;width:230px;">
                        <?php
                        $date_format = str_replace('%', '', $booklibrary_configuration['date_format']);
                        /* for 1.6 */ echo JHtml::_('calendar', date("Y-m-d"), 'lend_from', 'lend_from', $booklibrary_configuration['date_format']);
                        ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:left;padding:0px;display:inline-block;width:125px;">
        <?php echo _BOOKLIBRARY_LABEL_LEND_REQUEST_UNTIL; ?>:&nbsp;
                    </td>	
                    <td style="text-align:left;padding:5px 0px 0px 0px;display:inline-block;width:230px;">
        <!-- // change lend_until to be plus 14 days - 20150819 - Ralph degennaro -->
        <?php /* for 1.6 */ echo JHtml::_('calendar', date("Y-m-d", strtotime("+14 days")), 'lend_until', 'lend_until', $booklibrary_configuration['date_format']); ?>
                    </td>
                    <td></td>
                </tr>
            </table>
    <?php } ?>

        <table  class="basictable bl_bl_view_book_lent_request_intresting_table_with_hidden_inputs" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="260">
                    <?php
                    if ($params->get('show_lendstatus') && $params->get('show_lendrequest') && $params->get('lend_save')) {
                        ?>
                        <input type="hidden" name="option" value="com_booklibrary"/>
                        <input type="hidden" name="task" value="save_lend_request"/>
                        <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
                        <input type="button" class="button my_btn my_btn-success" value="<?php echo _BOOKLIBRARY_LABEL_BUTTON_LEND_REQU_SAVE; ?>" onclick="lend_request_submitbutton()" />
                        <input type="hidden" name="bookid" value="<?php echo $rows[0]->id; ?>"/>
                        <input type="hidden" name="bid[]" value="<?php echo $rows[0]->id; ?>"/>

                    <?php } else {
                        ?>
                        &nbsp;
                        <?php
                    }
                    ?>
                </td>                         
                <td align="right">
                    <?php
                    // 	displays back button
                    if (count($rows))
                        mosHTML::BackButton($params, $hide_js);
                    ?>
                </td>
            </tr>
        </table>

        <?php
        if ($params->get('show_lendstatus') && $params->get('show_lendrequest') && $params->get('lend_save')) {
            echo "</form>";
        }    
    ?>             


    <?php
    if ($is_exist_sub_categories) {
        ?>			<?php positions_bl($params->get('singlecategory07')); ?>
        <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
        <?php echo _BOOKLIBRARY_LABEL_FETCHED_SUBCATEGORIES . " : " . $params->get('category_name'); ?>
        </div>
        <?php positions_bl($params->get('singlecategory08')); ?>
        <?php
        HTML_booklibrary::listCategories($params, $categories, $catid, $tabclass, $currentcat);
        echo '<table  class="basictable bl_bl_single_category_intresting_table_with_not_visible_button" width="100%"><tr><td width="60%"></td><td>';
        mosHTML::BackButton($params, $hide_js);
        echo '</td></tr></table>';
    }
    ?>
<?php positions_bl($params->get('singlecategory09')); ?>

    <!-- Add item Begin -->
    
    <?php  positions_bl($params->get('singlecategory10')); ?>
    <!-- Add item end -->
    <?php
    if (count($rows) != 0) {
        $option = JRequest::getVar('option','com_booklibrary'); 
    if ($params->get('show_input_add_suggest') && $option == 'com_booklibrary')
        HTML_booklibrary::showSuggestion($params, 2, $catid, $Itemid);
    positions_bl($params->get('singlecategory11'));   
    }
    ?>	
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>
