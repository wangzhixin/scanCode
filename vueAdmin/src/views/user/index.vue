<template>
  <div class="app-container">
    <el-row>
      <el-table v-loading="checkLoading" :data="list" stripe style="width: 100%" :empty-text="emptyText">
        <el-table-column prop="user_id" label="用户ID" width="320" />
        <el-table-column show-overflow-tooltip prop="nick_name" label="昵称" />
        <el-table-column label="头像">
          <template slot-scope="scope">
            <img :src="scope.row.header_url" alt="" style="max-width: 80px;max-height:80px">
          </template>
        </el-table-column>
        <el-table-column prop="add_time" label="注册时间" />
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
  </div>
</template>
<script>
import { getUserList } from '@/api/user'
export default {
  data() {
    return {
      emptyText: '数据加载中……',
      checkLoading: false,
      list: [],
      total: 0,
      pageSize: 0,
      toPage: 1,
      form: {},
      loading: false
    }
  },
  created() {
    this.init()
  },
  methods: {
    init(form) {
      this.checkLoading = true
      getUserList(this.toPage, form).then(res => {
        if (res) {
          this.list = res.data.list
          this.total = res.data.total
          this.pageSize = res.data.pageSize
          if (res.data.list.length === 0) {
            this.emptyText = '暂无数据'
          }
        }
        this.checkLoading = false
      }, res => {
        this.checkLoading = false
      })
    },
    page(page) {
      this.toPage = page
      this.init()
    },
    serchList() {
      this.toPage = 1
      this.init(this.form)
    }
  }

}
</script>
