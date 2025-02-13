<template>
  <div class="app-container">
    <!-- FILTERS -->
    <PermissionChecker :permissions="['authority management edit', 'authority management list delete']">
      <div class="filter-container">
        <div class="filter-item">
          <PermissionChecker :permissions="['authority management list delete']">
            <el-button type="danger" icon="el-icon-delete" size="small" plain @click="handleDeleteSelectedPermission">{{ $t('btn.delete') }}</el-button>
          </PermissionChecker>
          <PermissionChecker :permissions="['authority management edit']">
            <el-button type="primary" icon="el-icon-plus" size="small" plain @click="handleCreatePermission">{{ $t('btn.add') }}</el-button>
          </PermissionChecker>
          <!-- <el-button type="primary" size="small" plain @click="handleReturnUpperLevel">{{ $t('btn.returnToSuperior') }}</el-button> -->
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
      highlight-current-row
      style="width: 100%"
      height="650"
      @selection-change="handleSelectionChange"
    >
      <!-- -->
      <el-table-column align="center" type="selection" width="50" />
      <el-table-column align="center" label="ID" width="70" prop="id" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.authorityName')">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('labels.display')" width="107">
        <template slot-scope="scope">
          <span>{{ options[scope.row.visible].label }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('table.sort')" width="107">
        <template slot-scope="scope">
          <span>{{ scope.row.sort }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.createTime')" width="207">
        <template slot-scope="scope">
          <span>{{ scope.row.create }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.updateTime')" width="207">
        <template slot-scope="scope">
          <span>{{ scope.row.update }}</span>
        </template>
      </el-table-column>
      <PermissionChecker :permissions="['authority management edit', 'authority management delete']">
        <el-table-column v-if="handleHavePermission(['sub-permission', 'edit', 'delete'])" align="center" :label="$t('table.action')" width="337">
          <template slot-scope="scope">
            <div>
              <PermissionChecker :permissions="['authority management edit']">
                <el-button type="primary" icon="el-icon-edit" plain :label="scope.row.id" size="small" @click="handleEditPermissions(scope.row.id)">{{ $t('btn.edit') }}</el-button>
              </PermissionChecker>
              <PermissionChecker :permissions="['authority management delete']">
                <el-button type="danger" icon="el-icon-delete" size="small" plain @click="handlerDeleteItem(scope.row.id)">{{ $t('btn.delete') }}</el-button>
              </PermissionChecker>
            </div>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <!-- PAGINATION -->
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
    <!-- dialog -->
    <el-dialog :title="formTitle" :visible.sync="permissionFormVisible" width="57%">
      <!-- [Start Permission list dialog] -->
      <el-dialog width="57%" height="650" :title="$t('labels.permissionListTree')" :visible.sync="permissionListTree" append-to-body>
        <!-- list permission TREE -->
        <el-tree :data="trees" :props="defaultProps" :load="loadTree" lazy show-checkbox />
      </el-dialog>
      <!-- ICONS -->
      <el-dialog width="57%" height="650" :title="$t('labels.permissionListIcons')" :visible.sync="permissionListIcons" append-to-body>
        <div>LIST ICONS</div>
      </el-dialog>
      <!-- [End Permission list dialog] -->
      <div class="form-container">
        <el-form ref="permissionsForm" :rules="rules" :model="currentPermission" label-position="rigth" label-width="177px" style="width: 100%">
          <!-- Parent handleShowListPermission -->
          <!-- <el-form-item :label="$t('labels.parent')" prop="parent">
            <el-button type="primary" size="mini" @click="permissionListTree = true">{{ $t('btn.choosePermissions') }}</el-button>
          </el-form-item> -->
          <!-- Name -->
          <el-form-item :label="$t('labels.name')" prop="name">
            <el-input v-model="currentPermission.name" :placeholder="$t('placeholders.permissionsName')" @input="verifyValueString('name')" />
          </el-form-item>
          <!-- Sort -->
          <el-form-item :label="$t('labels.sort')" prop="sort">
            <el-input v-model="currentPermission.sort" :placeholder="$t('placeholders.permissionsSort')" @input="verifySortInt" />
          </el-form-item>
          <!-- Visible -->
          <el-form-item :label="$t('labels.display')" prop="visible">
            <el-select v-model="currentPermission.visible" placeholder="please select your zone">
              <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value" />
            </el-select>
          </el-form-item>
          <!-- Icon -->
          <!-- <el-form-item :label="$t('labels.permissionsIcon')" prop="icon">
            <el-button type="primary" size="mini" @click="permissionListIcons = true">{{ $t('btn.choosePermissionsIcon') }}</el-button>
          </el-form-item> -->
        </el-form>
        <div slot="footer" class="dialog-footer" align="center">
          <!-- <el-button type="primary" icon="el-icon-close" plain @click="permissionFormVisible = false">
            Cancel
          </el-button> -->
          <el-button type="success" icon="el-icon-plus" plain @click="handleSubmit()">
            Confirm
          </el-button>
        </div>
      </div>
    </el-dialog>
    <!--  -->
  </div>
</template>

<script>
import pagination from '@/components/Pagination';
import waves from '@/directive/waves';
import PermissionsResource from '@/api/permission';
import permission from '@/directive/permission'; // Permission directive
import PermissionChecker from '@/components/Permissions/index.vue';
import havePermission from '@/utils/permission'; // test permvision
const permissionResource = new PermissionsResource();

export default {
  name: 'Authority',
  components: {
    pagination,
    PermissionChecker,
  },
  directives: { waves, permission },
  data() {
    // VALUE NAME
    var validateNameValue = (rule, value, callback) => {
      if (value) {
        if (value.length > 77) {
          callback(new Error(this.$t('messages.permissionsLess').replace(':int:', 77)));
        }
        if (/[^a-zA-Z\s]+/i.test(value)) {
          callback(new Error(this.$t('messages.permissionsOnlyLettersSpace')));
        }
        // FORM VALIDATE
        callback();
      } else {
        callback(new Error(this.$t('messages.notEmpty')));
      }
    };
    // SORT
    var validateSortValue = (rule, value, callback) => {
      if (value !== null || value !== undefined) {
        if (value.length > 17) {
          callback(new Error(this.$t('messages.permissionsLess').replace(':int:', 17)));
        }
        if (/[^\d]+/i.test(value)) {
          callback(new Error(this.$t('messages.permissionsOnlyNumbers')));
        } else {
          this.currentPermission.sort = value;
        }
        // FORM VALIDATE
        callback();
      } else {
        callback(new Error(this.$t('messages.notEmpty')));
      }
    };
    return {
      formTitle: '',
      currentParent: 0,
      currentPermission: {
        parent: 0,
        name: '',
        showName: '',
        route: '',
        sort: 0,
        visible: 1,
        icon: '',
      },
      total: 0,
      list: [],
      loading: false,
      query: {
        page: 1,
        limit: 15,
        // parent: 0,
      },
      permissionFormVisible: false,
      // permission
      allPermission: [],
      permissionListTree: false,
      // default props permission
      permissionListIcons: false,
      // parentListProps: {},
      defaultProps: {
        label: 'name',
        children: 'zones',
        isLeaf: 'leaf',
      },
      // selected permission for delete
      multipleSelection: [],
      // route tree
      topLevelTree: 0,
      routePermission: [],
      // route parent
      routeParent: 0,
      listsTrees: [],
      trees: [],
      options: [
        {
          value: 0,
          label: this.$t('labels.noDisplay'),
        },
        {
          value: 1,
          label: this.$t('labels.display'),
        }],
      // rules
      rules: {
        name: [{ required: true, validator: validateNameValue, trigger: 'blur' }],
        // showName: [{ required: true, validator: validateNameValue, trigger: 'blur' }],
        // route: [{ required: true, validator: validaterRoutingValue, trigger: 'blur' }],
        sort: [{ required: true, validator: validateSortValue, trigger: 'blur' }],
      },
    };
  },
  created() {
    let parent = this.$route.params.parent;
    if (parent === ':parent' || parent === undefined) {
      parent = this.routeParent;
    } else {
      this.routeParent = parent;
    }
    this.getList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await permissionResource.list(this.query);
      this.list = data;
      this.list.forEach((permission, index) => {
        permission['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    // sub-permission
    handleSubPermission(id, noRoute = false) {
      let parent = this.$route.params.parent;
      if (parent === undefined) {
        parent = id;
      } else {
        parent = this.routeParent = id;
        // set in patch
        this.$router.push('/system-management/authority/' + parent);
      }
      // console.log(parent);
      // console.log(id);
      this.query.parent = parent;
      // set route
      if (!noRoute) {
        this.routePermission.push(parent);
      }
      this.getList(this.query);
      // console.log(this.$router);
    },
    // delete selected role
    handleDeleteSelectedPermission() {
      const listIds = [];
      if (this.multipleSelection.length === 0) {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.noSelectionPermissionDelete'),
        //   duration: 1 * 1000,
        // });
      } else {
        this.multipleSelection.forEach((permission, index) => {
          listIds.push(permission.id);
        });
        // CONFIRM
        this.$confirm(this.$t('messages.permissionsDelete'), 'Warning', {
          confirmButtonText: this.$t('btn.ok'),
          cancelButtonText: this.$t('btn.cancel'),
          type: 'warning',
        })
          .then(() => {
            // DELETE PERMISSION LIST
            permissionResource
              .deliteLists({ 'ids': listIds })
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.permissionsListDeleted'),
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
    // SELECTED PREMISSION FOR DELETED LIST
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
    // empty data
    emptyPermissionData() {
      return {
        parent: 0,
        name: '',
        showName: '',
        route: '',
        sort: 0,
        visible: 1,
        icon: '',
      };
    },
    // ADD NEW PERMISIION
    handleCreatePermission() {
      this.enableShowForm(); // Show form
      this.formTitle = this.$t('messages.createPermission');
      this.currentPermission = this.emptyPermissionData();
      // clear trees
      this.trees = [];
      // push top level parent
      this.trees.push(this.handleAddItemTree(0, this.$t('labels.mainLevelTree')));
      // ADD NEW ---
    },
    handleTopParent() {
      return {};
    },
    // disable show form
    disableShowForm() {
      this.permissionFormVisible = false;
    },
    enableShowForm() {
      this.permissionFormVisible = true;
    },
    // submit role data
    handleSubmit() {
      this.$refs['permissionsForm'].validate((valid) => {
        if (valid) {
          if (this.currentPermission.id !== undefined) {
            // load
            this.dialogloading = true;
            permissionResource
              .update(this.currentPermission.id, this.currentPermission)
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.permissionUpdate').replace(':marker:', this.currentPermission.name),
                  duration: 1 * 1000,
                });
                // update list when add permission
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
          } else {
            // load
            this.dialogloading = true;
            permissionResource
              .store(this.currentPermission)
              .then(response => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.permissionAdd').replace(':marker:', this.currentPermission.name),
                  duration: 1 * 1000,
                });
                // update list when add permission
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
      this.formTitle = 'Edit category';
      this.currentRole = this.list.find(role => role.id === id);
      this.enableShowForm(); // show form
    },
    // DELETE
    handlerDeleteItem(id) {
      this.$confirm(this.$t('messages.permissionDelete'), 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
        type: 'warning',
      })
        .then(() => {
          // delete permission
          permissionResource
            .destroy(id)
            .then(response => {
              this.$message({
                type: 'success',
                message: this.$t('messages.permissionsDelete'),
                duration: 1 * 1000,
              });
              // delete on list
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
              // delete element on list
            });
        });
    },
    // UPDATE
    handleEditPermissions(id) {
      this.formTitle = this.$t('dialog.titles.editPermissions');
      const row = this.list.find(permissions => permissions.id === id);
      if (!row.visible) {
        row.visible = 1;
      }
      this.currentPermission = row;
      this.currentPermission.visible = this.options[this.currentPermission.visible].value;
      // show form
      this.enableShowForm();
    },
    // show permission
    handleShowListPermission() {},
    // Route return upper level
    handleReturnUpperLevel() {
      const route = this.routePermission;
      if (this.getCurrentItemArray(route) !== undefined) {
        // delete last index
        this.getLastItemAndDelete(route);
        // console.log('Current: ' + this.getCurrentItemArray(route));
        this.handleSubPermission(this.getCurrentItemArray(route), true);
      } else {
        // set parent lavel
        this.handleSubPermission(route.length, true);
        // this.update();
      }
    },
    // use have permission
    handleHavePermission(permission) {
      return havePermission(permission);
    },
    // ----> TREE
    // Tree functionality
    handleAddItemTree(id, label = '', children = [], disabled = false) {
      return {
        id: id,
        label: label,
        children: children,
        disabled: disabled,
      };
    },
    handleGetListPermission(id = 0) {
      this.query.parent = id;
    },
    // HELPERS
    getListIndex(id) {
      return this.list.findIndex((permission) => permission.id === id);
    },
    deleteItemList(index) {
      this.deleteArrayItem(index, this.list); // delete one element in permission list
    },
    deleteArrayItem(index, arrayItems) {
      arrayItems.splice(index, 1);
    },
    getCurrentItemArray(arr) {
      return arr[arr.length - 1];
    },
    getLastItemAndDelete(arr) {
      return arr.pop();
    },
    update() {
      this.getList();
    },
    // default route
    parentRoute() {
      this.$router.push('/system-management/authority/0');
    },
    setParentRoute(id) {
      this.$router.push('/system-management/authority/' + id);
    },
    // INPUT IN VALUE FORM
    verifySortInt() {
      const sort = this.currentPermission.sort;
      if (sort) {
        this.currentPermission.sort = sort.replace(/[^\d]+/, '');
      }
    },
    verifyValueString(val = 'name') {
      const value = this.currentPermission[val];
      if (value) {
        this.currentPermission[val] = value.replace(/[^a-zA-Z\s]+/, '');
      }
    },
    verifyRouteName() {
      const value = this.currentPermission.route;
      if (value) {
        this.currentPermission.route = value.replace(/[^a-zA-Z0-9\s\-/\#]+/, '');
      }
    },
  },
};
</script>
