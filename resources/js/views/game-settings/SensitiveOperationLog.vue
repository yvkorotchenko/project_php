<template>
  <div class="app-container">
    <!-- FILTERS -->
    <PermissionChecker :permissions="['sensitive operation log search']">
      <div class="filter-container">
        <!-- BOX -->
        <div class="box-line">
          <span class="demo-input-label">{{ $t('labels.sensetiveOperationLogIPQuery') }}</span>
          <el-input v-model="query.uid" class="filter-item" prefix-icon="el-icon-search" style="width: 200px" />
          <el-button class="filter-item" type="success" @click="getList">{{ $t('btn.query') }}</el-button>
        </div>
      </div>
    </PermissionChecker>
    <!-- CRUD TABLE -->
    <!-- TABLE -->
    <el-table v-loading="loading" :data="list" height="650" border fit highlight-current-row>
      <!-- checkbox -->
      <!-- <el-table-column align="center" type="selection" width="57" /> -->
      <!-- ID -->
      <el-table-column align="center" :label="$t('labels.userLoginLogIPQuery')" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.uid }}</span>
        </template>
      </el-table-column>
      <!-- ipAddress -->
      <el-table-column align="center" :label="$t('labels.sensetiveOperationLogloginTime')">
        <template slot-scope="scope">
          <span>{{ scope.row.created }}</span>
        </template>
      </el-table-column>
      <!-- remark -->
      <el-table-column align="center" :label="$t('labels.sensetiveOperationLoguserIP')">
        <template slot-scope="scope">
          <span>{{ scope.row.userIp }}</span>
        </template>
      </el-table-column>
      <!-- operator -->
      <el-table-column align="center" :label="$t('labels.sensetiveOperationLogphoneNumber')">
        <template slot-scope="scope">
          <span>{{ scope.row.phone }}</span>
        </template>
      </el-table-column>
      <!-- Operation -->
      <el-table-column align="center" :label="$t('labels.sensetiveOperationLogoperationType')">
        <template slot-scope="scope">
          <span>{{ scope.row.operationType }}</span>
        </template>
      </el-table-column>
      <!-- operator -->
      <el-table-column align="center" :label="$t('labels.sensetiveOperationLogdetailedRemarks')">
        <template slot-scope="scope">
          <span>{{ scope.row.remarks }}</span>
        </template>
      </el-table-column>
      <!-- Operation -->
      <el-table-column align="center" :label="$t('labels.sensetiveOperationLogwhetherTheOperationWasSuccessful')">
        <template slot-scope="scope">
          <span>{{ scope.row.successful }}</span>
        </template>
      </el-table-column>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.size" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
const sensetiveOperationLogResource = new Resource('sensetiveoperationlog');

export default {
  name: 'SensetiveOperationLog',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      total: 0,
      list: null,
      loading: false,
      query: {
        page: 1,
        size: 20,
        uid: '',
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      sensetiveOperationLogResource.list(this.query)
        .then(response => {
          this.list = response.data;
          this.total = response.meta.total;
          this.loading = false;
        });
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
