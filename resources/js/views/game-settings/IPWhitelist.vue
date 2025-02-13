<template>
  <div class="app-container">
    <!-- FILTERS -->
    <PermissionChecker :permissions="[viewPermission.create]">
      <div class="filter-container">
        <div class="bottom-separate-line">
          <!-- Add -->
          <el-button class="filter-item" type="primary" @click="onAccountForm">{{ $t('btn.add') }}</el-button>
        </div>
      </div>
    </PermissionChecker>
    <!-- TABLE -->
    <el-table v-loading="loading" :data="list" height="650" border fit highlight-current-row>
      <!-- checkbox -->
      <!-- <el-table-column align="center" type="selection" width="57" /> -->
      <!-- ID -->
      <el-table-column align="center" :label="$t('table.id')" width="80" prop="id">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!-- ipAddress -->
      <el-table-column align="center" :label="$t('table.ipAddress')" prop="route">
        <template slot-scope="scope">
          <span>{{ scope.row.ipAddress }}</span>
        </template>
      </el-table-column>
      <!-- remark -->
      <el-table-column align="center" :label="$t('labels.remarks')" prop="date">
        <template slot-scope="scope">
          <span>{{ scope.row.remark }}</span>
        </template>
      </el-table-column>
      <!-- operator -->
      <el-table-column align="center" :label="$t('table.operator')" prop="date">
        <template slot-scope="scope">
          <span>{{ (getOperatorById(scope.row.operator)!==undefined)? getOperatorById(scope.row.operator).name : $t('labels.notFound') }}</span>
        </template>
      </el-table-column>
      <!-- Operation -->
      <PermissionChecker :permissions="[viewPermission.delete]">
        <el-table-column align="center" :label="$t('table.operation')" prop="date">
          <template slot-scope="scope">
            <el-button type="danger" size="small" icon="el-icon-delete" :data="scope.row.id" @click="deleteRow(scope.row.id)">{{ $t('btn.delete') }}</el-button>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.total" @pagination="getList" />
    <!-- DIALOG -->
    <el-dialog :title="$t('dialog.titles.AddIpWhitelist')" :visible.sync="accountVisibleForm" width="30%">
      <!-- PRE LOADER -->
      <div class="form-container">
        <!-- FORM -->
        <el-form ref="ruleForm" :model="newRow" label-position="left" label-width="130px">
          <!-- -->
          <el-form-item
            :label="$t('dialog.user.ip')"
            prop="ipAddress"
            :rules="[
              { required: true, message: 'enter the correct ip address '},
            ]"
          >
            <el-input v-model="newRow.ipAddress" />
          </el-form-item>
          <!-- -->
          <el-form-item
            :label="$t('labels.remarks')"
            prop="remark"
            :rules="[
              { required: true, message: 'enter remark '},
            ]"
          >
            <el-input v-model="newRow.remark" type="textarea" rows="4" />
          </el-form-item>
        </el-form>
        <!-- FOOTER -->
        <div slot="footer" class="dialog-footer" align="right" style="margin-left: 130px;">
          <el-button type="success" icon="el-icon-plus" @click="createNewRow('ruleForm')">
            {{ $t('btn.save') }}
          </el-button>
          <el-button icon="el-icon-plus" @click="offAccountForm">
            {{ $t('btn.cancel') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import waves from '@/directive/waves';
import Resource from '@/api/resource';
import permission from '@/directive/permission';
import User from '@/api/user';
import viewsPermissions from '@/viewsPermissions.js';
import PermissionChecker from '@/components/Permissions/index.vue';
const iPWhitelistResource = new Resource('ipwhitelist');
const permissionResource = new Resource('permissions');
const userResource = new User();

export default {
  name: 'IPWhitelist',
  components: {
    Pagination,
    PermissionChecker,
  },
  directives: { waves, permission },
  data() {
    return {
      viewPermission: viewsPermissions.iPWhitelist.permissions.controls,
      total: 0,
      newRow: {
        ipAddress: '',
        remark: '',
      },
      list: null,
      loading: false,
      query: {
        page: 1,
        size: 15,
        keyword: '',
        user: '',
      },
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
      startDateAndTime: '',
      endDateAndTime: '',
      searchParam: '',
      // visibleForm
      accountVisibleForm: false,
      users: null,
    };
  },
  created() {
    this.getList();
    this.getUsers();
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
      iPWhitelistResource.list(this.query)
        .then(response => {
          this.loading = false;
          this.total = response.meta.total;
          this.list = response.data;
        });
    },
    createNewRow(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          iPWhitelistResource.store(this.newRow)
            .then(response => {
              this.$message({
                type: 'success',
                message: this.$t('messages.iPWhitelistIPAdded'),
                duration: 1 * 1000,
              });
              this.loading = false;
              this.getList();
              this.offAccountForm();
              this.$refs[formName].resetFields();
            })
            .catch(() => {
              this.loading = false;
              this.offAccountForm();
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
    async getUsers() {
      const users = await userResource.list();
      this.users = users.data;
    },
    getOperatorById(id) {
      if (this.users !== null && Object.keys(this.users).length !== 0) {
        return this.users.find((item) => item.id === id);
      }
    },
    deleteRow(id) {
      this.loading = true;
      iPWhitelistResource.destroy(id)
        .then(response => {
          this.$message({
            type: 'success',
            message: this.$t('messages.iPWhitelistIPDeleted'),
            duration: 1 * 1000,
          });
          this.loading = false;
          this.getList();
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
.bottom-separate-line {
  border: 1px solid rgb(246, 246, 246);
  border-width: 0 0 1px;
  padding-bottom: 10px;
  margin-bottom: 10px;
}
.el-dialog__header {
  border-bottom: 1px solid #eee;
  font-size: 1.3rem;
  overflow: hidden;
  background-color: #F8F8F8;
  border-radius: 2px 2px 0 0;
}
</style>
