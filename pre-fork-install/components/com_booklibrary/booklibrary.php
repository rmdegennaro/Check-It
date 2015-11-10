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
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;

global $mosConfig_lang; // for 1.6
$mainframe = JFactory::getApplication(); // for 1.6
$GLOBALS['mainframe'] = $mainframe;


if (get_magic_quotes_gpc()) {

    function stripslashes_gpc(&$value) {
        $value = stripslashes($value);
    }

    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}

require_once($mosConfig_absolute_path . "/components/com_booklibrary/compat.joomla1.5.php");
require_once($mosConfig_absolute_path . "/components/com_booklibrary/booklibrary.main.categories.class.php"); //for 1.6
require_once($mosConfig_absolute_path . "/components/com_booklibrary/functions.php"); //for 1.6
// load language
$languagelocale = "";
$query = "SELECT l.title, l.lang_code, l.sef ";
$query .= "FROM #__booklibrary_const_languages as cl ";
$query .= "LEFT JOIN #__booklibrary_languages AS l ON cl.fk_languagesid=l.id ";
$query .= "LEFT JOIN #__booklibrary_const AS c ON cl.fk_constid=c.id ";
$query .= "GROUP BY  l.title";
$database->setQuery($query);
$languages = $database->loadObjectList();

$lang = JFactory::getLanguage(); //print_r($lang->getLocale());exit;
foreach ($lang->getLocale() as $locale) {
    foreach ($languages as $key => $language) {
        if ($locale == $language->title || $locale == $language->lang_code || $locale == $language->sef) {
            $mosConfig_lang = $locale;
            $languagelocale = $language->lang_code;
            break;
        }
    }
}

if ($languagelocale == '') {
    $languagelocale = "en-GB";
    $mosConfig_lang = "en-GB";
   }
    
$query = "SELECT c.const, cl.value_const ";
$query .= "FROM #__booklibrary_const_languages as cl ";
$query .= "LEFT JOIN #__booklibrary_languages AS l ON cl.fk_languagesid=l.id ";
$query .= "LEFT JOIN #__booklibrary_const AS c ON cl.fk_constid=c.id ";
$query .= "WHERE l.lang_code = '$languagelocale'";
$database->setQuery($query);
$langConst = $database->loadObjectList();

foreach ($langConst as $item) {
   if(!defined($item->const) )  define($item->const, $item->value_const); // $database->quote()
}
// end load language

/** load the html drawing class */
require_once ($mosConfig_absolute_path . "/components/com_booklibrary/booklibrary.html.php"); // for 1.6
require_once ($mosConfig_absolute_path . "/components/com_booklibrary/booklibrary.class.php"); // for 1.6

require_once ($mosConfig_absolute_path . "/components/com_booklibrary/booklibrary.class.lend_request.php");
require_once ($mosConfig_absolute_path . "/components/com_booklibrary/booklibrary.class.lend.php");
require_once ($mosConfig_absolute_path . "/components/com_booklibrary/booklibrary.class.review.php");

require_once ($mosConfig_absolute_path . "/administrator/components/com_booklibrary/admin.booklibrary.class.others.php");
require_once ($mosConfig_absolute_path . "/administrator/components/com_booklibrary/admin.booklibrary.class.conf.php");
require_once ($mosConfig_absolute_path . "/administrator/components/com_booklibrary/admin.booklibrary.class.ws.php");

jimport('joomla.html.pagination');
jimport('joomla.application.pathway');
if (jrequest::getvar('option') == 'com_comprofiler') {
    global $booklibrary_configuration;
}
$GLOBALS['lendstatus_show'] = $booklibrary_configuration['lendstatus']['show'];
$GLOBALS['lendrequest_registrationlevel'] = $booklibrary_configuration['lendrequest']['registrationlevel'];
$GLOBALS['reviews_show'] = $booklibrary_configuration['reviews']['show'];
$GLOBALS['reviews_registrationlevel'] = $booklibrary_configuration['reviews']['registrationlevel'];
$GLOBALS['ebooks_show'] = $booklibrary_configuration['ebooks']['show'];
$GLOBALS['ebooks_registrationlevel'] = $booklibrary_configuration['ebooks']['registrationlevel'];
$GLOBALS['buy_now_show'] = $booklibrary_configuration['buy_now']['show'];
$GLOBALS['buy_now_allow_categories'] = $booklibrary_configuration['buy_now']['allow']['categories'];
$GLOBALS['price_show'] = $booklibrary_configuration['price']['show'];
$GLOBALS['price_registrationlevel'] = $booklibrary_configuration['price']['registrationlevel'];
$GLOBALS['lendrequest_email_show'] = $booklibrary_configuration['lendrequest_email']['show'];
$GLOBALS['lendrequest_email_address'] = $booklibrary_configuration['lendrequest_email']['address'];
$GLOBALS['lendrequest_email_registrationlevel'] = $booklibrary_configuration['lendrequest_email']['registrationlevel'];
$GLOBALS['suggest_email_address'] = $booklibrary_configuration['suggest_email']['address'];
$GLOBALS['suggest_added_email_show'] = $booklibrary_configuration['suggest_added_email']['show'];
$GLOBALS['suggest_added_email_registrationlevel'] = $booklibrary_configuration['suggest_added_email']['registrationlevel'];
$GLOBALS['license_show'] = $booklibrary_configuration['license']['show'];
$GLOBALS['cat_pic_show'] = $booklibrary_configuration['cat_pic']['show'];
$GLOBALS['debug'] = $booklibrary_configuration['debug'];
$GLOBALS['ebooks_location'] = $booklibrary_configuration['ebooks']['location'];
$GLOBALS['review_added_email_show'] = $booklibrary_configuration['review_added_email']['show'];
$GLOBALS['review_email_address'] = $booklibrary_configuration['review_email']['address'];
$GLOBALS['review_added_email_registrationlevel'] = $booklibrary_configuration['review_added_email']['registrationlevel'];
$GLOBALS['license_show'] = $booklibrary_configuration['license']['show'];
$GLOBALS['license_text'] = $booklibrary_configuration['license']['text'];
$GLOBALS['subcategory_show'] = $booklibrary_configuration['subcategory']['show'];
$GLOBALS['foto_high'] = $booklibrary_configuration['foto']['high'];
$GLOBALS['foto_width'] = $booklibrary_configuration['foto']['width'];
$GLOBALS['booklibrary_configuration'] = $booklibrary_configuration;
$GLOBALS['add_book_button'] = $booklibrary_configuration['addbook_button']['allow']['registrationlevel'];

$GLOBALS['task'] = $task = mosGetParam($_REQUEST, 'task', '');
$GLOBALS['option'] = $option = mosGetParam($_REQUEST, 'option', 'com_booklibrary');

//-----------------------------------------
$GLOBALS['print_pdf_show'] = $booklibrary_configuration['print_pdf']['show'];
$GLOBALS['print_pdf_registrationlevel'] = $booklibrary_configuration['print_pdf']['registrationlevel'];

$GLOBALS['print_view_show'] = $booklibrary_configuration['print_view']['show'];
$GLOBALS['print_view_registrationlevel'] = $booklibrary_configuration['print_view']['registrationlevel'];

$GLOBALS['mail_to_show'] = $booklibrary_configuration['mail_to']['show'];
$GLOBALS['mail_to_registrationlevel'] = $booklibrary_configuration['mail_to']['registrationlevel'];

//-----------------------------------------

$doc = JFactory::getDocument(); // for 1.6
$GLOBALS['doc'] = $doc; // for 1.6
$GLOBALS['op'] = $doc; // for 1.6
$doc->setTitle(_BOOKLIBRARY_TITLE); // for 1.6
//if ( !isset($GLOBALS['Itemid']) ) $GLOBALS['Itemid'] = JRequest::getInt( 'Itemid' );
if (!isset($GLOBALS['Itemid']))
    $GLOBALS['Itemid'] = $Itemid = intval(mosGetParam($_REQUEST, 'Itemid', 0));


$GLOBALS['option'] = $option = trim(mosGetParam($_REQUEST, 'option', "com_booklibrary"));

$task = trim(mosGetParam($_REQUEST, 'task', ""));
$id = intval(mosGetParam($_REQUEST, 'id', 0));
$catid = intval(mosGetParam($_REQUEST, 'catid', 0));
$bids = mosGetParam($_REQUEST, 'bid', array(0));

// Get Current User for J 1.6
$user = JFactory::getUser();
$uid = $user->get('id');
$current_user = new JUser($uid);
$GLOBALS['current_user'] = $current_user; //for 1.6


$printItem = trim(mosGetParam($_REQUEST, 'printItem', ""));

//print_r($printItem);exit;
// paginations
$intro = $booklibrary_configuration['page']['items']; // page length

if ($intro) {
    $paginations = 1;
    $limit = intval(mosGetParam($_REQUEST, 'limit', $intro));
    $GLOBALS['limit'] = $limit;

    $limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));

    $GLOBALS['limitstart'] = $limitstart;

    $total = 0;
    $LIMIT = 'LIMIT ' . $limitstart . ',' . $limit;
} else {
    $paginations = 0;
    $LIMIT = '';
}

$session = JFactory::getSession();
$session->set("array", $paginations);

$booklibrary_configuration['debug'] = 0;
if ($booklibrary_configuration['debug'] == '1') {
    echo "Task: " . $task . "<br />";
    print_r($_REQUEST);
    echo "<hr /><br />";
}

// for 1.6
if (isset($_REQUEST['view'])) {
    $view = mosGetParam($_REQUEST, 'view', '');
    if ((!isset($task) OR $task == '' ) AND isset($view))
        $task = $view;
}

if ((isset($_GLOBALS)) && ($_GLOBALS['task'] != "")) {
    $task = $_GLOBALS['task'];
}

//print_r($task);exit;

$PHP_booklibrary = new PHP_booklibrary();
//dev_ozi($task);

switch ($task) {
    case 'secret_image':
        $PHP_booklibrary->secretImage();
        break;

    case 'add_book_fe':
        $PHP_booklibrary->add_book_fe($option, 0);
        break;
    case 'edit_book':
        $PHP_booklibrary->add_book_fe($option, $id);
        break;

    case 'books': // show Books   
        $PHP_booklibrary->books($option);

        break;

    case 'show_search':
    case 'show_search_book':
        $PHP_booklibrary->showSearchBooks($option, $catid, $option);
        break;

    case 'search':
        $PHP_booklibrary->searchBooks($option, $catid, $option);
        break;

    case 'view':
    case 'view_bl':
        $PHP_booklibrary->showItemBL($id, $catid, $printItem/* , $layout */);
        break;

    case 'review':
        $PHP_booklibrary->reviewBook($option, $catid);
        break;

    case 'alone_category':
    case 'showCategory':

        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }
        $layout = $params->get('categorylayout', '');
        if ($catid) {
            PHP_booklibrary::showCategory($catid, $printItem, $layout);
        } else {
            if (version_compare(JVERSION, "1.6.0", "lt")) {
                $catid = $params->get('catid');
            } else if (version_compare(JVERSION, "1.6.0", "ge") && version_compare(JVERSION, "3.5.0", "lt")) {
                $single_category_id = ''; // for 1.6 
                $single_category_id = $params->get('single_category');
                if ($single_category_id > 0)
                    $catid = $single_category_id;
                else {
                    $query = "SELECT * FROM #__booklibrary_main_categories";
                    $database->setQuery($query);
                    $a = $database->loadObjectList();
                    $catid = $a[0]->id;
                }
            }

            PHP_booklibrary::showCategory($catid, $printItem, $layout);
        }
        break;

    case 'lend_request':
        $PHP_booklibrary->showLendRequest($option, $bids);
        break;

    case 'save_lend_request':
        $PHP_booklibrary->saveLendRequest($option, $bids);
        break;

    case 'mdownload':
        $PHP_booklibrary->mydownload($id);
        break;

    case 'downitsf':
        $PHP_booklibrary->downloaditself($id);
        break;
  
    case 'save_book_fe':
        $PHP_booklibrary->save_book_fe($option);
        break;

    case 'show_cart':
        $PHP_booklibrary->show_cart();
        break;

    case 'add_to_cart':
        $PHP_booklibrary->add_to_cart();
        break;

    case 'check_out':
        $PHP_booklibrary->check_out();
        break;

    case 'cart_event':
        echo 'cart_event';
        $PHP_booklibrary->cart_event();
        break;

    case 'showmybooks':

        global $booklibrary_configuration;
        //if($booklibrary_configuration['cb_history']['show']=='1'){$PHP_booklibrary -> rent_history($option); break;} else
        //case 'owner_books':
        // smbswitch($option);
        $PHP_booklibrary->showMyBooks($option);
        break;

    case 'show_rss_categories':
        $PHP_booklibrary->listRssCategories();
        break;

    case 'categories':
        $PHP_booklibrary->listCategories($catid);
        break;

    case 'lend_before_end_notify':
        $PHP_booklibrary->lendBeforeEndNotify($option);
        break;

    case 'rent_requests_cb_books':
        $PHP_booklibrary->rent_requests_cb($option, $bids);
        break;

    case 'rent_history_books':
        $PHP_booklibrary->rent_history($option);
        break;

    case 'accept_rent_requests_cb_book':
        $PHP_booklibrary->accept_rent_requests_cb($option, $bids);
        break;

    case 'decline_rent_requests_cb_book':
        $PHP_booklibrary->decline_rent_requests_cb($option, $bids);
        break;

    case 'lend_book':
        if (mosGetParam($_REQUEST, 'save') == 1)
            $PHP_booklibrary->saveLent($option, $bids);
        else
            $PHP_booklibrary->lent($option, $bids);
        break;


    case 'lend_return_book' :
        if (mosGetParam($_REQUEST, 'save') == 1)
            $PHP_booklibrary->saveLend_return($option, $bids);
        else
            $PHP_booklibrary->lend_return($option, $bids);
        break;

    case 'ownerslist':
    case 'owners_list':
        $PHP_booklibrary->ownersList($option);
        break;
    case 'show_my_books':
    case 'showownerbooks':
    case 'owner_books':
    case 'view_user_books':
     global $booklibrary_configuration;
        //smbswitch($option);
        $PHP_booklibrary->viewUserBooks($option);
        break;

    case 'view_book':
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }
        $layout = $params->get('viewbooklayout', '');
        if ($id) {
            //$query = "SELECT idcat AS catid FROM #__booklibrary_categories WHERE iditem=".$id;
            $query = "SELECT catid AS catid FROM #__booklibrary_categories WHERE bookid=" . $id;
            $database->setQuery($query);
            $catid = $database->loadObjectList();
            $catid = $catid[0]->catid;
            $PHP_booklibrary->showItemBL($id, $catid, $printItem, $layout);
        } else {
            if (version_compare(JVERSION, '3.0', 'ge')) {
                $menu = new JTableMenu($database);
                $menu->load($Itemid);
                $params = new JRegistry;
                $params->loadString($menu->params);
            } else {
                $menu = new mosMenu($database);
                $menu->load($GLOBALS['Itemid']);
                $params = new mosParameters($menu->params);
            }
            if (version_compare(JVERSION, "1.6.0", "lt")) {
                $id = $params->get('book');
            } else if (version_compare(JVERSION, "1.6.0", "ge") && version_compare(JVERSION, "3.5.0", "lt")) {
                $view_book_id = ''; // for 1.6 
                $view_book_id = $params->get('book');
                if ($view_book_id > 0) {
                    $id = $view_book_id;
                } else {
                    $query = "SELECT id FROM #__booklibrary";
                    $database->setQuery($query);
                    $a = $database->loadObjectList();
                    //print_r($a);exit;
                    $id = $a[0]->id;
                }
            }
            //      $query = "SELECT idcat AS catid FROM #__booklibrary_categories WHERE iditem=".$id;
            $query = "SELECT catid AS catid FROM #__booklibrary_categories WHERE bookid=" . $id;
            $database->setQuery($query);

            if (version_compare(JVERSION, '3.0', 'lt')) {
                $cat = $database->loadResultArray();
            } else {

                $cat = $database->loadColumn();
            }
            //$catid = $database->loadObjectList();
            //print_r($cat);exit;
            if (count($cat) > 0)
                $catid = $cat[0];
            else
                $catid = $cat[0]->catid;
            $PHP_booklibrary->showItemBL($id, $catid, $printItem, $layout);
        }
        break;

    default:
        if (JRequest::getVar('option') == 'com_comprofiler') {
            $PHP_booklibrary->viewUserBooks($option);
        } else {
            $PHP_booklibrary->listCategories($catid);
        }
        break;
}

class PHP_booklibrary {

    static function mylenStr($str, $lenght) {
        if (strlen($str) > $lenght) {
            $str = substr($str, 0, $lenght);
            $str = substr($str, 0, strrpos($str, " "));
        }
        return $str;
    }

    static function addTitleAndMetaTags() {
        global $database, $doc, $mainframe, $Itemid;

        $view = JREQUEST::getCmd('view', null);
        $catid = JREQUEST::getInt('catid', null);
        $id = JREQUEST::getInt('id', null);
        $lang = JREQUEST::getString('lang', null);
        $title = array();
        $sitename = htmlspecialchars($mainframe->getCfg('sitename'));

        if (isset($view)) {
            $view = str_replace("_", " ", $view);
            $view = ucfirst($view);
            $title[] = $view;
        }

        $s = blLittleThings::getWhereUsergroupsCondition();

        if (!isset($catid)) {

            // Parameters
            if (version_compare(JVERSION, '3.0', 'ge')) {
                $menu = new JTableMenu($database);
                $menu->load($Itemid);
                $params = new JRegistry;
                $params->loadString($menu->params);
            } else {
                $menu = new mosMenu($database);
                $menu->load($Itemid);
                $params = new mosParameters($menu->params);
            }
            if (version_compare(JVERSION, "1.6.0", "lt")) {
                $catid = $params->get('catid');
            } else if (version_compare(JVERSION, "1.6.0", "ge") && version_compare(JVERSION, "3.5.100", "lt")) {
                $single_category_id = ''; // for 1.6 
                $single_category_id = $params->get('single_category');
                if ($single_category_id > 0)
                    $catid = $single_category_id;
            }
        }

        //To get name of category
        if (isset($catid)) {
            $query = "SELECT  c.name, c.id AS catid, c.parent_id
                    FROM #__booklibrary_main_categories AS c
                    WHERE ($s) AND c.id = " . intval($catid);
            $database->setQuery($query);
            $row = null;
            $row = $database->loadObject();
            if (isset($row)) {
                $cattitle = array();
                $cattitle[] = $row->name;
                while (isset($row) && $row->parent_id > 0) {
                    $query = "SELECT  name, c.id AS catid, parent_id 
                        FROM #__booklibrary_main_categories AS c
                        WHERE ($s) AND c.id = " . intval($row->parent_id);
                    $database->setQuery($query);
                    $row = $database->loadObject();
                    if (isset($row) && $row->name != '') {
                        $cattitle[] = $row->name;
                    }
                }
                $title = array_merge($title, array_reverse($cattitle));
            }
        }

        //To get Name of the book
        if (isset($id)) {
            $row = $database->loadObject();
            if (isset($row)) {
                $idtitle = array();
                $title = array_merge($title, $idtitle);
            }
        }

        $tagtitle = "";
        for ($i = 0; $i < count($title); $i++) {
            $tagtitle = trim($tagtitle) . " | " . trim($title[$i]);
        }

        $blm = "BookLibrary Manager ";
        //To set Title
        $title_tag = PHP_booklibrary::mylenStr($blm . $tagtitle, 75);
        //To set meta Description
        $metadata_description_tag = PHP_booklibrary::mylenStr($blm . $tagtitle, 200);
        //To set meta KeywordsTag
        $metadata_keywords_tag = PHP_booklibrary::mylenStr($blm . $tagtitle, 250);

        $doc->setTitle($title_tag);
        $doc->setMetaData('description', $metadata_description_tag);
        $doc->setMetaData('keywords', $metadata_keywords_tag);
    }

    static function lend_return($option, $bid) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $my, $Itemid;

        if (!is_array($bid) || count($bid) < 1) {
            echo "<script> alert('Select an item to rent'); window.history.go(-1);</script>\n";
            exit;
        }
        $bids = implode(',', $bid);

