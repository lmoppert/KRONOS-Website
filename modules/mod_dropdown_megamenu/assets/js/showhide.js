function shMegamenu(menuid) {
	var id = menuid;
	alert = id;
  if(document.getElementById(menuid).style.display == 'block' ) {
        document.getElementById(menuid).style.display = 'none';
  } else {
        document.getElementById(menuid).style.display = ' block';
  }
}