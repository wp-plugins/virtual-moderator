<?php echo '<script type="text/javascript">
	objImage = new Image();
	objImage.src="'.$vms['waitIcon'].'";
			function update(matchClass,content){
    var elems = document.getElementsByTagName("*"),i;
    for (i in elems)
        {
        if((" "+elems[i].className+" ").indexOf(" "+matchClass+" ") > -1)
            {
            elems[i].innerHTML = content;
            }
        }
    }
function loadXMLDoc(post_id, key, action)
{
	var text = "<div class=\'waitIcon\'></div><div class=\'waitText flagText\'><div class=\'waitText-left\'></div><div class=\'waitText-text\'>'.$vms['waitText'].'</div><div class=\'waitText-right\'></div>";
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    	update("flag-arya-"+post_id, xmlhttp.responseText); 
    }
  else
	{
			update("flag-arya-"+post_id, text); 
	}
  }
xmlhttp.open("GET","'.$vmpath.'processor.php?action="+action+"&post_id="+post_id+"&key="+key,true);
xmlhttp.send();
}
</script>';?>