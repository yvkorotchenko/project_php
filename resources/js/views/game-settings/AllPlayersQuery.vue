<template>
  <div class="app-container">
    <!-- FILTERS -->
    <PermissionChecker :permissions="[viewPermission.searchFilter]">
      <div class="filter-container">
        <el-row type="flex" justify="start">
          <el-col :span="3">
            <div class="grid-content">
              <!-- SELECT Player ID, Mobile Number, Bank Card Name, Bank Card Number, VIP level -->
              <el-select
                v-model="query.fieldName"
                class="filter-item"
              >
                <el-option v-for="item in firstSelect" :key="item.value" :label="item.label" :value="item.value" />
              </el-select>
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <!-- FIELD VALUE -->
              <el-input
                v-model="query.fieldValue"
                class="filter-item"
              />
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <!-- SELECT Player ID, Mobile Number, Bank Card Name, Bank Card Number, VIP level -->
              <el-select
                v-model="query.total"
                class="filter-item"
              >
                <el-option v-for="item in secondSelect" :key="item.value" :label="item.label" :value="item.value" />
              </el-select>
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <!-- LOWER LIMIT -->
              <el-input
                v-model="query.lowerLimit"
                class="filter-item"
                :placeholder="$t('placeholders.lowerLimit')"
              />
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <!-- UPPER LIMIT -->
              <el-input
                v-model="query.upperLimit"
                class="filter-item"
                :placeholder="$t('placeholders.upperLimit')"
              />
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <!-- REGISTRATION START TIME -->
              <el-date-picker
                v-model="query.registerStart"
                class="filter-item"
                type="datetime"
                :placeholder="$t('placeholders.registrationStartTime')"
                format="yyyy-MM-dd hh:mm:ss"
                value-format="yyyy-MM-ddThh:mm:ss"
              />
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <!-- REISTRATION END TIME -->
              <el-date-picker
                v-model="query.registerEnd"
                class="filter-item"
                type="datetime"
                :placeholder="$t('placeholders.registrationEndTime')"
                format="yyyy-MM-dd hh:mm:ss"
                value-format="yyyy-MM-ddThh:mm:ss"
              />
            </div>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="3">
            <div class="grid-content">
              <!-- LAST LOGIN START TIME -->
              <el-date-picker
                v-model="query.loginStart"
                class="filter-item"
                type="datetime"
                :placeholder="$t('placeholders.lastLoginStartTime')"
                format="yyyy-MM-dd HH:mm:ss"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <!-- LAST LOGIN END TIME -->
              <el-date-picker
                v-model="query.loginEnd"
                class="filter-item"
                type="datetime"
                :placeholder="$t('placeholders.lastLoginEndTime')"
                format="yyyy-MM-dd HH:mm:ss"
                value-format="yyyy-MM-dd HH:mm:ss"
              />
            </div>
          </el-col>
          <el-col :span="8">
            <div class="grid-content">
              <!-- CHANNELS -->
              <el-select
                v-model="query.channels"
                class="filter-item"
                multiple
                collapse-tags
                :placeholder="$t('placeholders.sourceChannelMultipleChoicesCanBeSeparatedByCommas')"
                style="width: 100%"
              >
                <el-option v-for="item in channels" :key="item.id" :label="item.name" :value="item.id" />
              </el-select>
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <!-- VIPLEVELS -->
              <el-select
                v-model="query.vipLevels"
                class="filter-item"
                multiple
                collapse-tags
                :placeholder="$t('select.vIPLevel')"
                style="width: 100%"
              >
                <el-option v-for="item in vipLevels" :key="item.id" :label="item.name" :value="item.id" />
              </el-select>
            </div>
          </el-col>
          <el-col :span="3">
            <div class="grid-content">
              <el-button type="success" @click="getList">{{ $t('btn.query') }}</el-button>
              <el-button type="primary" @click="resetFilter">{{ $t('btn.reset') }}</el-button>
            </div>
          </el-col>
        </el-row>
      </div>
    </PermissionChecker>
    <el-row>
      <el-col>
        <!-- table top row icon -->
        <PermissionChecker :permissions="[viewPermission.downloadExcel, viewPermission.downloadCSV, viewPermission.printTable]">
          <div id="wrap-table">
            <div class="source">
              <div align="right" class="wrap-icon">
                <i class="item-icon">
                  <PermissionChecker :permissions="[viewPermission.downloadExcel, viewPermission.downloadCSV]">
                    <el-dropdown>
                      <span class="el-dropdown-link">
                        <i class="el-icon-folder-opened" />
                      </span>
                      <el-dropdown-menu slot="dropdown">
                        <PermissionChecker :permissions="[viewPermission.downloadExcel]">
                          <el-dropdown-item @click.native="getExcelFile">{{ $t('labels.downloadExcel') }}</el-dropdown-item>
                        </PermissionChecker>
                        <PermissionChecker :permissions="[viewPermission.downloadCSV]">
                          <el-dropdown-item @click.native="getCSVFile">{{ $t('labels.downloadCSV') }}</el-dropdown-item>
                        </PermissionChecker>
                      </el-dropdown-menu>
                    </el-dropdown>
                  </PermissionChecker>
                </i>
                <i class="item-icon">
                  <PermissionChecker :permissions="[viewPermission.printTable]">
                    <i v-print="printObj" class="el-icon-printer" />
                  </PermissionChecker>
                </i>
              </div>
            </div>
          </div>
        </PermissionChecker>
        <!-- table div -->
        <div>
          <div id="soTest">
            <div class="soflex1">
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryId') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.id }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryBalance') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.balance }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryAccountStatus') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.account }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryVIPLevel') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.vipLevel }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryTotalRecharge') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.totalRecharge }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryTotalWithdrawal') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.totalWithdraw }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryHistoryTotalCoding') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.totalEffectiveBet }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQuerySourceChannel') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.channel }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryRegistrationPlatform') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.platform }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryRegistrationTime') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.regTime }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryLastLoginTime') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.loginTime }}</div>
              </div>
              <div>
                <div><div class="table-title">{{ $t('labels.allPlayerQueryPlayingGame') }}</div></div>
                <div v-for="item in list" :key="item.id">{{ item.game }}</div>
              </div>
            </div>
          </div>
        </div>
      </el-col>
    </el-row>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';
