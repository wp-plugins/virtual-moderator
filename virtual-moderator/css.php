<?php
function default_css(){
$vmpath=WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
return "//////////////////////////////////Start Here/////////////////////////////
.flag, .unflag {
	border:none;
	background-color:transparent;
	float:left;
}

.flag {
	background-image:url({flag image});
	background-repeat:no-repeat;
	width:30px;
	height:40px;
	border:none;
	background-color:transparent;
	float:left;
        cursor:pointer;
}

.unflag {
	background-image:url({unflag image});
	background-repeat:no-repeat;
	width:30px;
	height:40px;
	border:none;
	background-color:transparent;
	float:left;
        cursor:pointer;
}

.waitIcon{
	background-image:url({wait image});
	background-repeat:no-repeat;
	width:32px;
	height:40px;
	border:none;
	background-color:transparent;
	float:left;
}

.flagText {
	float:left;
}

.flag-arya {
	display:inline;
	float:left;
}

.flag-button-text, .unflag-button-text {
	display:none;
}

.unflagText-left, .waitText-left, .flagText-left {
	background:url(".$vmpath."images/text-side.png);
	width:12px;
	height:30px;
	float:left;
}
.unflagText-right, .waitText-right, .flagText-right {
	background:url(".$vmpath."images/text-side.png);
	background-repeat:no-repeat;
	background-position:-15px;
	height:30px;
	width:6px;
	float:left;
}
.unflagText-text, .flagText-text, .waitText-text {
	display:inline-block;
	background-image:url(".$vmpath."images/text-center.png);
	background-repeat:repeat-x;
	height:30px;
	background-position:center top;
        color: green;
        float: left;
        font-weight: bold;
}
.flag-arya-top{
	float:right;
}";};

function style(){
echo '<style type="text/css">';
global $vms;
echo str_replace(array('{flag image}','{unflag image}', '{wait image}'), array($vms['postFlagIcon'], $vms['postUnflagIcon'], $vms['waitIcon']), $vms['css']);
echo '</style>';
};