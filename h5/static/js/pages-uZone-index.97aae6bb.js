(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-uZone-index"],{1290:function(t,i,e){"use strict";var n=e("f4fd"),a=e.n(n);a.a},"1a28":function(t,i,e){"use strict";var n=e("288e");Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var a=n(e("e814")),o=n(e("cebc")),s=n(e("5ede")),c=n(e("f12e")),r=e("2f62"),u={computed:(0,o.default)({},(0,r.mapState)(["user","token"])),components:{videoComment:c.default},onLoad:function(){this.userInfo=this.user,this.getCommunityList(),this.getMyCommunityList()},data:function(){return{userInfo:{},headerBarBackground:"transparent",page:1,communityList:[],myCommunityList:[],DoShowMyCommunity:!1}},onPageScroll:function(t){this.headerBarBackground="rgba(44,198,224,"+t.scrollTop/200+")"},onPullDownRefresh:function(){this.page=1,this.communityList=[],0==this.DoShowMyCommunity?this.getCommunityList():1==this.DoShowMyCommunity&&this.getMyCommunityList(),setTimeout(function(){uni.stopPullDownRefresh()},1e3)},onReachBottom:function(){this.page=this.page+1,0==this.DoShowMyCommunity?this.getCommunityList():1==this.DoShowMyCommunity&&this.getMyCommunityList()},methods:{preview:function(t){console.log(t);var i=[t];uni.previewImage({urls:i,longPressActions:{itemList:["保存图片"],success:function(t){console.log("选中了第"+(t.tapIndex+1)+"个按钮,第"+(t.index+1)+"张图片")},fail:function(t){console.log(t.errMsg)}}})},showMyCommunity:function(){0==this.myCommunityList.length?uni.showToast({title:"您还没有发布动态！",icon:none}):this.DoShowMyCommunity=!0},handleVideoComment:function(t){this.$refs.videoComment.show(t)},handlePublish:function(){uni.navigateTo({url:"/pages/uZone/publish"})},handleZoneMenu:function(){var t=this.$refs.uniPop;t.show({skin:"androidSheet",btns:[{text:"不看TA的动态",onTap:function(){t.close()}}]})},getCommunityList:function(){var t=this;s.default.request({url:"service=Community.getRecommendCommunitys",method:"POST",data:{uid:this.user.id,p:this.page}}).then(function(i){0==i.data.data.code?1==t.page?t.communityList=i.data.data.info:t.communityList=t.communityList.concat(i.data.data.info):i.data.data.code}).catch(function(t){})},YouLike:function(t){var i=this;s.default.request({url:"service=Community.addLike",method:"POST",data:{uid:this.userInfo.id,token:this.userInfo.token,communityid:this.communityList[t].id}}).then(function(e){0===e.data.data.code?(uni.showToast({title:e.data.data.msg}),i.communityList[t].islike=(0,a.default)(e.data.data.info[0].islike),i.getCommunityList()):uni.showToast({title:e.data.data.msg,icon:"none"})}).catch(function(t){})},YouCollect:function(t){var i=this;s.default.request({url:"service=Community.collectCommunity",method:"POST",data:{uid:this.userInfo.id,token:this.userInfo.token,communityid:this.communityList[t].id}}).then(function(e){0==e.data.data.code?(i.communityList[t].isCollect=e.data.data.info[0].iscollect,uni.showToast({title:e.data.data.msg}),i.getCommunityList()):uni.showToast({title:e.data.data.msg,icon:"none"})}).catch(function(t){uni.showToast({title:res.data.data.msg,icon:"none"})})},getMyCommunityList:function(){var t=this;s.default.request({url:"service=Community.getHomecommunity",method:"POST",data:{uid:this.user.id,touid:this.user.id,p:this.page}}).then(function(i){0==i.data.data.code?1==t.page?t.myCommunityList=i.data.data.info:t.myCommunityList=t.myCommunityList.concat(i.data.data.info):i.data.data.code}).catch(function(t){})},goUhome:function(t){uni.navigateTo({url:"/pages/ucenter/uhome?id="+t})}}};i.default=u},"349f":function(t,i,e){"use strict";var n,a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return t.popupVisible?e("v-uni-view",{staticClass:"popup-footer"},[e("v-uni-view",{staticClass:"pop__ui_panel"},[e("v-uni-view",{staticClass:"pop__ui_mask",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.close.apply(void 0,arguments)}}}),e("v-uni-view",{staticClass:"pop__ui_child anim-footer"},[e("v-uni-view",{staticClass:"pop__ui_head uni_borB"},[t._v(t._s(t.commentListlength)+" 条评论")]),e("v-uni-view",{staticClass:"pop__ui_body"},[e("v-uni-view",{staticClass:"wrap_commentPanel flexbox flex_col"},[e("v-uni-view",{staticClass:"wrap_cmtList flex1"},[t._l(t.commentList,function(i,n){return[e("v-uni-view",{key:n+"_0",staticClass:"item uni__material flexbox",on:{longpress:function(i){arguments[0]=i=t.$handleEvent(i),t.handleLongPressMenu.apply(void 0,arguments)}}},[e("v-uni-image",{staticClass:"avator",attrs:{src:i.userinfo.avatar?i.userinfo.avatar:i.avatar,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"cmtinfo flex1"},[e("v-uni-view",{staticClass:"name"},[t._v("@"+t._s(i.userinfo.user_nicename?i.userinfo.user_nicename:i.user_nicename))]),e("v-uni-text",{staticClass:"cnt"},[t._v(t._s(i.content))]),e("v-uni-view",{staticClass:"time"},[t._v(t._s(i.datetime))])],1),e("v-uni-view",{staticClass:"like"},[e("v-uni-text",{staticClass:"iconfont icon-like"}),t._v(t._s(i.likes))],1)],1)]})],2),e("v-uni-view",{staticClass:"wrap_cmtFoot"},[e("v-uni-view",{staticClass:"wrap__editorPanel flexbox"},[e("v-uni-view",{staticClass:"wrap_editor flex1"},[e("v-uni-textarea",{staticClass:"editor",attrs:{"show-confirm-bar":"false","cursor-spacing":"15","selection-start":t.editorLastCursor,"selection-end":t.editorLastCursor,"auto-height":!0,placeholder:"留下你的精彩评论吧"},on:{input:function(i){arguments[0]=i=t.$handleEvent(i),t.bindEditorInput.apply(void 0,arguments)},focus:function(i){arguments[0]=i=t.$handleEvent(i),t.bindEditorFocus.apply(void 0,arguments)},blur:function(i){arguments[0]=i=t.$handleEvent(i),t.bindEditorBlur.apply(void 0,arguments)}},model:{value:t.editorText,callback:function(i){t.editorText=i},expression:"editorText"}})],1),e("v-uni-view",{staticClass:"wrap_editor_btn",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.handleShowEmotion.apply(void 0,arguments)}}},[e("v-uni-text",{staticClass:"iconfont icon-emoj"})],1),e("v-uni-view",{staticClass:"wrap_editor_btn btn_submit uni__btn-primary bg_linear2",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.handleSubmit.apply(void 0,arguments)}}},[e("v-uni-text",{staticClass:"iconfont icon-send"})],1)],1),e("v-uni-view",{staticClass:"wrap_emotion uni_borT",style:[{display:t.showEmotionView?"block":"none"}]},[e("v-uni-view",{staticClass:"emotion__cells"},[e("v-uni-swiper",{staticStyle:{height:"100%",width:"100%",position:"absolute"},attrs:{"indicator-dots":!0,duration:200,"indicator-color":"#dbdbdb","indicator-active-color":"#999"}},[t._l(t.emotionList,function(i,n){return[e("v-uni-swiper-item",[e("v-uni-view",{staticClass:"face_list"},[t._l(i.nodes,function(i,n){return[e("v-uni-view",{key:n+"_0",staticClass:"item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.handleEmotionTaped(i)}}},["del"==i?e("v-uni-image",{staticClass:"del",attrs:{src:"/static/emotion_del.png",mode:"widthFix"}}):e("v-uni-text",{staticClass:"emoj"},[t._v(t._s(i))])],1)]})],2)],1)]})],2)],1)],1)],1)],1)],1)],1)],1),t.showFloatInputView?e("v-uni-view",{staticClass:"wrap__floatInputPanel"},[e("v-uni-view",{staticClass:"floatInput-mask",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.showFloatInputView=!1}}}),e("v-uni-view",{staticClass:"floatInput-body"},[e("v-uni-view",{staticClass:"wrap__editorPanel flexbox"},[e("v-uni-view",{staticClass:"wrap_editor flex1"},[e("v-uni-textarea",{staticClass:"editor",attrs:{"show-confirm-bar":"false","cursor-spacing":"15","selection-start":t.editorLastCursor,"selection-end":t.editorLastCursor,"auto-height":!0,placeholder:"留下你的精彩评论吧"},on:{input:function(i){arguments[0]=i=t.$handleEvent(i),t.bindEditorInput.apply(void 0,arguments)},focus:function(i){arguments[0]=i=t.$handleEvent(i),t.bindEditorFocus.apply(void 0,arguments)},blur:function(i){arguments[0]=i=t.$handleEvent(i),t.bindEditorBlur.apply(void 0,arguments)}},model:{value:t.editorText,callback:function(i){t.editorText=i},expression:"editorText"}})],1),e("v-uni-view",{staticClass:"wrap_editor_btn btn_submit uni__btn-primary bg_linear2",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.handleSubmit.apply(void 0,arguments)}}},[e("v-uni-text",{staticClass:"iconfont icon-send"})],1)],1),e("v-uni-view",{staticClass:"pad10 bg_fff"},[e("v-uni-label",{staticClass:"radio"},[e("v-uni-radio",{staticStyle:{transform:"scale(.8)"},attrs:{value:"1",color:"#feb719"}}),e("v-uni-text",{staticClass:"fs_12 c_999"},[t._v("评论并转发")])],1)],1),e("v-uni-view",{staticClass:"wrap_emotion uni_borT",style:[{display:t.showEmotionView?"block":"none"}]},[e("v-uni-view",{staticClass:"emotion__cells"},[e("v-uni-swiper",{staticStyle:{height:"100%",width:"100%",position:"absolute"},attrs:{"indicator-dots":!0,duration:200,"indicator-color":"#dbdbdb","indicator-active-color":"#999"}},[t._l(t.emotionList,function(i,n){return[e("v-uni-swiper-item",[e("v-uni-view",{staticClass:"face_list"},[t._l(i.nodes,function(i,n){return[e("v-uni-view",{key:n+"_0",staticClass:"item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.handleEmotionTaped(i)}}},["del"==i?e("v-uni-image",{staticClass:"del",attrs:{src:"/static/emotion_del.png",mode:"widthFix"}}):e("v-uni-text",{staticClass:"emoj"},[t._v(t._s(i))])],1)]})],2)],1)]})],2)],1)],1)],1)],1):t._e(),e("uni-pop",{ref:"uniPop"})],1):t._e()},o=[];e.d(i,"b",function(){return a}),e.d(i,"c",function(){return o}),e.d(i,"a",function(){return n})},"420a":function(t,i,e){"use strict";e.r(i);var n=e("57ac"),a=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(i,t,function(){return n[t]})}(o);i["default"]=a.a},"57ac":function(t,i,e){"use strict";var n=e("288e");Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0,e("a481");var a=n(e("cebc")),o=n(e("5ede")),s=e("2f62"),c=e("70be"),r={data:function(){return{commentListlength:0,popupVisible:!1,showFloatInputView:!1,showEmotionView:!1,communityid:"",editorText:"",editorLastCursor:null,emotionList:c,commentList:[]}},computed:(0,a.default)({},(0,s.mapState)(["user","token"])),props:["vlist"],methods:{show:function(t){var i=this;this.communityid=this.vlist[t].id,this.popupVisible=!0,o.default.request({url:"service=Community.getComments",method:"POST",data:{token:this.user.token,communityid:this.communityid,uid:this.user.id,p:1}}).then(function(t){0==t.data.data.code&&(i.commentList=t.data.data.info[0].commentlist,i.commentListlength=t.data.data.info[0].comments)}).catch(function(t){})},close:function(){this.popupVisible=!1,this.showEmotionView=!1},handleShowFloatInput:function(){this.showFloatInputView=!0,this.showEmotionView=!0},handleShowEmotion:function(){this.showEmotionView=!0},bindEditorInput:function(t){this.editorLastCursor=t.detail.cursor},bindEditorFocus:function(t){},bindEditorBlur:function(t){this.editorLastCursor=t.detail.cursor},handleEmotionTaped:function(t){if("del"!=t){var i=this.editorText.substr(0,this.editorLastCursor),e=this.editorText.substr(this.editorLastCursor);this.editorText=i+"".concat(t)+e}},isEmpty:function(t){return""==t.replace(/\r\n|\n|\r/,"").replace(/(?:^[ \t\n\r]+)|(?:[ \t\n\r]+$)/g,"")},handleSubmit:function(){var t=this;if(console.log(this.editorText),""==this.editorText)return uni.showToast({title:"请输入评论内容",icon:"none"}),!1;if(!this.isEmpty(this.editorText)){var i=this.commentList,e=i.length,n={id:"msg".concat(++e),userinfo:{avatar:this.user.avatar,user_nicename:this.user.user_nicename},content:this.editorText,datetime:(new Date).toLocaleString(),likes:"0"},a=this.$refs.uniPop;o.default.request({url:"service=Community.setComment",method:"POST",data:{token:this.user.token,communityid:this.communityid,touid:"",uid:this.user.id,content:this.editorText,parentid:"",commentid:"",at_info:""}}).then(function(e){0==e.data.data.code?(a.show({skin:"H5",content:e.data.data.msg}),i.unshift(n),t.commentList=i,t.editorText="",t.showFloatInputView=!1):(console.log(e.data.data.msg),a.show({skin:"H5",content:e.data.data.msg}))}).catch(function(t){a.show({skin:"H5",content:res.data.data.msg})})}},handleLongPressMenu:function(){return!1}}};i.default=r},"60be":function(t,i,e){i=t.exports=e("2350")(!1),i.push([t.i,'@charset "UTF-8";\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */\n/* 文章场景相关 */.uni__scrollview[data-v-775eea68]{background:#fff}.uni__material[data-v-775eea68]{\n  /* height: 10vh; */background:#fff}.big[data-v-775eea68]{font-size:%?45?%}.bg-lightpink[data-v-775eea68]{background-color:#ff69b4;color:#fff}.fz_header[data-v-775eea68]{position:relative}.avatar[data-v-775eea68]{z-index:111;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;position:absolute;bottom:%?-20?%;right:%?30?%}.avatar uni-image[data-v-775eea68]{width:%?90?%;height:%?90?%;border-radius:%?8?%}',""])},"700a":function(t,i,e){var n=e("c75c");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=e("4f06").default;a("7798ddea",n,!0,{sourceMap:!1,shadowMode:!1})},"70be":function(t,i,e){"use strict";t.exports=[{nodes:["😲","😁","😋","😎","😍","😘","😗","😃","😂","😓","😅","😉","😊","😴","😱","😐","😑","😶","😡","😏","del"]},{nodes:["😮","🤐","😪","😫","😴","😛","😜","🙃","🤑","🙁","😟","😤","😭","😨","😬","😰","🤔","😳","😵","🤐","del"]},{nodes:["🤒","😷","🤕","🤢","😇","🤠","🤡","🤥","🤓","💀","👻","😺","😹","😻","🙀","😿","👬","👂","👣","👀","del"]},{nodes:["👓","👄","💋","👕","👙","👜","👠","👑","🎓","💄","💍","🌂","👧","🧑","👩","🧓","🙍‍","️‍🤴","👲‍","👨️","del"]},{nodes:["💪","👈","👉","🤞","👇","🤟","🤘","👌","👍","👎","✊","🤚","👊","🤝","🙏","🙈","💦","🐶","🍉","🍌","del"]}]},"755e":function(t,i,e){"use strict";e.r(i);var n=e("cc5b"),a=e("b057");for(var o in a)"default"!==o&&function(t){e.d(i,t,function(){return a[t]})}(o);e("1290");var s,c=e("f0c5"),r=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"775eea68",null,!1,n["a"],s);i["default"]=r.exports},b057:function(t,i,e){"use strict";e.r(i);var n=e("1a28"),a=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(i,t,function(){return n[t]})}(o);i["default"]=a.a},c75c:function(t,i,e){i=t.exports=e("2350")(!1),i.push([t.i,'.pop__ui_panel[data-v-5b0073d0]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;height:100%;width:100%;position:fixed;left:0;top:0;z-index:201909;pointer-events:none;background:rgba(0,0,0,.5)}.pop__ui_mask[data-v-5b0073d0]{background:#000;opacity:0;height:100%;width:100%;position:fixed;left:0;top:0;pointer-events:auto;touch-action:none;-webkit-animation:anim_mask-data-v-5b0073d0 .5s;animation:anim_mask-data-v-5b0073d0 .5s}.pop__ui_child[data-v-5b0073d0]{background:#fff;border-radius:12px 12px 0 0;font-size:14px;overflow:hidden;pointer-events:auto;margin:0 auto;width:100%;position:fixed;bottom:0;left:0;right:0}.pop__ui_head[data-v-5b0073d0]{font-size:%?28?%;font-weight:700;padding:%?30?%;text-align:center}.wrap_commentPanel[data-v-5b0073d0]{height:%?900?%}.wrap_cmtList[data-v-5b0073d0]{overflow:auto}.wrap_cmtList .item[data-v-5b0073d0]{padding:%?30?%;position:relative}.wrap_cmtList .item[data-v-5b0073d0]:after{content:"";background:#dbdbdb;height:1px;width:100%;position:absolute;left:50px;bottom:0;-webkit-transform:scaleY(.5);transform:scaleY(.5);-webkit-transform-origin:0 100%;transform-origin:0 100%}.wrap_cmtList .item[data-v-5b0073d0]:last-child:after{display:none}.wrap_cmtList .item .avator[data-v-5b0073d0]{border-radius:50%;margin-right:%?30?%;height:30px;width:30px}.wrap_cmtList .item .cmtinfo .name[data-v-5b0073d0]{color:#999;font-size:%?24?%}.wrap_cmtList .item .cmtinfo .cnt[data-v-5b0073d0]{font-size:%?28?%;display:block;margin-top:%?10?%}.wrap_cmtList .item .cmtinfo .time[data-v-5b0073d0]{color:#bbb;font-size:%?24?%;margin-top:%?10?%}.wrap_cmtList .item .like[data-v-5b0073d0]{color:#bbb;font-size:%?24?%;text-align:center;padding-left:%?30?%}.wrap_cmtList .item .like .iconfont[data-v-5b0073d0]{color:#bbb;display:block}.floatInput-mask[data-v-5b0073d0]{background:#000;opacity:.6;height:100%;width:100%;position:fixed;left:0;top:0;z-index:201910;pointer-events:auto;touch-action:none;-webkit-animation:anim_mask-data-v-5b0073d0 .5s;animation:anim_mask-data-v-5b0073d0 .5s}.floatInput-body[data-v-5b0073d0]{background:#f7f8f9;font-size:14px;overflow:hidden;pointer-events:auto;margin:0 auto;width:100%;position:fixed;bottom:0;left:0;right:0;z-index:201911}.wrap__editorPanel[data-v-5b0073d0]{background:#fff;padding:%?20?% %?30?%;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.wrap__editorPanel.uni_borT[data-v-5b0073d0]:before{background:#bdbdbd}.wrap_editor[data-v-5b0073d0]{box-sizing:border-box;overflow:hidden}.wrap_editor .editor[data-v-5b0073d0]{caret-color:#feb719;font-size:14px;max-height:100px;max-width:100%;line-height:1.2}.wrap__editorPanel .wrap_editor_btn[data-v-5b0073d0]{-webkit-align-self:flex-end;align-self:flex-end;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;padding:0 4px;height:28px}.wrap__editorPanel .wrap_editor_btn .iconfont[data-v-5b0073d0]{color:#999;font-size:25px}.wrap__editorPanel .btn_submit[data-v-5b0073d0]{background:none!important;border-radius:20px;font-size:14px;margin-left:5px;padding:0 3px;line-height:28px}.wrap__editorPanel .btn_submit .iconfont[data-v-5b0073d0]{color:#999;font-size:25px}.wrap_emotion[data-v-5b0073d0]{height:%?580?%}.wrap_emotion .emotion__cells[data-v-5b0073d0]{height:100%;position:relative}.emotion__cells .face_list[data-v-5b0073d0]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:wrap;flex-wrap:wrap;margin-right:%?-15?%;padding:%?30?% 0 0 %?20?%}.emotion__cells .face_list .item[data-v-5b0073d0]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;margin-top:%?40?%;margin-right:%?15?%;height:%?90?%;width:%?90?% /*background: red;*/}.emotion__cells .face_list .item[data-v-5b0073d0]:active{background:#ebebeb}.emotion__cells .face_list .item .emoj[data-v-5b0073d0]{font-size:%?50?%}.emotion__cells .face_list .item .del[data-v-5b0073d0]{height:%?60?%;width:%?60?%}.anim-footer[data-v-5b0073d0]{-webkit-animation:anim_footer-data-v-5b0073d0 .3s;animation:anim_footer-data-v-5b0073d0 .3s}@-webkit-keyframes anim_footer-data-v-5b0073d0{from{opacity:0;-webkit-transform:translateY(100%);transform:translateY(100%)}to{opacity:1;-webkit-transform:none;transform:none}}@keyframes anim_footer-data-v-5b0073d0{from{opacity:0;-webkit-transform:translateY(100%);transform:translateY(100%)}to{opacity:1;-webkit-transform:none;transform:none}}@-webkit-keyframes anim_mask-data-v-5b0073d0{0%{opacity:0}}@keyframes anim_mask-data-v-5b0073d0{0%{opacity:0}}',""])},cc5b:function(t,i,e){"use strict";var n,a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"flexbox flex_col"},[e("header-bar",{staticClass:"big",attrs:{isBack:!0,title:"圈子",bgColor:{background:t.headerBarBackground},center:!0,transparent:!0}},[e("v-uni-text",{staticClass:"uni_btnIco iconfont icon-back",attrs:{slot:"back"},slot:"back"}),e("v-uni-text",{staticClass:"uni_btnIco mr_15 cuIcon-camerafill",attrs:{slot:"iconfont"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.handlePublish.apply(void 0,arguments)}},slot:"iconfont"})],1),e("v-uni-view",{staticClass:"uni__scrollview flex1"},[e("v-uni-view",{staticClass:"uni-friendZone"},[e("v-uni-view",{staticClass:"fz_header"},[e("v-uni-image",{staticClass:"poster",attrs:{src:"/static/placeholder/friendZone_bg.jpg",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"avatar",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.showMyCommunity.apply(void 0,arguments)}}},[e("v-uni-text",{staticClass:"text-white mr_20 text-xl"},[t._v(t._s(t.userInfo.user_nicename))]),e("v-uni-image",{attrs:{src:t.userInfo.avatar_thumb}})],1)],1),t.DoShowMyCommunity?e("v-uni-view",{staticClass:"fz_container mt_10"},t._l(t.myCommunityList,function(i,n){return e("v-uni-view",{key:n,staticClass:"fz_item flexbox uni__material",on:{longpress:function(i){arguments[0]=i=t.$handleEvent(i),t.handleZoneMenu.apply(void 0,arguments)}}},[e("v-uni-image",{staticClass:"fzitem_avator",attrs:{src:i.userinfo.avatar_thumb,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"fzitem_content flex1"},[e("v-uni-text",{staticClass:"fz_user"},[t._v(t._s(i.userinfo.user_nicename))]),e("v-uni-view",{staticClass:"mt_5"},[0==i.userinfo.sex?e("v-uni-view",{staticClass:"cu-tag bg-blue light sm radius"},[e("v-uni-text",{staticClass:"cuIcon-male text-blue"}),t._v(t._s(i.userinfo.age.substr(0,1)))],1):e("v-uni-view",{staticClass:"cu-tag bg-lightpink  sm radius"},[e("v-uni-text",{staticClass:"cuIcon-female text-white"}),t._v(t._s(i.userinfo.age.substr(0,1)))],1)],1),e("v-uni-view",{staticClass:"fz_cnts"},[t._v(t._s(i.content))]),i.imgs?e("v-uni-view",{staticClass:"fz_photos"},t._l(i.imgs,function(n,a){return i.imgs.length>1?e("v-uni-image",{key:a,staticClass:"fz_img",attrs:{src:n,mode:"aspectFill"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.preview(n)}}}):e("v-uni-image",{staticClass:"fz_img_auto",attrs:{src:n,mode:"aspectFill"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.preview(n)}}})}),1):t._e(),e("v-uni-view",{staticClass:"fz_foot flexbox flex_alignc"},[e("v-uni-text",{staticClass:"fz_time flex1"},[t._v(t._s(i.addtime))]),e("v-uni-view",{staticClass:"like mr_15"},["0"==i.likes?e("v-uni-text",{staticClass:"cuIcon-appreciate text-grey text-xs"},[t._v(t._s(i.likes))]):e("v-uni-text",{staticClass:"cuIcon-appreciatefill text-pink text-xs"},[t._v(t._s(i.likes))])],1),e("v-uni-view",{staticClass:"collect"},[0==i.collect?e("v-uni-text",{staticClass:"iconfont icon-xihuan text-grey text-xs"},[t._v(t._s(i.collect))]):e("v-uni-text",{staticClass:"cuIcon-likefill text-pink text-xs"},[t._v(t._s(i.collect))])],1)],1)],1)],1)}),1):e("v-uni-view",{staticClass:"fz_container mt_10"},t._l(t.communityList,function(i,n){return e("v-uni-view",{key:n,staticClass:"fz_item flexbox uni__material",on:{longpress:function(i){arguments[0]=i=t.$handleEvent(i),t.handleZoneMenu.apply(void 0,arguments)}}},[e("v-uni-image",{staticClass:"fzitem_avator",attrs:{src:i.userinfo.avatar_thumb,mode:"aspectFill"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goUhome(i.userinfo.id)}}}),e("v-uni-view",{staticClass:"fzitem_content flex1"},[e("v-uni-text",{staticClass:"fz_user"},[t._v(t._s(i.userinfo.user_nicename))]),e("v-uni-view",{staticClass:"mt_5"},[1==i.userinfo.sex?e("v-uni-view",{staticClass:"cu-tag bg-blue light sm radius"},[e("v-uni-text",{staticClass:"cuIcon-male text-blue"}),t._v(t._s(i.userinfo.age.substr(0,1)))],1):e("v-uni-view",{staticClass:"cu-tag bg-lightpink  sm radius"},[e("v-uni-text",{staticClass:"cuIcon-female text-white"}),t._v(t._s(i.userinfo.age.substr(0,1)))],1)],1),e("v-uni-view",{staticClass:"fz_cnts"},[t._v(t._s(i.content))]),i.imgs?e("v-uni-view",{staticClass:"fz_photos"},t._l(i.imgs,function(n,a){return i.imgs.length>1?e("v-uni-image",{key:a,staticClass:"fz_img",attrs:{src:n,mode:"aspectFill"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.preview(n)}}}):e("v-uni-image",{staticClass:"fz_img_auto",attrs:{src:n,mode:"aspectFill"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.preview(n)}}})}),1):t._e(),e("v-uni-view",{staticClass:"fz_foot flexbox flex_alignc"},[e("v-uni-text",{staticClass:"fz_time flex1"},[t._v(t._s(i.datetime))]),e("v-uni-view",{staticClass:"like mr_15",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.YouLike(n)}}},[0==i.likes?e("v-uni-text",{staticClass:"cuIcon-appreciate"}):e("v-uni-text",{staticClass:"cuIcon-appreciatefill text-pink"})],1),e("v-uni-view",{staticClass:"collect",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.YouCollect(n)}}},[0==i.isCollect?e("v-uni-text",{staticClass:"iconfont icon-xihuan"}):e("v-uni-text",{staticClass:"cuIcon-likefill text-pink"})],1),e("v-uni-text",{staticClass:"iconfont icon-pinglun ml_15",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.handleVideoComment(n)}}})],1)],1)],1)}),1)],1)],1),e("uni-pop",{ref:"uniPop"}),e("video-comment",{ref:"videoComment",attrs:{vlist:t.communityList}})],1)},o=[];e.d(i,"b",function(){return a}),e.d(i,"c",function(){return o}),e.d(i,"a",function(){return n})},f12e:function(t,i,e){"use strict";e.r(i);var n=e("349f"),a=e("420a");for(var o in a)"default"!==o&&function(t){e.d(i,t,function(){return a[t]})}(o);e("f33a");var s,c=e("f0c5"),r=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"5b0073d0",null,!1,n["a"],s);i["default"]=r.exports},f33a:function(t,i,e){"use strict";var n=e("700a"),a=e.n(n);a.a},f4fd:function(t,i,e){var n=e("60be");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=e("4f06").default;a("67ca2725",n,!0,{sourceMap:!1,shadowMode:!1})}}]);