<template>
  <div class="app-container">
    <!-- Control -->
    <PermissionChecker :permissions="[viewPermission.deleteList, viewPermission.create]">
      <div class="filter-item">
        <PermissionChecker :permissions="[viewPermission.deleteList]">
          <el-button type="danger" icon="el-icon-delete" size="small" plain @click="handlerDeleteUser()">{{ $t('btn.delete') }}</el-button>
        </PermissionChecker>
        <PermissionChecker :permissions="[viewPermission.create]">
          <el-button type="primary" icon="el-icon-plus" size="small" plain @click="handlerShowDialog(true, { name: 'dialogUser', value: true }, $t('dialog.user.addTitle'))">{{ $t('btn.add') }}</el-button>
        </PermissionChecker>
      </div>
    </PermissionChecker>
    <!-- Table -->
    <el-table
      ref="multipleTable"
      v-loading="loading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
      height="650"
      @selection-change="handlerMultipleSelectionsUsers"
    >
      <!-- -ID- -->
      <el-table-column
        align="center"
        type="selection"
        width="57"
      />
      <!-- Items -->
      <el-table-column
        v-for="(itemLabel, itemName) in tableItems"
        :key="itemLabel"
        align="center"
        :label="itemLabel"
        :prop="itemName"
        :width="columnDefaultWidth(itemName)"
      >
        <template slot-scope="scope">
          <span>{{ scope.row[itemName] }}</span>
        </template>
      </el-table-column>
      <!-- -Control buttons- -->
      <PermissionChecker :permissions="[viewPermission.edit, viewPermission.roles, viewPermission.authority, viewPermission.delete, viewPermission.verifyIdentity]">
        <el-table-column
          align="center"
          :label="$t('labels.action')"
          width="400"
        >
          <template slot-scope="scope">
            <PermissionChecker :permissions="[viewPermission.edit]">
              <el-button type="primary" size="small" class="button-group" icon="el-icon-edit" plain @click="handlerShowDialog(true, { name: 'dialogUser', value: true }, $t('dialog.user.editTitle'), currentUser = scope.row)">{{ $t('btn.edit') }}</el-button>
            </PermissionChecker>
            <PermissionChecker :permissions="[viewPermission.roles]">
              <el-button type="primary" size="small" class="button-group" icon="el-icon-user-solid" plain @click="handlerShowDialog(true, { name: 'dialogRole', value: true }, $t('dialog.role.roleTitle'), currentUserId = scope.row.id, handlerShowListRoles)">{{ $t('btn.roles') }}</el-button>
            </PermissionChecker>
            <PermissionChecker :permissions="[viewPermission.delete]">
              <el-button type="danger" size="small" icon="el-icon-delete" plain @click="handlerDeleteUser(scope.row.id)">{{ $t('btn.delete') }}</el-button>
            </PermissionChecker>
            <PermissionChecker :permissions="[viewPermission.verifyIdentity]">
              <el-button type="primary" size="small" icon="el-icon-s-check" plain @click="handlerShowDialog(true, { name: 'dialogGoogleQrCode', value: true }, $t('titles.googleVerificationCode'), {}, handlerShowQrCode, scope.row.id)">{{ $t('btn.verifyIdentidy') }}</el-button>
            </PermissionChecker>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <!-- Paginations -->
    <Pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getUserList" />
    <!-- Dialogs -->
    <el-dialog
      :title="title"
      :visible.sync="formVisible"
      :before-close="handlerCloseDialog"
    >
      <!-- USER -->
      <template v-if="dialogUser">
        <div v-loading="loading" class="form-container">
          <el-form ref="userForm" :model="currentUser" label-position="rigth" label-width="177px" style="width: 100%">
            <!-- -NAME- -->
            <el-form-item :label="$t('dialog.user.userName')" prop="name" :rules="[{ required: true, message: this.$t('dialog.user.nameRule'), trigger: 'blur' }]">
              <el-input v-model="currentUser.name" :placeholder="$t('placeholders.forName')" />
            </el-form-item>
            <!-- -NICK- -->
            <el-form-item :label="$t('dialog.user.nickName')" prop="nick" :rules="[{ required: true, message: this.$t('dialog.user.nickRule'), trigger: 'blur' }]">
              <el-input v-model="currentUser.nick" :placeholder="$t('placeholders.alias')" />
            </el-form-item>
            <!-- -IP- -->
            <el-form-item :label="$t('dialog.user.ip')" prop="code">
              <el-input v-model="currentUser.ip" type="textarea" :placeholder="$t('placeholders.PleaseEnterIp')" />
            </el-form-item>
            <!-- -PASSWORD- -->
            <el-form-item :label="$t('dialog.user.password')" prop="password" :rules="[{ required: true, validator: validateLengthPassword, trigger: 'blur' }]">
              <el-input v-model="currentUser.password" show-password maxlength="27" />
            </el-form-item>
            <!-- -CONFIRM-PASSWORD- -->
            <el-form-item :label="$t('dialog.user.confirmPassword')" prop="confirmPassword" :rules="[{ required: true, validator: validateConfirmPassword, trigger: 'blur' }]">
              <el-input v-model="currentUser.confirmPassword" show-password maxlength="27" />
            </el-form-item>
          </el-form>
        </div>
      </template>
      <!-- ROLES -->
      <template v-if="dialogRole">
        <div v-loading="listLoading" class="form-container">
          <div>
            <div class="list-roles list-roles-height-auto">
              <div class="role-item border-bottom" style="width: 100%;">
                <input id="selectAllRoles" v-model="selectedAllRole" type="checkbox" :value="false" @click="handlerSelectedAll(listsRole, 'selectedRole', 'selectedAllRole')">
                <label for="selectAllRoles">{{ $t('labels.selectAll') }}</label>
              </div>
            </div>
            <!-- list Roles -->
            <div class="list-roles">
              <div v-for="role in listsRole" :key="role.id" class="role-item">
                <input :id="role.id" v-model="selectedRole" type="checkbox" :value="role.id">
                <label :for="role.id">{{ language === 'en' ? role.name : role.name_zh }}</label>
              </div>
            </div>
          </div>
        </div>
      </template>
      <!-- AUTHORITY -->
      <template v-if="dialogAuthority">
        <div v-loading="listLoading" class="form-container">
          <div>
            <div class="list-roles list-roles-height-auto">
              <div class="role-item border-bottom" style="width: 100%;">
                <input id="selectAllAuthority" v-model="selectedAllAuthority" type="checkbox" :value="false" @click="handlerSelectedAll(listsAuthority, 'selectedAuthority', 'selectedAllAuthority')">
                <label for="selectAllAuthority">{{ $t('labels.selectAll') }}</label>
              </div>
            </div>
            <!-- list Roles -->
            <div class="list-roles">
              <div v-for="authority in listsAuthority" :key="authority.id" class="role-item">
                <input :id="authority.id" v-model="selectedAuthority" type="checkbox" :value="authority.id">
                <label :for="authority.id">{{ authority.name }}</label>
              </div>
            </div>
          </div>
        </div>
      </template>
      <!-- GOOGLE VERIFICATION CODE -->
      <template v-if="dialogGoogleQrCode">
        <div class="wrapper-qr-code">
          <div v-html="QrCode" />
        </div>
      </template>
      <!-- FOOTER -->
      <div slot="footer" class="dialog-footer" align="center">
        <template v-if="dialogGoogleQrCode !== true">
          <el-button type="success" icon="el-icon-plus" plain @click="handlerCallbackFunction(callFunctions)">
            {{ $t('btn.confirm') }}
          </el-button>
        </template>
        <el-button type="primary" icon="el-icon-close" plain @click="handlerCloseDialog">
          {{ $t('btn.cancel') }}
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import UserResource from '@/api/user';
import PermissionsResource from '@/api/permission';
import Resource from '@/api/resource';
import RoleResource from '@/api/role';
import Pagination from '@/components/Pagination';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const userResource = new UserResource();
const permissionResource = new PermissionsResource();
const rolesResource = new RoleResource();
const google2fa = new Resource('users-google-verification-code');

