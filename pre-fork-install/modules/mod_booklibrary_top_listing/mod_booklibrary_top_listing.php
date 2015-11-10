<?php
/**
 *
 * Module for booklibrary
 * @version 3.0 FREE
 * @package Booklibrary  
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @copyright 2011 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com);
 * Homepage: http://www.ordasoft.com
 *
 * */
?>

<link rel="stylesheet" type="text/css" href="components/com_booklibrary/includes/booklibrary.css">
<?php
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
$database = JFactory::getDBO();
$my = JFactory::getUser();
$GLOBALS['database'] = $database;
$GLOBALS['my'] = $my;
$acl = JFactory::getACL();
$GLOBALS['acl'] = $acl;


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

//Carls Edit For Params

if (is_callable(array($params, "get"))) {    // Mambo 4.5.0 compatibility
    $show_covers = $params->def('covers', 0);    //Get if we show covers
    $cover_height = $params->def('cover_height', "50");  //Get Cover Height 
    $show_extra = $params->def('extras', 1);    //Get if we show second column with additional info
    $show_ranking = $params->def('ranking', 0);    //Get if we show the ranking next to them
    $show_this_many = $params->def('top_number', "10");  //Get how many top books to show
    $sort_top_by = $params->def('sort_by_top', 0);  //Get how to sort the top items
    $show_published = $params->def('only_published', 1); //Get if we only show published items
    $layout = $params->get('layout', 'default');
} else { //Set Defaults If nothing set
    $show_covers = 0;
    $cover_height = "50";
    $show_extra = 1;
    $show_ranking = 0;
    $show_this_many = "10";
    $sort_top_by = 0;
    $show_published = 1;
}

//Check if only display published items
If ($show_published == 1) {
    $sql_published = " b.published=1 ";
    $where[] = $sql_published;
}

require_once ( JPATH_SITE . "/components/com_booklibrary/functions.php" );
$s = getWhereUsergroupsString("c");


$where[] = "( " . $s . " )";
$where[] = " c.published='1'";

//Definition of Sorts
switch ($sort_top_by) {
    case 0:
        $sql_sort_top = "hits";
        break;
    case 1:
        $sql_sort_top = "date";
        break;
    case 2:
        $sql_sort_top = "rating";
        break;
}

$rank_count = 0; //Set Initial Rank Count
//End Carls Edit

$selectstring = "SELECT b.id,b.imageURL,b.title,b.hits,b.bookid,bc.catid FROM #__booklibrary AS b
                \nLEFT JOIN #__booklibrary_categories AS bc ON bc.bookid=b.id
                \nLEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid
                \nWHERE" . implode(' AND ', $where) .
        "\n GROUP BY b.id ORDER BY $sql_sort_top DESC LIMIT 0,$show_this_many ;";

$database->setQuery($selectstring);
$rows = $database->loadObjectList();

$ItemId_tmp_from_params = $params->get('ItemId');
$database->setQuery("SELECT id  FROM #__menu WHERE menutype like '%menu%' AND link LIKE '%index.php?option=com_booklibrary%' ");
$ItemId_tmp_from_db = $database->loadResult();
if ($ItemId_tmp_from_params != '') {
    $ItemId_tmp = $ItemId_tmp_from_params;
} else {
    $ItemId_tmp = $ItemId_tmp_from_db;
}

$moduleclass_sfx = $params->get('moduleclass_sfx');

require(JModuleHelper::getLayoutPath('mod_booklibrary_top_listing', $layout));
?>