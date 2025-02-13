<template>
  <div class="app-container">
    <!-- FILTERS -->
    <PermissionChecker :permissions="['user login log user id search']">
      <div class="filter-container">
        <!-- BOX -->
        <div class="box-line">
          <span class="demo-input-label">{{ $t('labels.userLoginLogIPQuery') }}</span>
          <el-input v-model="query.uid" class="filter-item" prefix-icon="el-icon-search" style="width: 200px" />
          <el-button class="filter-item" type="success" @click="getList">{{ $t('btn.query') }}</el-button>
        </div>
      </div>
    </PermissionChecker>
    <!-- TABLE -->
    <el-table v-loading="loading" :data="list" height="650" border fit highlight-current-row>
      <!-- User ID -->
      <el-table-column align="center" :label="$t('table.userId')" width="80" prop="id">
        <template slot-scope="scope">
          <span>{{ scope.row.uid }}</span>
        </template>
      </el-table-column>
      <!-- Login time -->
      <el-table-column align="center" :label="$t('table.loginTime')" prop="name">
        <template slot-scope="scope">
          <span>{{ scope.row.loginTime }}</span>
        </template>
      </el-table-column>
      <!-- Login ip -->
      <el-table-column align="center" :label="$t('table.loginIp')" prop="action">
        <template slot-scope="scope">
          <span>{{ scope.row.loginIp }}</span>
        </template>
      </el-table-column>
      <!-- Login Method -->
      <el-table-column align="center" :label="$t('table.loginMethod')">
        <template slot-scope="scope">
          <span>{{ scope.row.loginMethod }}</span>
        </template>
      </el-table-column>
      <!-- Login platform -->
      <el-table-column align="center" :label="$t('table.loginPlatform')" prop="route">
        <template slot-scope="scope">
          <span>{{ scope.row.loginPlatform }}</span>
        </template>
      </el-table-column>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import waves from '@/directive/waves';
import Resource from '@/api/resource';
import permission from '@/directive/permission';
import PermissionChecker from '@/components/Permissions/index.vue';
const userLoginLogResource = new Resource('userloginlog');
const permissionResource = new Resource('permissions');

export default {
  name: 'UserLoginLog',
  components: {
    Pagination,
    PermissionChecker,
  },
  directives: { waves, permission },
  data() {
    return {
      total: 0,
      list: null,
      loading: true,
      query: {
        page: 1,
        size: 20,
        uid: '',
      },
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
      startDateAndTime: '',
      endDateAndTime: '',
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
      this.loading = true;
      userLoginLogResource.list(this.query)
        .then(response => {
          this.list = response.data;
          this.total = response.meta.total;
          this.loading = false;
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
