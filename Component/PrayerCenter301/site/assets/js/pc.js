	function selectAll(x) {
	  for(var i=0,l=x.form.length; i<l; i++)
	    if(x.form[i].type == 'checkbox' && x.form[i].name != 'markall')
		x.form[i].checked=x.form[i].checked?false:true
  }
  function uncheckPrivRadio() {
   var choice = document.adminForm.psend;
    if ( choice.checked = true ) 
     choice.checked = false; 
  }
  function uncheckPraiseRadio() {
   var choice = document.adminForm.display;
    if ( choice.checked = true ) 
     choice.checked = false; 
  }
  function sortingList(val) {
    if(val > 0){
      var form = document.viewlist;
      document.viewlist.sort.value = val;
      form.submit();
    } else {
      return false;
    }
  }
  function validateMod() {
    for (i=1;i<document.adminForm.elements.length;i++){
    if( document.adminForm.elements[i].type=="checkbox" && document.adminForm.elements[i].checked == true )
     {
     	if(confirm(confirm_act)){
      	var form = document.adminForm;
        form.submit();
        return true;
    	}
    	else {
    		return false;
    	}
     }
    }
    alert(choose_cb);
    return false;
   }
  function validateEdit() {
   	if(confirm(confirm_act)){
    	var form = document.adminForm;
      form.submit();
      return true;
  	}
  	else {
  		return false;
  	}
   }
   function validateSC(cap,livesite,form,action) {
    if( cap == 1 && form.security_code.value == "" ){
      alert(enter_sec_code);
      form.security_code.value = '';
      form.security_code.focus();
      document.getElementById('security_code').className = "inputbox invalid";
      var label = PCgetLabel('security_code',form);
      label.className = "invalid"; 
      return false;
    } else if( cap == 1 && form.security_code.value != "" ){
      document.getElementById('security_code').className = "inputbox";
      var label = PCgetLabel('security_code',form);
      label.className = ""; 
      if(!checkPCCapCode(form,form.security_code.value,livesite,action)){
        return false;
      }
    }
    if( cap == 2 && form.password3.value == "" ){
      alert(enter_sec_code);
      form.password3.value = '';
      form.password3.focus();
      document.getElementById('password3').className = "inputbox invalid";
      var label = PCgetLabel('password3',form);
      label.className = "invalid"; 
      return false;
    } else if( cap == 2 && form.password3.value != "" ){
      document.getElementById('password3').className = "inputbox";
      var label = PCgetLabel('password3',form);
      label.className = ""; 
    }
    if( cap == 4 && form.captcha.value == "" ){
      alert(enter_sec_code);
      form.captcha.value = '';
      form.captcha.focus();
      document.getElementById('captcha').className = "inputbox invalid";
      var label = PCgetLabel('captcha',form);
      label.className = "invalid"; 
      return false;
    } else if( cap == 4 && form.captcha.value != "" ){
      document.getElementById('captcha').className = "inputbox";
      var label = PCgetLabel('captcha',form);
      label.className = ""; 
    } else if( cap == 6 && form.recaptcha_response_field.value == "" ){
      alert(enter_sec_code);
      form.recaptcha_response_field.value = '';
      form.recaptcha_response_field.focus();
      return false;
    } else if( cap == 6 && form.recaptcha_response_field.value != "" ){
      if(!checkReCapCode(form,form.recaptcha_response_field.value,livesite)){
        return false;
      }
    } else if( cap == 7 ){
      var jcap = document.getElementById('jcap').value;
      if(jcap == 'crosscheck' && !checkCCCode(form,document.getElementById('user_code').value,0)){
        return false;
      }
      if(!checkJDefaultCapCode(form,livesite)){
        return false;
      }
    }
    return true;
   }
   function validateNew(cap,editor,livesite,form,action)
    {
    if(action == 'pccomp') PCchgClassName(editor,true,false,false,form);
    if( form.valreq.value == "" ){
      alert(enter_req);
      if(editor == 'none'){
        form.newrequest.focus();
      }
      if(editor == 'tinymce' || editor == 'jce') {
        tinyMCE.execCommand('mceFocus',false,'newrequest');
      }
      if(editor == 'fckeditor') {
        var fck = FCKeditorAPI.GetInstance('newrequest') ;
        fck.Focus();
      }
      if(editor == 'ckeditor'){
        var ckeditor = CKEDITOR.instances.newrequest;
        ckeditor.focus();
      }
      if(editor == 'tmedit'){
        editornewrequest.focusEditor();
      }
      return false;
    }
    if(editor == 'tmedit') form.newrequest.value=form.valreq.value;
    if(cap == 1 || cap == 2 || cap == 4 || cap == 6 || cap == 7){
      if(validateSC(cap,livesite,form,action)) {
        form.submit();
      }
    return false;
    }
    if(cap == 3){
      var ucode = form.user_code.value;
      if(checkCCCode(form,ucode)) form.submit();
      return false;
    }
    form.submit();
   }
   function validateNewE(cap,editor,livesite,form,action) {
    var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if(action == 'pccomp') PCchgClassName(editor,true,false,true,form);
    if( form.newemail.value == "" ){
      alert(confirm_enter_email);
      form.newemail.value = '';
      form.newemail.focus();
      return false;
    }
    if( !form.newemail.value.match(emailExp)){
      alert('Invalid Email Address');
      form.newemail.value = '';
      form.newemail.focus();
      return false;
    }
    if( form.valreq.value == "" ){
      alert(enter_req);
      if(editor == 'none'){
        form.newrequest.focus();
      }
      if(editor == 'tinymce' || editor == 'jce') {
        tinyMCE.execCommand('mceFocus',false,'newrequest');
      }
      if(editor == 'fckeditor') {
        var fck = FCKeditorAPI.GetInstance('newrequest') ;
        fck.Focus();
      }
      if(editor == 'ckeditor'){
        var ckeditor = CKEDITOR.instances.newrequest;
        ckeditor.focus();
      }
      if(editor == 'tmedit'){
        editornewrequest.focusEditor();
      }
    return false;
    }
    if(cap == 1 || cap == 2 || cap == 4 || cap == 6 || cap == 7){
      if(validateSC(cap,livesite,form,action)) form.submit();
      return false;
    }
    if(cap == 3){
      var ucode = form.user_code.value;
      if(checkCCCode(form,ucode)) form.submit();
      return false;
    }
    form.submit();
   }
   function validateSub(cap,livesite,form,action) {
    var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if(action == 'pccomp') PCchgClassName(null,false,true,false,form);
    if( form.newsubscribe.value == "" ){
      alert(enter_email);
      form.newsubscribe.value = '';
      form.newsubscribe.focus();
      return false;
    }
    if( !form.newsubscribe.value.match(emailExp)){
      alert('Invalid Email Address');
      form.newsubscribe.value = '';
      form.newsubscribe.focus();
      return false;
    }
    if(cap == 1 || cap == 2 || cap == 4 || cap == 6 || cap == 7){
      if(validateSC(cap,livesite,form,action)) form.submit();
      return false;
    }
    if(cap == 3){
      var ucode = form.user_code.value;
      if(checkCode(form,ucode)) form.submit();
      return false;
    }
    form.submit();
   }
  function PCgetLabel(el,form)
   {
   var labels = form.getElementsByTagName("label"),i;
   for(i=0; i<labels.length; i++){
    if(labels[i].htmlFor == el){
      return labels[i];
      }
    }
   return false;
   }
   function PCchgClassName(editor,ereq,esub,ereqa,form)
    {
    if( esub ){
      var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
      if( form.newsubscribe.value == "" || !form.newsubscribe.value.match(emailExp) ){
        document.getElementById('newsubaddr').className = "inputbox invalid";
        var label = PCgetLabel('newsubaddr',form);
        label.className = "invalid"; 
      } else {
        document.getElementById('newsubaddr').className = "inputbox";
        var label = PCgetLabel('newsubaddr',form);
        label.className = ""; 
      }
    }
    if( ereq ){
      if( ereqa && form.newemail.value == "" ){
        document.getElementById('newemail').className = "inputbox invalid";
        var label = PCgetLabel('newemail',form);
        label.className = "invalid"; 
      } else if( ereqa && form.newemail.value != "" ) {
        document.getElementById('newemail').className = "inputbox";
        var label = PCgetLabel('newemail',form);
        label.className = ""; 
      }
      if( form.valreq.value == "" ){
        if(editor == 'none'){
          document.getElementById('newrequest').className = "inputbox invalid";
        } 
        else if(editor == 'tinymce' || editor == 'jce') {
          var ed = tinyMCE.activeEditor;  
          ed.getContentAreaContainer().style.border = '2px solid red';
          ed.getContentAreaContainer().style.borderLeft = '2px solid black';
          ed.getContentAreaContainer().style.borderTop = '2px solid black';
        } 
        else if(editor == 'fckeditor') {
          var fck = FCKeditorAPI.GetInstance('newrequest') ;
          fck.EditorDocument.body.style.cssText += 'border: 2px solid red; border-left: 2px solid black; border-top: 2px solid red;';
        } 
        else if(editor == 'ckeditor') {
          var ckeditor = CKEDITOR.instances.newrequest;
          var ckdoc = ckeditor.document.getDocumentElement();
          ckdoc.setStyle('height','98%');
          ckdoc.setStyle('border','2px solid red');
        } 
        else if(editor == 'tmedit') {
    			editornewrequest._doc.body.style.cssText += 'border: 2px solid red; border-left: 2px solid black; border-top: 2px solid black;';
      }
        var label = PCgetLabel('newrequest',form);
        label.className = "invalid";
      } else {
        if(editor == 'none'){
          document.getElementById('newrequest').className = "inputbox";
        } 
        if(editor == 'tinymce' || editor == 'jce') {
          var ed = tinyMCE.activeEditor;
          ed.getContentAreaContainer().style.border = '0px;';
        } 
        else if(editor == 'fckeditor') {
          var fck = FCKeditorAPI.GetInstance('newrequest') ;
          fck.EditorDocument.body.style.cssText = 'border: 0px;';
        } 
        else if(editor == 'ckeditor') {
          var ckeditor = CKEDITOR.instances.newrequest;
          var ckdoc = ckeditor.document.getDocumentElement();
          ckdoc.setStyle('border','0px');
        } 
        else if(editor == 'tmedit') {
    			editornewrequest._doc.body.style.cssText = 'border: 0px;';
      }
        var label = PCgetLabel('newrequest',form);
        label.className = "";
      }
    }
   }
   function PCchgClassNameOnBlur(el)
    {
  	var form = document.adminForm;
    if( document.getElementById(el).value == "" ){
      document.getElementById(el).className = "inputbox invalid";
      var label = PCgetLabel(el,form);
      label.className = "invalid"; 
    } else {
      document.getElementById(el).className = "inputbox";
      var label = PCgetLabel(el,form);
      label.className = ""; 
    }
   }
  function PCgetImage(livesite,action){
    if(action == 'pccomp'){
      var sec_image = "sec_image";
    } else {
      var sec_image = action + "_sec_image";
    }  
    document.images[sec_image].src=livesite+"components/com_prayercenter/assets/captcha/prayercenter.captcha.inc.php?action=" + action + "&new="+escape(new Date().getTime());
  }
  function submitbutton(pressbutton)
  {
  	var form = document.adminForm;
  	if (pressbutton == 'cancel') {
  		submitform( pressbutton );
  		return;
  	}
  }
  function getPCXmlHttpRequestObject() {
   if (window.XMLHttpRequest) {
      return new XMLHttpRequest(); //IE7-9, Firefox, Safari, Opera, Chrome ...
   } else if (window.ActiveXObject) {
      return new ActiveXObject("Microsoft.XMLHTTP"); //IE6 and under
   } else {
      alert("Your browser doesn't support the XmlHttpRequest object.");
   }
  }
  var receivePCReq = getPCXmlHttpRequestObject();
  function checkPCCapCode(theForm,usercode,livesite,action){
   var url = livesite + 'index.php?option=com_prayercenter&tmpl=component&task=PCCapValid&';
   var postStr = 'usercode=' + encodeURIComponent( usercode ) + '&cap=1';
   if(action == 'pcmsr')  postStr += '&modtype=return_submsg&mod=pcmsr';
   if(action == 'pcmsub')  postStr += '&modtype=return_subscribmsg&mod=pcmsub';
   if (receivePCReq.readyState == 4 || receivePCReq.readyState == 0) {
     receivePCReq.open("POST", url, true);
     receivePCReq.onreadystatechange = function(){updatePCCapPage(theForm,livesite,action);} 
     receivePCReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     receivePCReq.setRequestHeader("Content-length", postStr.length);
     receivePCReq.setRequestHeader("Connection", "close");
     receivePCReq.send(postStr);
   }
  }
  function checkReCapCode(theForm,usercode,livesite){
   var url = livesite + 'index.php?option=com_prayercenter&tmpl=component&format=raw&task=PCCapValid&';
   var challenge = Recaptcha.get_challenge();
   var response = Recaptcha.get_response();
   var postStr = 'usercode=' + encodeURIComponent( usercode ) + '&recaptcha_challenge=' + encodeURIComponent( challenge ) + '&recaptcha_response=' + encodeURIComponent( response ) + '&cap=6';
   if (receivePCReq.readyState == 4 || receivePCReq.readyState == 0) {
     receivePCReq.open("POST", url, true);
     receivePCReq.onreadystatechange = function(){updateReCapPage(theForm);} 
     receivePCReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     receivePCReq.setRequestHeader("Content-length", postStr.length);
     receivePCReq.setRequestHeader("Connection", "close");
     receivePCReq.send(postStr);
   }
  }
  function updatePCCapPage(theForm,livesite,action) {
   var fname = theForm.name;
   if (receivePCReq.readyState == 4) {
   if(receivePCReq.responseText != true){
     alert(receivePCReq.responseText);
     theForm.security_code.value = '';
     theForm.security_code.style.border = '1px solid #ff0000';
     if(action == 'pccomp'){
       var ucodelabel = document.getElementById('seccode');
       ucodelabel.style.color = '#ff0000';
     }
     theForm.security_code.focus();
     PCgetImage(livesite,action);
     return false;
    }
    theForm.submit();
   }
  }
  function updateReCapPage(theForm) {
   var fname = theForm.name;
   if (receivePCReq.readyState == 4) {
   if(receivePCReq.responseText != true){
     alert(receivePCReq.responseText);
     theForm.recaptcha_response_field.value = '';
     theForm.recaptcha_response_field.focus();
     Recaptcha.reload();
     return false;
    }
    theForm.submit();
   }
  }
  function checkJDefaultCapCode(theForm,livesite){
   var jcap = document.getElementById('jcap').value;
   var url = livesite + 'index.php?option=com_prayercenter&tmpl=component&format=raw&task=PCCapValid';
   var postStr = '&cap=7';
   if(jcap == 'recaptcha'){
      var challenge = Recaptcha.get_challenge();
      var response = Recaptcha.get_response();
      postStr += '&recaptcha_challenge_field=' + encodeURIComponent( challenge ) + '&recaptcha_response_field=' + encodeURIComponent( response );
   }
   if (receivePCReq.readyState == 4 || receivePCReq.readyState == 0) {
     receivePCReq.open("POST", url, true);
     receivePCReq.onreadystatechange = function(){
     if (receivePCReq.readyState == 4){
       if(receivePCReq.responseText != true){
        alert(receivePCReq.responseText);
        if(jcap == 'recaptcha') {Recaptcha.reload();}
        return false;
       } 
      theForm.submit();
      }
     }    
     receivePCReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     receivePCReq.setRequestHeader("Content-length", postStr.length);
     receivePCReq.setRequestHeader("Connection", "close");
     receivePCReq.send(postStr);
   }
  }
  function capitalize(val){
    var newVal = '';
    val = val.split(' ');
    for(var c=0; c<val.length; c++){
      newVal += val[c].substring(0,1).toUpperCase() + val[c].substring(1,val[c].length) + ' ';
    }
    return newVal;
  }