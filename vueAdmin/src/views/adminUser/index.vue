<template>
  <div class="app-container">
    <el-row type="flex" justify="end">
      <el-button type="primary" icon="el-icon-plus" @click="addProblem">添加管理员</el-button>
    </el-row>
    <el-row>
      <el-table v-loading="checkLoading" :data="list" stripe style="width: 100%" :empty-text="emptyText">
        <el-table-column prop="admin_id" label="序号" />
        <el-table-column prop="admin_name" show-overflow-tooltip label="账户" />

        <el-table-column prop="address" label="操作">
          <template slot-scope="scope">
            <el-button type="text" @click="editProblem(scope.row)">编辑</el-button>
            <el-popconfirm v-if="scope.row.admin_type===2" title="确定提交删除吗？" style="margin-left: 10px;" @onConfirm="deleteHospital(scope.row.admin_id)">
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

    <el-dialog title="帐号管理" :visible.sync="showProblem" width="600px" :before-close="cancelForm">
      <el-form ref="form" status-icon :model="form" :rules="rules">
        <el-form-item label="账户：" label-width="120px" prop="admin_name">
          <el-input v-model="form.admin_name" />
        </el-form-item>
        <el-form-item label="密码：" label-width="120px" prop="admin_password">
          <el-input v-model="form.admin_password" show-password />
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
import { getAdminUserList, deleteAdminUser, submitAdminUser } from '@/api/adminUser'
export default {
  data() {
    return {
      showProblem: false,
      emptyText: '',
      clickTab: false,
      checkLoading: false,
      list: [],
      total: 0,
      pageSize: 0,
      toPage: 1,
      form: {
        admin_id: '',
        admin_name: '',
        admin_password: ''
      },
      rules: {
        admin_name: [{ required: true, message: '请输入账户', trigger: 'blur' }],
        admin_password: [{ required: true, message: '请输入密码', trigger: 'blur' }]
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
      getAdminUserList(this.toPage).then(res => {
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
      this.form = {
        admin_id: '',
        admin_name: '',
        admin_password: ''
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
          submitAdminUser(this.form).then(res => {
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
        admin_id: row.admin_id,
        admin_name: row.admin_name,
        admin_password: ''
      }
    },
    deleteHospital(admin_id) {
      this.checkLoading = true
      deleteAdminUser(admin_id).then(res => {
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
    },
    toDepartment(row) {
      this.$router.push({ path: '/department', query: { id: row.admin_id }})
    }
  }

}
</script>
