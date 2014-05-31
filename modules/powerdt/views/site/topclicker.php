<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
</head>
<script type="text/javascript">
x = 0; var clickwin; reclinks = new Array(
<?
$linkstr = '';
foreach($links as $link){
	$linkstr .= '"'.$link.'",';
}
echo substr($linkstr, 0, -1);
?>
);

function nextrec() {
	var msg='<h1 style="font-size:40px;color:white;">Fim! =D</h1><br/>Volte amanha!</h2>'
	if (x <= (reclinks.length - 1)) {
		parent.baseFrame.location.href = reclinks[x];
		document.getElementById('Ccount').innerHTML = reclinks[x]+'<br />'+(x+1)+'/'+reclinks.length ;
		x = x + 1;
	} else {
      x = 0;
	}
}
</script>
<body>
<div align="center" class="row4">
<input onclick="nextrec()" type="button" value="GO!" /><br />
Link do Alface: <input type="text" value="http://www.darkthrone.com/recruiter/outside/D5OD8OE6OA0OA3OD8OF1" />
Link da Violeta: <input type="text" value="http://www.darkthrone.com/recruiter/outside/A0OF9OF1OE4OB7OD8OF1" />
<div id="Ccount"></div>
</div>
</body>

</html>
