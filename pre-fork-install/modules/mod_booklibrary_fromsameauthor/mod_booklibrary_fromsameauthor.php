<?php
/**
 *
 * Module for booklibrary
 * @version 3.0 FREE
 * @package Booklibrary
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @copyright 2010 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); 
 * Homepage: http://www.ordasoft.com
 *
 * */
//$start=microtime(1);
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
?>
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
if (array_key_exists("id", $_REQUEST)
  && array_key_exists("option", $_REQUEST)
  && $_REQUEST["option"] == 'com_booklibrary' ) {


    $database = JFactory::getDBO();
    $id = intval($_REQUEST["id"]);
    $selectstring = "SELECT authors FROM #__booklibrary WHERE id=" . $id;
    $my = JFactory::getUser();
    $GLOBALS['database'] = $database;
    $GLOBALS['my'] = $my;
    $acl = JFactory::getACL();
    $GLOBALS['acl'] = $acl;
    $database->setQuery($selectstring);
    $authors = trim($database->loadResult());

    $countLimit = intval($params->get('count', 5));
    $moduleclass_sfx = $params->get('moduleclass_sfx', '');
    $g_words = $params->get('words', '');
    $showtitle = $params->get('showtitle', '');
    $showauthor = $params->get('showauthor', '');
    $showcover = $params->get('showcover', 1);
    $displaytype = $params->get('displaytype', 0);
    $coversize = $params->get('coversize', '127');
    //$start=microtime();
    $ItemId_tmp_from_params = $params->get('ItemId');
         
    $rowses = array();

    require_once ( JPATH_SITE . "/components/com_booklibrary/functions.php" );
    $s = getWhereUsergroupsString("c");

    $selectstring = "SELECT *
              FROM #__booklibrary AS b" .
            "\nLEFT JOIN #__booklibrary_categories AS bc ON bc.bookid=b.id" .
            "\nLEFT JOIN #__booklibrary_main_categories AS c ON c.id=bc.catid" .
            "\n WHERE authors LIKE '%" . $authors . "%' AND b.id != " . $id .
            " AND ({$s})" .
            "\n GROUP BY b.id ORDER BY hits DESC LIMIT 0, $countLimit;";
    $database->setQuery($selectstring);
    $rows = $database->loadObjectList();

    //option=com_
    $database->setQuery(
      "SELECT id  FROM #__menu WHERE menutype like '%menu%' AND link LIKE '%index.php?option=com_booklibrary%' ");
    $ItemId_tmp_from_db = $database->loadResult();
    if ($ItemId_tmp_from_params != '') {
        $ItemId_tmp = $ItemId_tmp_from_params;
    } else {
        $ItemId_tmp = $ItemId_tmp_from_db;
    }

    if (count($rows) > 0) {


        ?>
        <link rel="stylesheet" type="text/css" href="components/com_booklibrary/includes/booklibrary.css">
        <?php if ($moduleclass_sfx != '') { ?>
            <div  class="<?php echo $moduleclass_sfx; ?>"> <?php } ?>
            <noscript>Javascript is required to use Book Library <a
             href="http://ordasoft.com/Book-Library/booklibrary-versions-feature-comparison.html"
             >Book Library - create book library, ebook, book collection  </a>,
            <a href="http://ordasoft.com/location-map.html"
              >Book library book sowftware for Joomla</a></noscript>
            <table cellpadding="0" cellspacing="0" class="basictable">
                <tr>

                    <?php
                    foreach ($rows as $row) {

                        $comment = strip_tags($row->comment);
                        $prevwords = count(explode(" ",$comment));
                        if(trim($g_words == "" )) $words = $prevwords;
                        else $words = intval($g_words);
                        $text = implode(" ", array_slice(explode(" ",$comment), 0, $words));
                        if (count(explode(" ",$text))<$prevwords){
                            $text .= "";
                        }

                        $link1 = "index.php?option=com_booklibrary&amp;task=view&amp;id="
                         . $row->id . "&amp;Itemid=" . $ItemId_tmp . "&amp;catid=" . $row->catid;

                        //for local images
                        $imageURL = $row->imageURL;
                        if ($imageURL != '' && substr($imageURL, 0, 4) != "http") {
                            $imageURL = JURI::base() . $row->imageURL;
                        }
                        if ($imageURL == '') {
                            $imageURL = "./components/com_booklibrary/images/no-img_eng.gif";
                        }

                        if ($displaytype == 1) { // Display Horizontal
                            if ($showcover == 1) {
                                ?>
                                <td>
                                    <a href="<?php echo sefRelToAbs($link1); ?>" target="_self">
                                        <img src="<?php echo $imageURL; ?>"  hspace="15"
                                         vspace="2" border="0" height="<?php echo $coversize; ?>" /></a>
                                </td>
                                <?php
                            }
                            ?>
                            <td valign="top">
                                <p><strong>
                                        <?php
                                        if ($showtitle == "1") {
                                            echo $row->title;
                                        }
                                        ?></strong><br/>
                                        <?php
                                        if ($showauthor == "1") {
                                            echo '<br/>'.$row->authors;
                                        }

                                        if ($text != "") {
                                            echo '<br/>'.$text;
                                        }
                                        ?>
                                    <br />
                                <p><a class="readon" href="<?php echo sefRelToAbs($link1); ?>"
                                 target="_self"><?php echo _BOOKLIBRARY_VIEW_BOOK ?> ...</a></p>
                                 </td>
                                <?php
                            } else {
                                //Display Vertical
                                ?>

                        </tr>
                        <tr valign="top">
                            <td>
                                <a href="<?php echo sefRelToAbs($link1); ?>" target="_self">
                                    <?php if ($showcover == 1) { ?>
                                        <img src="<?php echo $imageURL; ?>"  hspace="2"
                                         vspace="2" border="0" height="<?php echo $coversize; ?>" /></a>
                                <?php } //End Show Image If ?>

                                <?php
                                if ($showtitle == "1") {
                                    echo "<br /><strong>" . $row->title . "</strong>";
                                }
                                ?>
                                <?php
                                if ($showauthor == "1") {
                                    echo "<br />" . $row->authors;
                                }
                                if ($text != "") {
                                    echo '<br/>'.$text;
                                }
                                ?>
                                <br />                        
                                <a class="readon" href="<?php echo sefRelToAbs($link1); ?>"
                                 target="_self"><?php echo _BOOKLIBRARY_VIEW_BOOK ?> ...</a>
                            </td>
                        </tr>
                        <tr> <td>&nbsp; </td>
                        </tr>
                        <?php
                    } //End Display If
                }
                if ($displaytype == 1) { // Display Horizontal
                    ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php if ($moduleclass_sfx != '') { ?>
            </div> <?php } ?>
        <div style="text-align: center;"><a href="http://ordasoft.com"
         style="font-size: 10px;">Powered by OrdaSoft!</a></div>
        <?php
    }
}
?>

