(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-ucenter-index"],{"08e0":function(t,n,i){var e=i("ec50");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var a=i("4f06").default;a("29044d4b",e,!0,{sourceMap:!1,shadowMode:!1})},"0c26":function(t,n,i){"use strict";i.r(n);var e=i("4cd4"),a=i.n(e);for(var s in e)"default"!==s&&function(t){i.d(n,t,function(){return e[t]})}(s);n["default"]=a.a},"4cd4":function(t,n,i){"use strict";var e=i("288e");Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var a=e(i("cebc")),s=(e(i("5ede")),i("2f62")),o={computed:(0,a.default)({},(0,s.mapState)(["user","token"])),onLoad:function(){this.userInfo=this.user,console.log("onLoad")},onShow:function(){console.log(this.userInfo)},onHide:function(){console.log("onHide")},data:function(){return{userInfo:{},page:1}},methods:{toFans:function(t){uni.navigateTo({url:"/pages/contact/index?id="+t})},toFollow:function(){uni.navigateTo({url:"/pages/ucenter/follows"})},shar:function(){uni.navigateTo({url:"../share/share"})},toPagage:function(){uni.navigateTo({url:"/pages/moneys/pagage"})},toPagagevip:function(){uni.navigateTo({url:"vip"})},GoUzone:function(){uni.navigateTo({url:"/pages/uZone/index"})},GoClect:function(){uni.navigateTo({url:"/pages/uVideo/scplay"})},qrcodeCard:function(){var t=this.$refs.uniPop;t.show({content:'\n\t\t\t\t\t\t<div class="aboutme" style="text-align: center;"><img src="./static/wx-qrcode.jpg" style="height:160px;width:160px;" /><div style="color:#999;font-family:simsun;margin-top:10px;">扫一扫，加我名片</div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t'})},history:function(t){uni.navigateTo({url:"/pages/uVideo/history?id="+t})},aboutSys:function(){var t=this.$refs.uniPop;t.show({content:'\n\t\t\t\t\t\t<div class="aboutme" style="text-align: center;padding:20px 0 10px;">\n\t\t\t\t\t\t\t<img src="./static/logo.png" style="height:100px;width:100px;" /><div style="color: #589bee; font-size:16px;margin-top:10px;">抖视</div>\n\t\t\t\t\t\t\t<div style="color:#999;font-family:simsun;margin-top:10px;">精彩内容尽在抖视APP，快分享好友一起体验吧</div><div style="color:#aaa;font-size:12px;font-family:arial;margin-top:10px;"></div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t',btns:[{text:"关闭",style:"color: #999;",onTap:function(){t.close()}}]})},logoutSys:function(){var t=this,n=this.$refs.uniPop;n.show({skin:"android",content:"<p>确定要退出登录吗？",btns:[{text:"取消",onTap:function(){n.close()}},{text:"退出",style:"color: #feb719",onTap:function(){t.$store.commit("LOGOUT"),uni.clearStorage(),n.close(),uni.redirectTo({url:"/pages/auth/login"})}}]})},toEdit:function(t){uni.navigateTo({url:"edit?id="+t})},toFriendList:function(){uni.navigateTo({url:"friendlist"})}}};n.default=o},"9e5d":function(t,n,i){"use strict";i.r(n);var e=i("bd58"),a=i("0c26");for(var s in a)"default"!==s&&function(t){i.d(n,t,function(){return a[t]})}(s);i("f70f");var o,c=i("f0c5"),u=Object(c["a"])(a["default"],e["b"],e["c"],!1,null,"7d981600",null,!1,e["a"],o);n["default"]=u.exports},bd58:function(t,n,i){"use strict";var e,a=function(){var t=this,n=t.$createElement,i=t._self._c||n;return i("v-uni-view",{staticClass:"uni__ucenterWrapper"},[i("v-uni-view",{staticClass:"uc-header"},[i("v-uni-view",{staticClass:"uni__listview"},[i("v-uni-view",{staticClass:"uni__list uni__material"},[i("v-uni-image",{staticClass:"avator mr_15",attrs:{src:t.userInfo.avatar_thumb,mode:"widthFix"}}),i("v-uni-view",{staticClass:"txt flex1 fs_18"},[t._v(t._s(t.userInfo.user_nicename)),1==t.userInfo.sex?i("v-uni-text",{staticClass:"iconfont icon-nv c_ff0f33 ml_5"}):i("v-uni-text",{staticClass:"iconfont icon-nan c_589bee ml_5"}),i("v-uni-text",{staticClass:"db c_999 fs_12 mt_5"},[t._v("ID: "+t._s(t.userInfo.id))])],1)],1),i("v-uni-view",{staticClass:"renqi"},[i("v-uni-view",[i("v-uni-text",{staticClass:"margin-right-sm",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.toFans(2)}}},[i("v-uni-text",{staticClass:"renqi_num"},[t._v(t._s(t.userInfo.praise))]),t._v("获赞")],1),i("v-uni-text",{staticClass:"margin-right-sm",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.toFans(1)}}},[i("v-uni-text",{staticClass:"renqi_num"},[t._v(t._s(t.userInfo.fans))]),t._v("粉丝")],1),i("v-uni-text",{staticClass:"margin-right-sm",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.toFollow.apply(void 0,arguments)}}},[i("v-uni-text",{staticClass:"renqi_num"},[t._v(t._s(t.userInfo.follows))]),t._v("关注")],1)],1),i("v-uni-view",[i("v-uni-view",{staticClass:"cu-tag radius text-white bg-grey31 padding-lr padding-tb-xs text-xs",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.toEdit(t.userInfo.id)}}},[t._v("编辑资料")])],1)],1),i("v-uni-view",{staticClass:"uni__list flex_alignc flex_col bgr"},[i("v-uni-text",{staticClass:"fs_12 bold db"},[t._v("剩余观看次数")]),i("v-uni-view",{staticStyle:{"font-size":"70upx","font-weight":"700",padding:"20upx 0"}},[t._v(t._s(t.userInfo.free_count))]),i("v-uni-view",{staticClass:"flexbox align_c",staticStyle:{width:"100%"}})],1)],1)],1),i("v-uni-view",{staticClass:"flex justify-around"},[i("v-uni-view",{staticClass:"flex flex-direction align-center",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.shar.apply(void 0,arguments)}}},[i("v-uni-image",{staticClass:"nav_img margin-tb-sm",attrs:{src:"/static/zhuan.png",mode:""}}),i("v-uni-view",[i("v-uni-text",{staticClass:"text-grey textsm"},[t._v("分享推广")])],1)],1),i("v-uni-view",{staticClass:"flex flex-direction align-center",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.toPagage.apply(void 0,arguments)}}},[i("v-uni-image",{staticClass:"nav_img margin-tb-sm",attrs:{src:"/static/tui.png",mode:""}}),i("v-uni-view",[i("v-uni-text",{staticClass:"text-grey textsm"},[t._v("我的钱包")])],1)],1),i("v-uni-view",{staticClass:"flex flex-direction align-center",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.toPagagevip.apply(void 0,arguments)}}},[i("v-uni-image",{staticClass:"nav_img margin-tb-sm",attrs:{src:"/static/qian.png",mode:""}}),i("v-uni-view",[i("v-uni-text",{staticClass:"text-grey textsm"},[t._v("VIP充值")])],1)],1)],1),i("v-uni-view",{staticClass:"uni__listview mt_15"},[i("v-uni-view",{staticClass:"item uni__list uni__material",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.GoUzone.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"txt flex1"},[t._v("朋友圈")]),i("v-uni-text",{staticClass:"uni_badge uni_badge_dot"}),i("v-uni-text",{staticClass:"iconfont icon-arrR c_999 fs_12"})],1),i("v-uni-view",{staticClass:"item uni__list uni__material",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.history(0)}}},[i("v-uni-view",{staticClass:"txt flex1"},[t._v("观看记录")]),i("v-uni-text",{staticClass:"iconfont icon-arrR c_999 fs_12"})],1),i("v-uni-view",{staticClass:"item uni__list uni__material",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.history(1)}}},[i("v-uni-view",{staticClass:"txt flex1"},[t._v("我的收藏")]),i("v-uni-text",{staticClass:"iconfont icon-arrR c_999 fs_12"})],1),i("v-uni-view",{staticClass:"item uni__list uni__material",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.aboutSys.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"txt flex1"},[t._v("版本信息")]),i("v-uni-text",{staticClass:"c_999 fs_12"},[t._v("版本1.0.5")]),i("v-uni-text",{staticClass:"iconfont icon-arrR c_999 fs_12"})],1),i("v-uni-view",{staticClass:"item uni__list uni__material",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.logoutSys.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"txt flex1"},[t._v("退出账号")])],1)],1),i("uni-pop",{ref:"uniPop"})],1)},s=[];i.d(n,"b",function(){return a}),i.d(n,"c",function(){return s}),i.d(n,"a",function(){return e})},ec50:function(t,n,i){n=t.exports=i("2350")(!1),n.push([t.i,'@charset "UTF-8";\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */\n/* 文章场景相关 */.uni__listview[data-v-7d981600]{padding:%?10?% %?20?%}.bg-grey31[data-v-7d981600]{background-color:#1d1f2b}.uni__list[data-v-7d981600]:after{height:0!important}.uni__list[data-v-7d981600]{background-color:#1d1f2b;color:#f3f3f3;border-radius:%?6?%;margin-bottom:%?8?%}.bgr[data-v-7d981600]{background:-webkit-linear-gradient(top,#210235,#8b2252);background:linear-gradient(180deg,#210235,#8b2252)}.renqi[data-v-7d981600]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;font-size:%?28?%;margin:%?20?% 0 %?10?%;padding-left:%?30?%;color:#fff}.renqi > uni-text[data-v-7d981600]{margin-right:%?20?%}.renqi .renqi_num[data-v-7d981600]{font-weight:700;margin-right:%?10?%}.nav_img[data-v-7d981600]{width:%?80?%;height:%?80?%}.textsm[data-v-7d981600]{font-size:%?20?%}.avator[data-v-7d981600]{border-radius:50%;width:%?110?%;height:%?110?%!important}',""])},f70f:function(t,n,i){"use strict";var e=i("08e0"),a=i.n(e);a.a}}]);