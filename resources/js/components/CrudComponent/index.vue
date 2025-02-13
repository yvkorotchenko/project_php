<template>
  <div>
    <el-table
      :data="items"
    >
      <el-table-column
        v-for="(fieldType, fieldName) in itemTypes"
        :key="fieldName"
        :prop="fieldName"
        :label="getFieldLabel(fieldName)"
        :min-width="250"
      >
        <template slot-scope="scope">
          <template v-if="currentEditableId === scope.row.id && notEditableFields.indexOf(fieldName) < 0">
            <el-select
              v-if="typeof scope.row[fieldName] === 'object' && fieldName in enumerableFields"
              v-model="currentEditable[scope.column.property]"
              value-key="id"
              :placeholder="getFieldLabel(fieldName)"
              :label="scope.row[nestedFieldsDisplay[fieldName]]"
            >
              <el-option
                v-for="one in enumerableFields[fieldName]"
                :key="one.id"
                :value="one"
                :label="one.name"
              />
            </el-select>

            <el-select
              v-else-if="fieldName in enumerableFields"
              v-model="currentEditable[scope.column.property]"
              :placeholder="getFieldLabel(fieldName)"
            >
              <el-option
                v-for="one in enumerableFields[fieldName]"
                :key="one"
                :value="one"
              />
            </el-select>

            <el-input
              v-else-if="fieldType === String"
              v-model="currentEditable[scope.column.property]"
            />
            <el-input-number
              v-else-if="fieldType === Number"
              v-model="currentEditable[scope.column.property]"
            />
            <el-checkbox
              v-else-if="fieldType === Boolean"
              v-model="currentEditable[scope.column.property]"
            >
              {{ scope.row[scope.column.property] ? $t('btn.on') : $t('btn.off') }}
            </el-checkbox>
            <div
              v-if="fieldType === 'Image'"
            >
              <label>
                <input
                  :id="scope.column.property"
                  type="file"
                  @change="(e) => onFileSave(e, scope.row, scope.column.property)"
                >
              </label>
            </div>
          </template>
          <template v-else>
            <span
              v-if="fieldType === String || fieldType === Number"
            >
              {{ scope.row[scope.column.property] }}
            </span>
            <el-tag
              v-else-if="fieldType === Boolean"
              :type="scope.row[scope.column.property] ? 'primary' : 'danger'"
            >
              {{ scope.row[scope.column.property] ? $t('btn.on') : $t('btn.off') }}
            </el-tag>
            <img
              v-else-if="fieldType === 'Image'"
              :src="scope.row[scope.column.property]"
              class="imageField"
            >
            <span
              v-else-if="fieldType === 'Nested'"
            >
              {{ scope.row[scope.column.property][nestedFieldsDisplay[scope.column.property]] }}
            </span>
          </template>
        </template>
      </el-table-column>
      <PermissionChecker :permissions="listsPermissions">
        <el-table-column
          fixed="right"
          :label="this.$t('table.operation')"
          :min-width="200"
        >
          <template slot-scope="scope">
            <template v-if="currentEditableId === scope.row.id">
              <el-button size="small" @click="resetEditableRow">{{ $t('btn.cancel') }}</el-button>
              <el-button size="small" plain type="success" @click="onSaveEditable">{{ $t('btn.save') }}</el-button>
            </template>
            <template v-else>
              <PermissionChecker :permissions="btnEditPermission">
                <el-button size="small" @click="makeRowEditable(scope.row)">{{ $t('btn.edit') }}</el-button>
              </PermissionChecker>
              <PermissionChecker :permissions="btnSlotPermission">
                <slot name="additional-buttons" :item="scope.row" />
              </PermissionChecker>
              <PermissionChecker :permissions="btnDeletePermission">
                <el-button v-if="null !== onItemDelete" plain type="danger" size="small" @click="onDeleteRow(scope.row)">{{ $t('btn.delete') }}</el-button>
              </PermissionChecker>
            </template>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <div
      v-if="!activeItemCreation"
      style="margin-top: 20px"
      class="add-row"
    >
      <el-button type="success" plain icon="el-icon-plus" @click="onCreate" />
    </div>
    <el-pagination
      :current-page.sync="page"
      :page-sizes="[2, 10, 25, 50, 100, 200, 300]"
      :page-size="perPage"
      layout="sizes, prev, pager, next"
      :total="totalItems"
      @size-change="onPaginationChangeSize"
      @current-change="onPaginationChangePage"
    />
  </div>
</template>

<script>
import Vue from 'vue';
import PermissionChecker from '@/components/Permissions/index.vue';

