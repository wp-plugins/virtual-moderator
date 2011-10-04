<?php
		$vms=get_option('vms');
		global $wpdb;
		if($display=='flag'||$display=='moderation'){
switch($_POST['settings']){
case "flag":
	$vms['flags2removePost']=$_POST['flags2removePost'];
	$vms['emailAdmin']=$_POST['emailAdmin'];
	$vms['emailAdminText']=$_POST['emailAdminText'];
	$vms['emailAuthor']=$_POST['emailAuthor'];
	$vms['emailAuthorText']=$_POST['emailAuthorText'];
	$vms['postFlagIcon']=$_POST['postFlagIcon'];
	$vms['displayText']=$_POST['displayText'];
	$vms['waitIcon']=$_POST['waitIcon'];
	$vms['waitText']=$_POST['waitText'];
	$vms['postUnflagIcon']=$_POST['postUnflagIcon'];
	$vms['unflagText']=$_POST['unflagText'];
	$vms['addContentTop']=$_POST['addContentTop'];
	$vms['addContentBottom']=$_POST['addContentBottom'];
	$vms['addExcerptTop']=$_POST['addExcerptTop'];
	$vms['addExcerptBottom']=$_POST['addExcerptBottom'];
	$vms['traceUser']=$_POST['traceUser'];
	$vms['traceIP']=$_POST['traceIP'];
	$vms['traceCookie']=$_POST['traceCookie'];
	$vms['noFlagAdmin']=$_POST['noFlagAdmin'];
	$vms['noFlagEditor']=$_POST['noFlagEditor'];
	$vms['canFlag']=$_POST['canFlag'];
	$vms['result']=$_POST['result'];
	$vms['safeUsers']=$_POST['safeUsers'];
	$vms['excCats']=$_POST['excCats'];
	$vms['css']=$_POST['css'];
	break;
case "moderation":
	$vms['canUnflag']=$_POST['canUnflag'];
	$vms['editDieText']=$_POST['editDieText'];
	break;
};
			update_option('vms', $vms);};
			
			if($_POST['remove-flag-data']){
				$table=$wpdb->prefix."vmdata";
				$wpdb->query("DROP TABLE IF EXISTS $table");
				$wpdb->query(	"
				DELETE FROM $wpdb->postmeta
				WHERE meta_key = '_flags'
				");
			};
			if($_POST['remove-vms'])delete_option('vms');
			
			if($_POST['uninstall']){
			$deactivate_url = 'plugins.php?action=deactivate&amp;plugin='.plugin_basename(dirname(__FILE__)).'/vmoderator.php';
			if(function_exists('wp_nonce_url')) { 
				$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_'.plugin_basename(dirname(__FILE__)).'/vmoderator.php');
			}
				};
?>
<?php if($display!='documentation'){?>
<form method="post" enctype="multipart/form-data">
<?php if($display=='flag'){?>
<h1 class="vms-title">Virtual Moderator Settings</h1>
<div class="vms-setting-options" id="vms-flag-setting-options">
<label for="flags2removePost">Flags requered to moderate a post</label>
<input type="text" name="flags2removePost" id="flags2removePost" value="<?php echo $vms['flags2removePost'];?>" />
<small> Type 0 to disable </small><br /><br />
<div>
<small style="float:right; width:40%; margin-top:50px">You can send a notification to admin and post author when a post is flagged specified times. Use {title}, {excerpt}, {id}, {url} tags to display them. Email will be plain text not html.</small>

<label for="emailAdmin">Send notification to admin</label>
<input type="checkbox" name="emailAdmin" id="emailAdmin" <?php if($vms['emailAdmin'])echo "checked";?> />
<label for="emailAdminText">Text</label>
<textarea name="emailAdminText" id="emailAdminText"><?php echo $vms['emailAdminText'];?></textarea>
<br /><br />


<label for="emailAuthor">Send notification post author</label>
<input type="checkbox" name="emailAuthor" id="emailAuthor" <?php if($vms['emailAuthor'])echo "checked";?> />
<label for="emailAuthorText">Text</label>
<textarea name="emailAuthorText" id="emailAuthorText"><?php echo $vms['emailAuthorText'];?></textarea><br /><br />

</div>
<label for="postFlagIcon">Post Flag Icon</label>
<input size="36" type="text" class="upload" value="<?php echo $vms['postFlagIcon'];?>" name="postFlagIcon" id="postFlagIcon" />
<input class="upload-button" type="button" value="Upload Image" />
<small>will replace the default icon</small><br /><br />

<label for="displayText">Display Text with Flag</label>
<input type="text" value="<?php echo $vms['displayText'];?>" name="displayText" id="displayText"><br />
<small>{flagged} = Number of flags done, {to go} = Flags before moderating the post and {total} = Total flags needed to moderate a post</small><br /><br />

<label for="waitIcon">Wait Icon</label>
<input size="36" type="text" class="upload" value="<?php echo $vms['waitIcon'];?>" name="waitIcon" id="waitIcon" />
<input class="upload-button" type="button" value="Upload Image" />
<small>set the wait animation</small><br /><br />

<label for="waitText">Wait Text</label>
<input type="text" value="<?php echo $vms['waitText'];?>" name="waitText" id="waitText"><br />
<small>text to display after flag click with wait animation.</small><br /><br />

<label for="postUnflagIcon">Post Unflag Icon</label>
<input size="36" type="text" class="upload" value="<?php echo $vms['postUnflagIcon'];?>" name="postUnflagIcon" id="postUnflagIcon" />
<input class="upload-button" type="button" value="Upload Image" />
<small>This will be displayed after someone has flagged</small><br /><br />

<label for="unflagText">Display Text with Unflag</label>
<input type="text" value="<?php echo $vms['unflagText'];?>" name="unflagText" id="unflagText">
<small>
You can use {flagged}, {to go} and {total} here too</small><br /><br />

<label for="addContentTop">Add to content top</label>
<input type="checkbox" name="addContentTop" id="addContentTop" <?php if($vms['addContentTop'])echo "checked";?>>
<small>automatically add flag option on post content top</small><br /><br />

<label for="addContentBottom">Add to content bottom</label>
<input type="checkbox" name="addContentBottom" id="addContentBottom" <?php if($vms['addContentBottom'])echo "checked";?>>
<small>automatically add flag option on post content bottom</small><br /><br />

<label for="addExcerptTop">Add to excerpt top</label>
<input type="checkbox" name="addExcerptTop" id="addExcerptTop" <?php if($vms['addExcerptTop'])echo "checked";?>>
<small>automatically add flag option on post excerpt top</small><br /><br />

<label for="addExcerptBottom">Add to excerpt bottom</label>
<input type="checkbox" name="addExcerptBottom" id="addExcerptBottom" <?php if($vms['addExcerptBottom'])echo "checked";?>>
<small>automatically add flag option on post excerpt bottom</small><br /><br />

<label for="traceUser">Record Users</label>
<input type="checkbox" name="traceUser" id="traceUser" <?php if($vms['traceUser'])echo "checked";?>>
<small>Prevent a registerd user from flagging a post multiple times.</small><br /><br />

<label for="traceIP">Trace by IP</label>
<input type="checkbox" name="traceIP" id="traceIP" <?php if($vms['traceIP'])echo "checked";?>>
<small>Store IP address to prevent multy flag</small><br /><br />

<label for="traceCookie">Trace by Cookie</label>
<input type="checkbox" name="traceCookie" id="traceCookie" <?php if($vms['traceCookie'])echo "checked";?>>
<small>Store Cookie to prevent multy flag</small><br /><br />

<label for="noFlagAdmin">Don't Flag admin's posts</label>
<input type="checkbox" name="noFlagAdmin" id="noFlagAdmin" <?php if($vms['noFlagAdmin'])echo "checked";?>>
<small>Check to disable flag on admin's posts</small><br /><br />

<label for="noFlagEditor">Don't Flag editors's posts</label>
<input type="checkbox" name="noFlagEditor" id="noFlagEditor" <?php if($vms['noFlagEditor'])echo "checked";?>>
<small>Check to disable flag on editor's posts</small><br /><br />

<label for="canFlag">Minimum role to flag</label>
<select name="canFlag" id="canFlag">
<optgroup>
<option value="10" selected="selected" <?php if($vms['canFlag']==10)echo "selected";?>>Administrator</option>
<option value="7" <?php if($vms['canFlag']==7)echo "selected";?>>Editor</option>
<option value="2" <?php if($vms['canFlag']==2)echo "selected";?>>Author</option>
<option value="1" <?php if($vms['canFlag']==1)echo "selected";?>>Contributor</option>
<option value="0" <?php if($vms['canFlag']==0)echo "selected";?>>Subscriber</option>
<option value="-1" <?php if($vms['canFlag']==-1)echo "selected";?>>Anyone</option>
</optgroup>
</select><br /><br />

<label for="result">Flag result</label>
<select name="result" id="result">
<optgroup>
<option value="draft" <?php if($vms['result']=="draft")echo "selected";?>>Draft</option>
<option value="pending" selected="selected" <?php if($vms['result']=="pending")echo "selected";?>>Pending Moderation</option>
<option value="moderated" <?php if($vms['result']=="moderated")echo "selected";?>>Moderated(Recomanded)</option>
<option value="trash" <?php if($vms['result']=="trash")echo "selected";?>>Trash</option>
<option value="remove" <?php if($vms['result']=="remove")echo "selected";?>>Remove Permanently</option>
<option value="nothing" <?php if($vms['result']=="nothing")echo "selected";?>>Do nothing(only notify)</option>
</optgroup>
</select>
<br /><br />

<label for="safeUsers">Safe users</label>
<input name="safeUsers" type="text" id="safeUsers" value="<?php echo $vms['safeUsers'];?>" />
<small>Add user id seperated by comma. Flag option will be disebled on there posts.</small><br /><br />

<label for="excCats">Exclude Categories</label>
<input name="excCats" type="text" id="excCats" value="<?php echo $vms['excCats'];?>" />
<small>Add category ids seperated by comma. Flag option will be disebled in these category posts.</small><br /><br />


<label for="css" style="float:left">CSS</label><br />
<textarea name="css" rows="10" style="width:80%" id="css"><?php echo $vms['css'];?></textarea><br />
<small>You can change the css here. Be sure to use {flag image} to display the flag image, {unflage image} to display unflag image and {wait image} to display the wait animation.</small><br /><br />

</div>
<button type="submit" class="button-primary vms-save-settings">Save</button>
<input type="hidden" name="settings" value="flag" />
<?php } elseif($display=='moderation'){
	$vms=get_option('vms');?>
<h1 class="vms-title">vModerator Moderation Settings</h1>
<h3>This settings are about the custom status <i>"Moderated"</i></h3>
<div class="vms-setting-options" id="vms-moderation-setting-options">

<label for="canUnflag">Minimum role to republish</label>
<select name="canUnflag" id="canUnflag">
<option value="10" <?php if($vms['canUnflag']>=8)echo "selected";?>>Administrator</option>
<option value="7" <?php if($vms['canUnflag']>2&&$vms['canUnflag']<8)echo "selected";?>>Editor</option>
<option value="2" <?php if($vms['canUnflag']>1&&$vms['canUnflag']<7)echo "selected";?>>Author</option>
</select>
<small>Minimum role to republish a post.</small><br /><br />

<label for="editDieText">Error text</label>
<textarea name="editDieText" id="editDieText"><?php echo $vms['editDieText'];?></textarea>
<small>When an unauthorized user try to publish a moderated post.</small><br /><br />
</div>
<button type="submit" class="button-primary vms-save-settings">Save</button>
<input type="hidden" name="settings" value="moderation" />

<?php } elseif ($display=='uninstallation'){?>
<h1>Uninstall Virtual Moderator</h1>
<?php if(!$_POST['uninstall']&&!$_POST['remove-flag-data']&&!$_POST['remove-vms']){?>
<h3>This will deactivate the plugin</h3>
<div class="vms-settings" id="vms-uninstallation">
<ul style="list-style-type:none">
<li><input type="checkbox" name="remove-flag-data" id="remove-flag-data" />
<label for="remove-flag-data">Remove flag database table</label> <small>(All flag information will be removed)</small></li>
<li><input type="checkbox" name="remove-vms" id="remove-vms" /><label for="remove-vms" >Remove <i>Virtual Moderator</i> settings</label></li></ul>
<input type="hidden" name="
uninstall" value="yes" />
</div>
<button type="submit" class="button-primary vms-save-settings">Uninstall</button>
<?php }elseif($_POST['uninstall']){?>
<p>Click finish to deactivate the plugin.
<a class="button-primary vms-save-settings" href="<?php echo $deactivate_url;?>">Finish</a>
<?php };?>
<?php };?>
</form>

<?php } else{?>

<div class="vms-settings" id="vms-documentation">
<h1><span style="font-family: Arial,sans-serif;">Documentation</span></h1>
<span style="font-family: Times New Roman,serif;">This is to demonstrate what you can do with <em>Virtual Moderator</em>. There are many option in the settings to use <em>Virtual Moderator</em> to suite your needs. But you can do lots more with it. Here you will know some features which can be used by developers or if you can do template modifications and css you can use this features. So, lets start.</span>
<h2><em><span style="font-family: Arial,sans-serif;">The function:</span></em></h2>
<span style="font-family: Times New Roman,serif;"><em><span style="color: #993300; background-color: #ffff00;">vmoderator($class);</span></em><span style="color: #000000;"> use this function to add the flag area where you want. In the settings page we have four options to add the flag area automatically to the content-top, content-bottom, excerpt-top and excerpt-bottom. But using them are not recommended as they have some limitations. Using the above function is recommended. The </span><span style="color: #804c19;">$class</span><span style="color: #000000;"> variable contains a class, multiple classes separated by space or an array of class. It can be left empty. </span></span>
<br  />
<span style="color: #800000;"><span style="font-family: Times New Roman,serif;"><span style="color: #000000;"><em><strong>Example:</strong></em></span><em><span style="color: #993300; background-color: #ffff00;"> if(function_exists('vmoderator'))echo vmoderator('contant top');</span></em></span></span>

<br  /><span style="font-family: Times New Roman,serif;"><span style="color: #000000;"><strong>Note: </strong></span><span style="color: #000000;">The <em><span style="color: #993300; background-color: #ffff00;">vmoderator();</span></em> function must be echoed.</span></span>
<h2><em><span style="color: #000000;"><span style="font-family: Arial,sans-serif;"><strong>Css:</strong></span></span></em></h2>
<span style="color: #800000;"><span style="font-family: Times New Roman,serif;"><span style="color: #000000;"><em>Virtual Moderator</em></span><span style="color: #000000;"> is css friendly. There are lots of opportunity to apply your css skills. For example you can display a post in different colors if the post is flagged many times to tell the visitor that following the post is not safe. Each post will have a css class with its number of flags if the theme supports dynamic css(most of them do). Posts without any flag will have a class no-flag, posts with one flag will have class 1-flag and others will have the class x-flags (while x will be replaced by the corresponding number; </span><strong><span style="color: #000000;"><em>Example:</em></span></strong><span style="color: #000000;"> 7-flags). So use the classes to do what you want. The limit is your imagination. Also you can modify the whole css design to change whatever you want because there is an option in the settings to modify css.</span></span></span>

<span style="color: #800000;"><span style="font-family: Times New Roman,serif;"><span style="color: #000000;">More features will be coming soon. Send your suggestion, feedback or bug report on </span><strong><a href="mailto:vm@eadnan.com">vm@eadnan.com</a></strong><span style="color: #000000;">. </span></span></span>
</div>
<?php };?>
