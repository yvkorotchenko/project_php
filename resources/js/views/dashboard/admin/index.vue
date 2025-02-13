<template>
  <div class="dashboard-editor-container">
    <!-- Top box -->
    <panel-group
      :new-member="newMemberCount"
      :total-recharge="totalRecharge"
      :total-withdrawal="totalWithdrawal"
      :total-profit-loss="totalProfitLoss"
      @handleSetLineChartData="handleSetLineChartData"
    />
    <PermissionChecker :permissions="[viewPermission.registrationChart, viewPermission.loginChart]">
      <el-row :gutter="32">
        <PermissionChecker :permissions="[viewPermission.registrationChart]">
          <el-col :xs="36" :sm="36" :lg="12">
            <div class="chart-wrapper">
              <!-- USER REGISTRATION -->
              <bar-chart
                :style="{ height: '537px' }"
                :title="this.$t('charts.userRegistration')"
                :chart-type="'registration'"
                :mark-inside="'inside'"
                :animation-duration="2000"
              />
            </div>
          </el-col>
        </PermissionChecker>
        <PermissionChecker :permissions="[viewPermission.loginChart]">
          <el-col :xs="36" :sm="36" :lg="12">
            <div class="chart-wrapper">
              <!-- USER LOGIN -->
              <bar-chart
                :style="{ height: '537px' }"
                :title="this.$t('charts.userLogin')"
                :chart-type="'login'"
                :mark-inside="'inside'"
                :animation-duration="2000"
              />
            </div>
          </el-col>
        </PermissionChecker>
      </el-row>
    </PermissionChecker>

    <PermissionChecker :permissions="[viewPermission.platformProfitAndLossPlayerBetting]">
      <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
        <line-chart
          :title="this.$t('charts.lineChartPlatformProfit')"
          :line-blue="this.$t('charts.betAmount')"
          :line-red="this.$t('charts.gameProfitAndLoss')"
        />
      </el-row>
    </PermissionChecker>

    <PermissionChecker :permissions="[viewPermission.betAmountEachPlatform, viewPermission.rechargeAndWithdrawal]">
      <el-row :gutter="32">
        <PermissionChecker :permissions="[viewPermission.betAmountEachPlatform]">
          <el-col :xs="36" :sm="36" :lg="12">
            <div class="chart-wrapper">
              <pie-chart
                :height="'537px'"
                :title="this.$t('charts.betAmountEachPlatform')"
              />
            <!--  <raddar-chart />-->
            </div>
          </el-col>
        </PermissionChecker>
        <PermissionChecker :permissions="[viewPermission.rechargeAndWithdrawal]">
          <el-col :xs="36" :sm="36" :lg="12">
            <div class="chart-wrapper">
              <mixed-chart
                :height="'537px'"
                :title="this.$t('charts.rechargeAndWithdrawal')"
              />
            </div>
          </el-col>
        </PermissionChecker>
      </el-row>
    </PermissionChecker>
  </div>
</template>

<script>
import PanelGroup from './components/PanelGroup';
import LineChart from './components/LineChart';
// import RaddarChart from './components/RaddarChart';
import MixedChart from './components/MixedChart';
import PieChart from './components/PieChart';
import BarChart from './components/BarChart';
import DashboardResource from '@/api/dashboard';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';
const Dashboard = new DashboardResource();

const lineChartData = {
  newVisitis: {
    expectedData: [100, 120, 161, 134, 105, 160, 165],
    actualData: [120, 82, 91, 154, 162, 140, 145],
  },
};
export default {
  name: 'DashboardAdmin',
  components: {
    MixedChart,
    PanelGroup, // top box
    LineChart,
    // RaddarChart,
    PieChart,
    BarChart,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.homePage.permissions.controls,
      lineChartData: lineChartData.newVisitis,
      // PanelGroup
      newMemberCount: null,
      newMemberDate: null,
      totalRecharge: 0,
      totalWithdrawal: 0,
      totalProfitLoss: 0,
      // list platforms
      betAmountPlatforms: [],
    };
  },
  created() {
    this.handleNewMembers();
    this.handlerTotalStatistics();
  },
  beforeMount() {
    // console.log('Run ThRE!!');
  },
  methods: {
    handleSetLineChartData(type) {
      this.lineChartData = lineChartData[type];
    },
    handleNewMembers() {
      Dashboard
        .newMembers()
        .then(response => {
          const { data } = response;
          this.newMemberCount = data.count;
          this.newMemberDate = data.date;
        });
    },
    handlerTotalStatistics() {
      Dashboard
        .totalStatistics()
        .then(response => {
          const { data } = response;
          this.totalRecharge = data.totalRecharge;
          this.totalWithdrawal = data.totalWithdrawal;
          this.totalProfitLoss = data.totalProfitAndLos;
        });
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard-editor-container {
  padding: 32px;
  background-color: rgb(240, 242, 245);
  .chart-wrapper {
    background: #fff;
    padding: 16px 16px 0;
    margin-bottom: 32px;
  }
}
</style>
