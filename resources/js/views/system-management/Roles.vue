<template>
  <div class="app-container">
    <!-- FILTERS -->
    <PermissionChecker :permissions="[viewPermission.deleteList, viewPermission.create]">
      <div class="filter-container">
        <div class="filter-item">
          <PermissionChecker :permissions="[viewPermission.deleteList]">
            <el-button icon="el-icon-delete" plain size="small" type="danger" @click="handleDeleteSelectedRole">
              {{ $t('btn.delete') }}
            </el-button>
          </PermissionChecker>
          <PermissionChecker :permissions="[viewPermission.create]">
            <el-button icon="el-icon-plus" plain size="small" type="primary" @click="handleCreateRole">{{
              $t('btn.add')
            }}
            </el-button>
          </PermissionChecker>
        </div>
      </div>
    </PermissionChecker>
    <!-- LIST ROLES -->
    <el-table
      ref="multipleTable"
      v-loading="loading"
      :data="list"
      border
      fit
      height="650"
      highlight-current-row
      style="width: 100%"
      @selection-change="handleSelectionChange"
    >
      <!-- -->
      <el-table-column align="center" type="selection" width="50" />
      <el-table-column :label="$t('table.id')" align="center" prop="id" sortable width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column :label="$t('table.name')" align="center" width="200">
        <template slot-scope="scope">
          <span>{{ language === 'en' ? scope.row.name : scope.row.name_zh }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column :label="$t('table.showName')" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.alias }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column :label="$t('table.createTime')" align="center" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.create }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column :label="$t('table.updateTime')" align="center" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.update }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <PermissionChecker :permissions="[viewPermission.edit, viewPermission.authority, viewPermission.delete]">
        <el-table-column :label="$t('table.action')" align="center" width="500">
          <template slot-scope="scope">
            <div>
              <PermissionChecker :permissions="[viewPermission.edit]">
                <el-button icon="el-icon-edit" plain size="small" type="primary" @click="handleEditRole(scope.row.id)">
                  {{ $t('btn.edit') }}
                </el-button>
              </PermissionChecker>
              <PermissionChecker :permissions="[viewPermission.authority]">
                <el-button
                  icon="el-icon-lock"
                  plain
                  size="small"
                  type="primary"
                  @click="handlePermission(scope.row.id)"
                >{{ $t('btn.authority') }}
                </el-button>
              </PermissionChecker>
              <PermissionChecker :permissions="[viewPermission.delete]">
                <el-button
                  v-if="scope.row.id!=11"
                  icon="el-icon-delete"
                  plain
                  size="small"
                  type="danger"
                  @click="handleDelete(scope.row.id)"
                >{{ $t('btn.delete') }}
                </el-button>
              </PermissionChecker>
            </div>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <!-- PAGINATION -->
    <pagination
      v-show="total>0"
      :limit.sync="query.limit"
      :page.sync="query.page"
      :total="total"
      @pagination="getRoles"
    />
    <!-- DIALOG -->
    <el-dialog :title="formTitle" :visible.sync="roleFormVisible" width="37%">
      <div v-loading="dialogloading" class="form-container">
        <el-form
          ref="roleForm"
          :model="currentRole"
          :rules="rules"
          label-position="rigth"
          label-width="177px"
          style="width: 100%"
        >
          <el-form-item :label="$t('table.name')" prop="name">
            <el-input v-model="currentRole.name" :placeholder="$t('placeholders.forName')" maxlength="27" />
          </el-form-item>
          <el-form-item :label="$t('table.nameZH')" prop="name">
            <el-input v-model="currentRole.name_zh" :placeholder="$t('placeholders.forName')" maxlength="27" />
          </el-form-item>
          <el-form-item :label="$t('dialog.authority.alias')" prop="alias">
            <el-input v-model="currentRole.alias" :placeholder="$t('placeholders.alias')" maxlength="27" />
          </el-form-item>
          <el-form-item :label="$t('dialog.authority.googleverification')" prop="code">
            <el-input
              v-model="currentRole.code"
              :placeholder="$t('placeholders.googleVerification')"
              maxlength="6"
              @focus="verifyGoogleCode"
              @input="verifyGoogleCodeInt"
            />
          </el-form-item>
        </el-form>
        <div slot="footer" align="center" class="dialog-footer">
          <el-button icon="el-icon-close" plain type="primary" @click="roleFormVisible = false">
            {{ $t('btn.cancel') }}
          </el-button>
          <el-button icon="el-icon-plus" plain type="success" @click="handleSubmit()">
            {{ $t('btn.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
    <!-- PERMISIONS -->
    <el-dialog :title="formTitle" :visible.sync="permissionFormVisible" width="37%">
      <div v-loading="permissionsLoading" class="form-container permissions-wrapper">
        <div class="radio-filter-box">
          <el-radio v-model="selectedRadioFilter" label="true" @change="selectAllOrResetPermissions">
            {{ $t('btn.selectAll') }}
          </el-radio>
          <el-radio v-model="selectedRadioFilter" label="false" @change="selectAllOrResetPermissions">{{
            $t('btn.reset')
          }}
          </el-radio>
        </div>
        <div class="wrapper-tree-box">
          <!-- TREE -->
          <el-tree
            ref="tree"
            :data="trees"
            :default-checked-keys="selectedPermissions"
            :props="defaultProps"
            node-key="id"
            show-checkbox
          />
        </div>
        <div slot="footer" align="center" class="dialog-footer permissions-control-box">
          <el-button icon="el-icon-close" plain type="primary" @click="permissionFormVisible = false">
            {{ $t('btn.cancel') }}
          </el-button>
          <el-button icon="el-icon-plus" plain type="success" @click="handleAddPermissionForRole()">
            {{ $t('btn.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import RoleResource from '@/api/role';
import waves from '@/directive/waves';
import permission from '@/directive/permission';
import PermissionsResource from '@/api/permission';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const roleResource = new RoleResource();
const permissionResource = new PermissionsResource();

export default {
  name: 'RoleList',
  components: {
    Pagination,
    PermissionChecker,
  },
  directives: { waves, permission },
  data() {
    var validateCode = (rule, value, callback) => {
      if (value) {
        value = value.replace('-', '');
        if (value.length < 6) {
          callback(new Error(this.$t('messages.codeLess')));
        } else if (/^\d+$/.test(value)) {
          this.currentRole.code = value.substr(0, 3) + '-' + value.substr(3, 5);
        } else {
          callback(new Error(this.$t('messages.ledNotCode')));
        }
        // if form validate
        callback();
      } else {
        callback(new Error(this.$t('messages.notEmpty')));
      }
    };
    return {
      viewPermission: viewsPermissions.roleManagement.permissions.controls,
      selectedRadioFilter: 'false',
      currentRole: {
        name: '',
        name_zh: '',
        alias: '',
        code: '',
      },
      list: [],
      loading: true,
      dialogloading: false,
      roleFormVisible: false,
      roles: [],
      // pagination
      total: 0,
      query: {
        page: 1,
        limit: 15,
      },
      //
      formTitle: '',
      rules: {
        code: [{ required: true, validator: validateCode, trigger: 'blur' }],
        name: [{ required: true, message: this.$t('messages.notEmpty'), trigger: 'blur' }],
        name_zh: [{ required: true, message: this.$t('messages.notEmpty'), trigger: 'blur' }],
        alias: [{ required: true, message: this.$t('messages.notEmpty'), trigger: 'blur' }],
      },
      multipleSelection: [],
      // PERMISSIONS
      permissions: [],
      permissionsLoading: false,
      permissionFormVisible: false,
      trees: [],
      allIdsTrees: [],
      selectedPermissions: [],
      defaultProps: {
        children: 'children',
        label: 'label',
      },
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getRoles();
    // get permissions current user
    this.permissions = this.$store.getters.permissions;
    // get role current user
    this.roles = this.$store.getters.roles;
  },
  methods: {
    async getRoles() {
      this.loading = true;
      const { limit, page } = this.query;
      const { data, meta } = await roleResource.list(this.query);
      this.list = data;
      this.list.forEach((role, index) => {
        role['index'] = (page - 1) * limit + index + 1;
      });
      this.loading = false;
      this.total = meta.total;
    },
    createTreeNode(id, label = '', children = []) {
      return {
        'id': id,
        'label': label,
        'children': children,
      };
    },
    insertListNode(lists, insertObj, children = false) {
      if (children) {
        if (lists[children] === undefined) {
          lists[children] = [];
        }
        lists[children].push(insertObj);
      } else {
        lists[insertObj.id] = insertObj;
      }
    },
    addChildrenNode(parent, children) {
      parent.children = children;
    },
    // list permissions
    getPermissions() {
      const idsPermissions = [];
      const trees = [];
      const listsChildrens = {};
      const listsParents = {};
      permissionResource.all()
        .then(response => {
          const data = response.data;
          // clean
          this.trees = [];
          this.allIdsTrees = [];
          if (data !== undefined) {
            for (const k in data) {
              if (data[k].parent_id === 0) {
                this.insertListNode(listsParents, this.createTreeNode(data[k].id, data[k].name));
                idsPermissions.push(data[k].id);
              } else {
                this.insertListNode(listsChildrens, this.createTreeNode(data[k].id, data[k].name), data[k].parent_id);
              }
            }
            // Filling the Array
            for (const k in listsParents) {
              if (listsParents[k].label !== '') {
                if (listsChildrens[listsParents[k].id] !== undefined) {
                  this.addChildrenNode(listsParents[k], listsChildrens[listsParents[k].id]);
                  delete listsChildrens[listsParents[k].id];
                }
                trees.push(listsParents[k]);
                delete listsParents[k];
              }
            }
            this.trees = trees;
            this.allIdsTrees = idsPermissions;
          }
          this.permissionsLoading = false;
        });
    },
    getRoleHavePermission(id) {
      roleResource.permissions(id)
        .then(response => {
          this.selectedPermissions = [];
          response.data.forEach((elem, index) => {
            this.selectedPermissions.push(elem.id);
          });
        });
    },
    handleGetArrayIndex(id) {
      return this.list.findIndex((role) => role.id === id);
    },
    handleDeleteElementList(index) {
      this.list.splice(index, 1); // delete one element in role list
    },
    deleteList(index, list) {
      list.splice(index, 1);
    },
    // DELETE ROLE
    handleDelete(id) {
      this.$confirm(this.$t('messages.roleDelete'), 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
        type: 'warning',
      })
        .then(() => {
          // delete role
          roleResource
            .destroy(id)
            .then(response => {
              this.$message({
                type: 'success',
                message: this.$t('messages.roleUpdated').replace(':marker:', this.currentRole.name),
                duration: 1 * 1000,
              });
            })
            .catch(error => {
              console.log(error);
              // this.$message({
              //   type: 'error',
              //   message: error,
              //   duration: 1 * 1000,
              // });
            })
            .finally(() => {
              // delete element on list
              this.handleDeleteElementList(this.handleGetArrayIndex(id));
            });
        });
    },
    // delete selected role
    handleDeleteSelectedRole() {
      const selectedListIds = [];
      const listIds = this.multipleSelection;
      if (this.multipleSelection.length === 0) {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.roleNotSelected'),
        //   duration: 1 * 1000,
        // });
      } else {
        // set delete ids
        listIds.forEach((elem, index) => {
          selectedListIds.push(elem.id);
        });
        // confirm
        this.$confirm(this.$t('messages.roleDelete'), 'Warning', {
          confirmButtonText: this.$t('btn.ok'),
          cancelButtonText: this.$t('btn.cancel'),
          type: 'warning',
        })
          .then(() => {
            // delete role
            roleResource
              .deliteLists({ 'ids': selectedListIds })
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.roleListDelete'),
                  duration: 1 * 1000,
                });
                this.update();
              })
              .catch(error => {
                console.log(error);
                // this.$message({
                //   type: 'error',
                //   message: error,
                //   duration: 1 * 1000,
                // });
              });
          });
      }
    },
    isAdmin(list, isRole = 'admin') {
      return list.findIndex((role) => role.name === isRole);
    },
    // SHOW SELECTED ELEMENTS IN TABLE
    handleSelectionChange(value) {
      const list = value;
      // if user admin
      const isAdmin = this.isAdmin(list);
      if (isAdmin !== -1) {
        this.$refs.multipleTable.toggleRowSelection(this.list[isAdmin], false);
        this.deleteList(list[isAdmin], list);
      }
      this.multipleSelection = list;
    },
    // empty data
    emptyRoleData() {
      return {
        name: '',
        alias: '',
        code: '',
      };
    },
    // ADD NEW ROLE
    handleCreateRole() {
      this.enableShowForm(); // show form
      this.formTitle = this.$t('dialog.authority.addTitle');
      this.currentRole = this.emptyRoleData(); // set empty object for role
    },
    // disable show form
    disableShowForm() {
      this.roleFormVisible = false;
    },
    enableShowForm() {
      this.roleFormVisible = true;
    },
    // submit role data
    handleSubmit() {
      // validation
      this.$refs['roleForm'].validate((valid) => {
        if (valid) {
          if (this.currentRole.id !== undefined) {
            this.currentRole.code = this.currentRole.code.replace('-', '');
            roleResource
              .update(this.currentRole.id, this.currentRole)
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.roleUpdate').replace(':marker:', this.currentRole.name),
                  duration: 1 * 1000,
                });
              })
              .catch(error => {
                console.log(error);
                // this.$message({
                //   type: 'error',
                //   message: error,
                //   duration: 1 * 1000,
                // });
              })
              .finally(() => {
                this.dialogloading = false;
                this.disableShowForm();
              });
          } else {
            this.dialogloading = true;
            this.currentRole.code = this.currentRole.code.replace('-', '');
            roleResource
              .store(this.currentRole)
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.roleAdd').replace(':marker:', this.currentRole.name),
                  duration: 1 * 1000,
                });
                this.update();
              })
              .catch(error => {
                console.log(error);
                // this.$message({
                //   type: 'error',
                //   message: error,
                //   duration: 1 * 1000,
                // });
              })
              .finally(() => {
                this.dialogloading = false;
                this.disableShowForm();
              });
          }
        }
      });
    },
    // EDIT  ROLE
    handleEditRole(id) {
      this.formTitle = this.$t('dialog.authority.editTitle');
      this.currentRole = this.list.find(role => role.id === id);
      this.enableShowForm(); // show form
    },
    // google verify code
    verifyGoogleCode() {
      if (this.currentRole.code) {
        this.currentRole.code = this.currentRole.code.replace('-', '');
      }
    },
    verifyGoogleCodeInt() {
      if (this.currentRole.code) {
        this.currentRole.code = this.currentRole.code.replace(/[^0-9]/g, '');
      }
    },
    update() {
      this.getRoles();
    },
    // ADD PERMISSION TO ROLE
    handlePermission(id) {
      this.formTitle = this.$t('titles.addPermissionsForRole');
      this.currentRoleId = '';
      this.currentRoleId = id;
      this.permissionsLoading = true;
      this.getRoleHavePermission(id);
      this.permissionFormVisible = true;
      this.getPermissions();
    },
    // add permission for roles
    handleAddPermissionForRole() {
      this.selectedPermissions = this.$refs.tree.getCheckedKeys().concat(this.$refs.tree.getHalfCheckedKeys());
      const selectedPermissions = this.selectedPermissions;
      const id = this.currentRoleId;
      const listPermissions = { 'permissions': selectedPermissions };
      if (id !== undefined) {
        this.permissionsLoading = true;
        roleResource
          .updatePermissions(id, listPermissions, 'add-permissions-role')
          .then(response => {
            const { data } = response;
            this.$message({
              type: 'success',
              message: data.message,
              duration: 1 * 1000,
            });
            this.permissionFormVisible = false;
            this.permissionsLoading = false;
          });
      }
    },
    selectAllOrResetPermissions(e) {
      const listPermissions = [];
      if (e === 'false') {
        this.$refs.tree.setCheckedKeys([]);
      } else {
        this.allIdsTrees.forEach((item, index) => {
          listPermissions.push(item);
        });
        this.$refs.tree.setCheckedKeys(listPermissions);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.wrapper-tree-box {
  padding-top: 10px;
  height: 600px;
  overflow-y: auto;
}

.permissions-control-box {
  padding-top: 15px;
}

.radio-filter-box {
  border-bottom: 1px solid #d7d7d7;
  padding-bottom: 10px;
  padding-left: 5px;
}
</style>
