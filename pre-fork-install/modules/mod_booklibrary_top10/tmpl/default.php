<?php
defined('_JEXEC') or die('Restricted access');
?>

<?php if ($moduleclass_sfx != '') { ?>
    <div  class="<?php echo $moduleclass_sfx; ?>"> <?php } ?>
    <table cellpadding="0" cellspacing="0" class="basictable" width="100%">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" class="moduletable" width="100%">
                    <?php
                    foreach ($rows as $row) {
                        $link1 = "index.php?option=com_booklibrary&amp;task=view&amp;id=" . $row->id . "&amp;Itemid=" . $ItemId_tmp . "&amp;catid=" . $row->catid;
                        ?><noscript>Javascript is required to use Book Library <a href="http://ordasoft.com/Book-Library/booklibrary-versions-feature-comparison.html">Book Library - create book library, ebook, book collection  </a>, 

                        <a href="http://ordasoft.com/location-map.html">Book library book sowftware for Joomla</a></noscript>
                        <tr>
                            <td>
                                <a href="<?php echo sefRelToAbs($link1); ?>"><?php echo $row->title; ?></a>
                            </td>
                            <td align="right">
                                <font class='small'>(<?php echo $row->hits; ?>)</font>
                            </td>
                        </tr>
<?php } ?>
                </table>
            </td>
        </tr>
    </table>
<?php if ($moduleclass_sfx != '') { ?>
    </div> <?php } ?>

<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>
