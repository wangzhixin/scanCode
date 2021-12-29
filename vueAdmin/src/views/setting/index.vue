<template>
  <div class="app-container">
    <div class="form-main">
      <el-form
        ref="form"
        :model="form"
        :rules="rules"
        label-width="150px"
        class="demo-form"
      >
        <el-form-item label="访问标题：" prop="title" style="width:500px;">
          <el-input v-model="form.title" placeholder="请输入访问标题" />
        </el-form-item>
        <el-form-item label="访问标题(英文)：" prop="title_en" style="width:500px;">
          <el-input v-model="form.title_en" placeholder="请输入访问标题" />
        </el-form-item>
        <el-form-item label="Logo：" prop="logo">
          <uploadImg :count="1" :success="uploadImgSuccess" :list="form.logo?[form.logo]:[]" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm()">保存</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<script>
import UploadImg from '@/components/UploadImg'
import { submitSetting, getSetting } from '@/api/setting'
export default {
  components: {
    UploadImg
  },
  data() {
    return {
      form: {
        logo: '',
        title: '',
        title_en: ''
      },
      rules: {
        logo: [
          { required: true, message: '请上传Logo', trigger: 'change' }
        ],
        title: [
          { required: true, message: '请输入访问标题', trigger: 'change' }
        ],
        title_en: [
          { required: true, message: '请输入访问标题(英文)', trigger: 'change' }
        ]
      }
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      getSetting().then(res => {
        if (res) {
          this.form = res.data
        }
      })
    },
    submitForm() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          const loading = this.$loading({
            lock: true,
            text: '提交中',
            spinner: 'el-icon-loading',
            background: 'rgba(0, 0, 0, 0.7)'
          })
          submitSetting(this.form).then(res => {
            loading.close()
            this.init()
          }).catch(() => {
            loading.close()
          })
        } else {
          return false
        }
      })
    },
    uploadImgSuccess(imgList) {
      this.form.logo = imgList[0]
    }
  }

}
</script>
<style lang="scss" scoped>
</style>
