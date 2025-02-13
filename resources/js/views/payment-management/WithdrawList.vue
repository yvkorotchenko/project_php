<template>
  <div class="app-container">
    <h1>{{ $t('titles.withdrawQuery') }}</h1>
    <div>
      <PermissionChecker :permissions="[viewPermission.filterSearch]">
        <el-form ref="filterQuery" :model="filterQuery" :rules="filterQueryRules">
          <el-col :span="3">
            <el-form-item :label="$t('labels.state')" prop="state">
              <el-select v-model="filterQuery.state">
                <el-option v-for="state in withdrawStates" :key="state" :value="state">{{ state }}</el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="3" class="filter-col">
            <el-form-item :label="$t('labels.operatorId')" prop="operatorId">
              <el-input v-model.number="filterQuery.operatorId" />
            </el-form-item>
          </el-col>
          <el-col :span="3" class="filter-col">
            <el-form-item :label="$t('labels.withdrawId')" prop="withdrawId">
              <el-input v-model.number="filterQuery.withdrawId" />
            </el-form-item>
          </el-col>
          <el-col :span="3" class="filter-col">
            <el-form-item :label="$t('labels.minAmount')" prop="minAmount">
              <el-input v-model.number="filterQuery.minAmount" />
            </el-form-item>
          </el-col>
          <el-col :span="3" class="filter-col">
            <el-form-item :label="$t('labels.maxAmount')" prop="maxAmount">
              <el-input v-model.number="filterQuery.maxAmount" />
            </el-form-item>
          </el-col>
          <el-col :span="3">
            <el-form-item :label="$t('labels.dateFrom')" prop="dateFrom">
              <el-date-picker v-model="filterQuery.updateFrom" format="yyyy-MM-dd" value-format="yyyy-MM-dd" />
            </el-form-item>
          </el-col>
          <el-col :span="3">
            <el-form-item :label="$t('labels.dateTo')" prop="dateTo">
              <el-date-picker v-model="filterQuery.updateTo" format="yyyy-MM-dd" value-format="yyyy-MM-dd" />
            </el-form-item>
          </el-col>
          <el-col :span="12" class="filter-col">
            <el-form-item>
              <el-button type="primary" plain @click="getItems">{{ $t('labels.query') }}</el-button>
              <el-button type="info" plain icon="el-icon-delete" @click="resetFilter" />
              <el-button type="info" plain icon="el-icon-document" @click.native="getExcelFile" />
            </el-form-item>
          </el-col>
        </el-form>
      </PermissionChecker>
    </div>
    <div>
      <el-table
        :data="items"
      >
        <el-table-column
          v-for="(itemLabel, itemName) in itemsLabels"
          :key="itemLabel"
          :prop="itemName"
          :label="itemLabel"
          min-width="200px"
        />
        <PermissionChecker :permissions="[viewPermission.audit, viewPermission.paid, viewPermission.reject]">
          <el-table-column fixed="right" :label="$t('labels.operations')" min-width="200px">
            <template slot-scope="scope">
              <PermissionChecker v-if="scope.row.state === 'CREATED'" :permissions="[viewPermission.audit]">
                <el-button type="default" @click="startProcessWithdraw(scope.row.id)">{{ $t('labels.audit') }}</el-button>
              </PermissionChecker>
              <PermissionChecker v-else-if="scope.row.state === 'PROCESSING'" :permissions="[viewPermission.paid, viewPermission.reject]">
                <span>
                  <PermissionChecker :permissions="[viewPermission.paid]">
                    <el-button type="default" @click="submitWithdraw(scope.row.id)">{{ $t('labels.paid') }}</el-button>
                  </PermissionChecker>
                  <PermissionChecker :permissions="[viewPermission.reject]">
                    <el-button type="default" @click="rejectWithdraw(scope.row.id)">{{ $t('labels.reject') }}</el-button>
                  </PermissionChecker>
                </span>
              </PermissionChecker>
            </template>
          </el-table-column>
        </PermissionChecker>
      </el-table>
      <pagination
        v-show="pagination.total > 0"
        :total="pagination.total"
        :page.sync="filterQuery.page"
        :limit.sync="filterQuery.size"
        @pagination="getItems"
      />
    </div>
  </div>
</template>

