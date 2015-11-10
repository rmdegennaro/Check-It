<?php
defined('_JEXEC') or die('Restricted access');
?>

<?php if ($moduleclass_sfx != '') { ?>
    <div  class="<?php echo $moduleclass_sfx; ?>">
<?php } ?>
    <table cellpadding="0" cellspacing="0" class="basictable" width="100%">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" class="moduletable" width="100%">
                    <?php
                    foreach ($rows as $row) {
                        $rank_count = $rank_count + 1; //start ranking
                        $link1 = "index.php?option=com_booklibrary&amp;task=view&amp;id="
                         . $row->id . "&amp;Itemid=" . $ItemId_tmp . "&amp;catid=" . $row->catid;
                        ?>
                        <tr>
                            <?php
                            if ($show_ranking == 1) {
                                echo "<td>" . $rank_count . ":&nbsp;</td>";
                            } //Add Column for Ranking if param set  ?>
                            <?php
                            if ($show_covers == 1) {
                                //for local images
                                $imageURL = $row->imageURL;
                                if ($imageURL != '' && substr($imageURL, 0, 4) != "http") {
                                    $imageURL = JURI::base() . $row->imageURL;
                                }
                                if($imageURL == "" ) $imageURL = "./components/com_booklibrary/images/no_book.png";

                            ?><td><img src="<?php echo $imageURL; ?>"
                             alt="<?php echo $row->title; ?>" hspace="2"
                              vspace="2" border="0"
                               style="max-width: none;height:<?php echo $cover_height; ?>px" /></td>
                             <?php
                            } //End Show Covers If
                             ?>
                            <td style="vertical-align:middle">
                                <noscript>Javascript is required to use Book Library <a
                                 href="http://ordasoft.com/Book-Library/booklibrary-versions-feature-comparison.html"
                                 >Book Library - create book library, ebook, book collection  </a>,

                                <a href="http://ordasoft.com/location-map.html"
                                >Book library book sowftware for Joomla</a></noscript>
                                <a href="<?php echo sefRelToAbs($link1); ?>">
                                  <?php echo $row->title; ?></a>
                            </td>
                            <?php if ($show_extra == 1) { ?>
                                <td align="right">
                                    <font class='small'>(<?php echo $row->hits; ?>)</font>
                                </td>
                            <?php } //End Show Extra If ?>
                            </tr>
              <?php } ?>
                </table>
            </td>
        </tr>
    </table>
<?php if ($moduleclass_sfx != '') { ?>
    </div>
<?php } ?>
<div style="text-align: center;"><a href="http://ordasoft.com"
 style="font-size: 10px;">Powered by OrdaSoft!</a></div>
