<template>
  <div class="app-container">
    <!-- FILTERS -->
    <div class="filter-container">
      <!-- BOX -->
      <div class="box-line">
        <el-input v-model="searchParam" class="filter-item" prefix-icon="el-icon-search" style="width: 200px" />
        <el-button class="filter-item" type="success">{{ $t('btn.query') }}</el-button>
      </div>
    </div>
    <!-- TABLE -->
    <el-table v-loading="loading" :data="list" height="650" border fit highlight-current-row>
      <!-- ID -->
      <el-table-column align="center" :label="$t('table.userId')" width="80" prop="id">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.loginTime')" prop="name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.userIp')" prop="action">
        <template slot-scope="scope">
          <span>{{ scope.row.action }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.phoneNumber')">
        ...
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.operationType')" prop="route">
        <template slot-scope="scope">
          <span>{{ scope.row.path_req }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.detailedRemarks')" prop="route">
        <template slot-scope="scope">
          <span>{{ scope.row.path_req }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.WhetherTheOperationSuccessful')" prop="route">
        <template slot-scope="scope">
          <span>{{ scope.row.path_req }}</span>
        </template>
      </el-table-column>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import waves from '@/directive/waves';
import Resource from '@/api/resource';
import permission from '@/directive/permission';
const operationLogResource = new Resource('operation-log');
const permissionResource = new Resource('permissions');

export default {
  name: 'MailManagement',
  components: { Pagination },
  directives: { waves, permission },
  data() {
    return {
      total: 0,
      list: null,
      loading: true,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        user: '',
      },
      users: ['admin', 'manager', 'editor', 'user', 'visitor', 'root'],
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
      startDateAndTime: '',
      endDateAndTime: '',
      searchParam: '',
      accountVisibleForm: false,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getPermissions() {
      const { data } = await permissionResource.list({});
      const { all, menu, other } = this.classifyPermissions(data);
      this.permissions = all;
      this.menuPermissions = menu;
      this.otherPermissions = other;
    },
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await operationLogResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
      this.list = [];
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
  },
};
</script>

<style lang="scss">
.app-container {
  padding: 10px;
}
.box-line {
  border: 1px solid rgb(246, 246, 246);
  border-width: 1px 0;
  padding: 21px 10px 10px 10px;
  margin: 10px 0;
}
.btn-label {
  padding: 6px 9px;
}
</style>