<script>
import FinanceRequest from '@/api/finance';
import Pagination from '@/components/Pagination';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const financeRequest = new FinanceRequest();

export default {
  name: 'WithdrawList',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.vIPWithdrawalQuery.permissions.controls,
      items: null,
      itemsLabels: {
        id: this.$t('labels.id'),
        uid: this.$t('labels.userId'),
        receiveAddress: this.$t('labels.receiveAddress'),
        thirdPartyId: this.$t('labels.thirdPartyId'),
        thirdPartyCallbackInfo: this.$t('labels.thirdPartyCallbackInfo'),
        totalRecharge: this.$t('labels.totalRecharge'),
        withdrawWithFee: this.$t('labels.withdraw'),
        fee: this.$t('labels.fee'),
        withdrawAmount: this.$t('labels.withdrawAmount'),
        created: this.$t('labels.applicationTime'),
        updated: this.$t('labels.updated'),
        operatorId: this.$t('labels.operatorId'),
        notes: this.$t('labels.notes'),
        state: this.$t('labels.state'),
      },
      pagination: {
        page: 1,
        size: 10,
        total: 0,
      },
      filterQuery: {
        page: 1,
        size: 10,
        state: null,
        operatorId: null,
        uid: null,
        withdrawId: null,
        minAmount: null,
        maxAmount: null,
        updateFrom: null,
        updateTo: null,
      },
      filterQueryRules: {
        operatorId: [{ type: 'integer', message: this.$t('labels.operatorId') + ' should be number', trigger: 'change' }],
        withdrawId: [{ type: 'integer', message: this.$t('labels.withdrawId') + ' should be number', trigger: 'change' }],
        minAmount: [{ type: 'integer', message: this.$t('labels.minAmount') + ' should be number', trigger: 'change' }],
        maxAmount: [{ type: 'integer', message: this.$t('labels.maxAmount') + ' should be number', trigger: 'change' }],
        updateFrom: [{ type: 'date', message: this.$t('labels.dateFrom') + ' should be the date', trigger: 'blur' }],
        updateTo: [{ type: 'date', message: this.$t('labels.dateFrom') + ' should be the date', trigger: 'blur' }],
      },
      withdrawStates: [],
    };
  },
  created() {
    this.getItems();
    this.getWithdrawStates();
  },
  methods: {
    getItems() {
      const data = this.filterNullItems(this.filterQuery);
      financeRequest.withdrawList(data).then((response) => {
        this.items = response.data;
        this.pagination.total = response.meta.total;
      });
    },
    filterNullItems(dataWithNulls) {
      const data = {};
      Object.assign(data, dataWithNulls);
      Object.keys(data).forEach((key) => {
        if (data[key] === null) {
          delete data[key];
        }
      });

      return data;
    },
    getWithdrawStates() {
      financeRequest.withdrawStates().then(response => {
        this.withdrawStates = response.data;
      });
    },
    startProcessWithdraw(withdrawId) {
      financeRequest
        .changeStatus(withdrawId, 'PROCESS')
        .then(() => this.getItems());
    },
    submitWithdraw(withdrawId) {
      financeRequest
        .changeStatus(withdrawId, 'SUBMIT')
        .then(() => this.getItems());
    },
    rejectWithdraw(withdrawId) {
      financeRequest
        .changeStatus(withdrawId, 'CANCEL')
        .then(() => this.getItems());
    },
    resetFilter() {
      this.filterQuery = {
        state: null,
        operatorId: null,
        uid: null,
        withdrawId: null,
        minAmount: null,
        maxAmount: null,
        updateFrom: null,
        updateTo: null,
      };
      this.getItems();
    },
    getExcelFile() {
      const query = {};
      Object.assign(query, this.filterQuery);
      query.size = this.pagination.total;

      financeRequest.withdrawList(this.filterNullItems(query))
        .then(response => {
            import('@/vendor/Export2Excel').then(excel => {
              const tHeader = Object.values(this.itemsLabels);
              const filterVal = Object.keys(this.itemsLabels);

              const data = response.data.map(item => filterVal.map(key => item[key]));
              excel.export_json_to_excel({
                header: tHeader,
                data,
                filename: 'withdraws',
                bookType: 'xlsx',
              });
              this.downloading = false;
            });
        });
    },
  },
};
</script>
<style scoped>
.filter-col {margin-right: 15px;}
</style>
