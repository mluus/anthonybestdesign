/**
 * Shareprints Gallery
 *
 * @package   Shareprints
 * @author    JR w/Freak Plugins <jr@freakplugins.com>
 * @license   GPLv3+
 * @link      http://freakplugins.com
 * @copyright Copyright (c) 2014 Freak Plugins, LLC - All Rights Reserved
 */tinymce.PluginManager.add("shareprints_shortcode_editor",function(e,t){function r(e){return e.replace(/\[shareprints([^\]]*)\]/g,function(e,t){var n=wp.shortcode.attrs(e).named;cls=n.gallery_type?n.gallery_type:"",pos=n.gallery_position?n.gallery_position:"",width=n.gallery_width?n.gallery_width:"";return'<img src="'+tinymce.Env.transparentSrc+'" class="sp-gallery mceItem '+cls+" "+pos+" "+width+'" title="shareprints'+tinymce.DOM.encode(t)+'" data-gallery-type="'+cls+'" data-mce-resize="false"/>'})}function i(e){function t(e,t){t=(new RegExp(t+'="([^"]+)"',"g")).exec(e);return t?tinymce.DOM.decode(t[1]):""}return e.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g,function(e,n){var r=t(n,"class");return r.indexOf("sp-gallery")!=-1?"["+tinymce.trim(t(n,"title"))+"]":e})}function s(t){var n=e.dom;if(t.nodeName!="IMG"||!n.hasClass(t,"sp-gallery"))return;var r=wp.shortcode.next("shareprints","["+n.getAttrib(t,"title")+"]");r=r.shortcode.attrs.named;sp_mce_app.updateModal(t,r)}function o(t,r){var i,s,o,u,a;o=e.dom.getViewPort(e.getWin());i=n.getPos(e.getContentAreaContainer());s=e.dom.getPos(t);u=Math.max(s.x-o.x,0)+i.x;a=Math.max(s.y-o.y,0)+i.y;n.setStyles(r,{top:a+69+"px",left:u+5+"px",display:"block"})}function u(){n.hide(n.select("#sp_gallerybtns"))}function a(){var t=e.dom,r,i;if(n.get("sp_gallerybtns"))return;n.add(document.body,"div",{id:"sp_gallerybtns",style:"display:none;"});r=n.add("sp_gallerybtns","a",{"class":"dashicons dashicons-edit",id:"sp_editgallery",title:e.getLang("wordpress.editgallery")});i=n.add("sp_gallerybtns","a",{id:"sp_delgallery","class":"dashicons dashicons-no-alt",title:e.getLang("wordpress.delgallery")});tinymce.DOM.bind(r,"mousedown",function(){tinymce.activeEditor.execCommand("SP_Gallery");u()});tinymce.DOM.bind(i,"mousedown",function(n){var r=tinymce.activeEditor.selection.getNode();if(r.nodeName=="IMG"&&t.hasClass(r,"sp-gallery")){t.remove(r);e.execCommand("mceRepaint");t.events.cancel(n)}u()})}var n=tinymce.DOM;e.on("init",function(){a();e.dom.bind(e.getWin(),"scroll",function(){u()});e.dom.bind(e.getBody(),"dragstart",function(){u()})});e.addCommand("SP_Gallery",function(){s(e.selection.getNode())});e.on("MouseDown",function(t){var n=e.dom,r=t.target;(r.nodeName!=="IMG"||!n.hasClass(r,"sp-gallery"))&&u()});tinymce.activeEditor.on("mouseup",function(t){var n=e.dom,r=t.target;if(r.nodeName=="IMG"&&n.hasClass(r,"sp-gallery")){u();o(r,"sp_gallerybtns")}else u()});e.on("ResolveName",function(t){var n=e.dom,r=t.target;if(r.nodeName==="IMG"&&n.hasClass(r,"sp-gallery")){var i=n.getAttrib(r,"data-gallery-type");t.name=i?i+" gallery":"gallery"}});e.on("BeforeSetContent",function(e){e.content=r(e.content)});e.on("PostProcess",function(e){e.get&&(e.content=i(e.content))})});