(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-ucenter-clected"],{"3e30":function(t,i,e){"use strict";var n=e("288e");Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var a=n(e("cebc")),s=n(e("5ede")),o=e("2f62"),u={computed:(0,a.default)({},(0,o.mapState)(["user","token"])),data:function(){return{communityList:[],page:1}},onShow:function(){},onLoad:function(){this.getvideolist()},onPullDownRefresh:function(){this.page=1,this.communityList=[],this.getvideolist(),setTimeout(function(){uni.stopPullDownRefresh()},1e3)},onReachBottom:function(){this.page=this.page+1,this.getvideolist()},methods:{getvideolist:function(){var t=this;s.default.request({url:"service=Community.getCollectLists",method:"POST",data:{uid:this.user.id,token:this.user.token,p:this.page}}).then(function(i){0==i.data.data.code&&(1==t.page?(t.communityList=i.data.data.info,console.log(i.data.data.info)):t.communityList=t.communityList.concat(i.data.data.info))}).catch(function(t){})},GoVideoPlay:function(t){uni.navigateTo({url:"/pages/uVideo/play?id="+t})}}};i.default=u},"7cae":function(t,i,e){"use strict";e.r(i);var n=e("e3fc"),a=e("ff3a");for(var s in a)"default"!==s&&function(t){e.d(i,t,function(){return a[t]})}(s);var o,u=e("f0c5"),c=Object(u["a"])(a["default"],n["b"],n["c"],!1,null,"09a4d27e",null,!1,n["a"],o);i["default"]=c.exports},e3fc:function(t,i,e){"use strict";var n,a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"uni__videoWrapper"},[e("v-uni-view",{staticClass:"uni__subNavs"},[e("v-uni-view",{staticClass:"ls flexbox flex_alignc"},[e("v-uni-text",{staticClass:"item on"},[t._v("我的收藏")])],1)],1),e("v-uni-view",{staticClass:"uni_videoLs"},[t._l(t.communityList,function(i,n){return[e("v-uni-view",{key:n+"_0",staticClass:"item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.GoVideoPlay(i.id)}}},[e("v-uni-image",{staticClass:"v-thumb",attrs:{src:i.thumb,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"v-ftinfo"},[e("v-uni-view",{staticClass:"title flexbox flex_alignb"},[t._v(t._s(i.title))]),e("v-uni-view",{staticClass:"flexbox flex_alignc"},[e("v-uni-view",{staticClass:"play flex1"},[e("v-uni-text",{staticClass:"iconfont icon-bofang"}),t._v(t._s(i.watch_ok)+"+次播放")],1),e("v-uni-text",{staticClass:"like"},[t._v(t._s(i.likes)+"赞")])],1)],1)],1)]})],2)],1)},s=[];e.d(i,"b",function(){return a}),e.d(i,"c",function(){return s}),e.d(i,"a",function(){return n})},ff3a:function(t,i,e){"use strict";e.r(i);var n=e("3e30"),a=e.n(n);for(var s in n)"default"!==s&&function(t){e.d(i,t,function(){return n[t]})}(s);i["default"]=a.a}}]);