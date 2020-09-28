while(!window.jQuery){setTimeout(function(){defer(method)},50);}
function smile2edit(textid,smile,replace)
{var itext;var tedit=null;if(replace==1)
itext="<img class='wpml_ico' alt='' src='"+smile+"' />";else
itext=" "+smile+" ";if(typeof tinyMCE!="undefined")
tedit=tinyMCE.get('content');if(tedit==null||tedit.isHidden()==true)
{tarea=document.getElementById(textid);insert_text(itext,tarea);}else if((tedit.isHidden()==false)&&window.tinyMCE)
{var tmce_ver=window.tinyMCE.majorVersion;if(tmce_ver=="4"){window.tinyMCE.execCommand('mceInsertContent',false,itext);}else{window.tinyMCE.execInstanceCommand('content','mceInsertContent',false,itext);}}}
function smile2comment(textid,smile,replace,myid){var tedit=null;tarea=top.document.getElementById(textid);if(tarea==null)
{tarea=jQuery('#'+myid).parent().find('textarea')[0];}
if(tarea==null)
{tarea=jQuery('#'+myid).parent().parent().find('textarea')[0];}
if(tarea==null)
{tarea=jQuery('#'+myid).parent().parent().parent().find('textarea')[0];}
if(tarea==null)
{tarea=jQuery('#'+myid).parent().parent().parent().parent().find('textarea')[0];}
if(tarea==null)
{tarea=jQuery('#'+myid).parent().parent().parent().parent().parent().find('textarea')[0];}
if(tarea==null)
{tarea=jQuery('#'+myid).parent().parent().parent().parent().parent().parent().find('textarea')[0];}
if(tarea==null)
{tarea=jQuery('jetpack_remote_comment').contents().find('textarea')[1];}
if(tarea==null)
{tarea=jQuery('#comment_content')[0];}
if(tarea==null){tarea=jQuery('#'+textid)[0];}
if(tarea==null){tarea=jQuery('#bbp_topic_content')[0];}
if(typeof tinyMCE!="undefined"){teid=tinyMCE.activeEditor.editorId;if(typeof teid=="undefined")
teid=tinyMCE.activeEditor.id;tedit=tinyMCE.get(teid);}
if(tarea==null&&tedit==null){alert('wp-monalisa: Textarea not found. Please contact the webmaster of this site.');return;}
var itext="";if(replace==1)
itext="<img class='wpml_ico' alt='"+smile+"' src='"+smile+"' />";else
itext=" "+smile+" ";if(tarea!=null){insert_text(itext,tarea);}
if(tedit!=null&&tedit.isHidden()==false)
{var tmce_ver=window.tinyMCE.majorVersion;if(tmce_ver=="4"){window.tinyMCE.execCommand('mceInsertContent',false,itext);}else{window.tinyMCE.execInstanceCommand(teid,'mceInsertContent',false,itext);}}}
function insert_text(stxt,obj)
{try{if(typeof window.CKEDITOR.instances.comment!='undefined')
{window.CKEDITOR.instances.comment.insertHtml(stxt);return;}
if(typeof CKEDITOR.instances.content!='undefined')
{CKEDITOR.instances.content.insertHtml(stxt);return;}}
catch(e){}
if(document.selection)
{obj.focus();document.selection.createRange().text=stxt;document.selection.createRange().select();}
else if(obj.selectionStart||obj.selectionStart=='0')
{intStart=obj.selectionStart;intEnd=obj.selectionEnd;obj.value=(obj.value).substring(0,intStart)+stxt+(obj.value).substring(intEnd,obj.value.length);obj.selectionStart=obj.selectionEnd=intStart+stxt.length;obj.focus();}
else
{obj.value+=stxt;}}
var wpml_first_preload=true;jQuery(document).load(setTimeout(function(){wpml_preload();},2000));function wpml_preload(){if(wpml_first_preload&&typeof wpml_imglist!="undefined"&&(wpml_imglist instanceof Array)){var i=0;for(i=0;i<wpml_imglist.length;i++){var wpml_image=new Image();wpml_image.src=wpml_imglist[i];}
wpml_first_preload=false;}}
function wpml_more_smilies(muid){if(jQuery('#smiley2-'+muid).html()=='&nbsp;'){jQuery('#smiley2-'+muid).html(unescape(wpml_more_html[muid]));}}
function wpml_toggle_smilies(uid){jQuery("#smiley1-"+uid).toggle("slow");jQuery("#smiley2-"+uid).toggle("slow");}
function wpml_comment_exclude(postid){jQuery("#wpml_messages").html('');jQuery.post("../wp-content/plugins/wp-monalisa/wpml_edit.php",{postid:postid},function(data){jQuery("#wpml_messages").html(data);});return false;}
function wpml_popup_toggle(id){var dobj=document.getElementById(id).style.display;if(dobj=='none'){document.getElementById(id).style.display='inline-block';}else{document.getElementById(id).style.display='none';}};