  function ltrim(str, chars) {
  	chars = chars || "\\s";
  	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
  }
  function rtrim(str, chars) {
  	chars = chars || "\\s";
  	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
  }
  function dedupe_list(sel)
  {
  	var count = 0;
  	var mainlist = document.getElementById(sel).value;
  	mainlist = mainlist.replace(/\r/gi, "");
  	mainlist = mainlist.replace(/\n+/gi, "");
    mainlist = ltrim(mainlist,",");
  	var listvalues = new Array();
  	var newlist = new Array();
  	listvalues = mainlist.split(",");
  	var hash = new Object();
  	for (var i=0; i<listvalues.length; i++)
  	{
  		if (hash[listvalues[i].toLowerCase()] != 1)
  		{
        if(listvalues[i] != 'No User'){
  			newlist = newlist.concat(listvalues[i]);
        hash[listvalues[i].toLowerCase()] = 1
  		  }
      }
  		else { count++; }
  	}
  	document.getElementById(sel).value = newlist.join(",");
  }
  function addChr(sel){
    var strlist = document.getElementById(sel).value;
    if(strlist.length == 0) return false;
    strlist = rtrim(strlist,",");
    document.getElementById(sel).value = strlist;
  }
  function appendList(){
    document.adminForm.jform_config_moderator_list.value+=(',')+document.adminForm.jform_config_moderator_select_name.value;
    dedupe_list('document.adminForm.jform_config_moderator_list');
  }