export default {
  name: 'UserList',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    // const tableItems = {
    //   id: this.$t('table.id'),
    //   name: this.$t('table.userName'),
    //   nick: this.$t('table.nickName'),
    //   create: this.$t('table.createTime'),
    //   update: this.$t('table.updateTime'),
    // };
    return {
      viewPermission: viewsPermissions.userManagement.permissions.controls,
      formVisible: false,
      listLoading: false,
      loading: false,
      title: '',
      dialogUser: false,
      dialogRole: false,
      dialogAuthority: false,
      dialogGoogleQrCode: false,
      authorityLists: [],
      QrCode: '',
      listPermissions: [],
      currentUserId: null,
      formTitle: '',
      list: [],
      // tableItems: tableItems,
      total: 0,
      query: {
        page: 1,
        limit: 20,
      },
      selectedAllRole: false,
      selectedAllAuthority: false,
      editMode: false,
      // Google verification code
      googleVerificationCodeTitle: this.$t('titles.googleVerificationCode'),
      googleVerificationCodeFormVisible: false,
      // loading preloader
      googleVerificationCodeLoading: false,
      editAuthorityForUserId: undefined,
      currentUser: {},
      // role
      listsRole: [],
      selectedRole: [],
      // authority
      listsAuthority: [],
      selectedAuthority: [],
      fnLists: [
        { fn: 'handlerSaveUser', type: 'dialogUser' },
        { fn: 'handlerSaveUserRoles', type: 'dialogRole' },
        { fn: 'handlerSaveUserAuthority', type: 'dialogAuthority' },
      ],
      multipleSelectionsUsers: [],
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
    tableItems: function() {
      return {
        id: this.$t('table.id'),
        name: this.$t('table.userName'),
        nick: this.$t('table.nickName'),
        create: this.$t('table.createTime'),
        update: this.$t('table.updateTime'),
      };
    },
  },
  created() {
    this.getUserList();
  },
  methods: {
    handlerGetCurrentUser() {
      return this.$store.getters.userId;
    },
    async getUserList() {
      const { limit, page } = this.query;
      this.loading = true;
      // get uer lists
      const { data, meta } = await userResource.list(this.query);
      // write list user on list
      this.list = data;
      // chunk to part
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      // count list total
      this.total = meta.total;
      // off loading
      this.loading = false;
    },
    columnDefaultWidth(item) {
      if (item === 'id') {
        return 80;
      }
    },
    handlerShowDialog(visible = false, dialog, title, obj, callback, params) {
      if (callback !== undefined) {
        if (params !== undefined) {
          callback(params);
        } else {
          callback();
        }
      }
      this.formVisible = visible;
      this[dialog.name] = dialog.value;
      this.title = title;
    },
    async handlerGetRoleAndPermission() {
      const { data } = await userResource.permissions(this.currentUserId);
      return data;
    },
    async handlerShowListRoles() {
      this.listLoading = true;
      const { roles } = await this.handlerGetRoleAndPermission();
      const { data } = await rolesResource.list({});
      if (data.length > 0) {
        data.forEach((elem, index) => {
          roles.forEach((role, i) => {
            if (elem.name === role.name) {
              this.selectedRole.push(elem.id);
            }
          });
          this.listsRole.push({ id: elem.id, name: elem.name, name_zh: elem.name_zh });
        });
      }
      this.listLoading = false;
    },
    async handlerShowListAuthority() {
      this.listLoading = true;
      const { permissions } = await this.handlerGetRoleAndPermission();
      this.listsAuthority = [];
      const { data } = await permissionResource.all();
      if (data.length > 0) {
        data.forEach((elem, index) => {
          permissions.forEach((permission, i) => {
            if (elem.name === permission.name) {
              this.selectedAuthority.push(elem.id);
            }
          });
          this.listsAuthority.push({ id: elem.id, name: elem.name });
        });
      }
      console.log(this.listsAuthority);
      this.listLoading = false;
    },
    async handlerShowQrCode(id) {
      this.listLoading = true;
      const { data } = await google2fa.update(id, {});
      this.QrCode = data;
      this.listLoading = false;
    },
    handlerCloseDialog() {
      this.formVisible = false;
      this.dialogUser = false;
      this.dialogRole = false;
      this.dialogAuthority = false;
      this.dialogGoogleQrCode = false;
      this.QrCode = '';
      this.currentUser = {};
      this.selectedRole = [];
      this.selectedAuthority = [];
      this.selectedAllRole = false;
      this.selectedAllAuthority = false;
      this.listsRole = [];
      this.listsAuthority = [];
      this.currentUser = {};
    },
    validateConfirmPassword(rule, value, callback) {
      if (value !== this.currentUser.password) {
        callback(new Error(this.$t('dialog.user.validateNoConfirmPassword')));
      } else if (value.length < 6) {
        callback(new Error(this.$t('dialog.user.validateConfirmPasswordLength')));
      } else {
        callback();
      }
    },
    validateLengthPassword(rule, value, callback) {
      if (value === '') {
        callback(new Error(this.$t('dialog.user.validateLengthPasswordEmpty')));
      } else if (value.length < 6) {
        callback(new Error(this.$t('dialog.user.validateLengthPasswordSmall')));
      } else {
        callback();
      }
    },
    handlerSelectedAll(lists, selected, toggle) {
      if (lists !== undefined) {
        if (this[toggle]) {
          this[selected] = [];
        } else {
          lists.forEach((item, index) => {
            this[selected].push(item.id);
          });
        }
      }
    },
    addFunctionToLists(fn) {
      this.fnLists.push(fn);
    },
    callFunctions() {
      return this.fnLists;
    },
    handlerCallbackFunction(callbacks, args) {
      if (callbacks !== undefined) {
        if (args !== undefined) {
          callbacks().forEach((func, index) => {
            if (this[func.type]) {
              this[func.fn](args);
            }
          });
        } else {
          callbacks().forEach((func, index) => {
            if (this[func.type]) {
              this[func.fn]();
            }
          });
        }
      }
    },
    handlerSaveUser() {
      this.$refs['userForm'].validate((valid) => {
        if (valid) {
          if (this.currentUser.id !== undefined) {
            this.loading = true;
            userResource
              .update(this.currentUser.id, this.currentUser)
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.userUpdate'),
                  duration: 1 * 1000,
                });
                this.loading = false;
                this.handlerCloseDialog();
                //
                this.getUserList();
              });
          } else {
            this.loading = true;
            userResource
              .store(this.currentUser)
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.newUserCreated').replace(':marker:', this.currentUser.name),
                  duration: 1 * 1000,
                });
                this.loading = false;
                this.handlerCloseDialog();
                //
                this.getUserList();
              });
          }
        } else {
          // this.$message(this.$t('messages.errorMessage'));
          return false;
        }
      });
      // userForm
    },
    handlerDeleteUser(id) {
      this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
        type: 'warning',
      })
        .then(() => {
          if (id !== undefined) {
            userResource
              .destroy(id)
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                  duration: 1 * 1000,
                });
                this.getUserList();
              });
          } else {
            if (this.multipleSelectionsUsers.length > 0) {
              const ids = [];
              this.multipleSelectionsUsers.forEach((item, index) => {
                ids.push(item.id);
              });
              ids.join();
              userResource
                .destroy(ids)
                .then(() => {
                  this.$message({
                    type: 'success',
                    message: this.$t('messages.delCompleteUser'),
                    duration: 1 * 1000,
                  });
                  this.getUserList();
                });
            } else {
              // this.$message({
              //   type: 'error',
              //   message: this.$t('messages.noDeleteItems'),
              //   duration: 1 * 1000,
              // });
            }
          }
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: this.$t('messages.delCancelUser'),
            duration: 1 * 1000,
          });
        });
    },
    handlerSaveUserRoles() {
      userResource
        .addRoles(this.currentUserId, {
          roles: this.selectedRole,
        })
        .then(response => {
          const { data } = response;
          this.$message({
            type: 'success',
            message: data.message,
            duration: 1 * 1000,
          });
          this.handlerCloseDialog();
          setTimeout(() => {
            this.getUserList();
          }, 207);
        });
    },
    handlerSaveUserAuthority() {
      userResource
        .updatePermission(this.currentUserId, {
          permissions: this.selectedAuthority,
        })
        .then(response => {
          const { data } = response;
          this.$message({
            type: 'success',
            message: data.message,
            duration: 1 * 1000,
          });
          this.handlerCloseDialog();
          setTimeout(() => {
            this.getUserList();
          }, 207);
        });
    },
    handlerMultipleSelectionsUsers(val) {
      this.multipleSelectionsUsers = val;
    },
  },
};
</script>

<style lang="scss">
.filter-item {
  padding-bottom: 20px;
}
.list-roles {
  height: 400px;
  overflow-x: scroll;
  display: flex;
  align-items: flex-start;
  width: 100%;
  flex-wrap: wrap;
  align-content: flex-start;
  .role-item {
    padding:10px;
    input[type="checkbox"] {
      width: 20px;
      height: 20px;
      position: relative;
      top: 3px;
    }
    label {
      font-size: 1rem;
    }
    input[type="checkbox"], label {
      cursor: pointer;
    }
  }
}
.list-roles-height-auto {
  height: auto;
}
.list-chackbox-group {
  margin-bottom: 2.1rem;
}
.roles-list {
  .is-checked {
    span {
      color: #fff !important;
      background-color: #5FB878 !important;
      border-color: #5fb878 !important;
      -webkit-box-shadow: -1px 0 0 0 #4e8d5f;
      box-shadow: -1px 0 0 0 #4e8d5f;
    }
  }
}
.wrapper-tree {
  height: 500px;
  overflow: auto;
}
.wrapper-qr-code {
  text-align: center;
}
.border-bottom {
  border-bottom: 1px solid #d7d7d7;
}
</style>
