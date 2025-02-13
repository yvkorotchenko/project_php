<template>
  <div class="app-container">
    <!-- TABLE -->
    <el-table ref="multipleTable" v-loading="tables.user.loading" :data="tables.user.list" border fit highlight-current-row style="width: 100%" height="650" @select-all="handlerSelectedAllForDeletes" @selection-change="handlerSelectedChangeForDeletes">
      <!-- -ID- -->
      <el-table-column align="center" :label="$t('table.id')" width="80" prop="id">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!-- -USER_NAME- -->
      <el-table-column align="center" :label="$t('table.priority')">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <!-- -USER_NIK- -->
      <el-table-column align="center" :label="$t('table.intervalMinutes')">
        <template slot-scope="scope">
          <span>{{ scope.row.nick }}</span>
        </template>
      </el-table-column>
      <!-- -ROLE- -->
      <el-table-column align="center" :label="$t('table.englishContent')">
        <template slot-scope="scope">
          <span>{{ scope.row.roles.join(', ') }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.thaiContent')">
        <template slot-scope="scope">
          <span>{{ scope.row.create }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.startTimeCreateTime')">
        <template slot-scope="scope">
          <span>{{ scope.row.update }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.EndTimeCreateTime')">
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.operation')">
        <template slot-scope="scope">
          <el-button type="success" :data-row="scope.row.id">
            noTranslate
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import UserResource from '@/api/user';
import Resource from '@/api/resource';
// Waves directive
import waves from '@/directive/waves';
// Permission directive
import permission from '@/directive/permission';
// Permission checking
// import checkPermission from '@/utils/permission';

const userResource = new UserResource();
const permissionResource = new Resource('permissions');
const roles = new Resource('roles');

console.log('PermissionResource:');
console.log(permissionResource);
console.log('usreResource');
console.log(userResource);

export default {
  name: 'PictureConfiguration',
  components: { Pagination },
  directives: { waves, permission },
  data() {
    // validate password input user
    var validateConfirmPassword = (rule, value, callback) => {
      if (value !== this.dialogs.user.currentUser.password) {
        callback(new Error(this.$t('dialog.user.validateNoConfirmPassword')));
      } else if (value.length < 6) {
        callback(new Error(this.$t('dialog.user.validateConfirmPasswordLength')));
      } else {
        callback();
      }
    };
    var validateLengthPassword = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('dialog.user.validateLengthPasswordEmpty')));
      } else if (value.length < 6) {
        callback(new Error(this.$t('dialog.user.validateLengthPasswordSmall')));
      } else {
        callback();
      }
    };
    return {
      dialogs: {
        formTitle: '',
        user: {
          // hide user form
          userFormVisible: false,
          userLoading: false,
          selectedUsersForDeletes: [],
          currentUser: {},
          rules: {
            name: [{ required: true, message: this.$t('dialog.user.nameRule'), trigger: 'blur' }],
            nick: [{ required: true, message: this.$t('dialog.user.nickRule'), trigger: 'blur' }],
            password: [{ required: true, validator: validateLengthPassword, trigger: 'blur' }],
            confirmPassword: [{ validator: validateConfirmPassword, trigger: 'blur' }],
          },
        },
        roles: {
          // hide role form
          roleFormVisible: false,
          // pre loading data
          rolesLoading: false,
          lists: [],
          selectedRoles: [],
        },
        authority: {
          // hide authority form
          authorityFormVisible: false,
          // loading preloader
          authorityLoading: false,
          tree: [{
            id: 1,
            label: 'Level one 1',
            children: [{
              id: 4,
              label: 'Level two 1-1',
              children: [{
                id: 9,
                label: 'Level three 1-1-1',
              }, {
                id: 10,
                label: 'Level three 1-1-2',
                children: [{
                  id: 33,
                  label: 'Item One',
                }],
              }, {
                id: 11,
                label: 'Level three 1-1-3',
              }, {
                id: 12,
                label: 'Level three 1-1-4',
              }, {
                id: 13,
                label: 'Level three 1-1-5',
              }, {
                id: 14,
                label: 'Level three 1-1-6',
              }],
            }],
          },
          // new item
          {
            id: 100,
            label: 'Item 100',
            children: [
              {
                id: 104,
                label: 'Item 104',
              },
              {
                id: 105,
                label: 'Item 105',
              },
              {
                id: 106,
                label: 'Item 106',
              },
              {
                id: 107,
                label: 'Item 107',
              },
            ],
          },
          {
            id: 101,
            label: 'Item 101',
            children: [
              {
                id: 104,
                label: 'Item 104',
              },
              {
                id: 105,
                label: 'Item 105',
              },
              {
                id: 106,
                label: 'Item 106',
              },
              {
                id: 107,
                label: 'Item 107',
              },
            ],
          },
          {
            id: 103,
            label: 'Item 103',
            children: [
              {
                id: 104,
                label: 'Item 104',
              },
              {
                id: 105,
                label: 'Item 105',
              },
              {
                id: 106,
                label: 'Item 106',
              },
              {
                id: 107,
                label: 'Item 107',
              },
            ],
          }],
        },
        identidy: {
          // hide authority form
          identidyFormVisible: false,
          // loading preloader
          identidyLoading: false,
        },
      },
      tables: {
        user: {
          loading: false,
          // selected user to deleted
          selectedToDelete: [],
          list: [],
          // weher slected all list
          selectedAllList: false,
        },
      },
      list: null,
      total: 0,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        role: '',
      },
      editMode: false,
      // list all isset roles
      rolesAndPermissions: [],
      defaultProps: {
        children: 'children',
        label: 'label',
      },
    };
  },
  // CREATED
  created() {
    // clear user data to empty value
    this.handlerResetCurrentUserData();
    // get list user
    this.getList();
    this.getRoles();
  },
  // METHODS
  methods: {
    /* START DIALOG TITLE */
    setFormTitle(val) {
      this.dialogs.formTitle = val;
    },
    getFormTitle() {
      return this.dialogs.formTitle;
    },
    /* END DIALOG TITLE */
    /* START DIALOG USER */
    // *** USER FORM VISIBLE OR HIDE
    getUserFormVisible() {
      return this.dialogs.user.userFormVisible;
    },
    setUserFormVisible(val) {
      this.dialogs.user.userFormVisible = val;
    },
    offUserForm() {
      this.setUserFormVisible(false);
    },
    onUserForm() {
      this.setUserFormVisible(true);
    },
    // *** USER DIALOG LOADING
    getUserLoading() {
      return this.dialogs.user.userLoading;
    },
    setUserLoading(val) {
      this.dialogs.user.userLoading = val;
    },
    onUserLoading() {
      this.setUserLoading(true);
    },
    offUserLoading() {
      this.setUserLoading(false);
    },
    // *** USER CURRENT USER DATA
    getCurrentUser() {
      return this.dialogs.user.currentUser;
    },
    setCurrentUser(val) {
      this.dialogs.user.currentUser = val;
    },
    // currentUser
    /* END DIALOG USER */
    /* START METHOD CREATE OR UPDATE USER */
    handlerShowFormAddNewUser() {
      // set title on form
      this.setFormTitle(this.$t('dialog.user.addTitle'));
      // set default obj user
      this.handlerResetCurrentUserData();
      // if user edit mode
      if (this.getEditMode()) {
        // add rule for validation
        this.handleAddUserRules();
        // disable edit mode
        this.offEditMode();
      }
      // show form add new user
      this.onUserForm();
      // clear validation
      this.$nextTick(() => {
        this.$refs['userForm'].clearValidate();
      });
    },
    handlerCreateUser() {
      // validation
      this.$refs['userForm'].validate((valid) => {
        // if enable edit mode no validate password
        if (valid) {
          // send reques to api backend
          if (this.getCurrentUser().id !== undefined) {
            // set object to current user
            const currentUser = this.getCurrentUser();
            // on loading progress
            this.onUserLoading();
            // SEND REQUEST TO SERVER CREATE NEW USER
            userResource
              .update(currentUser.id, currentUser)
              .then(response => {
                // show message update successfully
                this.message(this.$t('messages.userUpdate'));
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                // off loading progress
                this.offUserLoading();
                // hide user form
                this.offUserForm();
                // update user list
                this.handlerUpdate();
              });
            // RESET RULE
            this.handleAddUserRules();
          } else {
            // ON LOADING PROGRESS
            this.onUserLoading();
            // SEND REQUEST TO SERVER UPDATE USER
            userResource
              // get Object in user data
              .store(this.getCurrentUser())
              .then(response => {
                // replace name user to maker
                this.message(this.$t('messages.newUserCreated').replace(':marker:', this.getUserName()));
                // clear data curren user
                this.handlerResetCurrentUserData();
              })
              .catch(error => {
                console.log(error);
              });
            // OFF LOADING PROGRESS
            this.offUserLoading();
            // HIDE VISIBLE FORM
            this.offUserForm();
            // update list user
            this.handlerUpdate();
          }
        } else {
          // show error message for validate
          this.errorMessage(this.$t('messages.errorMessage'));
          console.log('error submit!!!');
          return false;
        }
      });
      //
    },
    /* END METHOD CREATE OR UPDATE USER */
    /* START METHOD TABLE LIST */
    getTableLoadingList() {
      return this.tables.user.loading;
    },
    setTableLoadingList(val) {
      this.tables.user.loading = val;
    },
    onTableLoad() {
      this.setTableLoadingList(true);
    },
    offTableLoad() {
      this.setTableLoadingList(false);
    },
    // SET GET LIST DATE
    getTableListUsers() {
      return this.tables.user.list;
    },
    setTableListUsers(val) {
      this.tables.user.list = val;
    },
    // *** SELECTED TO DELETE USERS
    getTableSelectedDeleteUsers() {
      return this.tables.user.selectedToDelete;
    },
    setTableSelectedDeleteUsers(val) {
      this.tables.user.selectedToDelete = val;
    },
    addTableSelectedToDeletUser(id) {
      this.getTableSelectedDeleteUsers().push(id);
    },
    deleteTableSelectedToIdDeletUser(id) {
      delete this.getTableSelectedDeleteUsers()[id];
    },
    /* LIST USERS */
    async getList() {
      const { limit, page } = this.query;
      // on loading data
      this.onTableLoad();
      // get uer lists
      const { data, meta } = await userResource.list(this.query);
      // console.log(data[0]);
      // write list user on list
      this.setTableListUsers(data);
      // chunk to part
      this.getTableListUsers().forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      // count list total
      this.total = meta.total;
      // off loading
      this.offTableLoad();
    },
    /* END METHOD TABLE LIST */
    /* START METHOD ROLES LIST */
    async getRoles() {
      // array role list empty
      if (this.isLengthRoleListNull()) {
        const { data } = await roles.list({});
        // write list roles
        data.forEach((elem, index) => {
          // add item on roles list
          this.addItemOnRoleList(elem.name);
        });
      }
    },
    /* END METHOD ROLES LIST */
    /* STAR METHOD EDIT USER */
    handlerEditUser(id) {
      // console.log(id);
      // set title on form
      this.setFormTitle(this.$t('dialog.user.editTitle'));
      // find edit user
      this.setCurrentUser(this.handlerFindUserById(this.getTableListUsers(), id));
      // show form edit for user
      this.onUserForm();
      // set edit mode for user
      this.onEditMode();
      // delete rule
      this.handlerDeleteUserRule();
    },
    /* END METHOD EDIT USER */
    /* START EDIT ROLE */
    // ROLE SET / GET
    // -- VISIBLE FORM ROLE
    getRoleFormVisible() {
      return this.dialogs.roles.roleFormVisible;
    },
    setRoleFormVisible(val) {
      this.dialogs.roles.roleFormVisible = val;
    },
    onRoleForm() {
      this.setRoleFormVisible(true);
    },
    offRoleForm() {
      this.setRoleFormVisible(false);
    },
    getRoleLoading() {
      return this.dialogs.roles.rolesLoading;
    },
    setRoleLoading(val) {
      this.dialogs.roles.rolesLoading = val;
    },
    onRoleLoading() {
      this.setRoleLoading(true);
    },
    offRoleLoading() {
      this.setRoleFormVisible(false);
    },
    getRoleLists() {
      return this.dialogs.roles.lists;
    },
    setRoleLists(val) {
      this.dialogs.roles.lists = val;
    },
    getRoleSelected() {
      return this.dialogs.roles.selectedRoles;
    },
    setRoleSelected(val) {
      this.dialogs.roles.selectedRoles = val;
    },
    // HANDLER
    handlerEditRole(id) {
      // set title for form
      this.setFormTitle(this.$t('dialog.role.roleTitle'));
      // get current user object roles and permission for user
      this.setRoleSelected(this.handlerFindUserById(this.getTableListUsers(), id).roles);
      // show dialog change role
      this.onRoleForm();
    },
    /* END EDIT ROLE */
    /* START EDIT AUTHORITY */
    // SET / GET
    getAuthorityFormVisible() {
      return this.dialogs.authority.authorityFormVisible;
    },
    setAuthorityFormVisible(val) {
      this.dialogs.authority.authorityFormVisible = val;
    },
    onAuthorityForm() {
      this.setAuthorityFormVisible(true);
    },
    offAuthorityForm() {
      this.setAuthorityFormVisible(false);
    },
    getAuthorityLoading() {
      return this.dialogs.authority.authorityLoading;
    },
    setAuthorityLoading(val) {
      this.dialogs.authority.authorityLoading = val;
    },
    onAuthorityLoading() {
      this.setAuthorityLoading(true);
    },
    offAuthorityLoading() {
      this.setAuthorityLoading(false);
    },
    // getAuthorityFormVisible() {},
    // getAuthorityFormVisible() {},
    // getAuthorityFormVisible() {},
    // --- HENDLERS ---
    hendlerEidtAuthority(id) {
      // set title
      this.setFormTitle(this.$t('dialog.authority.authorityTitle'));
      // show authority form
      this.onAuthorityForm();
      // console.log(id);
    },
    /* END EDIT AUTHORITY */
    /* DELETES METHODS */
    handlerSelectedAllForDeletes(list) {
      // when list dont selected
      if (this.getSelectedAllList()) {
        // clear all selected
        this.$refs.multipleTable.clearSelection();
        // clear selected list to delete
        this.setTableSelectedDeleteUsers([]);
        // off selected all list
        this.offSelectedAllList();
      } else {
        // filtered list
        const filteredList = [];
        list.forEach((item, index) => {
          if (this.handlerUserIsAdmin(item.roles)) {
            // set selected list for Delete
            this.$refs.multipleTable.toggleRowSelection(item, 'selected');
          } else {
            // add filtered item
            filteredList.push(item);
          }
        });
        // set to selected list for delete elements
        this.setTableSelectedDeleteUsers(filteredList);
        // on to selected list all
        this.onSelectedAllList();
      }
    },
    handlerSelectedChangeForDeletes(list) {
      // filtered list
      const filteredList = [];
      list.forEach((item, index) => {
        if (this.handlerUserIsAdmin(item.roles)) {
          // set selected list for Delete
          this.$refs.multipleTable.toggleRowSelection(item, false);
          // delete admin user on list
          delete list[index];
        } else {
          filteredList.push(item);
        }
      });
      // if array length change replace this array
      if (this.getTableSelectedDeleteUsers().length !== filteredList.length) {
        this.setTableSelectedDeleteUsers(filteredList);
      }
    },
    handlerSingleDeleteUser(id, name, roles) {
      if (this.handlerUserIsAdmin(roles)) {
        this.$alert(this.$t('messages.userDeleteAlert'), 'Warning', {
          confirmButtonText: 'OK',
          callback: action => {
            this.$message({
              type: 'info',
              message: this.$('messages.userNoDelete'),
              duration: 1 * 1000,
            });
          },
        });
      } else {
        // show warning messages when delete
        this.confirmMessage(this.$t('messages.ConfirmDeleteMessageUser').replace(':marker:', name))
          .then(() => {
            userResource.destroy(id).then(response => {
              this.message(this.$t('messages.delCompleteUser'));
              // Update list user
              this.handlerUpdate();
            }).catch(error => {
              console.log(error);
            });
          }).catch(() => {
            this.message(this.$t('messages.delCancelUser'), 'info');
          });
      }
    },
    handlerSelectedDeletes() {
      // list selected for delete
      const list = this.getTableSelectedDeleteUsers();
      if (list === null || list.length === 0) {
        this.errorMessage(this.$t('messages.selectedUserForDel'));
      } else {
        // list ids separate ,
        let ids = '';
        // filterd list no admin
        list.forEach((elem) => {
          // add id if user dont have role admin
          if (!this.handlerUserIsAdmin(elem.roles)) {
            ids += elem.id + ',';
          }
        });
        // delete last char ,
        ids = ids.substr(0, ids.length - 1);
        // show warning messages when delete
        this.confirmMessage(this.$t('messages.ConfirmDeleteMessageUser').replace(':marker:', name))
          .then(() => {
            userResource.destroy(ids).then(response => {
              this.message(this.$t('messages.delCompleteUser'));
              // Update list user
              this.handlerUpdate();
            }).catch(error => {
              console.log(error);
            });
          }).catch(() => {
            this.message(this.$t('messages.delCancelUser'), 'info');
          });
        // off selected list
        this.offSelectedAllList();
      }
    },
    // #---> HELPERS <---#
    message(msg, type = 'success', time = 7 * 100) {
      this.$message({
        message: msg,
        type: type,
        duration: time,
      });
    },
    errorMessage(msg) {
      this.$message.error(msg);
    },
    confirmMessage(msg, title = 'Warning', btnOk = 'OK', btnClose = 'Cancel', type = 'warning') {
      return this.$confirm(msg, title, {
        confirmButtonText: btnOk,
        cancelButtonText: btnClose,
        type: type,
      });
    },
    // filteret data
    handlerFilter() {
      this.query.page = 1;
      this.getList();
    },
    handlerUpdate() {
      this.getList();
    },
    handlerResetUserData() {
      return {
        name: '',
        nick: '',
        ip: '',
        password: '',
        confirmPassword: '',
      };
    },
    handlerResetCurrentUserData() {
      this.setCurrentUser(this.handlerResetUserData());
    },
    // USER HAVE ROLE ADMIN
    handlerUserIsAdmin(val) {
      var isadmin = false;
      val.forEach((role) => {
        if (role === 'admin') {
          isadmin = true;
        }
      });
      return isadmin;
    },
    handlerFindUserById(lists, id) {
      return lists.find(users => users.id === id);
    },
    // EDIT MODE
    getEditMode() {
      return this.editMode;
    },
    setEditMode(val) {
      this.editMode = val;
    },
    onEditMode() {
      this.setEditMode(true);
    },
    offEditMode() {
      this.setEditMode(false);
    },
    handlerDeleteUserRule() {
      this.dialogs.user.rules.password = [{}];
      this.dialogs.user.rules.confirmPassword = [{}];
    },
    handleAddUserRules() {
      this.dialogs.user.rules.password = [{ required: true, validator: this.validateLengthPassword, trigger: 'blur' }];
      this.dialogs.user.rules.confirmPassword = [{ validator: this.validateConfirmPassword, trigger: 'blur' }];
    },
    getUserName() {
      return this.dialogs.user.currentUser.name;
    },
    // GET / SET role and permission
    getRoleAndPermission() {
      return this.rolesAndPermissions;
    },
    setRoleAndPermission(val) {
      this.rolesAndPermissions = val;
    },
    isNullRoleAndPermission() {
      if (this.getRoleAndPermission() === null) {
        return true;
      }
      // permission isset
      return false;
    },
    lengthIsNullRoleAndPermission() {
      if (this.getRoleAndPermission() === 0) {
        return true;
      }
      // permission empty
      return false;
    },
    isRoleListsNull() {
      if (this.getRoleLists() === null) {
        return true;
      }
      // permission isset
      return false;
    },
    isLengthRoleListSelectedNull() {
      if (this.getRoleSelected().length === 0) {
        return true;
      }
      return false;
    },
    isLengthRoleListNull() {
      if (this.getRoleLists().length === 0) {
        return true;
      }
      // permission empty
      return false;
    },
    addItemOnRoleList(item) {
      this.getRoleLists().push(item);
    },
    clearAllRoleList() {
      this.setRoleLists([]);
    },
    deleteItemOnRoleList(id) {
      delete this.getRoleLists()[id];
    },
    tcheck(event) {
      // console.log('Event all');
      // console.log(event);
      // console.log('target');
      // console.log(event.target);
      // if (eve.target.name === "") {}
      return false;
    },
    // SELECTED LIST ALL
    getSelectedAllList() {
      return this.tables.user.selectedAllList;
    },
    setSelectedAllList(val) {
      this.tables.user.selectedAllList = val;
    },
    onSelectedAllList() {
      this.setSelectedAllList(true);
    },
    offSelectedAllList() {
      this.setSelectedAllList(false);
    },
    // END METHODS />
  },
};
</script>

<style lang="scss">
.button-group {
  background-color: #009688;
  border-color: #009688;

  &:hover, &:focus {
    background-color: #00bfad;
    border-color: #00d4c0;
  }

  &:active {
    background-color: #008275;
    border-color: #008275;
  }
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
</style>
