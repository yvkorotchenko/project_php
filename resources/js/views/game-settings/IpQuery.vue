<template>
  <div class="app-container">
    <!-- FILTERS -->
    <PermissionChecker :permissions="[viewPermission.searchFilter]">
      <div class="filter-container">
        <el-form
          ref="ipQueryRef"
          :model="query"
          class="box-line"
        >
          <el-form-item
            class="filter-item-ipquery filter-item-ipquery-select"
            prop="selectIP"
            :rules="{required: true, message: $t('placeholders.selectedItem'), trigger: 'blur'}"
          >
            <!-- SELECT IP -->
            <el-select v-model="selectIP" class="filter-item" style="width: 177px;" :placeholder="$t('placeholders.ipquerySelectIP')" clearable @change="handleFilter">
              <el-option selected label="IP" value="IP" />
            </el-select>
          </el-form-item>
          <el-form-item
            class="filter-item-ipquery filter-item-ipquery-input"
            prop="ip"
            :rules="{required: true, message: $t('placeholders.pleaseEnterContent'), trigger: 'blur'}"
          >
            <!-- SEARCH IP -->
            <el-input v-model="query.ip" class="filter-item" style="width: 200px" />
          </el-form-item>
          <el-button class="filter-item filter-item-ipquery filter-item-ipquery-btn" type="success" @click="getList">{{ $t('btn.query') }}</el-button>
        </el-form>
      </div>
    </PermissionChecker>
    <!-- table top row icon -->
    <PermissionChecker :permissions="[viewPermission.downloadExcel, viewPermission.downloadCSV, viewPermission.printTable]">
      <div id="wrap-table">
        <div class="source">
          <div align="right" class="wrap-icon">
            <PermissionChecker :permissions="[viewPermission.downloadExcel, viewPermission.downloadCSV]">
              <i class="item-icon">
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
              </i>
            </PermissionChecker>
            <i class="item-icon">
              <i v-print="printObj" class="el-icon-printer" />
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
            <div><div class="table-title">{{ $t('table.id') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.id }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.registrationTime') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.registrationTime }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.registeredIp') }}</div></div>
            <div v-for="item in list" :key="item.id" :class="matchCheckIP(item.registeredIp)">{{ item.registeredIp }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.lastTime') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.lastLoginTime }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.lastIp') }}</div></div>
            <div v-for="item in list" :key="item.id" :class="matchCheckIP(item.lastLoginIp)">{{ item.lastLoginIp }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.balance') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.balance }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.safeGoldCoin') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.safeGoldCoin }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('table.totalRecharge') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.totalRecharge }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('table.totalWithdrawal') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.totalWithdrawal }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.historyAlwaysBet') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.historyTotalBet }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.sourceChannel') }}</div></div>
            <div v-for="item in list" :key="item.id">{{ item.sourceChannelName }}</div>
          </div>
        </div>
      </div>
    </div>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import permission from '@/directive/permission';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';
const ipqueryResource = new Resource('ipquery');
const permissionResource = new Resource('permissions');

export default {
  name: 'IpQuery',
  components: {
    Pagination,
    PermissionChecker,
  },
  directives: { permission },
  data() {
    return {
      viewPermission: viewsPermissions.ipQuery.permissions.controls,
      selectIP: 'IP',
      total: 0,
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
      list: null,
      loading: false,
      query: {
        page: 1,
        size: 15,
        ip: '',
        selectIP: 'IP',
      },
      currentIP: '',
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
      startDateAndTime: '',
      endDateAndTime: '',
      searchParam: '',
      // visibleForm
      accountVisibleForm: false,
    };
  },
  methods: {
    async getPermissions() {
      const { data } = await permissionResource.list({});
      const { all, menu, other } = this.classifyPermissions(data);
      this.permissions = all;
      this.menuPermissions = menu;
      this.otherPermissions = other;
    },
    matchCheckIP(ip){
      return (this.currentIP === ip) ? 'currentIPRed' : '';
    },
    async getList() {
      this.$refs['ipQueryRef'].validate((valid) => {
        if (valid) {
          this.loading = true;
          ipqueryResource.list(this.query)
            .then(response => {
              this.list = response.data;
              this.total = response.meta.total;
              this.loading = false;
            });
        } else {
          return false;
        }
      });
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    getAccountVisibleForm() {
      return this.accountVisibleForm;
    },
    setAccountVisibleForm(val) {
      this.accountVisibleForm = val;
    },
    onAccountForm() {
      this.setAccountVisibleForm(true);
    },
    offAccountForm() {
      this.setAccountVisibleForm(false);
    },
    getExcelFile() {
      this.loading = true;
      this.downloading = true;
      const query = {};
      Object.assign(query, this.query);
      query.page = 1;
      query.size = this.total;
      ipqueryResource.list(query)
        .then(response => {
          this.loading = false;
          import('@/vendor/Export2Excel').then(excel => {
            const tHeader = ['ID', 'Registration time', 'Registered IP', 'Last time', 'Last IP', 'labels.balance', 'Safe Gold Coin', 'Total recharge', 'Total Withdrawal', 'History always bet', 'Source Channel'];
            const filterVal = ['id', 'registrationTime', 'registeredIp', 'lastLoginTime', 'lastLoginIp', 'balance', 'safeGoldCoin', 'totalRecharge', 'totalWithdrawal', 'historyTotalBet', 'sourceChannelName'];
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
      ipqueryResource.list(query)
        .then(response => {
          this.loading = false;
          import('@/vendor/Export2Excel').then(excel => {
            const tHeader = ['ID', 'Registration time', 'Registered IP', 'Last time', 'Last IP', 'labels.balance', 'Safe Gold Coin', 'Total recharge', 'Total Withdrawal', 'History always bet', 'Source Channel'];
            const filterVal = ['id', 'registrationTime', 'registeredIp', 'lastLoginTime', 'lastLoginIp', 'balance', 'safeGoldCoin', 'totalRecharge', 'totalWithdrawal', 'historyTotalBet', 'sourceChannelName'];
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

<style lang="scss" scoped>
.app-container {
  padding: 10px;
}
.box-line {
  border: 1px solid rgb(246, 246, 246);
  border-width: 0 0 1px;
  padding: 0 10px 10px 10px;
  margin-bottom: 10px;
}
.el-dropdown-link {
  cursor: pointer;
  color: #409EFF;
}
.wrap-icon{
  padding: 6px;
}
.wrap-icon .item-icon{
  line-height: 16px;
  text-align: center;
  color: #333;
  cursor: pointer;
  margin: 0 10px 0 5px;;
  width: 26px;
  height: 26px;
  padding: 5px 5px 2px 7px;
  line-height: 16px;
  color: #409eff;
  border: 1px solid #ccc;
}
.soflex1 div div {
  width: 100%;
  text-align: center;
  line-height: 39px;
  height: 39px;
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
.currentIPRed{
  color: red !important;
}
.filter-item-ipquery{
  float: left;
  margin: 0px 20px;
}
.filter-item-ipquery-btn{
  width: 80px;
}
.filter-item-ipquery-select{
  width: 177px;
}
.filter-item-ipquery-input{
  width: 200px;
}
</style>
