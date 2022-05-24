<template>
  <div class="app-container">
    <el-row type="flex" justify="end">
      <el-button type="primary" icon="el-icon-plus" @click="addProblem">添加问题</el-button>
    </el-row>
    <el-row>
      <el-table v-loading="checkLoading" :data="list" stripe style="width: 100%" :empty-text="emptyText">
        <el-table-column prop="problem_id" label="序号" />
        <el-table-column label="类型">
          <template slot-scope="scope">
            <div v-if="scope.row.type==1">
              单选
            </div>
            <div v-else>输入</div>

          </template>
        </el-table-column>
        <el-table-column prop="problem" show-overflow-tooltip label="问题" />
        <el-table-column prop="problem_en" show-overflow-tooltip label="英文" />
        <el-table-column label="图片">
          <template slot-scope="scope">
            <div v-if="scope.row.img.length>0">
              <el-image
                style="max-width: 80px;max-height:80px;margin-left: 10px;"
                :src="scope.row.img"
                :preview-src-list="[scope.row.img]"
              />
            </div>
            <div v-else>-</div>

          </template>
        </el-table-column>

        <el-table-column prop="address" label="操作">
          <template slot-scope="scope">
            <el-button type="text" @click="editProblem(scope.row)">编辑</el-button>
            <el-popconfirm title="确定提交删除吗？" style="margin-left: 10px;" @onConfirm="deleteProblem(scope.row.problem_id)">
              <el-button slot="reference" type="text">删除</el-button>
            </el-popconfirm>
          </template>
        </el-table-column>
      </el-table>
    </el-row>

    <el-dialog title="问题信息" :visible.sync="showProblem" width="600px" :before-close="cancelForm">
      <el-form ref="form" status-icon :model="form" :rules="rules">
        <el-form-item label="问题：" label-width="120px" prop="problem">
          <el-radio-group v-model="form.type">
            <el-radio :label="1">单选</el-radio>
            <el-radio :label="2">输入</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="问题：" label-width="120px" prop="problem">
          <el-input v-model="form.problem" />
        </el-form-item>
        <el-form-item label="英文：" label-width="120px" prop="problem_en">
          <el-input v-model="form.problem_en" />
        </el-form-item>
        <el-form-item label="图片：" label-width="120px">
          <uploadImg :count="1" :success="uploadImgSuccess" :list="form.img?[form.img]:[]" :replace="replace" />
        </el-form-item>
      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="cancelForm">取 消</el-button>
        <el-button type="primary" @click="submit">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>
<script>
import { problemList, submitProblem, deleteProblem } from '@/api/problem'
import UploadImg from '@/components/UploadImg'
export default {
  components: {
    UploadImg
  },
  data() {
    return {
      replace: 1,
      showProblem: false,
      emptyText: '',
      clickTab: false,
      checkLoading: false,
      list: [],
      form: {
        type: 1,
        problem: '',
        problem_en: '',
        img: ''
      },
      rules: {
        problem: [
          { required: true, message: '请输入问题', trigger: 'blur' }
        ],
        problem_en: [
          { required: true, message: '请输入英文', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      this.list = []
      this.emptyText = '数据加载中……'
      problemList().then(res => {
        if (res.data.list.length === 0) {
          this.emptyText = '暂无数据'
        } else {
          this.list = res.data.list
        }
        this.clickTab = true
      }, res => {
        this.clickTab = true
      })
    },
    addProblem() {
      this.showProblem = true
    },
    initForm() {
      this.replace++
      this.form = {
        type: 1,
        problem: '',
        problem_en: '',
        img: ''
      }
    },
    cancelForm() {
      this.initForm()
      this.showProblem = false
    },
    submit() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          const loading = this.$loading({
            lock: true,
            text: 'Loading',
            spinner: 'el-icon-loading',
            background: 'rgba(0, 0, 0, 0.7)'
          })
          submitProblem(this.form).then(res => {
            if (res) {
              this.cancelForm()
              this.init()
            }
            loading.close()
          }).catch(res => {
            loading.close()
          })
        } else {
          return false
        }
      })
    },
    editProblem(row) {
      this.showProblem = true
      this.form = {
        type: row.type,
        problem_id: row.problem_id,
        problem: row.problem,
        problem_en: row.problem_en,
        img: row.img
      }
    },
    deleteProblem(problem_id) {
      this.checkLoading = true
      deleteProblem(problem_id).then(res => {
        if (res) {
          this.init()
          this.checkLoading = false
        }
      }).catch(() => {
        this.checkLoading = false
      })
    },
    uploadImgSuccess(imgList) {
      this.form.img = imgList[0]
    }
  }

}
</script>
