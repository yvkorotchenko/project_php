<template>
  <div class="app-container">
    <div>
      <PermissionChecker :permissions="[viewPermission.filterSearch]">
        <div class="filter-container">
          <div class="box-line">
            <!-- ALL OPERATOR SELECT -->
            <el-select v-model="query.operatorId" class="filter-item box-line-item" :placeholder="$t('placeholders.allOperators')">
              <el-option v-for="item in users" :key="item.id" :label="item.name | uppercaseFirst" :value="item.id" />
            </el-select>
            <!-- STATUS SELECT -->
            <el-select v-model="query.status" class="filter-item box-line-item" :placeholder="$t('placeholders.allStates')">
              <el-option v-for="item in status" :key="item" :label="item | uppercaseFirst" :value="item" />
            </el-select>
            <!-- ORDER NUMBER INPUT -->
            <el-input v-model="query.id" :placeholder="$t('placeholders.orderNumber')" class="filter-item box-line-item" />
            <!-- PLAYER ID INPUT -->
            <el-input v-model="query.customerId" :placeholder="$t('placeholders.playerGameID')" class="filter-item box-line-item" />
          </div>
          <div class="box-line">
            <!-- MIN RECHARGE INPUT -->
            <el-input v-model="query.amountMin" :placeholder="$t('placeholders.minimumAmount')" class="filter-item box-line-item" />
            <!-- MAX RECHARGE INPUT -->
            <el-input v-model="query.amountMax" :placeholder="$t('placeholders.maximumAmount')" class="filter-item box-line-item" />
            <!-- DATE BEFORE INPUT -->
            <el-date-picker
              v-model="query.dateStart"
              class="filter-item box-line-item"
              type="datetime"
              :placeholder="$t('placeholders.applicationStartTime')"
              format="yyyy-MM-dd"
              value-format="yyyy-MM-dd"
            />
            <!-- DATE AFTER INPUT -->
            <el-date-picker
              v-model="query.dateEnd"
              class="filter-item box-line-item"
              type="datetime"
              :placeholder="$t('placeholders.applicationEndTime')"
              format="yyyy-MM-dd"
              value-format="yyyy-MM-dd"
            />
            <!-- BUTTON -->
            <el-button class="filter-item first-button" type="success" @click="getAllRecharcheList">{{ $t('btn.query') }}</el-button>
            <el-button class="filter-item" type="primary" @click="resetQuery">{{ $t('btn.reset') }}</el-button>
            <el-button class="filter-item last-button" type="success" @click="getExcelFile">{{ $t('btn.btnExportExcel') }}</el-button>
          </div>
        </div>
      </PermissionChecker>
      <!-- Table -->
      <PermissionChecker :permissions="[viewPermission.buttonsPrintDownload]">
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
                        <el-dropdown-item @click.native="getExcelFile">{{ $t('labels.downloadCSV') }}</el-dropdown-item>
                      </PermissionChecker>
                      <PermissionChecker :permissions="[viewPermission.downloadCSV]">
                        <el-dropdown-item @click.native="getCSVFile">{{ $t('labels.downloadExcel') }}</el-dropdown-item>
                      </PermissionChecker>
                    </el-dropdown-menu>
                  </el-dropdown>
                </i>
              </PermissionChecker>
              <PermissionChecker :permissions="[viewPermission.printTable]">
                <i class="item-icon">
                  <i v-print="printObj" class="el-icon-printer" />
                </i>
              </PermissionChecker>
            </div>
          </div>
        </div>
      </PermissionChecker>
      <div v-if="loading" class="el-loading-mask">
        <div class="el-loading-spinner">
          <svg viewBox="25 25 50 50" class="circular"><circle cx="50" cy="50" r="20" fill="none" class="path" /></svg>
        </div>
      </div>
      <div v-else id="soTest">
        <div class="soflex1">
          <div>
            <div><div class="table-title">{{ $t('labels.vipRechargeId') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ item.id }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.vipRechargeUserID') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ item.uid }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.whetherTheFirstChargeVIPSystem') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ (item.firstCharge)? $t('labels.firstChargeYes') : $t('labels.firstChargeNo') }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.merchantName') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ item.merchantName }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.vipRechargeRechargeAmount') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ item.amount/100 }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.paymentMethod') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ item.paymentMethod }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.vipRechargeStatus') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ item.status }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.operator') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ getUserNameById(item.operatorId) }}</div>
          </div>
          <div>
            <div><div class="table-title">{{ $t('labels.peymentDate') }}</div></div>
            <div v-for="item in tableData" :key="item.id">{{ item.created }}</div>
          </div>
        </div>
      </div>
      <!-- PAGINATION -->
      <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getAllRecharcheList" />
    </div>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import VIPRechargeQuery from '@/api/VIPRechargeQuery';
