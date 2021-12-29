<template>
  <div id="sortable">
    <div class="img_upload_box" :style="{width:uploadBoxWidth}">
      <el-upload
        ref="uploadImg"
        :action="action"
        list-type="picture-card"
        :file-list="imgList"
        :auto-upload="true"
        :headers="headers"
        :limit="limit"
        :on-preview="handlePictureCardPreview"
        :on-success="(response, file, fileList)=>{return uploadSuccess(response, file, fileList)}"
        :accept="accept"
        :on-remove="(file, fileList)=>{return removerImg(file, fileList)}"
        :on-exceed="exceed"
        :multiple="multiple"
      >
        <i slot="default" class="el-icon-plus" />
      </el-upload>
      <el-dialog :visible.sync="dialogVisible">
        <img width="100%" :src="dialogImageUrl" alt="">
      </el-dialog>
    </div>
  </div>
</template>

<script>
import { getApiUrl, getUploadFileHeaders } from '@/utils/index'
export default {
  name: 'UploadImg',
  props: {
    count: { // 允许上传数量
      type: Number,
      default: 1
    },
    success: { // 上传成功后回调方法
      type: Function,
      default: null
    },
    list: { // 上传列表
      type: Array,
      default: null
    },
    customize: { // 自定义参数
      type: Number,
      default: null
    },
    replace: { // 是否覆盖图片 >0 则覆盖
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      action: getApiUrl('uploadFile'),
      accept: 'image/png,image/jpg,image/jpeg',
      limit: this.count,
      dialogImageUrl: '',
      dialogVisible: false,
      headers: getUploadFileHeaders(),
      imgList: [],
      initDropCard: false, // 初始化dropCard
      uploadBoxWidth: '100%',
      multiple: false // 多选文件
    }
  },
  watch: {
    'list': {
      handler() {
        if (this.imgList.length === 0) {
          this.initImgList()
        }
      },
      deep: true, // 对象内部的属性监听，也叫深度监听
      immediate: true// 首次加载也执行
    },
    'replace': {
      handler(toReplace) {
        if (toReplace > 0) {
          this.initImgList()
        }
      },
      deep: true
    }
  },
  mounted() {
    if (this.count > 0) {
      // this.multiple = true
    }
  },
  methods: {
    initImgList() {
      this.imgList = []
      this.uploadBoxWidth = (this.count * 170 - 20) + 'px'
      if (this.list.length > 0) {
        this.list.forEach(item => {
          this.imgList.splice(this.imgList.length, 0, { url: item })
        })
      }
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url
      this.dialogVisible = true
    },
    uploadSuccess(response, file, fileList) {
      if (response.code === 1) {
        this.imgList.splice(this.imgList.length, 0, { url: response.data.url })
        this.syncList()
      } else {
        var msg = '服务器错误请稍后重试~'
        this.$message.error(msg)
      }
    },
    removerImg(file, fileList) {
      var removeUrl = file.url
      if (removeUrl.response) {
        removeUrl = removeUrl.response.data.url
      }
      var imgList = []
      this.imgList.forEach((item, key) => {
        if (item.url !== removeUrl) {
          imgList.splice(imgList.length, 0, item)
        }
      })
      this.imgList = imgList
      this.syncList()
    },
    // 同步数据
    syncList() {
      var imgList = []
      this.imgList.forEach((item, key) => {
        imgList.splice(imgList.length, 0, item.url)
      })
      if (this.customize !== null) {
        this.success(imgList, this.customize)
      } else {
        this.success(imgList)
      }
    },
    exceed(files, fileList) {
      this.$message.error('最多上传' + this.count + '张图片')
    }
  }
}
</script>

<style lang="scss" scoped>
  #sortable {
    display: flex;
    .img_upload_box {
      height: 150px;
      overflow: hidden;
    }
  }
</style>