const allPlayersQueryResource = new Resource('allplayersquery');
const usersChannelResource = new Resource('users-channel');
const vipLevelResource = new Resource('users-vip-level');

export default {
  name: 'AllPlayersQuery',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.allPlayersQuery.permissions.controls,
      total: 0,
      list: null,
      channels: null,
      vipLevels: null,
      loading: true,
      query: {
        page: 1,
        size: 15,
        id: '',
        fieldName: '',
        fieldValue: '',
        total: '',
        upperLimit: '',
        lowerLimit: '',
        registerStart: '',
        registerEnd: '',
        loginStart: '',
        loginEnd: '',
        channels: '',
        vipLevels: '',
      },
      printObj: {
        type: 'html',
        scanStyles: false,
        id: 'soTest',
        popTitle: 'Ip Query print',
        beforeOpenCallback(vue) {
          vue.printLoading = true;
        },
        openCallback(vue) {
          vue.printLoading = false;
        },
        closeCallback(vue) {
        },
      },
      firstSelect: [{
        value: 'id',
        label: this.$t('select.playerId'),
      },
      {
        value: 'phone',
        label: this.$t('select.mobileNumber'),
      },
      // {
      //   value: 'bankCardName',
      //   label: this.$t('select.bankCardName'),
      // },
      // {
      //   value: 'bankCardNumber',
      //   label: this.$t('select.bankCardNumber'),
      // },
      // {
      //   value: 'vipLevel',
      //   label: this.$t('select.vIPLevel'),
      // },
      ],
      secondSelect: [{
        value: 'balance',
        label: this.$t('select.balance'),
      },
      {
        value: 'recharge',
        label: this.$t('select.totalRecharge'),
      },
      {
        value: 'withdrawal',
        label: this.$t('select.totalWithdrawal'),
      },
      {
        value: 'effectiveBet',
        label: this.$t('select.historyTotalCoding'),
      }],
    };
  },
  created() {
    this.getSource();
    this.getVipLevel();
    this.getList();
    this.query.fieldName = 'id';
    this.query.total = 'balance';
  },
  methods: {
    async getSource() {
      await usersChannelResource.list(this.query)
        .then(response => {
          this.channels = response.data.data.data;
        });
    },
    async getVipLevel() {
      await vipLevelResource.list(this.query)
        .then(response => {
          this.vipLevels = response.data.data.data;
        });
    },
    async getList() {
      this.loading = true;
      await allPlayersQueryResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.list = response.data;
        });
    },
    resetFilter() {
      this.query = {
        id: '',
        fieldName: 'id',
        fieldValue: '',
        total: 'balance',
        upperLimit: '',
        lowerLimit: '',
        registerStart: '',
        registerEnd: '',
        loginStart: '',
        loginEnd: '',
        channels: '',
      };
      this.getList();
    },
    getExcelFile() {
      this.loading = true;
      this.downloading = true;
      const query = {};
      Object.assign(query, this.query);
      query.page = 1;
      query.size = this.total;
      allPlayersQueryResource.list(query)
        .then(response => {
          this.loading = false;
          import('@/vendor/Export2Excel').then(excel => {
            const tHeader = ['Player ID', 'Balance', 'Account Status', 'VIP Level', 'Total recharge', 'Total Withdrawal', 'History total coding', 'Source Channel', 'Registration Platform', 'Registration time', 'Last login time', 'Playing a game'];
            const filterVal = ['id', 'balance', 'account', 'vipLevel', 'totalRecharge', 'totalWithdraw', 'totalEffectiveBet', 'channel', 'platform', 'regTime', 'loginTime', 'game'];
            const data = this.formatJson(filterVal, response.data);
            excel.export_json_to_excel({
              header: tHeader,
              data,
              filename: 'IpQuery',
              bookType: 'xlsx',
            });
            this.downloading = false;
          });
        });
    },
    getCSVFile() {
      this.loading = true;
      this.downloading = true;
      const query = {};
      Object.assign(query, this.query);
      query.page = 1;
      query.size = this.total;
      allPlayersQueryResource.list(query)
        .then(response => {
          this.loading = false;
          import('@/vendor/Export2Excel').then(excel => {
            const tHeader = ['Player ID', 'Balance', 'Account Status', 'VIP Level', 'Total recharge', 'Total Withdrawal', 'History total coding', 'Source Channel', 'Registration Platform', 'Registration time', 'Last login time', 'Playing a game'];
            const filterVal = ['id', 'balance', 'account', 'vipLevel', 'totalRecharge', 'totalWithdraw', 'totalEffectiveBet', 'channel', 'platform', 'regTime', 'loginTime', 'game'];
            const data = this.formatJson(filterVal, response.data);
            excel.export_json_to_excel({
              header: tHeader,
              data,
              filename: 'IpQuery',
              bookType: 'csv',
            });
            this.downloading = false;
          });
        });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
  },
};
</script>

<style lang="scss">
.grid-content{
  margin: 0px 3px;
}
.app-container {
  padding: 10px;
}
.soflex1 div div {
  width: 100%;
  min-height: 40px;
  text-align: center;
  line-height: 39px;
  font-size: 14px;
  color: #606266;
  text-rendering: optimizeLegibility;
  overflow: hidden;
  white-space: nowrap;
  font-family: Helvetica Neue, Helvetica, PingFang SC, Hiragino Sans GB, Microsoft YaHei, Arial, sans-serif;
}
.soflex1 div {
  border-bottom: 1px solid #dfe6ec;
  width: 20%;
}
.soflex1 {
  flex-direction: row;
  display: flex;
}
.table-title{
  overflow: hidden;
  white-space: nowrap;
}
</style>
