(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-78c8ea6a"],{"3e56":function(t,e,a){"use strict";a.r(e);var l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-row",{attrs:{type:"flex",justify:"end"}},[a("el-button",{attrs:{type:"primary",loading:t.downloadLoading,icon:"el-icon-document"},on:{click:t.exportData}},[t._v("导出")])],1),a("el-row",[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.checkLoading,expression:"checkLoading"}],staticStyle:{width:"100%"},attrs:{data:t.list,stripe:"","empty-text":t.emptyText,"row-key":"data_id"},on:{"select-all":t.chooseRow,select:t.chooseRow}},[a("el-table-column",{attrs:{type:"selection",width:"55","reserve-selection":!0}}),a("el-table-column",{attrs:{type:"expand"},scopedSlots:t._u([{key:"default",fn:function(e){return t._l(e.row.problemList,(function(e,l){return a("div",{key:l},[a("p",[t._v(t._s(e.problem))]),"1"===e.value?a("p",{staticStyle:{color:"red"}},[t._v("是")]):a("p",[t._v("否")]),a("el-divider")],1)}))}}])}),a("el-table-column",{attrs:{prop:"data_id",label:"序号"}}),a("el-table-column",{attrs:{prop:"name",label:"姓名"}}),a("el-table-column",{attrs:{prop:"type",label:"状态"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.type?a("span",[t._v("正常")]):a("span",{staticStyle:{color:"red"}},[t._v("异常")])]}}])}),a("el-table-column",{attrs:{prop:"user_type",label:"类型"}}),a("el-table-column",{attrs:{prop:"id_type",label:"证件类型"}}),a("el-table-column",{attrs:{prop:"id_number","show-overflow-tooltip":"",label:"证件号码"}}),a("el-table-column",{attrs:{prop:"phone_number",label:"电话"}}),a("el-table-column",{attrs:{prop:"province_value",label:"省"}}),a("el-table-column",{attrs:{prop:"city_value",label:"市"}}),a("el-table-column",{attrs:{prop:"district_value",label:"区"}}),a("el-table-column",{attrs:{prop:"address","show-overflow-tooltip":"",label:"地址"}}),a("el-table-column",{attrs:{prop:"hospital_value","show-overflow-tooltip":"",label:"就诊医院"}}),a("el-table-column",{attrs:{prop:"department_value","show-overflow-tooltip":"",label:"就诊科室"}}),a("el-table-column",{attrs:{prop:"invalid_time",label:"失效时间"}})],1)],1),a("el-row",{staticStyle:{"margin-top":"20px"},attrs:{type:"flex",justify:"end"}},[a("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:t.total,"page-size":t.pageSize,"hide-on-single-page":""},on:{"current-change":t.page}})],1)],1)},o=[],n=(a("d3b7"),a("159b"),a("a434"),a("3ca3"),a("ddb0"),a("b775"));function i(t){var e={page:t};return Object(n["a"])({url:"/getUserDataList",method:"post",data:e})}function r(t){var e={ids:t};return Object(n["a"])({url:"/exportUserData",method:"post",data:e})}var s={data:function(){return{showProblem:!1,emptyText:"",clickTab:!1,checkLoading:!1,list:[],total:0,pageSize:0,toPage:1,chooseList:[],downloadLoading:!1}},created:function(){this.init()},methods:{init:function(){var t=this;this.list=[],this.emptyText="数据加载中……",i(this.toPage).then((function(e){0===e.data.list.length?t.emptyText="暂无数据":(t.list=e.data.list,t.total=e.data.total,t.pageSize=e.data.pageSize),t.clickTab=!0}),(function(e){t.clickTab=!0}))},exportData:function(){var t=this;if(console.log(this.chooseList),0===this.chooseList.length)this.$message({message:"请选中数据导出~",type:"warning"});else{this.downloadLoading=!0;var e=[];this.chooseList.forEach((function(t){e.splice(e.length,0,t["data_id"])})),r(e).then((function(e){if(e){var l=e.data.tHeader,o=e.data.list,n=e.data.filename;Promise.all([a.e("chunk-df9fae8c"),a.e("chunk-2125b98f")]).then(a.bind(null,"4bf8")).then((function(e){e.export_json_to_excel({header:l,data:o,filename:n}),t.downloadLoading=!1}))}}),(function(e){t.downloadLoading=!1}))}},page:function(t){this.toPage=t,this.init()},chooseRow:function(t){this.chooseList=t}}},c=s,p=a("2877"),d=Object(p["a"])(c,l,o,!1,null,null,null);e["default"]=d.exports},a434:function(t,e,a){"use strict";var l=a("23e7"),o=a("23cb"),n=a("a691"),i=a("50c4"),r=a("7b0b"),s=a("65f0"),c=a("8418"),p=a("1dde"),d=a("ae40"),u=p("splice"),h=d("splice",{ACCESSORS:!0,0:0,1:2}),b=Math.max,f=Math.min,m=9007199254740991,g="Maximum allowed length exceeded";l({target:"Array",proto:!0,forced:!u||!h},{splice:function(t,e){var a,l,p,d,u,h,v=r(this),w=i(v.length),y=o(t,w),_=arguments.length;if(0===_?a=l=0:1===_?(a=0,l=w-y):(a=_-2,l=f(b(n(e),0),w-y)),w+a-l>m)throw TypeError(g);for(p=s(v,l),d=0;d<l;d++)u=y+d,u in v&&c(p,d,v[u]);if(p.length=l,a<l){for(d=y;d<w-l;d++)u=d+l,h=d+a,u in v?v[h]=v[u]:delete v[h];for(d=w;d>w-l+a;d--)delete v[d-1]}else if(a>l)for(d=w-l;d>y;d--)u=d+l-1,h=d+a-1,u in v?v[h]=v[u]:delete v[h];for(d=0;d<a;d++)v[d+y]=arguments[d+2];return v.length=w-l+a,p}})}}]);