import VIPRechargeStatus from '@/api/VIPRechargeStatus';
import User from '@/api/user.js';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const resource = new VIPRechargeQuery();
const vIPRechargeStatusResource = new VIPRechargeStatus();
const userResource = new User();
export default {
  name: 'VIPRechargeQuery',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.vIPRechargeQuery.permissions.controls,
      widthTable: '100%',
      widthTableTD: '500',
      showExportButton: false,
      downloading: false,
      loading: true,
      total: 0,
      users: [],
      status: [],
      query: {
        page: 1,
        size: 10,
        orderDirection: 'ASC',
        orderField: 'id',
        amountMax: '',
        amountMin: '',
        customerId: '',
        dateEnd: '',
        dateStart: '',
        id: '',
        operatorId: '',
        status: '',
      },
      tableData: [{
        id: '',
        merchantName: '',
        paymentMethod: '',
        operatorId: '',
        created: '',
        amount: '',
        status: '',
        firstCharge: '',
        uid: '',
      }],
      printObj: {
        type: 'html',
        scanStyles: false,
        id: 'soTest',
        popTitle: 'VIP Recharge Query print',
        beforeOpenCallback(vue) {
          vue.printLoading = true;
        },
        openCallback(vue) {
          vue.printLoading = false;
        },
        closeCallback(vue) {
        },
      },
    };
  },
  created() {
    this.getAllRecharcheList();
    this.getAllUsers();
    this.getAllStatus();
  },
  methods: {
    width50() {
      this.widthTable = '950px';
    },
    width100() {
      this.widthTable = '100%';
    },
    resetQuery() {
      this.query = {
        orderDirection: 'ASC',
        orderField: 'id',
        amountMax: '',
        amountMin: '',
        customerId: '',
        dateEnd: '',
        dateStart: '',
        id: '',
        operatorId: '',
        status: '',
      };
      this.getAllRecharcheList();
    },
    getAllRecharcheList() {
      this.loading = true;
      resource.list(this.query)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.tableData = response.data;
        });
    },
    getExcelFile() {
      this.loading = true;
      this.downloading = true;
      const query = {};
      Object.assign(query, this.query);
      query.page = 1;
      query.size = this.total;
      resource.list(query)
        .then(response => {
          this.loading = false;
          const dataValue = response.data.map((value) => {
            value.operatorId = this.getUserNameById(value.operatorId);
            value.firstCharge = (value.firstCharge) ? 'Yes' : 'No';
            value.amount = value.amount / 100;
            return value;
          });
          import('@/vendor/Export2Excel').then(excel => {
            const tHeader = ['Id', 'user ID', 'first recharge(VIP)', 'Merchant name', 'Recharge amount', 'Payment method', 'Payment status', 'Operator', 'Peyment Date'];
            const filterVal = ['id', 'uid', 'firstCharge', 'merchantName', 'amount', 'paymentMethod', 'status', 'operatorId', 'created'];
            const data = this.formatJson(filterVal, dataValue);
            excel.export_json_to_excel({
              header: tHeader,
              data,
              filename: 'vip-recharge-query',
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
      resource.list(query)
        .then(response => {
          this.loading = false;
          const dataValue = response.data.map((value) => {
            value.operatorId = this.getUserNameById(value.operatorId);
            value.firstCharge = (value.firstCharge) ? 'Yes' : 'No';
            value.amount = value.amount / 100;
            return value;
          });
          import('@/vendor/Export2Excel').then(excel => {
            const tHeader = ['Id', 'user ID', 'first recharge(VIP)', 'Merchant name', 'Recharge amount', 'Payment method', 'Payment status', 'Operator', 'Peyment Date'];
            const filterVal = ['id', 'uid', 'firstCharge', 'merchantName', 'amount', 'paymentMethod', 'status', 'operatorId', 'created'];
            const data = this.formatJson(filterVal, dataValue);
            excel.export_json_to_excel({
              header: tHeader,
              data,
              filename: 'vip-recharge-query',
              bookType: 'csv',
            });
            this.downloading = false;
          });
        });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
    getAllStatus() {
      vIPRechargeStatusResource.list(this.query)
        .then(response => {
          this.status = response.data;
        });
    },
    getUserNameById(id) {
      if (Object.keys(this.users).length) {
        const user = this.users.find((value) => value.id === id);
        if (typeof user !== 'undefined') {
          return user.name;
        }
        return 'No';
      }
    },
    getAllUsers() {
      userResource.list(this.query)
        .then(response => {
          this.users = response.data;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
  width: inherit;
}
.box-line .box-line-item{
  width: 260px;
}
.first-button{
  margin-left: 10px;
}
.last-button{
  margin-left: 70px;
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
.el-dropdown-link {
  cursor: pointer;
  color: #409EFF;
}
.el-icon-arrow-down {
  font-size: 12px;
}
.soflex1 div div {
  width: 100%;
  text-align: center;
  line-height: 39px;
  height: 39px;
  font-size: 14px;
  color: #606266;
  text-rendering: optimizeLegibility;
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
