<template>
  <div class="app-container">
    <!-- item -->
    <PermissionChecker :permissions="[viewPermission.searchFilter]">
      <el-row>
        <el-col :span="24" class="left-column">
          <div class="wrap-filter-items">
            <el-input
              v-model="query.uid"
              :placeholder="$t('labels.taskDetailsPlayerID')"
            />
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
            <el-button class="button-filter1" type="success" size="small" plain @click="getDetails">{{ $t('btn.query') }}</el-button>
            <el-button class="button-filter2" type="info" size="small" plain @click="reset">{{ $t('btn.reset') }}</el-button>
          </div>
        </el-col>
      </el-row>
    </PermissionChecker>
    <!-- TABLE -->
    <el-table v-loading="loading" :data="tableData" height="650" border fit highlight-current-row>
      <!-- Date -->
      <el-table-column align="center" :label="$t('labels.taskDetailsDate')">
        <template slot-scope="scope">
          <span>{{ scope.row.created }}</span>
        </template>
      </el-table-column>
      <!-- Player ID -->
      <el-table-column align="center" :label="$t('labels.taskDetailsPlayerID')">
        <template slot-scope="scope">
          <span>{{ scope.row.uid }}</span>
        </template>
      </el-table-column>
      <!-- Player last login IP -->
      <el-table-column align="center" :label="$t('labels.taskDetailsPlayerLastLoginIP')">
        <template slot-scope="scope">
          <span>{{ scope.row.loginIp }}</span>
        </template>
      </el-table-column>
      <!-- Player total recharge -->
      <el-table-column align="center" :label="$t('labels.taskDetailsPlayerTotalRecharge')">
        <template slot-scope="scope">
          <span>{{ scope.row.rechargeAmount }}</span>
        </template>
      </el-table-column>
      <!-- Daily Tasks completed -->
      <el-table-column align="center" :label="$t('labels.taskDetailsDailyTasksCompleted')">
        <template slot-scope="scope">
          <span>{{ scope.row.daily }}</span>
        </template>
      </el-table-column>
      <!-- Recharge Tasks completed -->
      <el-table-column align="center" :label="$t('labels.taskDetailsRechargeTasksCompleted')">
        <template slot-scope="scope">
          <span>{{ scope.row.recharge }}</span>
        </template>
      </el-table-column>
      <!-- Time limited mission completed -->
      <el-table-column align="center" :label="$t('labels.taskDetailsTimeLimitedMissionCompleted')">
        <template slot-scope="scope">
          <span>{{ scope.row.timeLimited }}</span>
        </template>
      </el-table-column>
      <!-- Task total gift amount for the day -->
      <el-table-column align="center" :label="$t('labels.taskDetailsTaskTotalGiftAmountForTheDay')">
        <template slot-scope="scope">
          <span>{{ scope.row.reward }}</span>
        </template>
      </el-table-column>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getDetails" />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const taskManagementResource = new Resource('task-management/statistic/details');
export default {
  name: 'TaskDetails',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.taskDetails.permissions.controls,
      total: 1,
      query: {
        page: 1,
        size: 10,
        date: '',
        from: '',
        to: '',
        uid: '',
      },
      tableData: [],
    };
  },

  created() {
    this.getDetails();
  },
  methods: {
    async getDetails() {
      this.loading = true;
      this.query.from = (typeof (this.query.date[0]) !== 'undefined' || this.query.date[0] !== null) ? this.query.date[0] : null;
      this.query.to = (typeof (this.query.date[1]) !== 'undefined' || this.query.date[1] !== null) ? this.query.date[1] : null;
      taskManagementResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.tableData = response.data;
        });
    },
    reset(){
      this.query = {
        page: 1,
        size: 20,
        date: '',
        from: '',
        to: '',
        uid: '',
      };
      this.getDetails();
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
  .wrap-filter-items .el-input {
    width: 180px;
  }
  .double-el-date-picker .el-range-separator {
    width: 50px !important;
  }
</style>
