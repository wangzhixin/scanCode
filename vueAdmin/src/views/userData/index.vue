<template>
  <div class="app-container">
    <el-row type="flex" justify="end">
      <el-button type="primary" :loading="downloadLoading" icon="el-icon-document" @click="exportData">导出</el-button>
    </el-row>
    <el-row>
      <el-table v-loading="checkLoading" :data="list" stripe style="width: 100%" :empty-text="emptyText" row-key="data_id" @select-all="chooseRow" @select="chooseRow">
        <el-table-column type="selection" width="55" :reserve-selection="true" />
        <el-table-column type="expand">
          <template slot-scope="props">
            <div v-for="(each,key) in props.row.problemList" :key="key">
              <p>{{ each.problem }}</p>
              <p v-if="each.value==='1'" style="color:red;">是</p>
              <p v-else>否</p>
              <el-divider />
            </div>
          </template>
        </el-table-column>

        <el-table-column prop="data_id" label="序号" />
        <el-table-column prop="name" label="姓名" />
        <el-table-column prop="type" label="状态">
          <template slot-scope="scope">
            <span v-if="scope.row.type==1">正常</span>
            <span v-else style="color:red;">异常</span>
          </template>
        </el-table-column>
        <el-table-column prop="user_type" label="类型" />
        <el-table-column prop="id_type" label="证件类型" />
        <el-table-column prop="id_number" show-overflow-tooltip label="证件号码" />
        <el-table-column prop="phone_number" label="电话" />
        <el-table-column prop="province_value" label="省" />
        <el-table-column prop="city_value" label="市" />
        <el-table-column prop="district_value" label="区" />
        <el-table-column prop="address" show-overflow-tooltip label="地址" />
        <el-table-column prop="hospital_value" show-overflow-tooltip label="就诊医院" />
        <el-table-column prop="department_value" show-overflow-tooltip label="就诊科室" />
        <el-table-column prop="invalid_time" label="失效时间" />
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
import { getUserDataList, exportUserData } from '@/api/userData'
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
      chooseList: [],
      downloadLoading: false
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      this.list = []
      this.emptyText = '数据加载中……'
      getUserDataList(this.toPage).then(res => {
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
    exportData() {
      console.log(this.chooseList)

      if (this.chooseList.length === 0) {
        this.$message({
          message: '请选中数据导出~',
          type: 'warning'
        })
      } else {
        this.downloadLoading = true
        const ids = []
        this.chooseList.forEach(item => {
          ids.splice(ids.length, 0, item['data_id'])
        })
        exportUserData(ids).then(res => {
          if (res) {
            const tHeader = res.data.tHeader
            const data = res.data.list
            const filename = res.data.filename
            import('@/vendor/Export2Excel').then(excel => {
              excel.export_json_to_excel({
                header: tHeader,
                data,
                filename: filename
              })
              this.downloadLoading = false
            })
          }
        }, res => {
          this.downloadLoading = false
        })
      }
    },
    page(page) {
      this.toPage = page
      this.init()
    },
    chooseRow(row) {
      this.chooseList = row
    }
  }

}
</script>
