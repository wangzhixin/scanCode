<template>
  <div class="app-container">
    <el-row type="flex" justify="end">
      <el-button type="primary" icon="el-icon-plus" @click="addProblem">添加科室</el-button>
    </el-row>
    <el-row>
      <el-table v-loading="checkLoading" :data="list" stripe style="width: 100%" :empty-text="emptyText">
        <el-table-column prop="department_id" label="序号" />
        <el-table-column prop="department_name" show-overflow-tooltip label="科室名称" />

        <el-table-column prop="address" label="操作">
          <template slot-scope="scope">
            <el-button type="text" @click="editProblem(scope.row)">编辑</el-button>
            <el-popconfirm title="确定提交删除吗？" style="margin-left: 10px;" @onConfirm="deleteDepartmentList(scope.row.department_id)">
              <el-button slot="reference" type="text">删除</el-button>
            </el-popconfirm>
          </template>
        </el-table-column>
      </el-table>
    </el-row>
    <el-row type="flex" justify="end" style="margin-top: 20px;">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="total"
        :page-size="pageSize"
        hide-on-single-page
        @current-change="page"
      />
    </el-row>

    <el-dialog title="科室操作" :visible.sync="showProblem" width="600px" :before-close="cancelForm">
      <el-form ref="form" status-icon :model="form" :rules="rules">
        <el-form-item label="科室名称：" label-width="120px" prop="department_name">
          <el-input v-model="form.department_name" />
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
import { getDepartmentList, submitDepartmentList, deleteDepartmentList } from '@/api/hospitalList'
export default {
  data() {
    return {
      thisId: this.$route.query.id ? this.$route.query.id : null,
      replace: 1,
      showProblem: false,
      emptyText: '',
      clickTab: false,
      checkLoading: false,
      list: [],
      total: 0,
      pageSize: 0,
      toPage: 1,
      form: {
        department_name: '',
        department_id: ''
      },
      rules: {
        department_name: [
          { required: true, message: '请输入科室名称', trigger: 'blur' }
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
      getDepartmentList(this.thisId, this.toPage).then(res => {
        if (res.data.list.length === 0) {
          this.emptyText = '暂无数据'
        } else {
          this.list = res.data.list
          this.total = res.data.total
          this.pageSize = res.data.pageSize
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
          this.form.hospital_id = this.thisId
          submitDepartmentList(this.form).then(res => {
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
        department_id: row.department_id,
        department_name: row.department_name
      }
    },
    deleteDepartmentList(department_id) {
      this.checkLoading = true
      deleteDepartmentList(department_id).then(res => {
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
    },
    page(page) {
      this.toPage = page
      this.init()
    }
  }

}
</script>
