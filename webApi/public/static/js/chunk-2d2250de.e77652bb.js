(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d2250de"],{e382:function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-row",[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.checkLoading,expression:"checkLoading"}],staticStyle:{width:"100%"},attrs:{data:t.list,stripe:"","empty-text":t.emptyText}},[a("el-table-column",{attrs:{prop:"user_id",label:"用户ID",width:"320"}}),a("el-table-column",{attrs:{"show-overflow-tooltip":"",prop:"nick_name",label:"昵称"}}),a("el-table-column",{attrs:{label:"头像"},scopedSlots:t._u([{key:"default",fn:function(t){return[a("img",{staticStyle:{"max-width":"80px","max-height":"80px"},attrs:{src:t.row.header_url,alt:""}})]}}])}),a("el-table-column",{attrs:{prop:"add_time",label:"注册时间"}})],1)],1),a("el-row",{staticStyle:{"margin-top":"20px"},attrs:{type:"flex",justify:"end"}},[a("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:t.total,"page-size":t.pageSize,"hide-on-single-page":""},on:{"current-change":t.page}})],1)],1)},n=[],l=a("c24f"),o={data:function(){return{emptyText:"数据加载中……",checkLoading:!1,list:[],total:0,pageSize:0,toPage:1,form:{},loading:!1}},created:function(){this.init()},methods:{init:function(t){var e=this;this.checkLoading=!0,Object(l["b"])(this.toPage,t).then((function(t){t&&(e.list=t.data.list,e.total=t.data.total,e.pageSize=t.data.pageSize,0===t.data.list.length&&(e.emptyText="暂无数据")),e.checkLoading=!1}),(function(t){e.checkLoading=!1}))},page:function(t){this.toPage=t,this.init()},serchList:function(){this.toPage=1,this.init(this.form)}}},s=o,c=a("2877"),r=Object(c["a"])(s,i,n,!1,null,null,null);e["default"]=r.exports}}]);