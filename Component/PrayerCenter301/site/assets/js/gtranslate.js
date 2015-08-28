function getTranslator(srclang) { 
  var googlekey = '';      //Enter your application key here
  var text = encodeURIComponent(document.getElementById("pcrequest").innerHTML);
  var langstrto = document.getElementById("tol").value;
  if(langstrto == '') {alert(langtranmsg);return false;}
  var el = document.createElement("script"); 
  el.src = 'https://www.googleapis.com/language/translate/v2'; 
  el.src += '?callback=translateReq'; 
  el.src += '&key=' + googlekey; 
  el.src += '&q=' + escape (text); 
  if(srclang == '') var srclang = "auto";
  el.src += '&source=' + srclang + '&target=' + language; 
  document.getElementsByTagName('head')[0].appendChild (el); 
} 
function getTranslator2(langstrfrom,reqid,site){
   var langstrto = document.getElementById("tol").value;
   if(langstrto == '') {alert(langtranmsg);return false;}
   var langstr = langstrfrom + "|" + langstrto;
   var requesturl = site + 'index.php?option=com_prayercenter&task=view_request&id=' + reqid + '&pop=1&tmpl=component';
   var requrl = encodeURIComponent(requesturl);
   var url = 'http://translate.google.com/translate?langpair=' + langstr + '&u=' + requrl;
   window.open(url,'RequestTranslation','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=480,directories=no,location=no');
   document.getElementById("tol").value='';
   return false;
}
function translateReq(response) { 
  if (response.error) alert (response.error.message); 
  else document.getElementById("pcrequest").innerHTML = response.data.translations[0].translatedText; 
} 
function showLanguageDropDown(wsel,se){
  var frl=document.getElementById(wsel);
  if (frl.length>0) return;
  var a = {
    'Translate request' : '',
    'Afrikaans' : 'af',
    'Albanian' : 'sq',
    'Arabic' :  'ar',
    'Armenian' : 'hy',
    'Azerbaijani' : 'az',
    'Basque' : 'eu',
    'Belarusian' : 'be',
    'Bengali' : 'bn',
    'Bulgarian' : 'bg',
    'Catalan' : 'ca',
    'Chinese Simplified' : 'zh-CN',
    'Chinese Traditional' : 'zh-TW',
    'Croatian' : 'hr',
    'Czech' : 'cs',
    'Danish' : 'da',
    'Dutch' : 'nl',
    'English' : 'en',
    'Estonian' : 'et',
    'Filipino' : 'tl',
    'Finnish' : 'fi',
    'French' : 'fr',
    'Galician' : 'gl',
    'Georgian' : 'ka',
    'German' : 'de',
    'Greek' : 'el',
    'Gujarati' : 'gu',
    'Haitian Creole' : 'ht',
    'Hebrew' : 'he',
    'Hindi' : 'hi',
    'Hungarian' : 'hu',
    'Icelandic' : 'is',
    'Indonesian' : 'id',
    'Irish' : 'ga',
    'Italian' : 'it',
    'Japanese' : 'ja',
    'Kannada' : 'kn',
    'Korean' : 'ko',
    'Latin' : 'la',
    'Latvian' : 'lv',
    'Lithuanian' : 'lt',
    'Macedonian' : 'mk',
    'Malay' : 'ms',
    'Maltese' : 'mt',
    'Norwegian' : 'no',
    'Persian' : 'fa',
    'Polish' : 'pl',
    'Portuguese' : 'pt',
    'Romanian' : 'ro',
    'Russian' : 'ru',
    'Serbian' : 'sr',
    'Slovak' : 'sk',
    'Slovenian' : 'sl',
    'Spanish' : 'es',
    'Swahili' : 'sw',
    'Swedish' : 'sv',
    'Tamil' : 'ta',
    'Telugu' : 'te',
    'Thai' : 'th',
    'Turkish' : 'tr',
    'Ukrainian' : 'uk',
    'Urdu' : 'ur',
    'Vietnamese' : 'vi',
    'Welsh' : 'cy',
    'Yiddish' : 'yi' 
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