export default {
  name: 'CrudComponent',
  components: {
    PermissionChecker,
  },
  props: {
    listsPermissions: {
      type: Array,
      default: () => [],
    },
    btnEditPermission: {
      type: Array,
      default: () => [],
    },
    btnSlotPermission: {
      type: Array,
      default: () => [],
    },
    btnDeletePermission: {
      type: Array,
      default: () => [],
    },
    fetchItems: {
      type: Function,
      required: true,
    },
    onItemSave: {
      type: Function,
      default: () => x => x,
    },
    onItemDelete: {
      type: Function,
      default: null,
    },
    onItemCreate: {
      type: Function,
      default: () => x => x,
    },
    notEditableFields: {
      type: Array,
      default: () => [],
    },
    enumerableFields: {
      type: Object,
      default: () => ({}),
    },
    itemTypes: {
      type: Object,
      default: () => {},
    },
    fieldsLabels: {
      type: Object,
      default: () => ({}),
    },
    notRequiredFields: {
      type: Array,
      default: () => [],
    },
    onItemFileUploads: {
      type: Object,
      default: () => ({}),
    },
    nestedFieldsDisplay: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      items: [],
      currentEditableId: null,
      currentEditable: {},
      page: 1,
      perPage: 10,
      totalItems: 0,
      activeItemCreation: false,
      files: [],
    };
  },
  computed: {
    operations: () => ({ create: true, read: true, update: true, delete: true }),
    paginator: () => ({
      page: 1,
      pagesCount: 10,
    }),
  },
  created() {
    this.getItems();
  },
  methods: {
    getItems() {
      this.fetchItems({ page: this.page, limit: this.perPage })
        .then(res => {
          this.totalItems = res.meta.total;
          this.items = res.data;
        })
        .catch(err => console.log(err));
    },
    getFieldLabel(name) {
      return this.fieldsLabels[name];
    },
    makeRowEditable(row) {
      this.currentEditableId = row.id;
      this.currentEditable = { ...row };
    },
    resetEditableRow() {
      if (this.activeItemCreation) {
        this.items = this.items.filter(el => el.id !== 0);
        this.activeItemCreation = false;
      }
      this.currentEditableId = null;
      this.currentEditable = null;
    },
    onDeleteRow(row) {
      this.onItemDelete(row).then(() => {
        setTimeout(() => this.getItems(), 300);
      });
    },
    onSaveEditable() {
      const valid = this.validateItemBeforeSave();

      if (!valid) {
        return;
      }

      if (!this.activeItemCreation) {
        this.onItemSave(this.currentEditable).then(() => {
          this.getItems();
          this.resetEditableRow();
        });
      } else {
        this.onItemCreate(this.currentEditable).then(newItem => {
          if (this.files.length > 0) {
            const reductChain = (files) => {
              return files.reduce((chain, curr) => {
                return chain.then(() => this.onItemFileUploads[curr.fieldName](newItem, curr.file, curr.fieldName));
              }, Promise.resolve());
            };
            reductChain(this.files).then(() => {
              this.getItems();
              this.activeItemCreation = false;
              this.resetEditableRow();
            });
          } else {
            this.getItems();
            this.activeItemCreation = false;
            this.resetEditableRow();
          }
        });
      }
    },
    validateItemBeforeSave() {
      let valid = true;
      for (const [key, val] of Object.entries(this.currentEditable)) {
        const ndx = this.notRequiredFields.findIndex(el => el === key);
        console.log(ndx);
        if (ndx > -1) {
          continue;
        }
        if (this.itemTypes[key] === String && val === '') {
          valid = false;
        }
      }

      return valid;
    },
    onCreate() {
      const newItem = {};
      for (const [key, typo] of Object.entries(this.itemTypes)) {
        switch (typo) {
          case String: newItem[key] = ''; break;
          case Number: newItem[key] = 0; break;
          case Boolean: newItem[key] = false; break;
          case 'Nested': newItem[key] = this.enumerableFields[key][0]; break;
        }
      }
      Vue.set(this.items, this.items.length, newItem);
      this.currentEditableId = 0;
      this.currentEditable = newItem;
      this.activeItemCreation = true;
    },
    onPaginationChangeSize(pageSize) {
      this.perPage = pageSize;
      this.getItems();
    },
    onPaginationChangePage(pageNumber) {
      this.page = pageNumber;
      this.getItems();
    },
    onFileSave(e, item, fieldName) {
      if (this.activeItemCreation){
        this.files = this.files.concat([{
          fieldName: fieldName,
          item: item,
          file: e.target.files[0],
        }]);
        /*
        Vue.set(
          this.files,
          this.file.length,
          {
            fieldName: fieldName,
            item: item,
            file: e.target.files[0],
          }
        );
        */
      } else {
        this.onItemFileUploads[fieldName](item, e.target.files[0], fieldName).then(newFileUrl => {
          this.currentEditable[fieldName] = newFileUrl;
        });
      }
    },
  },
};
</script>

<style scoped>
  .add-row {text-align: center;}
  .imageField { height: 50px; }
</style>
