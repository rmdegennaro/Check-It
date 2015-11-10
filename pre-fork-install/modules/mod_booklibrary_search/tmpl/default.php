<?php
    defined('_JEXEC') or die('Restricted access');
?>

<?php if($moduleclass_sfx!='') {?>
  <div  class="<?php echo $moduleclass_sfx;?>"> <?php }?>
  <div>
      
  <form action="<?php echo sefRelToAbs("index.php?option=com_booklibrary&amp;task=search&amp;catid=0&amp;ItemId=".$Itemid); ?>" method="get" name="mod_booklibsearchForm">
  <table  border="0" cellpadding="4" cellspacing="0" class="basictable" width="100%">
  <tr>
	  <td align="left">
		   <?php echo _BOOKLIBRARY_LABEL_SEARCH_BUTTON ?>:&nbsp;
	  </td>
  </tr>
  <tr>
	  <td align="left">
		  <input class="inputbox" type="text" name="searchtext" size="15" maxlength="20"/>
	  </td>
  </tr>

  <?php if($showAuthor==0){?>
  <tr>
    <td  align="left" nowrap>
    <?php echo _BOOKLIBRARY_SHOW_SEARCH_FOR_AUTORS ?>:&nbsp;<input type="checkbox" name="author" checked="checked">
    </td>
  </tr>
  <?php }elseif($showAuthor==1){ ?>
    <input type="hidden" name="author" value="on">
  <?php }?>  
  
  <?php if($showTitle==0){?>
  <tr>
    <td  align="left" nowrap>
    <?php echo _BOOKLIBRARY_SHOW_SEARCH_FOR_TITLE ?>:&nbsp;<input type="checkbox" name="title" checked="checked">
    </td>
  </tr>
  <?php }elseif($showTitle==1){ ?>
    <input type="hidden" name="title" value="on">
  <?php }?>  
  
  <?php if($showIsbn==0){?>
  <tr>
    <td  align="left" nowrap>
    <?php echo _BOOKLIBRARY_SHOW_SEARCH_FOR_ISBN ?>:&nbsp;<input type="checkbox" name="isbn" checked="checked">
    </td>
  </tr>
  <?php }elseif($showIsbn==1){ ?>
    <input type="hidden" name="isbn" value="on">
  <?php }?>
  <?php if($showBookId==0){?>
  <tr>
    <td  align="left" nowrap>
    <?php echo _BOOKLIBRARY_SHOW_SEARCH_FOR_BOOK_ID ?>:&nbsp;<input type="checkbox" name="bookid" checked="checked">
    </td>
  </tr>
  <?php }elseif($showBookId==1){ ?>
    <input type="hidden" name="bookid" value="on">
  <?php }?>
  <?php if($showDescription==0){?>
  <tr>
    <td  align="left" nowrap>
    <?php echo _BOOKLIBRARY_SHOW_SEARCH_FOR_DESCRIPTION ?>:&nbsp;<input type="checkbox" name="description" checked="checked">
    </td>
  </tr>
  <?php }elseif($showDescription==1){ ?>
    <input type="hidden" name="description" value="on">
  <?php }?>  
        
  <?php if($showPublisher==0){?>
  <tr>
    <td  align="left" nowrap>
    <?php echo _BOOKLIBRARY_SHOW_SEARCH_FOR_PUBLISHER ?>:&nbsp;<input type="checkbox" name="publisher" checked="checked">
    </td>
  </tr>
  <?php }elseif($showPublisher==1){ ?>
    <input type="hidden" name="publisher" value="on">
  <?php }?>  
  <?php if($showPrice==0):?>
  <tr>
    <td colspan="1" >
        <?php echo _BOOKLIBRARY_SHOW_SEARCH_PRICE_FROM; ?>:&nbsp;
        <input type="text" name="pricefrom" size="6"/>&nbsp;
    </td>
  </tr>
  <tr>
    <td>
        <?php echo _BOOKLIBRARY_SHOW_SEARCH_PRICE_TO; ?>:&nbsp;
        <input type="text" name="priceto" size="6"/>
    </td>
  </tr>
  <?php endif;?>
	<tr>
		<td align="left">
			<?php echo _BOOKLIBRARY_HEADER_CATEGORY ?>:&nbsp;
		</td>
	</tr>
	<tr>
	  <td align="left">
			  <?php echo $clist; ?>
	  </td>
  </tr>
	<tr>
		<td align="left" colspan="2" style="margin: 5px;padding: 5px;">
		<div style="float:left;margin-right: 5px"><input type="submit" value="<?php echo _BOOKLIBRARY_LABEL_SEARCH_BUTTON; ?>" class="button" /></div>
		<noscript>Javascript is required to use Book Library <a href="http://ordasoft.com/Book-Library/booklibrary-versions-feature-comparison.html">Book Library - create book library, ebook, book collection  </a>, 

<a href="http://ordasoft.com/location-map.html">Book library book sowftware for Joomla</a></noscript>

			<input type="hidden" name="option" value="com_booklibrary">
			<input type="hidden" name="task" value="search">
			<input type="hidden" name="Itemid" value="<?php echo $Itemid ?>">
		</td>
	</tr>
		    </table>
		</form>
</div>
<?php if($moduleclass_sfx!='') {?>
   </div> <?php }?>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>