        //for databases without subselect
        $select = "SELECT a.*, l.id as lendid, l.lend_from as lend_from, " .
                "l.lend_return as lend_return, l.lend_until as lend_until, " .
                "l.user_name as user_name, l.user_email as user_email " .
                "\nFROM #__booklibrary_lend AS l " .
                "\nLEFT JOIN #__booklibrary AS a ON l.fk_bookid = a.id " .
                "\nWHERE l.lend_return is null and l.fk_bookid in (" . $bids . ")";
        $database->setQuery($select);

        if (!$database->query()) {
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
            exit;
        }

        $books = $database->loadObjectList();
        $count = count($books);
        for ($i = 0; $i < 1; $i++) {
            if (((@$books[$i]->lend_from) == '') && ((@$books[$i]->lend_return) == '')) {
                ?>
                <script type="text/JavaScript" language="JavaScript">
                    alert('You cannot return books that were not lent out');
                    window.history.go(-1);
                </script>
                <?php
                exit;
            }
        }

        // get list of users
        $userlist[] = mosHTML :: makeOption('-1', '----------');
        $database->setQuery("SELECT id AS value, name AS text from #__users ORDER BY name");
        $userlist = array_merge($userlist, $database->loadObjectList());
        $usermenu = mosHTML :: selectList($userlist, 'userid', 'class="inputbox" size="1"', 'value', 'text', '-1');
        HTML_booklibrary :: showLendBooks($option, $books, $usermenu, "lend_return");
    }

    static function saveLend_return($option, $lids) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $my, $Itemid;
        $bookid = mosGetParam($_REQUEST, 'bookid');
        $id = mosGetParam($_REQUEST, 'id');
        $lend_from = mosGetParam($_REQUEST, 'lend_from');
        $lend_until = mosGetParam($_REQUEST, 'lend_until');
        if (!is_array($lids) || count($lids) < 1) {
            echo "<script> alert('Select an item to return'); window.history.go(-1);</script>\n";
            exit;
        }
        for ($i = 0, $n = count($lids); $i < $n; $i++) {
            $lend = new mosBookLibrary_lend($database);
            $lend->load($lids[$i]);
            if ($lend->lend_return != null) {
                echo "<script> alert('Already returned'); window.history.go(-1);</script>\n";
                exit;
            }
            $lend->lend_return = date("Y-m-d H:i:s");
            if (!$lend->check($lend)) {
                echo "<script> alert('" . $lend->getError() . "'); window.history.go(-1); </script>\n";
                exit();
            }
            if (!$lend->store()) {
                echo "<script> alert('" . $lend->getError() . "'); window.history.go(-1); </script>\n";
                exit();
            }
            $lend->checkin();
            $is_update_book_lend = true;
            if ($is_update_book_lend) {
                $book = new mosBooklibrary($database);
                $book->load($id);
                $query = "SELECT * FROM #__booklibrary_lend WHERE fk_bookid=" . $id . " AND lend_return IS NULL ";
                $database->setQuery($query);
                $check_lends = $database->loadObjectList();
                if (isset($check_lends[0]->id)) {
                    $book->fk_lendid = $check_lends[0]->id;
                    $is_update_book_lend = false;
                } else {
                    $book->fk_lendid = 0;
                }
                $book->store();
                $book->checkin();
            }
        }
        if ($option == 'com_comprofiler')
            $link_for_mosRedirect = "index.php?option=" . $option . "&tab=getmybooksTab&Itemid=" . $Itemid;
        else
            $link_for_mosRedirect = "index.php?option=" . $option . "&view=show_my_books&layout=mybooks";
        //mosRedirect($link_for_mosRedirect);
        PHP_booklibrary::ShowMyBooks($option);
    }

    static function saveLent($option, $bids, $task = "") {
        global $database, $Itemid;

        PHP_booklibrary::addTitleAndMetaTags();
        $checkB = mosGetParam($_REQUEST, 'checkbook');
        if ($checkB != "on") {
            echo "<script> alert('Select an item to Lend'); window.history.go(-1);</script>\n";
            exit;
        }

        $data = JFactory::getDBO();
        $bookid = mosGetParam($_REQUEST, 'bookid');
        $id = mosGetParam($_REQUEST, 'id');
        $lend_from = mosGetParam($_REQUEST, 'lend_from');
        $lend_until = mosGetParam($_REQUEST, 'lend_until');

        if (!is_array($bids) || count($bids) < 1) {
            echo "<script> alert('Select an item to lend'); window.history.go(-1);</script>\n";
            exit;
        }

        $lend = new mosBookLibrary_lend($database);
        if ($task == "edit_lend")
            $lend->load($bids[0]);
        $query = "SELECT * FROM #__booklibrary_lend where fk_bookid = " . $id . " AND lend_return is NULL ";
        $data->setQuery($query);
        $lendTerm = $data->loadObjectList();

        if ($lend_from > $lend_until) {
            echo "<script> alert('" . $lend_from . " more then " . $lend_until . "'); window.history.go(-1); </script>\n";
            exit();
        }

        $lend_from = substr($lend_from, 0, 10);
        $lend_until = substr($lend_until, 0, 10);

        if (isset($lendTerm[0])) {
            for ($e = 0, $m = count($lendTerm); $e < $m; $e++) {
                if ($task == "edit_lend" && $bids[0] == $lendTerm[$e]->id)
                    continue;
                $lendTerm[$e]->lend_from = substr($lendTerm[$e]->lend_from, 0, 10);
                $lendTerm[$e]->lend_until = substr($lendTerm[$e]->lend_until, 0, 10);
                //check lend
                if (($lend_from >= $lendTerm[$e]->lend_from && $lend_from <= $lendTerm[$e]->lend_until) ||
                        ($lend_from <= $lendTerm[$e]->lend_from && $lend_until >= $lendTerm[$e]->lend_until) ||
                        ($lend_until >= $lendTerm[$e]->lend_from && $lend_until <= $lendTerm[$e]->lend_until)) {
                    echo "<script> alert('Sorry, this item already lend out from " . $lendTerm[$e]->lend_from . " until " . $lendTerm[$e]->lend_until . "'); window.history.go(-1); </script>\n";
                    exit();
                }
            }
        }//if end

        if (mosGetParam($_REQUEST, 'lend_from') != "")
            $lend->lend_from = data_transformer(mosGetParam($_REQUEST, 'lend_from'),"to");
        else
            $lend->lend_from = null;
        if (mosGetParam($_REQUEST, 'lend_until') != "")
            $lend->lend_until = data_transformer(mosGetParam($_REQUEST, 'lend_until'),"to");
        else
            $lend->lend_until = null;

        $lend->fk_bookid = $id;
        $userid = mosGetParam($_REQUEST, 'userid');

        if ($userid == "-1") {
            $lend->user_name = mosGetParam($_REQUEST, 'user_name', '');
            $lend->user_email = mosGetParam($_REQUEST, 'user_email', '');
        } else
            $lend->fk_userid = $userid;

        if (!$lend->check($lend)) {
            echo "<script> alert('" . $lend->getError() . "'); window.history.go(-1); </script>\n";
            exit();
        }

        if (!$lend->store()) {
            echo "<script> alert('" . $lend->getError() . "'); window.history.go(-1); </script>\n";
            exit();
        }

        $lend->checkin();
        $book = new mosBooklibrary($database);
        $book->load($id);
        $book->fk_lendid = $lend->id;
        $book->store();
        $book->checkin();



        if ($option == 'com_comprofiler')
            $link_for_mosRedirect = "index.php?option=" . $option . "&tab=getmybooksTab&Itemid=" . $Itemid;
        else
            $link_for_mosRedirect = "index.php?option=" . $option . "&view=show_my_books&layout=mybooks";

        //print_r($link_for_mosRedirect);exit;
        //mosRedirect($link_for_mosRedirect);
        PHP_booklibrary::showMyBooks($option);
    }

    static function lent($option, $bid) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $my;
        if (!is_array($bid) || count($bid) < 1) {
            echo "<script> alert('Select an item to lend'); window.history.go(-1);</script>\n";
            exit;
        }
        $bids = implode(',', $bid);

        $select = "SELECT a.*, cc.name AS category, l.id as lendid, l.lend_from as lend_from, " .
                "l.lend_return as lend_return, l.lend_until as lend_until, " .
                "l.user_name as user_name, l.user_email as user_email " .
                "\nFROM #__booklibrary AS a" .
                "\nLEFT JOIN #__booklibrary_categories AS bc ON bc.bookid  = a.bookid " .
                "\nLEFT JOIN #__booklibrary_main_categories AS cc ON cc.id = bc.catid" .
                "\nLEFT JOIN #__booklibrary_lend AS l ON l.id = a.fk_lendid" .
                "\nWHERE a.id in (" . $bids . ")";

        $database->setQuery($select);

        if (!$database->query()) {
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
            exit();
        }

        $books = $database->loadObjectList();

        //for rent or not
        $count = count($books);

        // get list of categories
        $userlist[] = mosHTML :: makeOption('-1', '----------');
        $database->setQuery("SELECT id AS value, name AS text from #__users ORDER BY name");
        $userlist = array_merge($userlist, $database->loadObjectList());
        $usermenu = mosHTML :: selectList($userlist, 'userid', 'class="inputbox" size="1"', 'value', 'text', '-1');

        HTML_booklibrary:: showLendBooks($option, $books, $usermenu, "lend");
    }

    function decline_rent_requests_cb($option, $vids) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $booklibrary_configuration, $Itemid;
        $datas = array();
        foreach ($vids as $vid) {
            $rent_request = new mosBookLibrary_lend_request($database);
            $rent_request->load($vid);
            $tmp = $rent_request->decline();
            if ($tmp != null) {
                echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
                exit();
            }
            foreach ($datas as $c => $data) {
                if ($rent_request->user_email == $data['email']) {
                    $datas[$c]['ids'][] = $rent_request->fk_bookid;
                    continue 2;
                }
            }
            $datas[] = array('email' => $rent_request->user_email, 'name' => $rent_request->user_name, 'id' => $rent_request->fk_bookid);
        }
        if ($booklibrary_configuration['lend_answer']) {
            PHP_booklibrary::sendMailRentRequestCB($datas, _BOOKLIBRARY_LENDREQUEST_EMAIL_DECLINED);
        }
        if ($option == 'com_booklibrary') {
            mosRedirect("index.php?option=$option&task=show_my_books&&layout=mybooks&Itemid=" . $Itemid);
        } else {
            mosRedirect("index.php?option=$option&task=view_user_books&tab=getmybooksTab&is_show_data=1&Itemid=" . $Itemid);
        }
    }

    function accept_rent_requests_cb($option, $bids) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $booklibrary_configuration, $Itemid;
        $datas = array();
        foreach ($bids as $vid) {
            $rent_request = new mosBookLibrary_lend_request($database);
            $rent_request->load($vid); //echo $rent_request->load($vid);exit;
            $tmp = $rent_request->accept();

            if ($tmp != null) {
                echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
                exit();
            }

            foreach ($datas as $c => $data) {
                if ($rent_request->user_email == $data['email']) {
                    $datas[$c]['ids'][] = $rent_request->fk_bookid;
                    continue 2;
                }
            }
            $datas[] = array('email' => $rent_request->user_email, 'name' => $rent_request->user_name, 'id' => $rent_request->fk_bookid);
        }

        if ($booklibrary_configuration['lend_answer']) {
            PHP_booklibrary::sendMailRentRequestCB($datas, _BOOKLIBRARY_LENDREQUEST_EMAIL_ACCEPTED);
        }
        if ($option == 'com_booklibrary') {
            mosRedirect("index.php?option=$option&task=show_my_books&&layout=mybooks&Itemid=" . $Itemid);
        } else {
            mosRedirect("index.php?option=$option&task=view_user_books&tab=getmybooksTab&is_show_data=1&Itemid=" . $Itemid);
        }
    }

    function sendMailRentRequestCB($datas, $answer) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $mosConfig_mailfrom, $booklibrary_configuration;
        $conf = JFactory::getConfig();

        foreach ($datas as $key => $data) {
            $mess = null;
            $zapros = "SELECT title FROM #__booklibrary WHERE id=" . $data['id'];
            $database->setQuery($zapros);
            $item_book = $database->loadResult();
            echo $database->getErrorMsg();
            $database->setQuery("SELECT u.name AS ownername,vm.owneremail
                            \nFROM #__users AS u
                            \nLEFT JOIN #__booklibrary AS vm ON vm.owneremail=u.email
                            \nWHERE vm.id=" . $data['id']);
            echo $database->getErrorMsg();
            $ownerdata = $database->loadObjectList();

            $datas[$key]['title'] = $item_book;

            $message = _BOOKLIBRARY_EMAIL_NOTIFICATION_LEND_REQUEST_ANSWER;
            $message = str_replace("{title}", $datas[$key]['title'], $message);
            $message = str_replace("{answer}", $answer, $message);
            $message = str_replace("{username}", $datas[$key]['name'], $message);
            if ($answer == _BOOKLIBRARY_LENDREQUEST_EMAIL_ACCEPTED) {
                $message = str_replace("{ownername}", $ownerdata[0]->ownername, $message);
                $message = str_replace("{owneremail}", $ownerdata[0]->owneremail, $message);
            } else {
                $message = str_replace("{ownername}", '', $message);
                $message = str_replace("{owneremail}", '', $message);
            }

            mosMail($mosConfig_mailfrom, $conf->_registry['config']['data']->fromname, $data['email'], _BOOKLIBRARY_EMAIL_LEND_ANSWER_SUBJECT, $message, true);
        }
    }

    function save_book_fe($option) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $my, $mosConfig_absolute_path, $mosConfig_live_site,
        $acl, $booklibrary_configuration, $mosConfig_mailfrom, $Itemid;

        if (array_key_exists('Itemid', $_POST))
            $Itemid = intval($_POST['Itemid']);

        $err_msg = '';
        //check how the other info should be provided
        $book = new mosBookLibrary($database);

        if (!is_numeric($_POST['bookid']) &&
                $booklibrary_configuration['bookid']['auto-increment']['boolean'] == 1) {
            $err_msg .= "You set no numeric BookID. Please set option " .
                    _BOOKLIBRARY_ADMIN_CONFIG_BOOKID_AUTO_INCREMENT .
                    " to 'No' or change BookID to numeric <br />";
        }

        if (!$book->bind($_POST)) {
            $err_msg .= "Catchable error: " . $book->getError() . '<br />';
        }

        if ((strlen($book->owneremail) > 0) && ($book->owner_id == 0))
            $book->owner_id = $my->id;

        /* if ($id != 0 && $my->id != $book->owner_id)
          {
          mosRedirect('index.php?option=com_booklibrary&Itemid=' . $Itemid);
          exit;
          }
          print_r($book);exit; */
        //fetch all information from the webservices if necessary
        if ($_POST['informationFrom'] != 0)
            $book = mosBooklibraryWS :: fetchInfos($book);

        if (is_string($book)) {
            $err_msg = "Error fetching info";
            mosRedirect("index.php?option=$option&Itemid=$Itemid", $err_msg);
        }

        if ($_POST['ebook_Url'] != '')
            $book->ebookURL = $_POST['ebook_Url'];

        //storing e-book
        $file = $_FILES['ebook_file'];

        //check if fileupload is correct
        if ($booklibrary_configuration['ebooks']['allow'] && intval($file['error']) > 0 && intval($file['error']) < 4) {

            echo "<script> alert('" . _BOOKLIBRARY_LABEL_EBOOK_UPLOAD_ERROR .
            "'); window.history.go(-1); </script>\n";
            exit();
        } elseif ($booklibrary_configuration['ebooks']['allow'] && intval($file['error']) != 4) {
            /* $file_new = $mosConfig_absolute_path . $booklibrary_configuration['ebooks']['location'] .
              $file['name'];
              echo $file_new; */

            //---------------------

            $uploaddir = $mosConfig_absolute_path . $booklibrary_configuration['ebooks']['location'];
            $file_new = $uploaddir . $_FILES['ebook_file']['name'];
            echo $file_new;
            $ext = pathinfo($_FILES['ebook_file']['name'], PATHINFO_EXTENSION);
            $allowed_exts = explode(",", $booklibrary_configuration['allowed_exts']);

            if (!in_array($ext, $allowed_exts)) {
                echo "<script> alert(' File ext. not allowed to upload! - " . $edfile['name'] . "'); window.history.go(-1); </script>\n";
                exit();
            }
            $db = JFactory::getDbo();
            $db->setQuery("SELECT mime_type FROM #__booklibrary_mime_types WHERE `mime_ext` = " . $db->quote($ext));
            $file_db_mime = $db->loadResult();
            $file['type'] = $_FILES['ebook_file']['type'];

            if ($file_db_mime != $file['type']) {
                echo "<script> alert(' File mime type not match file ext. - " . $edfile['name'] . "'); window.history.go(-1); </script>\n";
                exit();
            }
            //----------------------

            if (!move_uploaded_file($file['tmp_name'], $file_new)) {
                echo "<script> alert('" . _BOOKLIBRARY_LABEL_EBOOK_UPLOAD_ERROR .
                "'); window.history.go(-1); </script>\n";
                exit();
            } else {
                $book->ebookURL = $mosConfig_live_site . $booklibrary_configuration['ebooks']['location'] .
                        $file['name'];
            }
        }

        if ($booklibrary_configuration['publish_on_add']['show']) {
            if (checkAccessBL($booklibrary_configuration['publish_on_add']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $book->published = 1;
            } else
                $book->published = 0;
        } else
            $book->published = 0;

        $file = $_FILES['picture_file'];

        //-------------------
        if (intval($file['error']) != 4) {

            $ext = pathinfo($_FILES['picture_file']['name'], PATHINFO_EXTENSION);
            $allowed_exts = explode(",", $booklibrary_configuration['allowed_exts_img']);

            if (!in_array($ext, $allowed_exts)) {
                echo "<script> alert(' File ext. not allowed to upload! - " . $file['name'] . "'); window.history.go(-1); </script>\n";
                exit();
            }
        }
        //-------------------
        //check if fileupload is correct

        if ($file['size'] != 0 && ( $file['error'] != 0 || strpos($file['type'], 'image') === false || strpos($file['type'], 'image') === "")) {

            $err_msg .= _BOOKLIBRARY_LABEL_PICTURE_URL_UPLOAD_ERROR . '<br />';
        }

        //store pictures locally if neccesary, first check remote URL
        $retVal = null;
        if (intval($booklibrary_configuration['fetchImages']['boolean']) == 1 && trim($book->imageURL) != "" && $file['size'] == 0) {
            $retVal = mosBooklibraryOthers :: storeImageFile($book, null);
        }

        if (intval($booklibrary_configuration['fetchImages']['boolean']) == 1 && $file['size'] != 0) {
            $retVal = mosBooklibraryOthers :: storeImageFile($book, $file);
            if ($retVal != null) {
                $err_msg .= $retVal . "<br />";
            }
        }
        if ($file['size'] == 0) {
            $file = null;
        }

        //ERR OUT
        if ($err_msg != '')
            mosRedirect("index.php?option=$option&Itemid=$Itemid", $err_msg);
        //END ERR OUT

        $book->date = date("Y-m-d H:i:s");

        if (!$book->check()) {
            echo "<script> alert('" . addslashes($book->getError()) . "'); window.history.go(-1); </script>\n";
            exit();
        }

        if (!$book->store()) {
            echo "<script> alert('" . addslashes($book->getError()) . "'); window.history.go(-1); </script>\n";
            exit();
        }

        $catid = mosGetParam($_POST, 'catid', '');
        if (empty($catid)) {
            ?>
            <script>alert("<?php echo _BOOKLIBRARY_ADMIN_INFOTEXT_JS_EDIT_CATEGORY; ?>");
                window.history.go(-1);</script>
            <?php
            exit();
        }

        $query = "SELECT id FROM #__booklibrary_categories WHERE bookid='" . $book->id . "'";
        $database->setQuery($query);
        $categ_id = $database->loadResult();
        if (isset($categ_id) || $categ_id != 0) {
            $stroka = "Update #__booklibrary_categories SET bookid='" . $book->id . "', catid='" . $catid[0] . "' WHERE id='" . $categ_id . "'";
        } else {
            $catid_tmp = array();
            for ($i = 0; $i < count($catid); $i++) {
                $catid_tmp[] = $catid[$i];
                $stroka = "INSERT INTO #__booklibrary_categories (bookid, catid)" .
                        "\n VALUES" .
                        "\n ('" . $book->id . "', '" . $catid_tmp[$i] . "');";
                $database->setQuery($stroka);
                $database->query();
            }
        }

        $book->checkin();

       // Parameters
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        }

        if ($booklibrary_configuration['addbook_email']['show']) { 
                if (checkAccessBL($booklibrary_configuration['addbook_email']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl))
              $params->def('show_input_email', 1); 
        }
        if ($params->get('show_input_email')) { 
              $mail_to = explode(",", $booklibrary_configuration['lendrequest_email']['address']);
              $userid = $my->id;
              $zapros = "SELECT name, email FROM #__users WHERE id=" . $userid . ";";
              $database->setQuery($zapros);
              $item_user = $database->loadObjectList();              
              $query = "SELECT * FROM #__booklibrary_main_categories WHERE id='" . $catid[0] . "'";
              $database->setQuery($query);
              $cat_name = $database->loadAssoc();
              $mes_title = "Add Book";

              if ($_POST['owneremail'] != "")
                  $email = $_POST['owneremail'];
              else
                  $email = "anonymous";
              $message = _BOOKLIBRARY_EMAIL_NOTIFICATION_ADD_BOOK;
              $message = str_replace("{title}", $mes_title, $message);
              $message = str_replace("{id}", $_POST['bookid'], $message);
              $message = str_replace("{username}", $email, $message);
              $message = str_replace("{date}", date("r"), $message);
              $message = str_replace("{category}", $cat_name['title'], $message);
              mosMail($mosConfig_mailfrom, $item_user[0]->name, $mail_to, $mes_title, $message, true);
        }
        mosRedirect("index.php?option=$option&Itemid=$Itemid", 'Book successfuly added. You can see it after administrator approval.');
    }

    static function add_book_fe($option, $bid) {
        global $database, $my, $mosConfig_live_site, $booklibrary_configuration, $Itemid, $mainframe;

        PHP_booklibrary::addTitleAndMetaTags();

        $book = new mosBookLibrary($database);
        $book->load(intval($bid));

        if ($bid != 0 && $my->email != $book->owneremail) {
            mosRedirect('index.php?option=com_booklibrary&Itemid=$Itemid');
            exit;
        }
        if ($bid == 0) {
            $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=add_book_fe&amp;Itemid=' . $Itemid);
            $pathway_name = _BOOKLIBRARY_LABEL_TITLE_ADD_BOOK;
        } else {
            $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=edit_book&amp;Itemid=' . $Itemid . '&amp;id=' . $bid);
            $pathway_name = _BOOKLIBRARY_LABEL_TITLE_EDIT_BOOK;
        }
        $path_way = $mainframe->getPathway();
        $path_way->addItem($pathway_name, $pathway);


        $tpl_list = array();
        if (array_key_exists('catid', $_POST))
            $catid = intval($_POST['catid']);
        else
            $catid = '';

        $auto_bookID = '';
        if ($booklibrary_configuration['bookid']['auto-increment']['boolean'] == 1) {
            $database->setQuery("select bookid from #__booklibrary ORDER by bookid");
            $bookids = $database->loadObjectList();
            foreach ($bookids as $bookid) {
                if (!is_numeric($bookid->bookid)) {
                    echo "<script> alert('You have no numeric BookId. Please set option  " . _BOOKLIBRARY_ADMIN_CONFIG_BOOKID_AUTO_INCREMENT . " to \'No\' or change all BookID to numeric '); window.history.go(-1); </script>\n";
                    exit();
                }
                if ((int) $auto_bookID < $bookid->bookid)
                    $auto_bookID = $bookid->bookid;
            }
            if ($auto_bookID != '') {
                ++$auto_bookID;
            } else
                $auto_bookID = 1;
        }


        $categories[] = mosHTML :: makeOption('0', _BOOKLIBRARY_LABEL_SELECT_CATEGORIES);

        $query = "SELECT  id ,name, parent_id as parent"
                . "\n FROM #__booklibrary_main_categories"
                . "\n WHERE section='com_booklibrary'"
                . "\n AND published > 0"
                . "\n ORDER BY parent_id, ordering";

        $database->setQuery($query);

        $rows = $database->loadObjectList();


        // establish the hierarchy of the categories
        $children = array();
        // first pass - collect children
        foreach ($rows as $v) {
            $pt = $v->parent;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }

        // second pass - get an indent list of the items
        $list = PHP_booklibrary::bookLibraryTreeRecurse(0, '', array(), $children);

        foreach ($list as $i => $item) {
            $item->text = $item->treename;
            $item->value = $item->id;
            $list[$i] = $item;
        }

        $categories = array_merge($categories, $list);

        $allow_categories = explode(',', $booklibrary_configuration['addbook_button']['allow']['categories']);

        if (count($categories) <= 1) {
            mosRedirect("index.php?option=com_booklibrary&section=categories", _BOOKLIBRARY_ADMIN_IMPEXP_ADD);
        }
        $query = "select catid from #__booklibrary_categories where bookid='" . $book->id . "'";
        $database->setQuery($query);

        if (version_compare(JVERSION, '3.0', 'lt')) {
            $cat_idlist = $database->loadResultArray();
        } else {

            $cat_idlist = $database->loadColumn();
        }

        if (empty($cat_idlist))
            $cat_idlist[0] = '0';

        if (in_array("-2", $allow_categories)) {
            $clist = mosHTML :: selectList($categories, 'catid[]', 'class="inputbox" multiple', 'value', 'text', $cat_idlist);
        } else {
            $categories_n = array();
            for ($i = 1; $i < count($categories); $i++) {
                if (in_array($categories[$i]->id, $allow_categories))
                    $categories_n[] = $categories[$i];
            }
            $clist = mosHTML :: selectList($categories_n, 'catid[]', 'class="inputbox" multiple', 'value', 'text', $cat_idlist);
        }

        $tpl_list['clist'] = $clist;
        $tpl_list['ncid'] = $catid;

        // get list of WS
        $retVal = mosBooklibraryWS :: getArray();
        $ws = null;
        for ($i = 0, $n = count($retVal); $i < $n; $i++) {
            $help = $retVal[$i];
            $ws[] = mosHTML :: makeOption($help[0], $help[1]);
        }

        $tpl_list['wlist'] = mosHTML :: selectList($ws, 'informationFrom', 'class="inputbox" size="1"', 'value', 'text', intval($booklibrary_configuration['editbook']['default']['host']));


        //get language List
        $retVal1 = mosBooklibraryOthers :: getLanguageArray();
        $lang = null;
        for ($i = 0, $n = count($retVal1); $i < $n; $i++) {
            $help = $retVal1[$i];
            $lang[] = mosHTML :: makeOption($help[0], $help[1]);
        }

        $tpl_list['langlist'] = mosHTML :: selectList($lang, 'language', 'class="inputbox" size="1"', 'value', 'text', $booklibrary_configuration['editbook']['default']['lang']);
        $tpl_list['auto_bookID'] = $auto_bookID;

        //get Rating
        $retVal2 = mosBooklibraryOthers :: getRatingArray();
        $rating = null;
        for ($i = 0, $n = count($retVal2); $i < $n; $i++) {
            $help = $retVal2[$i];
            $rating[] = mosHTML :: makeOption($help[0], $help[1]);
        }
        $ratinglist = mosHTML :: selectList($rating, 'rating', 'class="inputbox" size="1"', 'value', 'text', $book->rating);

        HTML_booklibrary::showAddBook($tpl_list, $option, $Itemid, $ratinglist, $book);
    }

    static function rent_requests_cb($option, $vid) {

        global $database, $my, $mainframe, $mosConfig_list_limit, $Itemid, $booklibrary_configuration;

        PHP_booklibrary::addTitleAndMetaTags();

        $limit = $booklibrary_configuration['page']['items'];
        $limitstart = mosGetParam($_REQUEST, 'limitstart', 0);

        $database->setQuery("SELECT count(*) FROM #__booklibrary AS a" .
                "\nLEFT JOIN #__booklibrary_lend_request AS l" .
                "\nON l.fk_bookid = a.id" .
                "\nWHERE l.status = 0 AND a.owneremail LIKE '$my->email'");
        $total = $database->loadResult();
        echo $database->getErrorMsg();

        $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
        $query = "SELECT * FROM #__booklibrary AS a" .
                "\nLEFT JOIN #__booklibrary_lend_request AS l" .
                "\nON l.fk_bookid = a.id" .
                "\nWHERE l.status = 0 AND a.owneremail LIKE '$my->email'" .
                "\nORDER BY l.lend_from, l.lend_until, l.user_name" .
                "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
        $database->setQuery($query);
        $rent_requests = $database->loadObjectList();
        ;

        echo $database->getErrorMsg();
//     $menu = new mosMenu($database);
//     $menu->load( $Itemid );
//        $params = new mosParameters( $menu->params );
        if (version_compare(JVERSION, '3.0', 'lt')) {
            $menu = new JTableMenu($database); // for 1.6
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        } else {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        }

        HTML_booklibrary :: showRequestRentBooksCB($option, $rent_requests, $pageNav, $params);
    }

    static function books($option) {
        global $mainframe, $database, $my, $acl;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $booklibrary_configuration, $limitstart, $limit;

        PHP_booklibrary::addTitleAndMetaTags();
// --
        $sp = 0;
        if (array_key_exists("sp", $_REQUEST)) {
            $sp = mosGetParam($_REQUEST, 'sp', 0);
        }
        $where = array();
        $list_str = array();
        if (array_key_exists("letindex", $_REQUEST)) {
            $search = mosGetParam($_REQUEST, 'letindex', '');
            //echo $search;

            if (isset($_REQUEST['now_indexed'])) { // for 1.6
                switch ($_REQUEST['now_indexed']) {
                    case "authors":
                        if ($sp == 1)
                            array_push($where, "(LOWER(b.authors) LIKE '$search%' )");
                        break;

                    case "title":
                        if ($sp == 1)
                            array_push($where, "(LOWER(b.title) LIKE '$search%' )");
                        break;
                }
            } // --
        }

        array_push($where, "b.published='1'");
        array_push($where, "b.approved='1'");
        array_push($where, "b.archived='0'");

        // getting all books for this category
        // Sort start

        if ($booklibrary_configuration['category']['default_sort'] != '')
            $default_field = $booklibrary_configuration['category']['default_sort'];
        else
            $default_field = 'title';

        // SORTING parameters start
        $prefix = '';
        $item_session = JFactory::getSession();

        $item_sort_param = '';
        $sort_arr['direction'] = '';
        if (array_key_exists('sortup', $_GET)) {
            $item_sort_param = mosGetParam($_GET, 'sortup', '');
            $sort_arr['direction'] = 'DESC';
        }

        if (array_key_exists('sortdown', $_GET)) {
            $item_sort_param = mosGetParam($_GET, 'sortdown', '');
            $sort_arr['direction'] = '';
        }

        $sort_arr['field'] = $item_sort_param;

        $item_sort_param = preg_replace('/[^A-Za-z0-9_]*/', '', $item_sort_param);

        if ($item_sort_param == '') {
            if (is_array($sort_arr = $item_session->get('bl_fe_booksort', ''))) {
                $sort_string = $sort_arr['field'] . " " . $sort_arr['direction'];
            } else {
                $sort_string = $default_field;
                $sort_arr = array();
                $sort_arr['field'] = $default_field;
                $sort_arr['direction'] = '';
                $item_session->set('bl_fe_booksort', $sort_arr);
            }
        } else {
            if ($item_sort_param != $sort_arr['field']) {
                $sort_arr['field'] = $item_sort_param;
                $sort_arr['direction'] = '';
            }
            if ($sort_arr['field'] != 'category')
                $prefix = 'b.';
            $sort_string = $prefix . $sort_arr['field'] . " " . $sort_arr['direction'];
            $item_session->set('bl_fe_booksort', $sort_arr);
        }
        //sorting item
        $item_session = JFactory::getSession();
        $sort_arr = $item_session->get('bl_fe_booksort', '');
        if (is_array($sort_arr)) {
            $tmp1 = mosGetParam($_POST, 'direction');
            if ($tmp1 != '') {
                $sort_arr['direction'] = $tmp1;
            }
            $tmp1 = mosGetParam($_POST, 'field');
            if ($tmp1 != '') {
                $sort_arr['field'] = $tmp1;
            }
            $item_session->set('bl_fe_booksort', $sort_arr);
        } else {
            $sort_arr = array();
            $sort_arr['field'] = 'title';
            $sort_arr['direction'] = 'asc';
            $item_session->set('bl_fe_booksort', $sort_arr);
        }
        if ($sort_arr['field'] == "price")
            $sort_string = "CAST( " . $sort_arr['field'] . " AS SIGNED)" . " " . $sort_arr['direction'];
        else
            $sort_string = $sort_arr['field'] . " " . $sort_arr['direction'];

//end sorting item
        $s = getWhereUsergroupsString("c"); // for 1.6
        $where[] = $s;
        //print_r($where);exit;
        $query = "SELECT COUNT(DISTINCT b.id)
    FROM #__booklibrary AS b " .
                "\nLEFT JOIN #__booklibrary_categories AS bc ON b.id = bc.bookid" .
                "\nLEFT JOIN #__booklibrary_main_categories AS c ON bc.catid = c.id" .
                "\n WHERE {$where[0]}";

        $database->setQuery($query);
        $total = $database->loadResult();
        //print_r($total);exit;
        $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
        // Sort end

        $query = "SELECT b.*,blr2.rating2, c.title as category, c.id AS category_id, c.ordering as category_ordering FROM #__booklibrary AS b " .
                "\nLEFT JOIN #__booklibrary_categories AS bc ON b.id = bc.bookid" .
                "\nLEFT JOIN #__booklibrary_main_categories AS c ON bc.catid = c.id" .
                "\nLEFT JOIN ( SELECT ROUND(avg(blr1.rating)) as rating2, fk_bookid   
        FROM #__booklibrary as bl  LEFT JOIN #__booklibrary_review as blr1 on blr1.fk_bookid = bl.id group by blr1.fk_bookid ) blr2 
          ON  blr2.fk_bookid = b.id" .
                ((count($where) ? "\nWHERE " . "(" . $s . ") AND $where[0]" : "")) .
                "\nGROUP BY b.id" .
                "\nORDER BY $sort_string" .
                "\nLIMIT $pageNav->limitstart,$pageNav->limit;";

        $database->setQuery($query);
        $books = $database->loadObjectList();


        $currentcat = NULL;
        // Parameters
        //$menu = new JTableMenu( $database ); // for 1.6 - JTableMenu
        /* $menu = new mosMenu( $database );
          $menu_name = set_header_name_bl($menu, $Itemid);

          $menu->load( $Itemid );
          $params = new mosParameters( $menu->params ); */

        if (version_compare(JVERSION, '3.0', 'lt')) {
            $menu = new JTableMenu($database); // for 1.6
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        } else {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        }
        $menu_name = set_header_name_bl($menu, $Itemid);

        $params->def('header', $menu_name); // for 1.6
        $params->def('pageclass_sfx', '');
        $params->def('category_name', _BOOKLIBRARY_LABEL_ALLBOOKS);
        $params->def('search_request', '1');
        $params->def('hits', 1);
        $params->def('show_rating', 1);
        //------------------------------------- begin add for  Manager : buttons    ******************************
        if (($GLOBALS['print_pdf_show'])) {
            $params->def('show_print_pdf', 1);
            if (checkAccessBL($GLOBALS['print_pdf_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_print_pdf', 1);
            }
        }

        if (($GLOBALS['print_view_show'])) {
            $params->def('show_print_view', 1);
            if (checkAccessBL($GLOBALS['print_view_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_print_view', 1);
            }
        }


        if (($GLOBALS['mail_to_show'])) {
            $params->def('show_mail_to', 1);
            if (checkAccessBL($GLOBALS['mail_to_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_mail_to', 1);
            }
        }



        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }



        if (($GLOBALS['lendstatus_show'])) {
            $params->def('show_lendstatus', 1);

            if (checkAccessBL($GLOBALS['lendrequest_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {

                $params->def('show_lendrequest', 1);
            }
        }




        if (checkAccessBL($booklibrary_configuration['addbook_email']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl))
            $user = $my->username;
        else {
            $user = 'anonymouse';
        }

//*******   begin add for Manager Suggestion: button 'Suggest a book' *******

//****   end add for Manager Suggestion: button 'Suggest a book'   *****
        $currentcat = new stdClass();
        $currentcat->descrip = _BOOKLIBRARY_ALLBOOK_DESC;
        $currentcat->align = 'right';
        // page image
        $currentcat->img = $mosConfig_live_site . "/components/com_booklibrary/images/book.png";

        $currentcat->header = '';
        if (@$currentcat->name <> '') {
            $currentcat->header = $currentcat->name;
        } else {
            $currentcat->header = $params->get('header');
        }

        $currentcat->header = $currentcat->header . (($currentcat->header != '') ? ": " : '') . _BOOKLIBRARY_LABEL_ALLBOOKS;

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');


        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }

        if (checkAccessBL($booklibrary_configuration['search_field']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl) &&
                $booklibrary_configuration['search_field']['show']) {
            $params->def('search_fieldshow', 1);
        }
        if (checkAccessBL($booklibrary_configuration['advsearch']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl) &&
                $booklibrary_configuration['advsearch']['show']) {
            $params->def('advsearch_show', 1);
        }

        $s = getWhereUsergroupsString("c"); // for 1.6

        if ($booklibrary_configuration['litpage']['show'] &&
                checkAccessBL($booklibrary_configuration['litpage']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
            // Show literally index
            if ($sort_arr['field'] == 'title') {
                $query = 'SELECT DISTINCT UPPER(SUBSTRING(b.title, 1,1)) AS symb
                                        FROM #__booklibrary as b
                                        LEFT JOIN #__booklibrary_categories AS bc ON b.id=bc.bookid
                                        LEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid
                                        WHERE  b.published=\'1\' AND b.approved=\'1\'
                                                 AND c.published=\'1\' AND (' . $s . ') OR c.params=""
                                                   ORDER BY symb';
            } else {
                $query = 'SELECT DISTINCT UPPER(SUBSTRING(b.authors, 1,1)) AS symb
                                                FROM #__booklibrary as b
                                                LEFT JOIN #__booklibrary_categories AS bc ON b.id=bc.bookid
                                                LEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid
                                                WHERE  b.published=\'1\' AND b.approved=\'1\'
                                                 AND c.published=\'1\' AND (' . $s . ') OR c.params=""
                                                 ORDER BY symb';
            }

            $database->setQuery($query);
            $tmp_arr = $database->loadObjectList();

            $symb_list_str = '<div style="margin:0px auto;" class="my_pagination my_pagination-small my_pagination-centered"><ul>';
            foreach ($tmp_arr as $symbol) {
                $symb_list_str .= '<li style="padding:5px;list-style:none;"><a href="index.php?option=' . $option . '&task=books&letindex=' . $symbol->symb . '&sp=1&Itemid=' . $Itemid . '&now_indexed=' . $sort_arr['field'] . '">' . $symbol->symb . '</a></span> ';
            }
            $symb_list_str.="<ul></div>";
            $list_str['symbol_list'] = $symb_list_str;
        }
        //************* choose layout****************

        $layout = $params->get('books', '');
        if (!isset($layout) or $layout == '') {
            $layout = $booklibrary_configuration['books'];
        }
        //************* end choose layout****************
        //sorting item
        $item_session = JFactory::getSession();
        $sort_arr = $item_session->get('bl_fe_booksort', '');
        if (is_array($sort_arr)) {
            $tmp1 = mosGetParam($_POST, 'direction');
            if ($tmp1 != '') {
                $sort_arr['direction'] = $tmp1;
            }
            $tmp1 = mosGetParam($_POST, 'field');
            if ($tmp1 != '') {
                $sort_arr['field'] = $tmp1;
            }
            $item_session->set('bl_fe_booksort', $sort_arr);
        } else {
            $sort_arr = array();
            $sort_arr['field'] = 'title';
            $sort_arr['direction'] = 'asc';
            $item_session->set('bl_fe_booksort', $sort_arr);
        }
        if ($sort_arr['field'] == "price")
            $sort_string = "CAST( " . $sort_arr['field'] . " AS SIGNED)" . " " . $sort_arr['direction'];
        else
            $sort_string = $sort_arr['field'] . " " . $sort_arr['direction'];
        $params->def('sort_arr_direction', $sort_arr['direction']);
        $params->def('sort_arr_field', $sort_arr['field']);
        //end sorting item
        //echo'<pre>';        
        //print_r($list_str);
        //echo'</pre>';
        HTML_booklibrary::displayBooks($books, $currentcat, $params, $tabclass, 0, null, false, $sort_arr, $list_str, $pageNav, $layout);
    }

    function output_file($file, $name, $mime_type = '') {

        PHP_booklibrary::addTitleAndMetaTags();
        /*
          This function takes a path to a file to output ($file),
          the filename that the browser will see ($name) and
          the MIME type of the file ($mime_type, optional).

          If you want to do something on download abort/finish,
          register_shutdown_function('function_name');
         */
        if (!is_readable($file))
            die('File not found or inaccessible!');

        $size = filesize($file);
        $name = rawurldecode($name);

        /* Figure out the MIME type (if not specified) */
        $known_mime_types = array(
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "html" => "text/html",
            "htm" => "text/html",
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "doc" => "application/msword",
            "xls" => "application/vnd.ms-excel",
            "ppt" => "application/vnd.ms-powerpoint",
            "gif" => "image/gif",
            "png" => "image/png",
            "jpeg" => "image/jpg",
            "jpg" => "image/jpg",
            "php" => "text/plain"
        );

        if ($mime_type == '') {
            $file_extension = strtolower(substr(strrchr($file, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
            } else {
                $mime_type = "application/force-download";
            };
        };

        ob_end_clean(); //turn off output buffering to decrease cpu usage
        // required for IE, otherwise Content-Disposition may be ignored
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header('Content-Type: application/force-download');
        header("Content-Disposition: inline; filename=$name");
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        /* The three lines below basically make the 
          download non-cacheable */
        header("Cache-control: private");
        header('Pragma: private');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        // multipart-download and download resuming support
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }

            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }


        $chunksize = 1 * (1024 * 1024); //you may want to change this
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);

            while (!feof($file) &&
            (!connection_aborted()) &&
            ($bytes_send < $new_length)
            ) {
                $buffer = fread($file, $chunksize);
                print($buffer); //echo($buffer); // is also possible
                flush();
                $bytes_send += strlen($buffer);
            }
            fclose($file);
        } else
            die('Error - can not open file.');

        die();
    }

    function mydownload($id) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $booklibrary_configuration;
        global $mosConfig_absolute_path;


        $session = JFactory::getSession();
        $pas = $session->get("ssmid", "default");
        $sid_1 = $session->getId();

        if (!($session->get("ssmid", "default")) ||
                $pas == "" ||
                $pas != $sid_1 ||
                $_COOKIE['ssd'] != $sid_1 ||
                !array_key_exists("HTTP_REFERER", $_SERVER) ||
                $_SERVER["HTTP_REFERER"] == "" ||
                strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) === false) {
            echo '<H3 align="center">Link failure</H3>';
            exit;
        }

        if ($GLOBALS['license_show']) {
            $fd = fopen($mosConfig_absolute_path . "/components/com_booklibrary/mylicense.php", "w") or die("Config license file is failure");
            fwrite($fd, $GLOBALS['license_text']);
            fclose($fd);
            HTML_booklibrary :: displayLicense($id);
        } else {
            $this->downloaditself($id);
        }
    }

    function downloaditself($idt) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $my;
        global $booklibrary_configuration;
        global $mosConfig_absolute_path;



        $session = JFactory::getSession();
        $pas = $session->get("ssmid", "default");
        $sid_1 = $session->getId();

        if (!($session->get("ssmid", "default")) ||
                $pas == "" ||
                $pas != $sid_1 ||
                $_COOKIE['ssd'] != $sid_1 ||
                !array_key_exists("HTTP_REFERER", $_SERVER) ||
                $_SERVER["HTTP_REFERER"] == "" ||
                strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) === false) {
            echo '<H3 align="center">Link failure</H3>';
            exit;
        }
        $session->set("ssmid", "default");

        if (array_key_exists("id", $_POST))
            $id = intval($_POST['id']);
        else
            $id = intval($idt);

        $query = "SELECT * from #__booklibrary where id = " . $id;
        $database->setQuery($query);
        $book = $database->loadObjectList();
        if ($book[0]->published == 0)
            JError::raiseError(404, _BOOKLIBRARY_RESULT_NOT_FOUND);
        if (strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) !== false) {
            $name = explode('/', $book[0]->ebookURL);
            $file_path = $mosConfig_absolute_path . $GLOBALS['ebooks_location'] . $name[count($name) - 1];

            set_time_limit(0);
            PHP_booklibrary::output_file($file_path, $name[count($name) - 1]);
            exit;
        } else {
            header("Cache-control: private");
            header('Pragma: private');
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("HTTP/1.1 301 Moved Permanently");
            header('Content-Type: application/force-download');
            header("Location: " . $book[0]->ebookURL);
            exit;
        }
    }

    function saveLendRequest($option, $bids) {
        global $mainframe, $database, $my, $Itemid, $acl, $mosConfig_live_site;
        global $booklibrary_configuration, $mosConfig_mailfrom, $doc;
        //print_r($mosConfig_mailfrom);
        PHP_booklibrary::addTitleAndMetaTags();

        $doc->addStyleSheet($mosConfig_live_site . '/components/com_booklibrary/includes/booklibrary.css');
        $data = JFactory::getDBO();
        if (!($GLOBALS['lendstatus_show']) ||
                !checkAccessBL($GLOBALS['lendrequest_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
            echo _BOOKLIBRARY_NOT_AUTHORIZED;
            return;
        }

        $help = array();
        foreach ($bids as $bid) {

            $lend_request = new mosBookLibrary_lend_request($database);
            if (!$lend_request->bind($_POST)) {
                echo "<script> alert('" . addslashes($lend_request->getError()) . "'); window.history.go(-1); </script>\n";
                exit();
            }

            //-----------------
            $date_format = $booklibrary_configuration['date_format'];
            if(phpversion() >= '5.3.0') {
                $date_format = str_replace('%', '', $date_format);
                $d_from = DateTime::createFromFormat($date_format, $_POST['lend_from']);
                $d_until = DateTime::createFromFormat($date_format, $_POST['lend_until']);
                if ($d_from === FALSE or $d_until === FALSE) {
                     echo "<script> alert('Bad date format'); window.history.go(-1); </script>\n";
                     exit;
                }            
                $lend_request->lend_from = $d_from->format('Y-m-d');
                $lend_request->lend_until = $d_until->format('Y-m-d');
            } else {
                $lend_request->lend_from = data_transformer($_POST['lend_from'],'to');
                $lend_request->lend_until = data_transformer($_POST['lend_until'],'to');
            }
            //-----------------

            $lend_request->lend_request = date("Y-m-d H:i:s");
            $lend_request->fk_bookid = intval($bid);
            $query = "SELECT id FROM #__users WHERE email='" . $lend_request->user_email . "'";
            $data->setquery($query);
            $user_id = $data->loadResult();

            if (isset($user_id) || $user_id != '0') {
                $lend_request->fk_userid = intval($user_id);
            }


            $query = "SELECT * FROM #__booklibrary where id= " . $lend_request->fk_bookid;
            $data->setQuery($query);
            $bookid = $data->loadObjectList();
            $query = "SELECT * FROM #__booklibrary_lend where fk_bookid= " .
                    $bookid[0]->id . " AND lend_return IS NULL";
            $data->setQuery($query);
            $rents = $data->loadObjectList();


            if (isset($rents[0])) {
                for ($e = 0, $m = count($rents); $e < $m; $e++) {
                    $rents[$e]->lend_from = substr($rents[$e]->lend_from, 0, 10);
                    $rents[$e]->lend_until = substr($rents[$e]->lend_until, 0, 10);
                    //cheking the rent  

                    if (($lend_request->lend_from >= $rents[$e]->lend_from && $lend_request->lend_from <= $rents[$e]->lend_until) ||
                            ($lend_request->lend_until >= $rents[$e]->lend_from && $lend_request->lend_until <= $rents[$e]->lend_until) ||
                            ($lend_request->lend_from <= $rents[$e]->lend_from && $lend_request->lend_until >= $rents[$e]->lend_until)) {
                        echo "<script> alert('Sorry this object is already rent out from " . $rents[$e]->lend_from . " to " . $rents[$e]->lend_until . "'); window.history.go(-1); </script>\n";
                        exit();
                    }
                }
            }

            if (!$lend_request->check()) {
                echo "<script> alert('" . addslashes($lend_request->getError()) . "'); window.history.go(-1); </script>\n";
                exit();
            }
            if (!$lend_request->store()) {
                echo "<script> alert('" . addslashes($lend_request->getError()) . "'); window.history.go(-1); </script>\n";
                exit();
            }

            $lend_request->checkin();
            array_push($help, $lend_request);
        }

        $currentcat = NULL;
        // Parameters
        //$menu = new JTableMenu( $database );//for 1.6
//   $menu = new mosMenu( $database );
//   $menu_name = set_header_name_bl($menu, $Itemid);
// 
//   $menu->load( $Itemid );
//   $params = new mosParameters( $menu->params );

        if (version_compare(JVERSION, '3.0', 'lt')) {
            $menu = new JTableMenu($database); // for 1.6
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        } else {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        }

        $menu_name = set_header_name_bl($menu, $Itemid);
        $params->def('header', $menu_name); //for 1.6

        $params->def('pageclass_sfx', '');
        //
        $params->def('show_search', '1');
        $params->def('back_button', $mainframe->getCfg('back_button'));
        $currentcat = new stdClass();
        $currentcat->descrip = _BOOKLIBRARY_LABEL_LEND_REQUEST_THANKS;

        // page image
        $currentcat->img = $mosConfig_live_site . "/components/com_booklibrary/images/book.png";


        $currentcat->header = '';
        if (@$currentcat->name <> '') {
            $currentcat->header = $currentcat->name;
        } else {
            $currentcat->header = $params->get('header');
        }
        $currentcat->header = $currentcat->header;

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

//********************   begin add send mail for admin   ********
        if (($GLOBALS['lendrequest_email_show'])) {
            $params->def('show_email', 1);
            if (checkAccessBL($GLOBALS['lendrequest_email_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_email', 1);
            }
        }

        if ($params->get('show_input_email')) {
            if (trim($GLOBALS['lendrequest_email_address']) != "")
                $mail_to = explode(",", $GLOBALS['lendrequest_email_address']);

            $userid = $my->id;
            //select user (added lend request)
            $zapros = "SELECT name, email FROM #__users WHERE id=" . $userid . ";";
            $database->setQuery($zapros);
            $item_user = $database->loadObjectList();
            echo $database->getErrorMsg();


            for ($i = 0; $i < count($bids); $i++) {
                $zapros = "SELECT id, bookid, isbn,title,owneremail FROM #__booklibrary WHERE id=" . intval($bids[$i]) . ";";
                $database->setQuery($zapros);
                $item_book = $database->loadObjectList();
                echo $database->getErrorMsg();
                
                if (trim($item_book[0]->owneremail) != '')
                    $mail_to[] = $item_book[0]->owneremail;

            }

            $query = "SELECT * FROM #__booklibrary WHERE id='" . $_REQUEST['bookid'] . "'";
            $database->setQuery($query);
            $book_name = $database->loadAssoc();

            if ($_REQUEST['user_name'] != "")
                $name = $_REQUEST['user_name'];
            else
                $name = "anonymous";
                
            if (count($mail_to) > 0) 
                $username = (isset($item_user[0]->name)) ? $item_user[0]->name : "anonymous";
                $message = _BOOKLIBRARY_EMAIL_NOTIFICATION_LEND_REQUEST;
                $message = str_replace("{username}", $name, $message);
                $message = str_replace("{book_title}", $book_name['title'], $message);
            if ($userid == 0) {
                mosMail($mosConfig_mailfrom, 'anonymous', $mail_to, 'New rent request added!', $message, true);
            } else {
                mosMail($mosConfig_mailfrom, $item_user[0]->name, $mail_to, 'New rent request added!', $message, true);
            }
        }
        //********************   end add send mail for admin   ****************
        if ($option == 'com_comprofiler')
            $link_for_mosRedirect = "index.php?option=" . $option . "&tab=getmybooksTab&Itemid=" . $Itemid;
        else
            $link_for_mosRedirect = "index.php?option=" . $option;
        $HTML_booklibrary = new HTML_booklibrary();
        $HTML_booklibrary->showLendRequestThanks($params, $currentcat);
    }

    static function showLendRequest($option, $bid) {
        global $mainframe, $database, $my, $Itemid, $acl;
        global $booklibrary_configuration, $mosConfig_live_site;

        PHP_booklibrary::addTitleAndMetaTags();

        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }

        if (!($GLOBALS['lendstatus_show']) ||
                !checkAccessBL($GLOBALS['lendrequest_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
            echo _BOOKLIBRARY_NOT_AUTHORIZED;
            return;
        }

        $bids = implode(',', $bid);

        // getting all books for this category
        /// Maybe   IN (" .  $bids . ")  needs to be removed with LIKE construction
        $query = "SELECT * FROM #__booklibrary"
                . "\nWHERE id IN (" . $bids . ")"
                . "\nORDER BY ordering";
        $database->setQuery($query);
        $books = $database->loadObjectList();

        $currentcat = NULL;

        // Parameters
        //$menu = new mosMenu( $database );
        //$menu = new JTableMenu( $database ); // for 1.6 - JTableMenu
//      $menu->load( $Itemid );
//      $params = new mosParameters( $menu->params );

        if (version_compare(JVERSION, '3.0', 'lt')) {
            $menu = new JTableMenu($database); // for 1.6
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        } else {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        }

        $menu_name = set_header_name_bl($menu, $Itemid);

        $params->def('header', _BOOKLIBRARY_DESC_TITLE);
        $params->def('header', $menu_name); // for 1.6
        $params->def('pageclass_sfx', '');
        $params->def('show_lendstatus', 1);
        $params->def('show_lendrequest', 1);
        $params->def('lend_save', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));

        // page description
        $currentcat = new stdClass();
        $currentcat->descrip = _BOOKLIBRARY_DESC_LEND;

        // page image
        $currentcat->img = $mosConfig_live_site . '/components/com_booklibrary/images/book.png';
        $currentcat->align = 'right';

        $currentcat->header = '';
        if (@$currentcat->name <> '') {
            $currentcat->header = $currentcat->name;
        } else {
            $currentcat->header = $params->get('header');
        }

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        $sort_arr['flag'] = 0;


        HTML_booklibrary :: showLendRequest($books, $currentcat, $params, $tabclass, $catid, $sub_categories, false, $sort_arr);
    }

    /**
     * comments for registered users
     */
    function reviewBook($options, $catid) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $mainframe, $database, $my, $Itemid, $acl;
        global $booklibrary_configuration, $mosConfig_absolute_path;/* , $catid */
        global $mosConfig_mailfrom, $session;


        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }

        if (!($GLOBALS['reviews_show']) ||
                !checkAccessBL($GLOBALS['reviews_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
            echo _BOOKLIBRARY_NOT_AUTHORIZED;
            return;
        }


        $review = new mosBookLibrary_review($database);


        $review->date = date("Y-m-d H:i:s");
        $review->fk_userid = $my->id;


//*********************   begin compare to key   ***************************
        
//**********************   end compare to key   *****************************
//**********************   BEGIN review approve   ***************************        
        if ($booklibrary_configuration['approve_review']['show'] == '1') {
            $review->published = 1;
        } else {
            $review->published = 0;
        }

        if ($booklibrary_configuration['approve_review']['show']) {
            if (checkAccessBL($booklibrary_configuration['approve_review']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $review->published = 1;
            } else
                $review->published = 0;
        } else
            $review->published = 0;
//**********************   END review approve   ***************************

        if (!$review->bind($_POST)) {
            echo "<script> alert('" . addslashes($book->getError()) . "'); window.history.go(-1); </script>\n";
            exit();
        }

        if (!$review->check()) {
            echo "<script> alert('" . addslashes($book->getError()) . "'); window.history.go(-1); </script>\n";
            exit();
        }
        if (!$review->store()) {
            echo "<script> alert('" . addslashes($book->getError()) . "'); window.history.go(-1); </script>\n";
            exit();
        }



        $review->updateRatingBook();

       //***************   begin add send mail for admin   ******************
        if (version_compare(JVERSION, '3.0', 'lt')) {
            $menu = new JTableMenu($database); // for 1.6
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        } else {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        }

        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }

        if (($GLOBALS['review_added_email_show']) && trim($GLOBALS['review_email_address']) != "") {
            $params->def('show_email', 1);
            if (checkAccessBL($GLOBALS['review_added_email_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_email', 1);
            }
        }
        if ($params->get('show_input_email')) {       
            $mail_to = explode(",", $GLOBALS['review_email_address']);
            
           // select book title
            $zapros = "SELECT title FROM #__booklibrary WHERE id = '" . intval($_POST['fk_bookid']) . "';";
            $database->setQuery($zapros);
            $book_title = $database->loadObjectList();
            echo $database->getErrorMsg();
            $userid = $my->id;
            //select new review
            $zapros = "SELECT * FROM #__booklibrary_review WHERE date = '" . $review->date . "';";
            $database->setQuery($zapros);
            $item_review = $database->loadObjectList();
            echo $database->getErrorMsg();
            $zapros = "SELECT name, email FROM #__users WHERE id=" . $userid . ";";
            $database->setQuery($zapros);
            $item_user = $database->loadObjectList();
            echo $database->getErrorMsg();
            $rating = (($item_review[0]->rating) / 2);
            $query = "SELECT * FROM #__booklibrary WHERE id='" . $_REQUEST['fk_bookid'] . "'";
            $database->setQuery($query);
            $book_name = $database->loadAssoc();
            
            $username = (isset($item_user[0]->name)) ? $item_user[0]->name : "anonymous";
            $message = _BOOKLIBRARY_EMAIL_NOTIFICATION_REVIEW;
            $message = str_replace("{username}", $username, $message);
            $message = str_replace("{book_title}", $book_name['title'], $message);
            $message = str_replace("{label title comment}", _BOOKLIBRARY_LABEL_TITLE_COMMENT, $message);
            $message = str_replace("{title}", $_REQUEST['title'], $message);
            $message = str_replace("{label rating}", _BOOKLIBRARY_LABEL_RATING, $message);
            $message = str_replace("{rating}", $_REQUEST['rating'], $message);
            $message = str_replace("{label label title review comment}", _BOOKLIBRARY_LABEL_TITLE_REVIEW_COMMENT, $message);
            $message = str_replace("{comment}", $_REQUEST['comment'], $message);
          
            if ($userid == 0) {
               mosMail($mosConfig_mailfrom, 'anonymous', $mail_to, 'New book review added', $message, true);
            } else{
               mosMail($mosConfig_mailfrom, $item_user[0]->name, $mail_to, 'New book review added', $message, true);
            }
        }
         mosRedirect("index.php?option=com_booklibrary&task=view&catid=".$catid."&id=$review->fk_bookid&Itemid=$Itemid");
      }
        //********************   end add send mail for admin ************
//*******************   end add for suggestion   ********************
//this function check - is exist books in this folder and folders under this category 
    static function is_exist_curr_and_subcategory_books($catid) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $my;

        $query = "SELECT cc.id FROM #__booklibrary_main_categories AS cc
                           \n LEFT JOIN #__booklibrary_categories as ac ON cc.id = ac.catid"
                . "\n LEFT JOIN #__booklibrary AS a ON a.id = ac.bookid"
                . "\n WHERE a.published='1' AND a.approved='1' AND section='com_booklibrary' AND cc.id='$catid' AND cc.published='1' "
                . "\n GROUP BY cc.id";
        $database->setQuery($query);
        $categories = $database->loadObjectList();
        if (count($categories) != 0)
            return true;

        $query = "SELECT id "
                . "FROM #__booklibrary_main_categories AS cc "
                . " WHERE section='com_booklibrary' AND parent_id='$catid' AND published='1' ";
        $database->setQuery($query);
        $categories = $database->loadObjectList();

        if (count($categories) == 0)
            return false;

        foreach ($categories as $k) {
            if (PHP_booklibrary::is_exist_curr_and_subcategory_books($k->id))
                return true;
        }
        return false;
    }

//end function
//*****************************************************************************
//this function check - is exist folders under this category 
    static function is_exist_subcategory_books($catid) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $my;

        $query = "SELECT *, COUNT(a.id) AS numlinks FROM #__booklibrary_main_categories AS cc,#__booklibrary_categories AS bc"
                . "\n LEFT JOIN #__booklibrary AS a ON a.id = bc.bookid"
                . "\n WHERE bc.catid=cc.id AND a.published='1' AND a.approved='1' AND section='com_booklibrary' AND parent_id='$catid' AND cc.published='1'"
                . "\n GROUP BY cc.id"
                . "\n ORDER BY cc.ordering";
        $database->setQuery($query);
        $categories = $database->loadObjectList();
        if (count($categories) != 0)
            return true;

        $query = "SELECT id "
                . "FROM #__booklibrary_main_categories AS cc "
                . " WHERE section='com_booklibrary' AND parent_id='$catid' AND published='1' ";
        $database->setQuery($query);
        $categories = $database->loadObjectList();

        if (count($categories) == 0)
            return false;

        foreach ($categories as $k) {
            if (PHP_booklibrary::is_exist_subcategory_books($k->id))
                return true;
        }
        return false;
    }

//end function

    /**
     * This function is used to show a list of all books
     */
    static function listCategories($catid) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $mainframe, $database, $my, $acl;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $booklibrary_configuration;

        //PHP_booklibrary::addTitleAndMetaTags();

        $s = getWhereUsergroupsString("c");

        //-----------------------
        $lang = JFactory::getLanguage();
        $lang1 = $lang->getName();
        //-----------------------
        //echo "<br /><pre>" . print_r($lang1, true) . "</pre>" ; exit ;

        $query = "SELECT c.id,c.parent_id, c.language, COUNT(bc.bookid) AS books, c.title, c.image, '1' AS display" .
                " FROM  #__booklibrary_main_categories as c
        LEFT JOIN #__booklibrary_categories AS bc ON c.id=bc.catid \n
        LEFT JOIN #__booklibrary AS b ON b.id=bc.bookid AND b.published=1 AND b.approved=1
        WHERE  c.section='com_booklibrary'
          AND c.published = 1 AND ({$s}) AND (c.langshow LIKE'" . $lang1 . "' OR c.language='' OR c.language IS NULL OR c.language='*')
        GROUP BY c.id \n
        ORDER BY parent_id DESC, c.ordering ";
        $database->setQuery($query);
        $cat_all = $database->loadObjectList();
        
        foreach ($cat_all as $k1 => $cat_item1) { 
          $cat_all[$k1]->display = PHP_booklibrary::is_exist_curr_and_subcategory_books($cat_all[$k1]->id);        
        }   
        $currentcat = NULL;

        // Parameters
//      $menu = new mosMenu( $database );
//      //$menu = new JTableMenu( $database ); // for 1.6
// 
//      $menu->load( $Itemid );
//      $params = new mosParameters( $menu->params );
        if (version_compare(JVERSION, '3.0', 'lt')) {
            $menu = new JTableMenu($database); // for 1.6
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        } else {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        }

        $params->def('pageclass_sfx', '');
        $params->def('show_search', '1');
        $params->def('back_button', $mainframe->getCfg('back_button'));
//print_r($params);exit;
//print_r($params->def('back_button', $mainframe->getCfg('back_button')));exit;
        // page header
        $currentcat = new stdClass();
        $currentcat->header = '';
        if (@$currentcat->name <> '') {
            $currentcat->header = $currentcat->name;
        } else {
            $currentcat->header = $params->get('header');
        }

//*****   begin add for Manager Suggestion: button 'Suggest a book' 
      
        
//*********   end add for Manager Suggestion: button 'Suggest a book'   **
        //add for show in category picture
        if (($GLOBALS['cat_pic_show']))
            $params->def('show_cat_pic', 1);

        // page description
        $currentcat->descrip = _BOOKLIBRARY_DESC;

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');


        if (checkAccessBL($booklibrary_configuration['search_field']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl) &&
                $booklibrary_configuration['search_field']['show']) {
            $params->def('search_fieldshow', 1);
        }
       

        $params->def('allcategories01', "{loadposition com_booklibrary_all_categories_01}");
        $params->def('allcategories02', "{loadposition com_booklibrary_all_categories_02}");
        $params->def('allcategories03', "{loadposition com_booklibrary_all_categories_03}");
        $params->def('allcategories04', "{loadposition com_booklibrary_all_categories_04}");
        $params->def('allcategories05', "{loadposition com_booklibrary_all_categories_05}");
        $params->def('allcategories06', "{loadposition com_booklibrary_all_categories_06}");
        $params->def('allcategories07', "{loadposition com_booklibrary_all_categories_07}");
        $params->def('allcategories08', "{loadposition com_booklibrary_all_categories_08}");
        $params->def('allcategories09', "{loadposition com_booklibrary_all_categories_09}");
        $params->def('allcategories10', "{loadposition com_booklibrary_all_categories_010}");
        //print_r($cat_all);exit;
        HTML_booklibrary::showCategories($params, $cat_all, $catid, $tabclass, $currentcat);
    }

    static function constructPathway($cat) {

        PHP_booklibrary::addTitleAndMetaTags();
        global $mainframe, $database, $option, $Itemid, $mosConfig_absolute_path;

        $app = JFactory::getApplication();
        $path_way = $app->getPathway();



        $query = "SELECT * FROM #__booklibrary_main_categories WHERE section = 'com_booklibrary' AND published = 1";
        $database->setQuery($query);
        $rows = $database->loadObjectlist('id');
        $pid = $cat->parent_id;
        $pathway = array();
        $pathway_name = array();

        while ($pid != 0) {

            $cat = $rows[$pid];
            $pathway[] = sefRelToAbs('index.php?option=' . $option . '&task=showCategory&catid=' . $cat->id . '&Itemid=' . $Itemid);
            $pathway_name[] = $cat->title;
            $pid = $cat->parent_id;
        }

        $pathway = array_reverse($pathway);
        $pathway_name = array_reverse($pathway_name);

        for ($i = 0, $n = count($pathway); $i < $n; $i++) {

            $path_way->addItem($pathway_name[$i], $pathway[$i]);
        }
    }

    /**
     * This function is used to show a list of all books
     */
    static function showCategory($catid, $printItem, $layout) {
        global $mainframe, $database, $acl, $my;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $booklibrary_configuration, $option, $limit, $total, $limitstart;

        PHP_booklibrary::addTitleAndMetaTags();

        $list_str = array();

        $search = '';
        if (array_key_exists("letindex", $_REQUEST)) {
            $search = mb_substr(mosGetParam($_REQUEST, 'letindex', ''), 0, 1, 'UTF-8');
            $fieldOrdering = JRequest::getVar('field',false);
            if ($fieldOrdering == 'authors' || $fieldOrdering == 'title')$search = '';
        }

        $sp = 0;
        if (array_key_exists("sp", $_REQUEST)) {
            $sp = mosGetParam($_REQUEST, 'sp', 0);
        }
        // Sort start

        if ($booklibrary_configuration['category']['default_sort'] != '')
            $default_field = $booklibrary_configuration['category']['default_sort'];
        else
            $default_field = 'title';

        // SORTING parameters start
        $prefix = '';
        $item_session = JFactory::getSession();

        $item_sort_param = '';
        $sort_arr['direction'] = '';
        if (array_key_exists('sortup', $_GET)) {
            $item_sort_param = mosGetParam($_GET, 'sortup', '');
            $sort_arr['direction'] = 'DESC';
        }

        if (array_key_exists('sortdown', $_GET)) {
            $item_sort_param = mosGetParam($_GET, 'sortdown', '');
            $sort_arr['direction'] = '';
        }

        $sort_arr['field'] = $item_sort_param;


        $item_sort_param = preg_replace('/[^A-Za-z0-9_]*/', '', $item_sort_param);

        if ($item_sort_param == '') {
            if (is_array($sort_arr = $item_session->get('bl_fe_booksort', ''))) {
                $sort_string = $sort_arr['field'] . " " . $sort_arr['direction'];
            } else {
                $sort_string = $default_field;
                $sort_arr = array();
                $sort_arr['field'] = $default_field;
                $sort_arr['direction'] = '';
                $item_session->set('bl_fe_booksort', $sort_arr);
            }
        } else {
            if ($item_sort_param != $sort_arr['field']) {
                $sort_arr['field'] = $item_sort_param;
                $sort_arr['direction'] = '';
            }
            if ($sort_arr['field'] != 'category')
                $prefix = '';
            $sort_string = $prefix . $sort_arr['field'] . " " . $sort_arr['direction'];
            $item_session->set('bl_fe_booksort', $sort_arr);
        }

        // Sort end
        //sorting item
        $item_session = JFactory::getSession();
        $sort_arr = $item_session->get('bl_fe_booksort', '');
        if (is_array($sort_arr)) {
            $tmp1 = mosGetParam($_POST, 'direction');
            if ($tmp1 != '') {
                $sort_arr['direction'] = $tmp1;
            }
            $tmp1 = mosGetParam($_POST, 'field');
            if ($tmp1 != '') {
                $sort_arr['field'] = $tmp1;
            }
            $item_session->set('bl_fe_booksort', $sort_arr);
        } else {
            $sort_arr = array();
            $sort_arr['field'] = 'title';
            $sort_arr['direction'] = 'asc';
            $item_session->set('bl_fe_booksort', $sort_arr);
        }
        if ($sort_arr['field'] == "price")
            $sort_string = "CAST( " . $sort_arr['field'] . " AS SIGNED)" . " " . $sort_arr['direction'];
        else
            $sort_string = $sort_arr['field'] . " " . $sort_arr['direction'];

//end sorting item
//print_r($search);//exit;
//print_r($_GET);//exit;
        $add_str = "";
        /* if( isset($_GET['sort']) && ($sort_arr['field'] == $_GET['sort']))
          { */
        if ($sp == 1) {
            if ($sort_arr['field'] == 'title')
                $add_str = " AND (LOWER(bl.title) LIKE '$search%' )";
            elseif ($sort_arr['field'] == 'authors')
                $add_str = " AND (LOWER(bl.authors) LIKE '$search%' )";
        }
        //}
        $s = getWhereUsergroupsString("c");

        //PAGINATOR
        $query = "SELECT COUNT(DISTINCT bl.id)
      \nFROM #__booklibrary AS bl"
                . "\nLEFT JOIN #__booklibrary_categories AS bc ON bc.bookid=bl.id"
                . "\nLEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid"
                . "\nWHERE c.id = '$catid' AND bl.published='1' AND bl.approved='1'
        AND c.published='1' $add_str
        AND ({$s})";
        $database->setQuery($query);
        $total = $database->loadResult();

        $lang = JFactory::getLanguage();
        $lang1 = $lang->getLocale();

        $current = $lang->getName();

        $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
        // getting books for this category
        $query = "SELECT bl.id, bl.isbn, bl.authors, bl.rating,blr2.rating2, bl.title, bl.fk_lendid, bl.date, bl.hits, bl.URL,bl.imageURL, bl.price, bl.priceunit, bl.language" .
                "\nFROM #__booklibrary AS bl " .
                "\nLEFT JOIN #__booklibrary_categories AS bc ON bl.id=bc.bookid" .
                "\nLEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid" .
                "\nLEFT JOIN ( SELECT ROUND(avg(blr1.rating)) as rating2, fk_bookid   
                FROM #__booklibrary as b  LEFT JOIN #__booklibrary_review as blr1 on   blr1.fk_bookid = b.id group by blr1.fk_bookid ) blr2 
                  ON  blr2.fk_bookid = bl.id " .
                "\nWHERE c.id = '$catid' AND bl.published='1' AND (bl.langshow LIKE '" . $current . "' OR bl.langshow='*')  AND bl.approved='1'" . $add_str .
                " AND ({$s}) AND c.published='1' " .
                "\nORDER BY $sort_string" .
                "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
        //print_r($query);exit;
        $database->setQuery($query);
        $books = $database->loadObjectList();

        //echo "<br/><pre>". print_r($asd, true)."</pre>";
        //echo "<br/><pre>". print_r($asd2, true)."</pre>";
        //echo "<br/><pre>". print_r($books, true)."</pre>";
        //echo "<br/><pre>". print_r($lang1, true)."</pre>";exit;
        //getting the current category informations
        $query = "SELECT * FROM #__booklibrary_main_categories WHERE id='$catid'";
        $database->setQuery($query);
        $category = $database->loadObjectList();
        if (isset($category[0]))
            $category = $category[0];
        else
            return;

        $params2 = unserialize($category->params2);
        //print_r($params2); print_r($params2->alone_category); exit;
        if ($layout == '') {
//       if($params2 != '')
//       {
            if (!$params2 == null && $params2->alone_category != null ) {
                $layout = $params2->alone_category;
            } else
                $layout = $booklibrary_configuration['view_type']; //$layout = "list";
//      if ($params2->alone_category == '') 
//      {
//        if ($booklibrary_configuration['view_type']==0)
//        {
//          $layout="list";
//        } 
//        elseif ($booklibrary_configuration['view_type']==1)
//        {
//          $layout="gallery";
//        }
//        else $layout = "list";
//      }
//      else 
//      {
//          $layout = $params2->alone_category;
//      }
//       }
//       else
//       {
//      $layout = "default";
//       }
        }

        $s = getWhereUsergroupsString("c");

        $query = "SELECT c.id,c.parent_id, c.language, COUNT(bc.bookid) AS books, c.title, c.image, '1' AS display" .
                " FROM  #__booklibrary_main_categories as c
        LEFT JOIN #__booklibrary_categories AS bc ON c.id=bc.catid \n
        LEFT JOIN #__booklibrary AS b ON b.id=bc.bookid AND b.published=1 AND b.approved=1
        WHERE  c.section='com_booklibrary'
          AND c.published = 1 AND ({$s}) AND (c.language LIKE'" . '$lang1' . "' OR c.language='' OR c.language IS NULL OR c.language='*')
        GROUP BY c.id \n
        ORDER BY parent_id DESC, c.ordering ";
        $database->setQuery($query);
        $cat_all = $database->loadObjectList();
        
        foreach ($cat_all as $k1 => $cat_item1) { 
                  
        $query = "SELECT COUNT(bc.bookid) as books " .
            " FROM  #__booklibrary_main_categories as c
            \n LEFT JOIN #__booklibrary_categories AS bc ON c.id=bc.catid \n
            \n LEFT JOIN #__booklibrary AS b ON b.id=bc.bookid 
            \n WHERE  c.section='com_booklibrary'
            \n AND b.published=1 AND b.approved=1 AND ({$s}) AND (c.language LIKE'" . '$lang1' . "' OR c.language='' OR c.language IS NULL OR c.language='*')
            \n AND c.id = " . $cat_all[$k1]->id . "    
            \n GROUP BY c.id
            \n ORDER BY c.parent_id DESC, c.ordering ";              
                
                    $database->setQuery($query);

                    $book_count = $database->loadObjectList();
                    if($book_count)
                        $cat_all[$k1]->books = $book_count[0]->books;
                    else
                        $cat_all[$k1]->books = 0;            
        }
        
        $currentcat = NULL;

        // Parameters
//      $menu = new mosMenu( $database );
//      //$menu = new JTableMenu( $database ); // for 1.6
// 
//      $menu->load( $Itemid );
//      $params = new mosParameters( $menu->params );
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        }

        $params->def('pageclass_sfx', '');
        $params->def('show_search', '1');
        $params->def('back_button', $mainframe->getCfg('back_button'));

        PHP_booklibrary::constructPathway($category);
        $app = JFactory::getApplication();
        $path_way = $app->getPathway();
        $path_way->addItem($category->name, " ");


        //-----------------------   Manager print pdf: button 'print PDF'  ----------------------------------

        if (($GLOBALS['print_pdf_show'])) {
            $params->def('show_print_pdf', 1);
            if (checkAccessBL($GLOBALS['print_pdf_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_print_pdf', 1);
            }
        }



        if (($GLOBALS['print_view_show'])) {
            $params->def('show_print_view', 1);
            if (checkAccessBL($GLOBALS['print_view_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_print_view', 1);
            }
        }



        if (($GLOBALS['mail_to_show'])) {
            $params->def('show_mail_to', 1);
            if (checkAccessBL($GLOBALS['mail_to_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_mail_to', 1);
            }
        }
//---------------------------------   end add for  Manager mail to: button 'mail to'    ******************************

        if (($GLOBALS['lendstatus_show'])) {
            $params->def('show_lendstatus', 1);
            if (checkAccessBL($GLOBALS['lendrequest_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_lendrequest', 1);
            }
        }

//*********   begin add for Manager Suggestion: button 'Suggest a book'   ****
       
//*********   end add for Manager Suggestion: button 'Suggest a book'   ***
        //add for show in category picture
        if (($GLOBALS['cat_pic_show']))
            $params->def('show_cat_pic', 1);

        $params->def('show_rating', 1);

        $params->def('hits', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));


        if ($booklibrary_configuration['addbook_button']['show']) {
            if (array_search($catid, preg_split('/,/', $booklibrary_configuration['addbook_button']['allow']['categories'])) !== false || array_search('-2', preg_split('/,/', $booklibrary_configuration['addbook_button']['allow']['categories'])) !== false) {
                if (checkAccessBL($booklibrary_configuration['addbook_button']['allow']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl))
                    $params->def('show_addbook', 1);
            }
        }
        $currentcat = new stdClass();
        $currentcat->descrip = $category->description;

        // page image
        $currentcat->img = null;
        $path = $mosConfig_live_site . '/images/stories/';
        if ($category->image != null && count($category->image) > 0) {
            $currentcat->img = $path . $category->image;
            $currentcat->align = $category->image_position;
        }

        // page header
        $currentcat->header = '';
        if (@$currentcat->name <> '') {
            $currentcat->header = $currentcat->name;
        } else {
            $currentcat->header = $params->get('header');
        }
        $currentcat->header = ((trim($currentcat->header) != "") ? ($currentcat->header . ": ") : ("")) . $category->title;

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');
        if ($booklibrary_configuration['litpage']['show'] && checkAccessBL($booklibrary_configuration['litpage']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
            if ($sort_arr['field'] == 'title') {
                $query = 'SELECT DISTINCT UPPER(SUBSTRING(b.title, 1,1)) AS symb
            FROM #__booklibrary as b
            LEFT JOIN #__booklibrary_categories AS bc ON b.id=bc.bookid
            LEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid
            WHERE  b.published=\'1\' AND b.approved=\'1\'
                 AND c.published=\'1\' AND c.id=\'' . $catid . '\' AND (' . $s . ')
                   ORDER BY symb';
            } elseif ($sort_arr['field'] == 'authors') {
                $query = 'SELECT DISTINCT UPPER(SUBSTRING(b.authors, 1,1)) AS symb
                FROM #__booklibrary as b
                LEFT JOIN #__booklibrary_categories AS bc ON b.id=bc.bookid
                LEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid
                WHERE  b.published=\'1\' AND b.approved=\'1\'
                 AND c.published=\'1\' AND c.id=\'' . $catid . '\' AND (' . $s . ')
                 ORDER BY symb';
            } elseif ($sort_arr['field'] == 'rating') {
                $query = 'SELECT DISTINCT UPPER(SUBSTRING(b.rating, 1,1)) AS symb
                FROM #__booklibrary as b
                LEFT JOIN #__booklibrary_categories AS bc ON b.id=bc.bookid
                LEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid
                WHERE  b.published=\'1\' AND b.approved=\'1\'
                 AND c.published=\'1\' AND c.id=\'' . $catid . '\' AND (' . $s . ')
                 ORDER BY symb';
            } elseif ($sort_arr['field'] == 'hits') {
                $query = 'SELECT DISTINCT UPPER(SUBSTRING(b.hits, 1,1)) AS symb
                FROM #__booklibrary as b
                LEFT JOIN #__booklibrary_categories AS bc ON b.id=bc.bookid
                LEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid
                WHERE  b.published=\'1\' AND b.approved=\'1\'
                 AND c.published=\'1\' AND c.id=\'' . $catid . '\' AND (' . $s . ')
                 ORDER BY symb';
            }//print_r($sort_arr);
            $database->setQuery($query);
            $tmp_arr = $database->loadObjectList();
            $symb_list_str = '<ul>';
            if (count($tmp_arr) > 0) {
                foreach ($tmp_arr as $symbol) {
                    $symb_list_str .= '<li>' .
                            '<a href="index.php?option=' . $option .
                            '&task=showCategory&catid=' . $catid .
                            '&letindex=' . $symbol->symb . '&sp=1&sort=' . $sort_arr['field'] . '&Itemid=' . $Itemid .
                            '">' . $symbol->symb . '</a></li>';
                }
                $symb_list_str.="</ul>";
                $list_str['symbol_list'] = $symb_list_str;
            }
        }

        if (checkAccessBL($booklibrary_configuration['search_field']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl) &&
                $booklibrary_configuration['search_field']['show']) {
            $params->def('search_fieldshow', 1);
        }
        

        $params->def('view_type', $booklibrary_configuration['view_type']);
        $params->def('sort_arr_direction', $sort_arr['direction']);
        $params->def('sort_arr_field', $sort_arr['field']);

//-----------------------------------------------------------
        $params->def('singlecategory01', "{loadposition com_booklibrary_single_category_01}");
        $params->def('singlecategory02', "{loadposition com_booklibrary_single_category_02}");
        $params->def('singlecategory03', "{loadposition com_booklibrary_single_category_03}");
        $params->def('singlecategory04', "{loadposition com_booklibrary_single_category_04}");
        $params->def('singlecategory05', "{loadposition com_booklibrary_single_category_05}");
        $params->def('singlecategory06', "{loadposition com_booklibrary_single_category_06}");
        $params->def('singlecategory07', "{loadposition com_booklibrary_single_category_07}");
        $params->def('singlecategory08', "{loadposition com_booklibrary_single_category_08}");
        $params->def('singlecategory09', "{loadposition com_booklibrary_single_category_09}");
        $params->def('singlecategory10', "{loadposition com_booklibrary_single_category_010}");
        $params->def('singlecategory11', "{loadposition com_booklibrary_single_category_011}");

        switch ($printItem) {
 
            default: HTML_booklibrary::displayBooks($books, $currentcat, $params, $tabclass, $catid, $cat_all, PHP_booklibrary::is_exist_subcategory_books($catid), $sort_arr, $list_str, $pageNav, $layout);
                break;
        }
    }

    static function showItemBL($id, $catid, $printItem/* , $layout */) {
        global $mainframe, $database, $my, $acl, $option;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $booklibrary_configuration; //print_r($printItem);exit;
        //for 1.6  
        $mosConfig_live_site = JURI::root(true);
        $doc = JFactory::getDocument();
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_booklibrary/includes/custom.css');
        $path_way = $mainframe->getPathway(); // variant 2
        // --
        PHP_booklibrary::addTitleAndMetaTags();

        if (!empty($catid)) {
            $query = "SELECT * FROM #__booklibrary_main_categories WHERE id='$catid'";

            $database->setQuery($query);
            $category = $database->loadObjectList();

            $category = $category[0];

            PHP_booklibrary::constructPathway($category);

            $path_way->addItem($category->title, sefRelToAbs('index.php?option=' . $option . '&task=showCategory&catid=' . $catid . '&Itemid=' . $Itemid)); // for 1.6
        }
        //Record the hit
        $sql2 = "UPDATE #__booklibrary SET featured_clicks = featured_clicks - 1 WHERE featured_clicks > 0 and id = " . $id . "";
        $database->setQuery($sql2);
        $database->query();

        $sql = "UPDATE #__booklibrary SET hits = hits + 1 WHERE id = " . $id . "";
        $database->setQuery($sql);
        $database->query();

        $sql3 = "UPDATE #__booklibrary SET featured_shows = featured_shows - 1 WHERE featured_shows > 0";
        $database->setQuery($sql3);
        $database->query();
        //load the book
        $book = new mosBookLibrary($database);
        $book->load($id);
        //check access to book
        $access = $book->getAccessBook();
        //print_r($access);exit;
        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }
        $query = "SELECT * FROM #__booklibrary_main_categories WHERE id='$catid'";

        $database->setQuery($query);
        $category = $database->loadObjectList();

        if (isset($category[0]))
            $category = $category[0];
        else {
            echo _BOOKLIBRARY_ERROR_ACCESS_PAGE;
            return;
        }
        $path_way->addItem($book->title, '  '); // for 1.6
        //end check access to book
        $session = JFactory::getSession();
        $session->get("obj_book", $book);

        // Parameters
//      $menu = new mosMenu( $database );
//      //$menu = new JTableMenu( $database ); // for 1.6
//     
//      $menu->load( $Itemid );
//      $params = new mosParameters( $menu->params );

        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }

        $menu_name = set_header_name_bl($menu, $Itemid);
        $params->def('header', $menu_name); //for 1.6
        $params->def('pageclass_sfx', '');

        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }

        if (($GLOBALS['lendstatus_show'])) {
            $params->def('show_lendstatus', 1);
            if (checkAccessBL($GLOBALS['lendrequest_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_lendrequest', 1);
            }
        }

        if (($GLOBALS['reviews_show'])) {
            $params->def('show_reviews', 1);
            if (checkAccessBL($GLOBALS['reviews_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_inputreviews', 1);
            }
        }

        if (($GLOBALS['ebooks_show'])) {
            $params->def('show_ebookstatus', 1);
            if (checkAccessBL($GLOBALS['ebooks_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_ebooksrequest', 1);
            }
        }

        if (($GLOBALS['price_show'])) {
            $params->def('show_pricestatus', 1);
            if (checkAccessBL($GLOBALS['price_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_pricerequest', 1);
            }
        }


        //------------------------------------- begin add for  Manager : buttons    ******************************
        if (($GLOBALS['print_pdf_show'])) {
            $params->def('show_print_pdf', 1);
            if (checkAccessBL($GLOBALS['print_pdf_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_print_pdf', 1);
            }
        }

        if (($GLOBALS['print_view_show'])) {
            $params->def('show_print_view', 1);
            if (checkAccessBL($GLOBALS['print_view_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_print_view', 1);
            }
        }


        if (($GLOBALS['mail_to_show'])) {
            $params->def('show_mail_to', 1);
            if (checkAccessBL($GLOBALS['mail_to_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_input_mail_to', 1);
            }
        }
//------------------------------------------   end add for  Manager : buttons     ******************************
//************   begin add button 'buy now'   ***************************
        if (($GLOBALS['buy_now_show'])) {
            $params->def('show_buy_now', 1);
            $s = explode(',', $GLOBALS['buy_now_allow_categories']);
            foreach ($s as $i) {
                if ($i == $catid || $i == -2) {
                    $params->def('show_input_buy_now', 1);
                    break;
                }
            }
        }
//************   end add button 'buy now'   ********************************
//************   begin add button 'buy now vm'   ***************************



//************   end add button 'buy now vm'   ********************************


        $params->def('pageclass_sfx', '');
        $params->def('item_description', 1);
        $params->def('lend_request', $GLOBALS['lendrequest_registrationlevel']);
        $params->def('show_ebook', $GLOBALS['ebooks_show']);
        $params->def('show_price', $GLOBALS['price_show']);
        $params->def('back_button', $mainframe->getCfg('back_button'));

        // page header
        $currentcat = new stdClass();
        $currentcat->header = '';
        if (@$currentcat->name <> '') {
            $currentcat->header = $currentcat->name;
        } else {
            $currentcat->header = $params->get('header');
        }
        $currentcat->header = $currentcat->header . (($currentcat->header != '') ? ": " : '') . $book->title;

        //get language List
        $retVal1 = mosBooklibraryOthers :: getLanguageArray();
        $book_lang = null;
        for ($i = 0, $n = count($retVal1); $i < $n; $i++) {
            $help = $retVal1[$i];
            if ($book->language == $help[0]) {
                $book_lang = $help[1];
                break;
            }
        }

        if ($book->rating == 0) {
            $database->setQuery("SELECT ROUND(avg(rating) ) FROM #__booklibrary_review WHERE fk_bookid = " . $book->id . " group by  fk_bookid  ");
            $book->rating = $database->loadResult();
            if ($book->rating == null)
                $book->rating = 0;
        }

        $database->setQuery("SELECT username FROM #__users WHERE email='$book->owneremail'");
        $book->ownername = $database->loadResult();

        // show the book
        // for 1.6
        // get a category list which the book is connected to
        $s = getWhereUsergroupsString("mc");  // get user acces level

        $query_c = "SELECT * FROM #__booklibrary_categories AS bc
                LEFT JOIN  #__booklibrary_main_categories  AS mc
                ON bc.catid=mc.id AND ($s)
                WHERE bc.bookid=" . $book->id;

        $database->setQuery($query_c);
        $categories = $database->loadObjectList();
        // --  
        //---------------------------------------------------------------- 

        $params->def('view01', "{loadposition com_booklibrary_view_book_01}");
        $params->def('view02', "{loadposition com_booklibrary_view_book_02}");
        $params->def('view03', "{loadposition com_booklibrary_view_book_03}");
        $params->def('viewdescription', "{loadposition com_booklibrary_view_book_description}");
        $params->def('view04', "{loadposition com_booklibrary_view_book_04}");
        $params->def('view05', "{loadposition com_booklibrary_view_book_05}");
        $params->def('view06', "{loadposition com_booklibrary_view_book_06}");
        $params->def('view07', "{loadposition com_booklibrary_view_book_07}");

//   if ($layout == ''){
//       $params2 = unserialize($category->params2);
//       $layout = $params2->view_book;
//   }
//   if ($layout == '') 
//       $layout = 'default';
//   $params2 = unserialize($category->params2);
//       
//   if($layout == null)
//   {
//     $layout = "default"; 
//   }
//   else
//   {
//     if($params2 == null)
//     {
//       $layout = "default";
//     }
//     else
//     {
//       $layout = $params2->view_book;
//     }
//   }

        $query = "SELECT * FROM #__booklibrary_main_categories WHERE id=" . $catid;
        $database->setQuery($query);
        $catid = $database->loadObjectList();
        //print_r($catid);exit;
        $params2 = unserialize($catid[0]->params2);
        //print_r($params2);exit;
        if (!$params2 == null) {
            //print_r($catid);
            //print_r($a);
            $layout = $params2->view_book;
            //print_r($layout);
            //exit;
        } else
            $layout = "default";

        //--------------------
        //get owner
        if ($book->owner_id != 0 && $booklibrary_configuration['owner']['show'] !='0') {
            $query = "SELECT name, email FROM #__users WHERE id = " . $book->owner_id . "";
            $database->setQuery($query);
            $user_b = $database->loadObject();
            $book->user_b = $user_b;
        }
        //--------------------
         //ebook urls
        $query = "select * " .
                " from  #__booklibrary_files AS s " .
                " where s.fk_book_id=" . $book->id .
                " ORDER BY s.id ";
        $database->setQuery($query);
        $row = $database->loadObjectList();
        //print_r($row);exit;
        $book->ebookURL = $row;
        
        switch ($printItem) {
          
            default: HTML_booklibrary::displayBook($book, $tabclass, $params, $currentcat, $categories, $ratinglist, $book_lang, $id, $catid, $layout);
                break;
        }

//----------------------------------------------------------------------
    }

//*************** begin gevi direct url for VM************************


//************** end gevi direct url for VM ***************************

//**************   begin gevi direct url   *************************

//************   end gevi direct url   ******************************


    static function showMyBooks($option) {
        global $database, $Itemid, $mainframe, $booklibrary_configuration, $my;

        PHP_booklibrary::addTitleAndMetaTags();
        //print_r($_REQUEST);
        //$menu = new JTableMenu($database);
//   $menu = new mosMenu($database);
//   $menu_name = set_header_name_bl($menu, $Itemid);
//   $menu->load( $Itemid );
//   $params = new mosParameters( $menu->params );

        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        }
        $menu_name = set_header_name_bl($menu, $Itemid);

        $database->setQuery("SELECT id FROM #__menu WHERE link='index.php?option=com_booklibrary'");
        if ($database->loadResult() != $Itemid) {
            $params->def('wrongitemid', '1');
        };

        $user = mosGetParam($_REQUEST, 'name');
        if (!isset($user)) {
            $params = $mainframe->getParams();
            $user = $params->get('ownerslists');
        }

        $limit = $booklibrary_configuration['page']['items'];
        $limitstart = mosGetParam($_REQUEST, 'limitstart', 0);
        if (!$params->get('wrongitemid')) {
            $pathway = sefRelToAbs('index.php?option=' . $option .
                    '&amp;task=show_my_books&amp;Itemid=' . $Itemid);
            $params->def('title', _BOOKLIBRARY_LABEL_TITLE_USER_BOOKS);
            $params->def('header', $menu_name);

            $params->def('show_rating', 1);
            $params->def('hits', 1);
            $params->def('back_button', $mainframe->getCfg('back_button'));


            //check user
            if ($my->email == null) {
                mosRedirect("index.php?", "Please login");
                exit;
            }
            //publish books

            if (isset($_REQUEST['submitbutton'])) {
                $do = mosGetParam($_REQUEST, 'submitbutton');
                $bid = mosGetParam($_REQUEST, 'bid');
                if ($do == 'addbook') {
                    if ($option == 'com_comprofiler') {
                        $redirect = "index.php?option=$option&task=show_add&is_show_data=1&tab=getmybooksTab&Itemid=$Itemid";
                    } else {
                        $redirect = "index.php?option=$option&task=show_add&Itemid=$Itemid";
                    }
                    mosRedirect($redirect);

                    exit;
                }//print_r($do);exit;
                //get real user books id

                if (count($bid)) {
                    $database->setQuery("SELECT id FROM #__booklibrary
                            \nWHERE owner_id='$my->id' AND id IN (" . implode(', ', $bid) . ")");

                    if (version_compare(JVERSION, '3.0', 'lt')) {
                        $bid = $database->loadResultArray();
                    } else {

                        $bid = $database->loadColumn();
                    }
                    //$bid=$database->loadResultArray();
                    if (count($bid)) {
                        $bids = implode(',', $bid);
                        switch ($do) {
                            case 'publish':

                                $database->setQuery("UPDATE #__booklibrary SET published = 1
                      \n WHERE owneremail='$my->email' AND id IN (" . $bids . ");");
                                $database->query();
                                break;
                            case 'unpublish':

                                $database->setQuery("UPDATE #__booklibrary SET published = 0
                      \n WHERE owneremail='$my->email' AND id IN (" . $bids . ");");
                                $database->query();
                                break;

                            case 'delete':

                                $database->setQuery("DELETE FROM #__booklibrary_review WHERE fk_bookid IN ($bids)");
                                if (!$database->query()) {
                                    echo "<script> alert('" . addslashes($database->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
                                }
                                $database->setQuery("DELETE FROM #__booklibrary_categories WHERE bookid IN ($bids)");
                                if (!$database->query()) {
                                    echo "<script> alert('" . addslashes($database->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
                                }
                                $database->setQuery("DELETE FROM #__booklibrary WHERE id IN ($bids)");
                                if (!$database->query()) {
                                    echo "<script> alert('" . addslashes($database->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
                                }
                                break;
                        }
                    }
                }
            }

            $database->setQuery("SELECT COUNT(id) FROM #__booklibrary
                            \nWHERE owneremail='$my->email'");
            $total = $database->loadResult();
            $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
            //getting my cars
            $selectstring = "SELECT a.*, GROUP_CONCAT(cc.title SEPARATOR ', ') AS category,
                l.id as lendid, l.lend_from as lend_from, l.lend_return as
                lend_return,
                l.lend_until as lend_until, u.name AS editor,
                l.user_name as user_name, l.user_email as user_email, l.user_mailing as user_mailing
                FROM #__booklibrary AS a " .
                    "\nLEFT JOIN #__booklibrary_categories AS vc ON vc.bookid = a.id " .
                    "\nLEFT JOIN #__booklibrary_main_categories AS cc ON cc.id = vc.catid " .
                    "  LEFT JOIN #__booklibrary_lend AS l ON a.fk_lendid = l.id
                LEFT JOIN #__users AS u ON u.id = a.checked_out                 
                 WHERE owneremail='$my->email' " .
                    "\nGROUP BY a.id" .
                    "\nORDER BY a.title " .
                    "\nLIMIT $pageNav->limitstart,$pageNav->limit;";

            $database->setQuery($selectstring);
            $books = $database->loadObjectList();
//print_r($books);exit;


            $database->setQuery("SELECT id,bookid FROM #__booklibrary
                            \nWHERE owneremail='$my->email'
                            \nLIMIT $pageNav->limitstart,$pageNav->limit;");
            //$results=$database->loadResultArray();

            if (version_compare(JVERSION, '3.0', 'lt')) {
                $results = $database->loadResultArray();
            } else {
                $results = $database->loadColumn();
            }

            $params->def('my01', "{loadposition com_booklibrary_my_book_01}");
            $params->def('my02', "{loadposition com_booklibrary_my_book_02}");
            $params->def('my03', "{loadposition com_booklibrary_my_book_03}");
            $params->def('my04', "{loadposition com_booklibrary_my_book_04}");
            $params->def('my05', "{loadposition com_booklibrary_my_book_05}");

            $tab = JRequest::getVar('tab','','get');
            
     /*       if($tab === 'showmybooks') {
                $currentcat = new stdClass();
                $currentcat->header = $params->get('header');
                $currentcat->img = null;
                for($i = 0;$i < count($books); $i++) {
                    $books[$i]->rating2 = 0;
                }
                $pageNav = null;
                $layout = $booklibrary_configuration['view_type'];
                HTML_booklibrary::displayBooks($books, $currentcat, $params, 0, 0, null, false, array('field' =>'id'),array(),$pageNav,$layout);
            }  
            else */
          HTML_booklibrary :: showMyBooks($books, $params, $pageNav);
        }
    }

    static function showSearchBooks($options, $catid, $option) {

        global $mainframe, $database, $my;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid;

        PHP_booklibrary::addTitleAndMetaTags();

        $currentcat = NULL;
        // Parameters
//     $menu = new mosMenu( $database );
//     //$menu = new JTableMenu( $database );//for 1.6
//      $menu->load( $Itemid );
//      $params = new mosParameters( $menu->params );
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        }

        $menu_name = set_header_name_bl($menu, $Itemid);

        $params->def('header', $menu_name); //for 1.6

        $params->def('pageclass_sfx', '');
        //
        $params->def('show_search', '1');
        $params->def('back_button', $mainframe->getCfg('back_button'));

        $currentcat = new stdClass();
        $currentcat->descrip = _BOOKLIBRARY_SEARCH_DESC1;
        $currentcat->align = 'right';

        // page image
        $currentcat->img = $mosConfig_live_site . "/components/com_booklibrary/images/book.png";

        $currentcat->header = '';
        if (@$currentcat->name <> '') {
            $currentcat->header = $currentcat->name;
        } else {
            $currentcat->header = $params->get('header');
        }
        $currentcat->header = $currentcat->header . (($currentcat->header != '') ? ": " : '') . _BOOKLIBRARY_LABEL_SEARCH;

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        $categories[] = mosHTML :: makeOption('0', _BOOKLIBRARY_SEARCH_CATEGORY);

        if (count($categories) < 1) {
            mosRedirect("index.php?option=categories&section=$option&Itemid=$Itemid&err_msg=You must first create category for that section.");
        }
        $clist = PHP_booklibrary::categoryParentList(0, '', true, $categories);

        // show thte book 
        $params->def('showsearch01', "{loadposition com_booklibrary_show_search_01}");
        $params->def('showsearch02', "{loadposition com_booklibrary_show_search_02}");
        $params->def('showsearch03', "{loadposition com_booklibrary_show_search_03}");
        $params->def('showsearch04', "{loadposition com_booklibrary_show_search_04}");
        $params->def('showsearch05', "{loadposition com_booklibrary_show_search_05}");
        HTML_booklibrary::showSearchBooks($params, $currentcat, $clist, $option);
    }

    static function searchBooks($options, $catid, $option, $ownername = '') {

        global $mainframe, $database, $my, $acl, $task;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $booklibrary_configuration, $limitstart, $limit;

        PHP_booklibrary::addTitleAndMetaTags();

        if (array_key_exists('Itemid', $_GET))
            $Itemid = intval($_GET['Itemid']);

        $list_str = array();
        $session = JFactory::getSession();

//   if ($layout == "" && $booklibrary_configuration['view_type']==0){
//     $layout="list";
//   } else if ($layout == "" && $booklibrary_configuration['view_type']==1){
//     $layout="gallery";
//   }
        //sorting item
        $item_session = JFactory::getSession();
        $sort_arr = $item_session->get('bl_fe_booksort', '');
        if (is_array($sort_arr)) {
            $tmp1 = mosGetParam($_POST, 'direction');
            if ($tmp1 != '') {
                $sort_arr['direction'] = $tmp1;
            }
            $tmp1 = mosGetParam($_POST, 'field');
            if ($tmp1 != '') {
                $sort_arr['field'] = $tmp1;
            }
            $item_session->set('bl_fe_booksort', $sort_arr);
        } else {
            $sort_arr = array();
            $sort_arr['field'] = 'title';
            $sort_arr['direction'] = 'asc';
            $item_session->set('bl_fe_booksort', $sort_arr);
        }
        if ($sort_arr['field'] == "price")
            $sort_string = "CAST( " . $sort_arr['field'] . " AS SIGNED)" . " " . $sort_arr['direction'];
        else
            $sort_string = $sort_arr['field'] . " " . $sort_arr['direction'];

//end sorting item
        //get current user groups
        $s = getWhereUsergroupsString("c");
        $session = JFactory::getSession();
        if ($ownername == '') {

            $pathway = sefRelToAbs('index.php?option=' . $option .
                    '&amp;task=show_search&amp;Itemid=' . $Itemid);
            $pathway_name = _BOOKLIBRARY_LABEL_SEARCH;
        }
        $search = "";
        if (array_key_exists("searchtext", $_REQUEST)) {
            $search = urldecode(mosGetParam($_REQUEST, 'searchtext', ''));
            $search = addslashes($search);
        }

        $li_search = '';
        if (array_key_exists("letindex", $_REQUEST)) {
            $li_search = mb_substr(mosGetParam($_REQUEST, 'letindex', ''), 0, 1, 'UTF-8');
        }

        $sp = 0;
        if (array_key_exists("sp", $_REQUEST)) {
            $sp = mosGetParam($_REQUEST, 'sp', 0);
        }

        $where = array();

        // Sort start

        if ($booklibrary_configuration['category']['default_sort'] != '')
            $default_field = $booklibrary_configuration['category']['default_sort'];
        else
            $default_field = 'title';

        // SORTING parameters start

        $prefix = '';
        $item_session = JFactory::getSession();

        $item_sort_param = '';
        $sort_arr['direction'] = '';
        if (array_key_exists('sortup', $_GET)) {
            $item_sort_param = mosGetParam($_GET, 'sortup', '');
            $sort_arr['direction'] = 'DESC';
        }

        if (array_key_exists('sortdown', $_GET)) {
            $item_sort_param = mosGetParam($_GET, 'sortdown', '');
            $sort_arr['direction'] = '';
        }

        $sort_arr['field'] = $item_sort_param;

        $item_sort_param = preg_replace('/[^A-Za-z0-9_]*/', '', $item_sort_param);

        if ($item_sort_param == '') {
            if (is_array($sort_arr = $item_session->get('bl_fe_booksort', ''))) {
                $sort_string = $sort_arr['field'] . " " . $sort_arr['direction'];
            } else {
                $sort_string = $default_field;
                $sort_arr = array();
                $sort_arr['field'] = $default_field;
                $sort_arr['direction'] = '';
                $item_session->set('bl_fe_booksort', $sort_arr);
            }
        } else {
            if ($item_sort_param != $sort_arr['field']) {
                $sort_arr['field'] = $item_sort_param;
                $sort_arr['direction'] = '';
            }
            if ($sort_arr['field'] != 'category')
                $prefix = 'b.';
            $sort_string = $prefix . $sort_arr['field'] . " " . $sort_arr['direction'];
            $item_session->set('bl_fe_booksort', $sort_arr);
        }
        // end sort
        //--------------------------------
        //********owners search
        if (isset($_REQUEST['ownername']) && $_REQUEST['ownername'] == "on") {

            $ownername = "$exactly";
        }


        if ($ownername != '' && $ownername != '%%') {
            $query = "SELECT u.email
            \n FROM #__users AS u
            \n WHERE LOWER(u.name) LIKE '$ownername';";
            $database->setQuery($query);

//     if(version_compare(JVERSION, '3.0', 'ge')) {
//         $owneremails=$database->loadColumn();
//     } else {
//         $owneremails=$database->loadResultArray();
//     }

            if (version_compare(JVERSION, '3.0', 'lt')) {
                $owneremails = $database->loadResultArray();
            } else {
                $owneremails = $database->loadColumn();
            }


            $ownername = ""; //print_r($owneremails);exit;
            if (count($owneremails)) {
                foreach ($owneremails as $owneremail) {
                    if (isset($_REQUEST['ownername']) && $_REQUEST['ownername'] == "on") {
                        //search from frontend

                        if ($is_add_or)
                            $ownername .= " or ";
                        $is_add_or = true;
                        $ownername .= "b.owneremail='$owneremail'";
                    }
                    else {
                        //show owner books
                        $where[] = "b.owneremail='$owneremail'";
                    }
                }
            }
        }
        //---------------------------------

        $add_query = '';
        if ($sp == 1)
            $add_query = " AND LOWER(b." . $sort_arr['field'] . ") LIKE '$li_search%' ";


        $comp_search = array();

        if (array_key_exists('searchtype', $_REQUEST) && $_REQUEST['searchtype'] == 'simplesearch') {
            $comp_search[] = "LOWER(b.authors) LIKE '%$search%'";
            $comp_search[] = "LOWER(b.isbn) LIKE '%$search%'";
            $comp_search[] = "LOWER(b.title) LIKE '%$search%'";
            $comp_search[] = "LOWER(b.manufacturer) LIKE '%$search%'";
            $comp_search[] = "LOWER(b.comment) LIKE '%$search%'";
            $comp_search[] = "LOWER(b.bookid) LIKE '%$search%'";
        }

        $att_str = '';

        if (isset($_REQUEST['author'])) {
            $comp_search[] = "LOWER(b.authors) LIKE '%$search%'";
            $att_str .= '&author=on';
        }
        if (isset($_REQUEST['bookid'])) {
            $comp_search[] = "LOWER(b.bookid) LIKE '%$search%'";
            $att_str .= '&bookid=on';
        }
        if (isset($_REQUEST['isbn'])) {
            $comp_search[] = "LOWER(b.isbn) LIKE '%$search%'";
            $att_str .= '&isbn=on';
        }
        if (isset($_REQUEST['title'])) {
            $comp_search[] = "LOWER(b.title) LIKE '%$search%'";
            $att_str .= '&title=on';
        }
        if (isset($_REQUEST['publisher'])) {
            $comp_search[] = "LOWER(b.manufacturer) LIKE '%$search%'";
            $att_str .= '&publisher=on';
        }
        if (isset($_REQUEST['description'])) {
            $comp_search[] = "LOWER(b.comment) LIKE '%$search%'";
            $att_str .= '&description=on';
        }
        $pricefrom = mosGetParam($_REQUEST, 'pricefrom', '');
        $priceto = mosGetParam($_REQUEST, 'priceto', '');
        if ($pricefrom != "" && $pricefrom >= 0) {
            $where[] = " (b.price) >= " . intval($pricefrom);
            $att_str .= '&pricefrom=' . intval($pricefrom);
        }
        if ($priceto != "" && $priceto > 0) {
            $where[] = " (b.price) <= " . intval($priceto);
            $att_str .= '&priceto=' . intval($priceto);
        }

        $att_str = '';
        foreach ($_GET as $key => $val) {
            if ($key == 'sortdown' || $key == 'sortup')
                continue;
            $att_str .= '&' . $key . '=' . $val;
        }



        $sort_arr['att_str'] = $att_str;

        if (array_key_exists(0, $comp_search) || array_key_exists(0, $where)) {
            $comp_search_str = implode(' OR ', $comp_search);

            if ($comp_search_str != '') {
                array_push($where, '(' . $comp_search_str . ')');
                array_push($where, "b.published='1'");
                array_push($where, "b.approved='1'");
                array_push($where, "b.archived='0'");
            }
            if ($catid) {
                array_push($where, "c.id='$catid'");
            }
            array_push($where, "c.published='1'");
            array_push($where, "b.published='1'");

            $s = getWhereUsergroupsString("c");  // for 1.6

            array_push($where, "({$s})");

            $query = "SELECT COUNT(DISTINCT b.id)
          FROM #__booklibrary AS b " .
                    "\nLEFT JOIN #__booklibrary_categories AS bc ON bc.bookid = b.id" .
                    "\nLEFT JOIN #__booklibrary_main_categories AS c ON bc.catid = c.id" .
                    ((count($where) ? "\nWHERE " . implode(' AND ', $where) : "")) . $add_query;
            $database->setQuery($query);
            $total = $database->loadResult();
            $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

            $query = "SELECT b.*,blr2.rating2, c.title AS category, c.id AS category_id, c.ordering AS category_ordering FROM #__booklibrary AS b " .
                    "\nLEFT JOIN #__booklibrary_categories AS bc ON bc.bookid = b.id" .
                    "\nLEFT JOIN #__booklibrary_main_categories AS c ON bc.catid = c.id" .
                    "\nLEFT JOIN ( SELECT ROUND(avg(blr1.rating)) as rating2, fk_bookid   
                FROM #__booklibrary as bl  LEFT JOIN #__booklibrary_review as blr1 on blr1.fk_bookid = bl.id group by blr1.fk_bookid ) blr2 
                  ON  blr2.fk_bookid = b.id" .
                    ((count($where) ? "\nWHERE " . implode(' AND ', $where) : "")) . $add_query .
                    "\nGROUP BY b.id" .
                    "\nORDER BY $sort_string" .
                    "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
            $database->setQuery($query);
            $books = $database->loadObjectList();
        }

        $currentcat = NULL;

        // Parameters
//     $menu = new mosMenu( $database );
//     //$menu = new JTableMenu( $database );//for 1.6
//     
// 
//      $menu->load( $Itemid );
//      $params = new mosParameters( $menu->params );
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        }

        $menu_name = set_header_name_bl($menu, $Itemid);

        $params->def('header', $menu_name); //for 1.6
        $params->def('pageclass_sfx', '');
        $params->def('category_name', _BOOKLIBRARY_LABEL_SEARCH);
        $params->def('search_request', '1');
        $params->def('hits', 1);
        $params->def('show_rating', 1);

        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }

        if (($GLOBALS['lendstatus_show'])) {
            $params->def('show_lendstatus', 1);
            if (checkAccessBL($GLOBALS['lendrequest_registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl)) {
                $params->def('show_lendrequest', 1);
            }
        }

//*******   begin add for Manager Suggestion: button 'Suggest a book' *******
       

//****   end add for Manager Suggestion: button 'Suggest a book'   *****
        //add for show in category picture
        if (($GLOBALS['cat_pic_show']))
            $params->def('show_cat_pic', 1);

        $params->def('back_button', $mainframe->getCfg('back_button'));
        $currentcat = new stdClass();
        $currentcat->descrip = _BOOKLIBRARY_SEARCH_DESC2;
        $currentcat->align = 'right';
        // page image
        $currentcat->img = $mosConfig_live_site . "/components/com_booklibrary/images/book.png";

        $currentcat->header = '';
        if (@$currentcat->name <> '') {
            $currentcat->header = $currentcat->name;
        } else {
            $currentcat->header = $params->get('header');
        }

        if ($currentcat->header != '')
            $currentcat->header = $currentcat->header;
        else
            $currentcat->header = _BOOKLIBRARY_LABEL_SEARCH;

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        if (!isset($my->id)) { //for 1.6        
            $my->id = 0;
        }

        if (checkAccessBL($booklibrary_configuration['search_field']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl) &&
                $booklibrary_configuration['search_field']['show']) {
            $params->def('search_fieldshow', 1);
        }
        if (checkAccessBL($booklibrary_configuration['advsearch']['registrationlevel'], 'RECURSE', userGID_BL($my->id), $acl) &&
                $booklibrary_configuration['advsearch']['show']) {
            $params->def('advsearch_show', 1);
        }

        $params->def('view_type', $booklibrary_configuration['view_type']);
        $params->def('sort_arr_direction', $sort_arr['direction']);
        $params->def('sort_arr_field', $sort_arr['field']);

        $params->def('singlecategory01', "{loadposition com_booklibrary_single_category_01}");
        $params->def('singlecategory02', "{loadposition com_booklibrary_single_category_02}");
        $params->def('singlecategory03', "{loadposition com_booklibrary_single_category_03}");
        $params->def('singlecategory04', "{loadposition com_booklibrary_single_category_04}");
        $params->def('singlecategory05', "{loadposition com_booklibrary_single_category_05}");
        $params->def('singlecategory06', "{loadposition com_booklibrary_single_category_06}");
        $params->def('singlecategory07', "{loadposition com_booklibrary_single_category_07}");
        $params->def('singlecategory08', "{loadposition com_booklibrary_single_category_08}");
        $params->def('singlecategory09', "{loadposition com_booklibrary_single_category_09}");
        $params->def('singlecategory10', "{loadposition com_booklibrary_single_category_010}");
        $params->def('singlecategory11', "{loadposition com_booklibrary_single_category_011}");
        //************* choose layout****************
        $layout = $params->get('ownerslist_page', '');
        if (!isset($layout) or $layout == '') {
            $layout = $booklibrary_configuration['view_type'];
        }
        //************* end choose layout****************

        if (count($books)) {
        
        HTML_booklibrary::displayBooks($books, $currentcat, $params, $tabclass, $catid, null, false, $sort_arr, $list_str, $pageNav, $layout);
    } else {
          $option = 'com_booklibrary';
          if (isset($_REQUEST['userId'])) {
            if($my->id == $_REQUEST['userId']){
              if ($booklibrary_configuration['cb_mybook']['show'] == '1'
              && checkAccessBL($booklibrary_configuration['cb_mybook']['registrationlevel'],
              'NORECURSE', userGID_BL($my->id), $acl))
              echo " <span class='books_button'><a class='my_btn my_btn-primary' href='"
              . JRoute::_('index.php?option='.$option.'&task=show_my_books&layout=mybooks&Itemid='.$Itemid) . "'>"
              . _BOOKLIBRARY_LABEL_CBBOOKS_TT . "</a></span>";

          if(($booklibrary_configuration['cb_edit']['show'])=='1'
          && checkAccessBL($booklibrary_configuration['cb_mybook']['registrationlevel'],
          'NORECURSE', userGID_BL($my->id), $acl))
          echo " <span class='books_button'><a class='my_btn my_btn-primary' href='"
          . JRoute::_('index.php?option='.$option.'&task=show_my_books&layout=mybooks&Itemid='.$Itemid) . "'>"
          . _BOOKLIBRARY_LABEL_CBEDIT . "</a></span>";
             }
          }
          else {
            if ($booklibrary_configuration['cb_mybook']['show'] == '1'
              && checkAccessBL($booklibrary_configuration['cb_mybook']['registrationlevel'],
              'NORECURSE', userGID_BL($my->id), $acl))
              echo " <span class='books_button'><a class='my_btn my_btn-primary' href='"
              . JRoute::_('index.php?option='.$option.'&task=show_my_books&layout=mybooks&Itemid='.$Itemid) . "'>"
              . _BOOKLIBRARY_LABEL_CBBOOKS_TT . "</a></span>";

          if(($booklibrary_configuration['cb_edit']['show'])=='1'
          && checkAccessBL($booklibrary_configuration['cb_mybook']['registrationlevel'],
          'NORECURSE', userGID_BL($my->id), $acl))
          echo " <span class='books_button'><a class='my_btn my_btn-primary' href='"
          . JRoute::_('index.php?option='.$option.'&task=show_my_books&layout=mybooks&Itemid='.$Itemid) . "'>"
          . _BOOKLIBRARY_LABEL_CBEDIT . "</a></span>";

          }
    } if(!count($books)){
      echo "<h1><center>" . _BOOKLIBRARY_NOTHING_FOUND . "</center></h1>";
      }
}
  
    static function categoryParentList($id, $action, $is_new, $options = array()) {
        PHP_booklibrary::addTitleAndMetaTags();
        global $database;
        $list = CAT_Utils_booklib::categoryArray();
        $cat = new mainBooklibraryCategories($database); //for 1.6
        $cat->load($id);

        $this_treename = '';
        $childs_ids = Array();
        foreach ($list as $item) {
            if ($item->id == $cat->id || array_key_exists($item->parent_id, $childs_ids))
                $childs_ids[$item->id] = $item->id;
        }

        foreach ($list as $item) {
            if ($this_treename) {
                if ($item->id != $cat->id && strpos($item->treename, $this_treename) === false && array_key_exists($item->id, $childs_ids) === false) {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                }
            } else {
                if ($item->id != $cat->id) {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                } else {
                    $this_treename = "$item->treename/";
                }
            }
        }

        $parent = null;
        $parent = mosHTML::selectList($options, 'catid', 'class="inputbox" size="1"', 'value', 'text', $cat->parent_id);
        return $parent;
    }

    /*
     * function bookLibraryTreeRecurse ()
     * Redefines a standard function to not display '&nbsp;'.
     * for 1.6
     */

    static function bookLibraryTreeRecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1) {
        PHP_booklibrary::addTitleAndMetaTags();
        if (@$children[$id] && $level <= $maxlevel) {
            $parent_id = $id;
            foreach ($children[$id] as $v) {
                $id = $v->id;

                if ($type) {
                    $pre = " "; //'<sup>|_</sup>_';
                    $spacer = '. -- ';
                } else {
                    $pre = "- ";
                    $spacer = ' . -';
                }

                if ($v->parent == 0) {
                    $txt = $v->name;
                } else {
                    $txt = $pre . $v->name;
                }
                $pt = $v->parent;
                $list[$id] = $v;
                $list[$id]->treename = "$indent$txt";
                $list[$id]->children = count(@$children[$id]);
                $list[$id]->all_fields_in_list = count(@$children[$parent_id]);

                $list = PHP_booklibrary::bookLibraryTreeRecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
            }
        }
        return $list;
    }

    function add_to_cart() {
        PHP_booklibrary::addTitleAndMetaTags();
        global $mainframe, $database, $my, $Itemid;
        global $aea_shop_configuration;

        //for amazon cart
        $amazon_cart_item_id = mosGetParam($_REQUEST, 'id', '');
        $amazon_cart_item_quantity = mosGetParam($_REQUEST, 'quantity', '');
        $amazon_cart_item = array('id' => $amazon_cart_item_id, 'quantity' => $amazon_cart_item_quantity);

        $amazon_cart = "";
        $session = JFactory::getSession();
        $amazon_cart = $session->get("amazon_cart_booklibr", "");
        $changed = false;
//for amazon cart
        if ($amazon_cart_item_id != "" && $amazon_cart_item_quantity != "") {
            if (!empty($amazon_cart)) {
                foreach ($amazon_cart as $key => $item):
                    if ($item['id'] == $amazon_cart_item_id) {
                        $amazon_cart[$key]['quantity']+=$amazon_cart_item_quantity;
                        $changed = true;
                    }
                endforeach;
                if (!$changed) {
                    $amazon_cart[] = $amazon_cart_item;
                }
            } else {
                $amazon_cart[] = $amazon_cart_item;
            }
        }
        $session->set("amazon_cart_booklibr", $amazon_cart);

        mosRedirect('index.php?option=com_booklibrary&task=show_cart&Itemid=' . $Itemid);
    }

    function show_cart() {
        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $Itemid;
        $books = array();
        $session = JFactory::getSession();
        $amazon_cart = $session->get("amazon_cart_booklibr", "");

        if (!empty($amazon_cart)) {
            foreach ($amazon_cart as $key => $item) {
                $amazon_cart[$key] = new mosBooklibrary($database);
                $amazon_cart[$key]->load($item['id']);
                $amazon_cart[$key]->quantity = $item['quantity'];
            }
        }
        HTML_booklibrary :: show_cart($amazon_cart);
    }

    function addToAmazonCartRest($reqs, $version = '2009-03-01') {
        global $booklibrary_configuration;
        PHP_booklibrary::addTitleAndMetaTags();
        $endpoint = "http://ecs.amazonaws.com/onca/xml";
        if ($booklibrary_configuration['ws']['amazon']['secret_key'] == "")
            $secret_key = "ooTVCJy06UNXeMujmlyso9Wj4VD1flgEPsCx5HYY";
        else
            $secret_key = $booklibrary_configuration['ws']['amazon']['secret_key'];

        //create items array - string for request
        $items_set = "";
        $request = "$endpoint?" .
                "Service=AWSECommerceService" .
                "&Operation=CartCreate" .
                "&Version={$version}" .
                "&AWSAccessKeyId=" . $booklibrary_configuration['ws']['amazon']['devtag'] . //'AKIAIWZKHN3I6UWTN3XQ' .
                "&AssociateTag=" . $booklibrary_configuration['ws']['amazon']['tag'] . // 'orda-20' .
                "&ResponseGroup=Cart";
        // Get a nice array of elements to work with
        $uri_elements = parse_url($request);


        // Grab our request elements
        $request = $uri_elements['query'];

        // Throw them into an array
        parse_str($request, $parameters);

        // Add the new required paramters
        $parameters['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
        $parameters['Version'] = $version;

        //create items array - string for request
        $i = 0;
        foreach ($reqs as $req) {
            $i++;
            $parameters["Item." . $i . ".ASIN"] = $req['isbn'];
            $parameters["Item." . $i . ".Quantity"] = $req['quantity'];
        }
        // The new authentication requirements need the keys to be sorted
        ksort($parameters);

        // Create our new request
        foreach ($parameters as $parameter => $value) {
            // We need to be sure we properly encode the value of our parameter
            if (!strpos($parameter, "ASIN") && !strpos($parameter, "Quantity")) {
                $parameter = str_replace("%7E", "~", rawurlencode($parameter));
                $value = str_replace("%7E", "~", rawurlencode($value));
            }
            $request_array[] = $parameter . '=' . $value;
        }

        // Put our & symbol at the beginning of each of our request variables and put it in a string
        $new_request = implode('&', $request_array);

        // Create our signature string
        $signature_string = "GET\n{$uri_elements['host']}\n{$uri_elements['path']}\n{$new_request}";

        // Create our signature using hash_hmac
        $signature = urlencode(base64_encode(hash_hmac('sha256', $signature_string, $secret_key, true)));

        // new request
        $request = "http://{$uri_elements['host']}{$uri_elements['path']}?{$new_request}&Signature={$signature}";

// for 1.6
        $request_url = $request;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        $xml = simplexml_load_string($data) or die("Error of URL load");
        $result = $xml;
// --
        // for 1.6
        if (array_key_exists('Error', $result)) {
            $retVal = $result->Error->Message;
            echo "<p>$retVal</p>";
            return $retVal;
        } // --

        $goods = $result;
        return $goods;
    }

    function check_out($cart_temp = '') {
        global $mainframe, $database, $my, $Itemid;
        PHP_booklibrary::addTitleAndMetaTags();
        if ($cart_temp == '') {
            $session = JFactory::getSession();
            $cart_temp = $session->get("amazon_cart_booklibr", "");
        }


        foreach ($cart_temp as $key => $item) {
            $database->setQuery("SELECT isbn FROM #__booklibrary WHERE id='" . $item['id'] . "'");
            $cart_temp[$key]['isbn'] = $database->loadResult();
        }

        $goods = PHP_booklibrary::addToAmazonCartRest($cart_temp);
        if (is_string($goods)) {

            //there was an error while fetching!
            echo "<script> alert('" . addslashes($goods) . "'); window.history.go(-1); </script>\n";
            exit();
        }

        header("location:" . $goods->Cart->PurchaseURL);
    }

    function cart_event() {
        PHP_booklibrary::addTitleAndMetaTags();
        global $Itemid;
        $session = JFactory::getSession();
        $check_out = mosGetParam($_REQUEST, 'check_out', '');
        $clean_cart = mosGetParam($_REQUEST, 'clean_cart', '');
        if (!empty($clean_cart)) {
            $session->set("amazon_cart_booklibr", "");
            mosRedirect('index.php?option=com_booklibrary&task=show_cart&Itemid=' . $Itemid);
        }
        $continue_shop = mosGetParam($_REQUEST, 'continue_shop', '');
        $quant = mosGetParam($_REQUEST, 'quantity', '');
        $id = mosGetParam($_REQUEST, 'id', '');
        $cart_temp = array();
        foreach ($quant as $key => $value):
            $cart_temp[$key] = array();
            $cart_temp[$key]['id'] = $id[$key];
            $cart_temp[$key]['quantity'] = $quant[$key];
        endforeach;
        $session->set("amazon_cart_booklibr", $cart_temp);

        if (!empty($continue_shop)) {
            mosRedirect('index.php?option=com_booklibrary&Itemid=' . $Itemid);
        }

        if (!empty($check_out)) {
            PHP_booklibrary::check_out($cart_temp);
        }
    }

 

    static function ownersList($option) {
        global $database, $my, $Itemid, $mainframe, $booklibrary_configuration,
        $acl, $mosConfig_list_limit, $limit, $limitstart;

        PHP_booklibrary::addTitleAndMetaTags();

        $symbol = mosGetParam($_REQUEST, 'letindex', '');
        $symbol_str = '';
        if ($symbol) {
            $symbol_str = " AND (LOWER(u.name) LIKE '$symbol%' ) ";
        }
        //getting groups of user
        $s = getWhereUsergroupsString("c");


//   $menu = new JTableMenu( $database );
//   $menu->load( $Itemid );
//   $params = new mosParameters( $menu->params );
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        }

        $database->setQuery("SELECT id FROM #__menu WHERE link='index.php?option=com_booklibrary'");
        if ($database->loadResult() != $Itemid) {
            $params->def('wrongitemid', '1');
        }
        $params->def('header', _BOOKLIBRARY_LABEL_TITLE_OWNERSLIST);
        if (!$params->get('wrongitemid')) {
            $pathway = sefRelToAbs('index.php?option=' . $option .
                    '&amp;task=owners_list&amp;Itemid=' . $Itemid);
            $pathway_name = _BOOKLIBRARY_LABEL_TITLE_OWNERSLIST;
            $mainframe->appendPathWay($pathway_name, $pathway);
        }

        if (checkAccessBL($booklibrary_configuration['ownerslist']['registrationlevel'], 'NORECURSE', userGID_BL($my->id), $acl) &&
                $booklibrary_configuration['ownerslist']['show']) {
            $params->def('ownerslist_show', 1);
        }
        $db = JFactory::getDBO();
        $query = "SELECT COUNT(DISTINCT u.email)
                          \nFROM #__booklibrary AS bl
                          \nLEFT JOIN #__booklibrary_categories AS mc ON mc.bookid=bl.id
                          \nLEFT JOIN #__booklibrary_main_categories AS c ON c.id=mc.catid
                          \nLEFT JOIN #__users AS u ON bl.owneremail=u.email
                          \nWHERE bl.published=1 AND bl.approved=1 AND c.published=1
                                AND ({$s}) $symbol_str;
                          ";
        $database->setQuery($query);
        $total = $database->loadResult();
        $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

        $query = "SELECT u.name, COUNT(DISTINCT mc.bookid) AS books
                          \nFROM #__booklibrary AS bl
                          \nLEFT JOIN #__booklibrary_categories AS mc ON  mc.bookid=bl.id
                          \nLEFT JOIN #__booklibrary_main_categories AS c ON c.id=mc.catid
                          \nLEFT JOIN #__users AS u ON bl.owneremail=u.email
                          \nWHERE bl.published=1 AND bl.approved=1 AND c.published=1 AND bl.owneremail!=''
                                  AND ({$s}) $symbol_str
                          \nGROUP BY u.name
                          \nORDER BY u.name
                          \nLIMIT $pageNav->limitstart,$pageNav->limit;";
        $database->setQuery($query);
        $ownerslist = $database->loadObjectList();
        
        if(!empty($ownerslist[0]->books) && !isset($ownerslist[0]->name))$ownerslist[0]->name = 'anonymous';

        $query = "SELECT DISTINCT UPPER(SUBSTRING(u.name, 1,1)) AS symb 
                          \nFROM #__booklibrary AS bl
                          \nLEFT JOIN #__booklibrary_categories AS mc ON mc.bookid=bl.id
                          \nLEFT JOIN #__booklibrary_main_categories AS c ON c.id=mc.catid
                          \nLEFT JOIN #__users AS u ON bl.owneremail=u.email
                          \nWHERE bl.published=1 AND bl.approved=1 AND c.published=1 AND bl.owneremail!=''
                                  AND ({$s})
                          \nORDER BY u.name ;";
        $database->setQuery($query);
        $symb = $database->loadObjectList();
        if (count($symb) > 0) {
            $symb_list_str = '<div style="display:inline; margin-left:auto;margin-right:auto;">';
            foreach ($symb as $symbol) {
                $symb_list_str .= '<span style="padding:5px; ">' .
                        '<a href="index.php?option=' . $option .
                        '&task=owners_list' .
                        '&letindex=' . $symbol->symb . '&Itemid=' . $Itemid .
                        '">' . $symbol->symb . '</a></span>';
            }
            $symb_list_str.="</div>";
            $params->def('symb_list_str', $symb_list_str);
        }
        $params->def('ownerlist01', "{loadposition com_booklibrary_owner_list_01}");
        $params->def('ownerlist02', "{loadposition com_booklibrary_owner_list_02}");
        $params->def('ownerlist03', "{loadposition com_booklibrary_owner_list_03}");
        //print_r($ownerslist);
        HTML_booklibrary :: showOwnersList($params, $ownerslist, $pageNav);
    }

   static function viewUserBooks($option) {
        global $database, $my, $Itemid, $mainframe;

        PHP_booklibrary::addTitleAndMetaTags();

        if (version_compare(JVERSION, '3.0', 'lt')) {
            $menu = new JTableMenu($database); // for 1.6
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        } else {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        }

        $menu_name = set_header_name_bl($menu, $Itemid);

   if (isset($_REQUEST['option']) && $_REQUEST['option'] == 'com_comprofiler') {
                        if (!isset($user) or $user == "") {
                                $params = @$mainframe->getParams();
                                $user = $params->get('username');
                                if ($user == '') {
                                        if (isset($_REQUEST['name'])) $user = $_REQUEST['name'];
                                                                                                                 elseif (isset($_SESSION['vm_user'])) $user = $_SESSION['vm_user']; // for 1.6
                else $user = "Guest";
                                }
                        }
        if (isset($user) and is_numeric($user)) {
            $user = $params->get('username');
            $user = JFactory::getUser($user);
            $user = $user->name;
        }
        $anonym_flag = false;
        if ($user == '') {
            $user = "Anonymous";
            $anonym_flag = true;
        }
        } else {
            $database->setQuery("SELECT id FROM `#__menu` WHERE link LIKE '%com_booklibrary%' AND menutype <> 'main' ORDER BY link");
        $itms = $database->loadObjectList();
        $item_flag = false;
        foreach ($itms as $item)
            if ($item->id == $Itemid) {
                $item_flag = true;
                break;
            }
            if (!$item_flag)
            $params->def('wrongitemid', '1');
        
        //    setting  user - id 
        if(!isset($_REQUEST['userId'])) {
                $id = mosGetParam($_REQUEST, 'id');
        } else {
                $id = mosGetParam($_REQUEST, 'userId');
        }

        if (isset($_REQUEST['option']) && $_REQUEST['option'] == 'com_comprofiler' && $id < 1) {
            $id = JFactory::getUser()->id;
        }
        if ($id == 0) { 
            $user = "anonymous";
        }
        if (!isset($id)) {
            $id = JFactory::getUser()->id;
        }
        $database->setQuery("SELECT name FROM #__users WHERE id='$id'");
        $user = $database->loadResult();
    }

        $params->def('header', $menu_name . ' : ' . _BOOKLIBRARY_LABEL_TITLE_USER_BOOKS); //for 1.6
        $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=owners_list&amp;Itemid=' . $Itemid);
        $pathway_name = $user;
        if (!$params->get('wrongitemid')) {
            // for 1.6
            $path_way = $mainframe->getPathway();
            $path_way->addItem(_BOOKLIBRARY_LABEL_TITLE_OWNERSLIST, $pathway);
            // --
        } else
            return "Wrong Itemid was given";
        $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=view_user_books&amp;Itemid=' . $Itemid . '&amp;name=' . $user);

        // for 1.6
        $path_way = $mainframe->getPathway();
        $path_way->addItem($pathway_name, $pathway);
        
        PHP_booklibrary::searchBooks($option, 0, $option, $user);
    }

    function lendBeforeEndNotify($option) {
        PHP_booklibrary::addTitleAndMetaTags();
        global $database, $booklibrary_configuration, $Itemid, $mosConfig_mailfrom;

        $send_email = 0;
        if (($booklibrary_configuration['lend_before_end_notify']) &&
                trim($booklibrary_configuration['lend_before_end_notify_email']) != "" && is_numeric($booklibrary_configuration['lend_before_end_notify_days'])) {
            $send_email = 1;
        }
        if ($send_email) {
            $mail_to = explode(",", $booklibrary_configuration['lend_before_end_notify_email']);


            $zapros = "SELECT c.id, c.bookid, c.title, d.lend_from,d.lend_until,d.user_name,d.user_email " .
                    " FROM #__booklibrary as c " .
                    " left join #__booklibrary_lend as d on d.fk_bookid = c.id " .
                    " WHERE d.lend_return IS NULL and TIMESTAMPDIFF(DAY, now(),lend_until ) = " .
                    $booklibrary_configuration['lend_before_end_notify_days'] . " ; ";
            $database->setQuery($zapros);
            $item_book = $database->loadObjectList();
            echo $database->getErrorMsg();


            $message = "So books lend expire soon (in " . $booklibrary_configuration['lend_before_end_notify_days'] . " days):<br /><br />";

            foreach ($item_book as $item) {
                $message .= 'Lend User: ' . $item->user_name . '(' . $item->user_email . ')<br /> ' .
                        'Book: ' . $item->title . ' <br />' .
                        'ID: ' . $item->id . ' <br />' .
                        'BookID: ' . $item->bookid . ' <hr /><br />';
            }

            if (count($item_book) > 0)
                JUTility::sendMail($mosConfig_mailfrom, "Lend expire  Notice", $mail_to, 'Lend expire Notice!', $message, true);
        }
    }

    static function rent_history($option) {
        global $database, $my, $Itemid, $booklibrary_configuration, $mainframe, $mosConfig_list_limit;

        PHP_booklibrary::addTitleAndMetaTags();

//     $menu = new mosMenu($database);
//     $menu->load( $Itemid );
//     $params = new mosParameters( $menu->params );

        if (version_compare(JVERSION, '3.0', 'lt')) {
            $menu = new JTableMenu($database); // for 1.6
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        } else {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        }

        $database->setQuery("SELECT id FROM #__menu WHERE link='index.php?option=com_booklibrary'");
        if ($database->loadResult() != $Itemid)
            $params->def('wrongitemid', '1');

        if ($my->email == null) {
            echo "
             <script type=\"text/JavaScript\" language = \"JavaScript\">
                alert('You cannot view My Books that were not authorizated!');
                window.history.go(-1);
            </script>";
            exit;
        }

        $limit = $booklibrary_configuration['page']['items'];
        $limitstart = mosGetParam($_REQUEST, 'limitstart', 0);

        $database->setQuery("SELECT count(*) FROM #__booklibrary_lend AS l" .
                "\nLEFT JOIN #__booklibrary AS a ON a.id = l.   fk_bookid" .
                "\nWHERE l.fk_userid = '$my->id'");
        $total = $database->loadResult();
        echo $database->getErrorMsg();

        $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

        $query = "SELECT l.*,a.* FROM #__booklibrary_lend AS l" .
                "\nLEFT JOIN #__booklibrary AS a ON a.id = l.   fk_bookid " .
                "\nWHERE l.fk_userid = '" . $my->id . "' LIMIT $pageNav->limitstart,$pageNav->limit;";

        $database->setQuery($query);
        $books = $database->loadObjectList();
        HTML_booklibrary :: showRentHistory($option, $books, $pageNav, $params);
    }

}

class HTML {

    // TODO :: merge categoryList and categoryParentList
    // add filter option ?
    function categoryList($id, $action, $options = array()) {
        $list = CAT_Utils_booklib::categoryArray();
        // assemble menu items to the array
        foreach ($list as $item) {
            $options[] = mosHTML::makeOption($item->id, $item->treename);
        }
        $parent = mosHTML::selectList($options, 'catid', 'id="catid" class="inputbox" size="1" onchange="' . $action . '"', 'value', 'text', $id);
        return $parent;
    }

    function categoryParentList($id, $action, $is_new, $options = array()) {
        global $database;
        $list = CAT_Utils_booklib::categoryArray();
        $cat = new mainBooklibraryCategories($database); //for 1.6
        $cat->load($id);

        $this_treename = '';
        $childs_ids = Array();
        foreach ($list as $item) {
            if ($item->id == $cat->id || array_key_exists($item->parent_id, $childs_ids))
                $childs_ids[$item->id] = $item->id;
        }

        foreach ($list as $item) {

            if ($this_treename) {
                if ($item->id != $cat->id && strpos($item->treename, $this_treename) === false && array_key_exists($item->id, $childs_ids) === false) {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                }
            } else {
                if ($item->id != $cat->id) {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                } else {
                    $this_treename = "$item->treename/";
                }
            }
        }

        $parent = null;
        $parent = mosHTML::selectList($options, 'parent_id', 'class="inputbox" size="1"', 'value', 'text', $cat->parent_id);
        return $parent;
    }

    function imageList($name, &$active, $javascript = null, $directory = null) {

        global $mosConfig_absolute_path;
        if (!$javascript) {
            $javascript = "onchange=\"javascript:if (document.adminForm." . $name .
                    ".options[selectedIndex].value!='')    " .
                    "{document.imagelib.src='../images/stories/' + document.adminForm."
                    . $name . ".options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
        }
        if (!$directory) {
            $directory = '/images/stories';
        }

// inserted by Wonderer
        if (!file_exists($mosConfig_absolute_path . $directory)) {
            @mkdir($mosConfig_absolute_path . $directory, 0777) or die("Error of directory creating: [" . $mosConfig_absolute_path . $directory . "] ");
        } else {
            
        }
// --
        $imageFiles = mosReadDirectory($mosConfig_absolute_path . $directory);
        $images = array(mosHTML::makeOption('', _BOOKLIBRARY_A_SELECT_IMAGE));
        foreach ($imageFiles as $file) {
            if (preg_match("/bmp|gif|jpg|png/i", $file)) {
                $images[] = mosHTML::makeOption($file);
            }
        }
        $images = mosHTML::selectList($images, $name, 'id="' . $name . '" class="inputbox" size="1" '
                        . $javascript, 'value', 'text', $active);
        return $images;
    }

}

class CAT_Utils_booklib {
    /*
     * function categoryArray ()
     * Gets the Category list depending of user access level.
     * for 1.6
     */

    static function categoryArray() {
        global $database, $my;

        $s = getWhereUsergroupsString("c");

        $query = "select c.id,c.parent_id, COUNT(bc.bookid) AS books, c.title, c.image, '1' AS display, c.parent_id AS parent, c.name" .
                " FROM  #__booklibrary_main_categories as c
        LEFT JOIN #__booklibrary_categories AS bc ON c.id=bc.catid \n
        LEFT JOIN #__booklibrary AS b ON b.id=bc.bookid AND b.published=1 AND b.approved=1
        WHERE  c.section='com_booklibrary'
          AND c.published=1 AND ({$s})
        GROUP BY c.id \n
        ORDER BY parent_id DESC, c.ordering ";

        $database->setQuery($query);
        $items = $database->loadObjectList();

        // establish the hierarchy of the menu
        $children = array();
        // first pass - collect children
        foreach ($items as $v) {
            $pt = $v->parent;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }
        // second pass - get an indent list of the items
        $array = PHP_booklibrary::bookLibraryTreeRecurse(0, '', array(), $children);
        return $array;
    }

}

