<?php
/**
 *
 * Module for booklibrary
 * @version 3.0FREE
 * @package Booklibrary
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @copyright 2011 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com);
 * Homepage: http://www.ordasoft.com
 * */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
?>

<link rel="stylesheet" type="text/css" href="components/com_booklibrary/includes/booklibrary.css">
<?php
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
$database = JFactory::getDBO();
$acl = JFactory::getACL();
$my = JFactory::getUser();

require_once ( JPATH_SITE . "/components/com_booklibrary/functions.php" );
$s = getWhereUsergroupsString("c");

$selectstring = "SELECT a.id,a.title,a.hits,a.bookid,bc.catid FROM #__booklibrary AS a
                    LEFT JOIN #__booklibrary_categories AS bc ON a.id=bc.bookid" .
        "\nLEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid" .
        "\nWHERE ({$s}) AND c.published='1' AND a.published='1'" .
        "\nGROUP BY a.id ORDER BY hits DESC LIMIT 0,10;";

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

require(JModuleHelper::getLayoutPath('mod_booklibrary_top10', "default"));
?>
