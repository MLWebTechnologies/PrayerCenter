function getTranslator(){
    var appId = '';   //Enter your application ID here
    var text = encodeURIComponent(document.getElementById("pcrequest").innerHTML);
    var languageTo = document.getElementById("tol").value;
    if(languageTo == '') {alert(langtranmsg);return false;}
    window.mycallback = function(response) { document.getElementById("pcrequest").innerHTML=response;}
    var s = document.createElement("script");
    s.src = "http://api.microsofttranslator.com/V2/Ajax.svc/Translate?oncomplete=mycallback&appId=" + appId +"&to=" + languageTo + "&text=" + text;
    document.getElementsByTagName("head")[0].appendChild(s);
}
function getTranslator2(reqid,site){
   var languageTo = document.getElementById("tol").value;
   if(languageTo == '') {alert(langtranmsg);return false;}
   var requesturl = site + '/index.php?option=com_prayercenter&task=view_request&id=' + reqid + '&pop=1&tmpl=component';
   var requrl = encodeURIComponent(requesturl);
   var url = 'http://www.microsofttranslator.com/bv.aspx?from=&to=' + languageTo + '&a=' + requrl;
   window.open(url,'RequestTranslation','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=980,height=600,directories=no,location=no');
   document.getElementById("tol").value='';
   return false;
}
function showLanguageDropDown(wsel,se){
  var frl=document.getElementById(wsel);
  if (frl.length>0) return;
  var a = {
    'Translate request' : '',
    'Arabic' :  'ar',
    'Bulgarian' : 'bg',
    'Catalan' : 'ca',
    'Chinese Simplified' : 'zh-CHS',
    'Chinese Traditional' : 'zh-CHT',
    'Czech' : 'cs',
    'Danish' : 'da',
    'Dutch' : 'nl',
    'English' : 'en',
    'Estonian' : 'et',
    'Finnish' : 'fi',
    'French' : 'fr',
    'German' : 'de',
    'Greek' : 'el',
    'Haitian Creole' : 'ht',
    'Hebrew' : 'he',
    'Hindi' : 'hi',
    'Hungarian' : 'hu',
    'Indonesian' : 'id',
    'Italian' : 'it',
    'Japanese' : 'ja',
    'Korean' : 'ko',
    'Latvian' : 'lv',
    'Lithuanian' : 'lt',
    'Norwegian' : 'no',
    'Polish' : 'pl',
    'Portuguese' : 'pt',
    'Romanian' : 'ro',
    'Russian' : 'ru',
    'Slovak' : 'sk',
    'Slovenian' : 'sl',
    'Spanish' : 'es',
    'Swedish' : 'sv',
    'Thai' : 'th',
    'Turkish' : 'tr',
    'Ukrainian' : 'uk',
    'Vietnamese' : 'vi' 
  };
  var r=0;
  for(var key in a){
    var lan= key.toLowerCase();
    lan = capitalize(lan);
    var lcode=a[key];
    frl.options[r]=new Option (lan,lcode,false,false);
    r++;
  }
}