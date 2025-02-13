<template>
  <div class="app-container">
    <!-- QUERY -->
    <PermissionChecker :permissions="[viewPermission.searchFilter]">
      <el-row>
        <el-col :span="24" class="left-column">
          <div class="wrap-filter-items">
            <el-date-picker
              v-model="query.date"
              class="double-el-date-picker"
              type="daterange"
              range-separator="To"
              :start-placeholder="$t('labels.taskRecordStartDate')"
              :end-placeholder="$t('labels.taskRecordEndDate')"
              format="yyyy-MM-dd"
              value-format="yyyy-MM-dd"
            />
            <el-button class="button-filter1" type="success" plain size="small" @click="getRecords">{{ $t('btn.query') }}</el-button>
            <el-button class="button-filter2" type="info" plain size="small" @click="reset">{{ $t('btn.reset') }}</el-button>
          </div>
        </el-col>
      </el-row>
    </PermissionChecker>
    <!-- TABLE -->
    <el-table v-loading="loading" :data="tableData" height="650" border fit highlight-current-row>
      <!-- Date -->
      <el-table-column align="center" :label="$t('labels.taskRecordDate')" prop="created">
        <template slot-scope="scope">
          <span>{{ scope.row.created }}</span>
        </template>
      </el-table-column>
      <!-- Number of people completed -->
      <el-table-column align="center" :label="$t('labels.taskRecordNumberOfPeopleCompleted')">
        <template slot-scope="scope">
          <span>{{ scope.row.daily + scope.row.recharge + scope.row.timeLimited }}</span>
        </template>
      </el-table-column>
      <!-- Daily Tasks -->
      <el-table-column align="center" :label="$t('labels.taskRecordDailyTasks')">
        <template slot-scope="scope">
          <span>{{ scope.row.daily }}</span>
        </template>
      </el-table-column>
      <!-- Recharge Tasks -->
      <el-table-column align="center" :label="$t('labels.taskRecordRechargeTasks')">
        <template slot-scope="scope">
          <span>{{ scope.row.recharge }}</span>
        </template>
      </el-table-column>
      <!-- Time limited mission -->
      <el-table-column align="center" :label="$t('labels.taskRecordTimeLimitedMission')">
        <template slot-scope="scope">
          <span>{{ scope.row.timeLimited }}</span>
        </template>
      </el-table-column>
      <!-- The total amount of gifts per day -->
      <el-table-column align="center" :label="$t('labels.taskRecordTheTotalAmountOfGiftsPerDay')">
        <template slot-scope="scope">
          <span>{{ scope.row.reward }}</span>
        </template>
      </el-table-column>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getRecords" />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const taskManagementResource = new Resource('task-management/statistic/record');
export default {
  name: 'TaskRecord',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.taskRecord.permissions.controls,
      total: 1,
      query: {
        page: 1,
        size: 10,
        date: '',
        from: '',
        to: '',
      },
      tableData: [],
    };
  },
  created() {
    this.getRecords();
  },
  methods: {
    async getRecords() {
      this.loading = true;
      this.query.from = (typeof (this.query.date[0]) !== 'undefined' || this.query.date[0] !== null) ? this.query.date[0] : null;
      this.query.to = (typeof (this.query.date[1]) !== 'undefined' || this.query.date[1] !== null) ? this.query.date[1] : null;
      taskManagementResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.tableData = response.data;
        })
        .catch(error => {
          console.log(error);
          // this.$message({
          //   type: 'error',
          //   message: error,
          //   duration: 1 * 1000,
          // });
        });
    },
    reset(){
      this.query = {
        page: 1,
        size: 10,
        date: '',
        from: '',
        to: '',
      };
      this.getRecords();
    },
  },
};
</script>

<style lang="scss">
  .wrap-filter-items{
    margin: 20px 0 30px 0;
    display: flex;
    align-items: center;
    height: 40px;
  }
  .button-filter1{
    margin: 0 0 0 15px;
  }
  .double-el-date-picker .el-range-separator {
    width: 50px !important;
  }
</style>
