<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC'))
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
 *
 * @package Booklibrary
 * @copyright 2011 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru);
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * Homepage: http://www.ordasoft.com
 * @version: 3.0 FREE
 * */
$showInterior_extras = $params->get('showInterior_extras', 0);
$showSafety_options = $params->get('showSafety_options', 0);
$showWarranty_options = $params->get('showWarranty_options', 0);
$showInterior_colors = $params->get('showInterior_colors', 0);
$showWheeltype = $params->get('showWheeltype', 0);
?>

<link rel="stylesheet" type="text/css" href="components/com_booklibrary/includes/booklibrary.css">
<?php
if (!function_exists('categoryArray')) {
    /*
     * function categoryArray ()
     * Gets the Category list depending of user access level.
     * for 1.6
     */
    if (defined("DS") != true) {
        define('DS', DIRECTORY_SEPARATOR);
    }

    function categoryArray() {
        global $database, $my;

        $usergroups = getGroupsByUser($my->id, '');
        $usergroups_sh = $usergroups;

        if (version_compare(JVERSION, '3.0', 'lt')) {
            $usergroups = '-2' . $usergroups;
        } else {
            $usergroups = '-2' . $usergroups[0];
        }





        $usergroups_sh[] = -2;

        $s = '';
        for ($i = 0; $i < count($usergroups_sh); $i++) {
            $g = $usergroups_sh[$i];
            $s .= " c.params like '%,{$g}' or c.params = '{$g}' or c.params like '{$g},%' or c.params like '%,{$g},%' ";
            if (($i + 1) < count($usergroups_sh))
                $s .= ' or ';
        }

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
/// $array = mosTreeRecurse(0, '', array(), $children);
        $array = bookLibraryTreeRecurse(0, '', array(), $children);
        return $array;
    }

}

if (!function_exists('bookLibraryTreeRecurse')) {
    /*
     * function bookLibraryTreeRecurse ()
     * Redefines a standard function to not display &nbsp;
     */

    function bookLibraryTreeRecurse($id, $indent, $list, $children, $maxlevel = 9999, $level = 0, $type = 1) {

        if (@$children[$id] && $level <= $maxlevel) {
            $parent_id = $id;
            foreach ($children[$id] as $v) {
                $id = $v->id;

                if ($type) {
                    $pre = " ";
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

                $list = bookLibraryTreeRecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
            }
        }
        return $list;
    }

}


if (!function_exists('mod_categoryTreeList')) {

    function mod_categoryTreeList($id, $action, $is_new, $options = array()) {
        global $database, $mosConfig_absolute_path;
        $list = categoryArray();
        ///$cat = new mosCategory($database);
        require_once($mosConfig_absolute_path . DS . "components" . DS . "com_booklibrary" . DS . "booklibrary.main.categories.class.php"); //for 1.6

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
                if ($item->id != $cat->id
                        && strpos($item->treename, $this_treename) === false
                        && array_key_exists($item->id, $childs_ids) === false) {
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
        $parent = mosHTML::selectList($options, 'catid', 'class="inputbox" size="1" style="max-width:170px;"', 'value', 'text', $cat->parent_id);
        return $parent;
    }

}

if (!function_exists('sefreltoabs')) {

    function sefRelToAbs($value) {
        //Need check!!!
        // Replace all &amp; with & as the router doesn't understand &amp;
        $url = str_replace('&amp;', '&', $value);
        if (substr(strtolower($url), 0, 9) != "index.php")
            return $url;
        $uri = JURI::getInstance();
        $prefix = $uri->toString(array('scheme', 'host', 'port'));
        return $prefix . JRoute::_($url);
    }

}


global $mosConfig_absolute_path, $mosConfig_allowUserRegistration, $mosConfig_lang;
$database = JFactory::getDBO();
$my = JFactory::getUser();
$GLOBALS['database'] = $database;
$GLOBALS['my'] = $my;
$acl = JFactory::getACL();
$GLOBALS['acl'] = $acl;
$GLOBALS['mosConfig_absolute_path'] = $mosConfig_absolute_path = JPATH_SITE;


// load language
if (defined("_BOOKLIBRARY_LABEL_SEARCH_BUTTON") != true) {

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
        $mosConfig_lang = $lang->getTag();
        $languagelocale = $lang->getTag();
    }

    if ($languagelocale == '')
        $languagelocale = "en-GB";

    $query = "SELECT c.const, cl.value_const ";
    $query .= "FROM #__booklibrary_const_languages as cl ";
    $query .= "LEFT JOIN #__booklibrary_languages AS l ON cl.fk_languagesid=l.id ";
    $query .= "LEFT JOIN #__booklibrary_const AS c ON cl.fk_constid=c.id ";
    $query .= "WHERE l.lang_code = '$languagelocale'";
    $database->setQuery($query);
    $langConst = $database->loadObjectList();

    foreach ($langConst as $item) {
        define($item->const, $item->value_const);
    }
}
include_once($mosConfig_absolute_path . DS . "components" . DS . "com_booklibrary" . DS . "compat.joomla1.5.php" );

$showAuthor = $params->get('showAuthor', 0);
$showTitle = $params->get('showTitle', 0);
$showIsbn = $params->get('showIsbn', 0);
$showBookId = $params->get('showBookId', 0);
$showDescription = $params->get('showDescription', 0);
$showPublisher = $params->get('showPublisher', 0);
$showAdvanceSearch = $params->get('showAdvanceSearch', 0);
$showPrice = $params->get('showPrice', 0);


$categories[] = mosHTML :: makeOption('0', _BOOKLIBRARY_SEARCH_CATEGORY);

$ItemId_tmp_from_params = $params->get('ItemId');
$database->setQuery("SELECT id  FROM #__menu WHERE menutype like '%menu%' AND link LIKE '%index.php?option=com_booklibrary%' ");
$ItemId_tmp_from_db = $database->loadResult();
if ($ItemId_tmp_from_params != '') {
    $Itemid = $ItemId_tmp_from_params;
} else {
    $Itemid = $ItemId_tmp_from_db;
}


$clist = mod_categoryTreeList(0, '', true, $categories);
$moduleclass_sfx = $params->get('moduleclass_sfx');

require(JModuleHelper::getLayoutPath('mod_booklibrary_search', "default"));
?>
