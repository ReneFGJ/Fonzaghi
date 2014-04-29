<script>
/**
 * @author Willian
 */
function showCustomer(str,verb)
{
var xmlhttp;    
if (str=="")
  {
	  document.getElementById("aval_form").innerHTML="";
	  return;
  }
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
	    document.getElementById("aval_form").innerHTML=xmlhttp.responseText;
    }
  }
 xmlhttp.open("GET","aval_ajax.php?dd0="+str+"&dd1="+verb,true);
 xmlhttp.send();
}
/* Function Item novo Grava */
function aval_grava()
	{
		var xmlhttp; 
		if (str=="")
		  {
			  document.getElementById("aval_form").innerHTML="";
			  return;
		  }
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
			    document.getElementById("aval_form").innerHTML=xmlhttp.responseText;
		    }
		  }
			/* Ajax Inicial */
		var aval_comp = new Array();
		var aval_loja = $("#aval_loja").val();
		var aval_cargo = $("#aval_cargo").val();
		aval_comp[1] = $("#aval_comp[1]").val();
		var str='';
		var verb="grava";
		
		xmlhttp.open("GET","aval_ajax.php?dd0="+str+"&dd1="+verb+"&dd2="+aval_loja+"&dd3="+aval_cargo+"&dd4="+aval_comp,true);		
		 xmlhttp.send();
			}
			
			
</script>