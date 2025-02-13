<template>
  <div>
    <div class="app-container">
      <h1>{{ $t('route.importantNotice') }}</h1>
      <div class="line-separate" />
      <PermissionChecker :permissions="[viewPermission.create]">
        <div class="filter-item">
          <el-button
            type="success"
            plain
            size="small"
            @click="() => openEditDialog(getEmptyItem())"
          >{{ $t('btn.btnNew') }}</el-button>
          <el-button
            v-if="bulkItems.length > 0"
            type="danger"
            plain
            size="small"
            @click="bulkDelete"
          >{{ $t('btn.delete') }}</el-button>
        </div>
        <div class="line-separate" />
      </PermissionChecker>
      <div class="wrapper-table">
        <el-table :data="list" fit highlight-current-row border @selection-change="handleBulkSelection">
          <el-table-column type="selection" width="50px" />
          <el-table-column
            v-for="(itemLabel, itemName) in itemsLabels"
            :key="itemLabel"
            :prop="itemName"
            :label="itemLabel"
            min-width="200px"
          >
            <template slot-scope="scope">
              <span v-if="typeof scope.row[itemName] === 'boolean'">
                <PermissionChecker :permissions="[viewPermission.status]">
                  <el-switch
                    v-model="scope.row[itemName]"
                    style="display: block"
                    active-color="#13ce66"
                    inactive-color="#ff4949"
                    :active-text="$t('btn.on')"
                    :inactive-text="$t('btn.off')"
                    @change="() => changeNoticeState(scope.row)"
                  />
                </PermissionChecker>
              </span>
              <span v-else>{{ scope.row[itemName] }}</span>
            </template>
          </el-table-column>
          <PermissionChecker :permissions="[viewPermission.updated, viewPermission.delete]">
            <el-table-column fixed="right" :label="$t('labels.operations')" min-width="200px">
              <template slot-scope="scope">
                <PermissionChecker :permissions="[viewPermission.updated]">
                  <el-button type="default" size="small" @click="() => openEditDialog(scope.row)">{{ $t('btn.update') }}</el-button>
                </PermissionChecker>
                <PermissionChecker :permissions="[viewPermission.delete]">
                  <el-button type="danger" plain size="small" @click="() => deleteItem(scope.row)">{{ $t('btn.delete') }}</el-button>
                </PermissionChecker>
              </template>
            </el-table-column>
          </PermissionChecker>
        </el-table>
      </div>
    </div>
    <Pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.size"
      :page-sizes="[10, 15, 25, 45, 65, 85, 130, 160, 250, 350]"
      @pagination="getNoticeList"
    />
    <el-dialog
      :title="title"
      :visible.sync="formVisible"
      closed="clearForm"
    >
      <el-form
        v-if="current !== null"
        ref="notice"
        :model="current"
        label-position="right"
        class="dialog-form"
      >
        <el-form-item :label="itemsLabels.titleEn" prop="titleEn">
          <el-input v-model="current.titleEn" :disabled="true" />
        </el-form-item>
        <el-form-item :label="itemsLabels.contentEn" prop="contentEn">
          <el-input v-model="current.contentEn" type="textarea" rows="4" resize="none" />
        </el-form-item>
        <el-form-item :label="itemsLabels.titleLocal" prop="titleLocal">
          <el-input v-model="current.titleLocal" :disabled="true" />
        </el-form-item>
        <el-form-item :label="itemsLabels.contentLocal" prop="contentLocal">
          <el-input v-model="current.contentLocal" type="textarea" rows="4" resize="none" />
        </el-form-item>
        <el-form-item :label="itemsLabels.state" class="dialog-form-label">
          <div class="form-checkbox-wrp">
            <el-switch
              v-model="current.state"
              style="display: block"
              active-color="#13ce66"
              inactive-color="#ff4949"
              :active-text="$t('btn.on')"
              :inactive-text="$t('btn.off')"
            />
          </div>
        </el-form-item>
        <el-form-item class="text-center">
          <el-button
            v-if="current.id === null"
            plain
            type="success"
            @click="createItem(current)"
          >{{ $t('btn.confirm') }}</el-button>
          <el-button
            v-else
            plain
            type="success"
            @click="updateItem(current)"
          >{{ $t('btn.confirm') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import ImportantNoticeResource from '@/api/ImportantNotice';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const noticeResource = new ImportantNoticeResource();

export default {
  name: 'ImportantNotice',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    const itemsLabels = {
      id: this.$t('labels.id'),
      contentEn: this.$t('table.content') + ' (' + this.$t('lang.en') + ')',
      contentLocal: this.$t('table.content') + ' (' + this.$t('lang.sc') + ')',
      created: this.$t('table.createTime'),
      updated: this.$t('table.updateTime'),
      state: this.$t('table.status'),
      titleEn: this.$t('table.title') + ' (' + this.$t('lang.en') + ')',
      titleLocal: this.$t('table.title') + ' (' + this.$t('lang.sc') + ')',
    };

    return {
      viewPermission: viewsPermissions.importantNotice.permissions.controls,
      loading: false,
      formVisible: false,
      title: '',
      list: [],
      current: null,
      query: {
        size: 10,
        page: 1,
      },
      total: 0,
      itemsLabels: itemsLabels,
      bulkItems: [],
      emptyItem: {
        id: null,
        titleEn: this.language === 'en' ? 'Important Notice' : '重要通知',
        contentEn: '',
        titleLocal: '重要通知',
        contentLocal: '',
        state: true,
      },
      rules: {
        titleEn: [{ required: true, message: this.$t('warningRequiredField', { fieldName: itemsLabels['titleEn'] }) }],
        titleLocal: [{ required: true, message: this.$t('warningRequiredField', { fieldName: itemsLabels['titleLocal'] }) }],
        contentEn: [{ required: true, message: this.$t('warningRequiredField', { fieldName: itemsLabels['contentEn'] }) }],
        contentLocal: [{ required: true, message: this.$t('warningRequiredField', { fieldName: itemsLabels['contentLocal'] }) }],
        state: [{ required: true, message: this.$t('warningRequiredField', { fieldName: itemsLabels['active'] }) }],
      },
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  created() {
    this.getNoticeList();
  },
  methods: {
    getNoticeList() {
      this.loading = true;
      noticeResource.list(this.query)
        .then((response) => {
          const { data, meta } = response;
          this.list = data;
          this.total = meta.total;
          this.loading = false;
        });
    },
    changeNoticeState(item) {
      this.updateItem(item);
    },
    updateItem(item) {
      noticeResource.update(item.id, item).then(() => {
        this.closeEditDialog();
        this.getNoticeList();
      });
    },
    createItem(item) {
      noticeResource.store(item).then(() => {
        this.closeEditDialog();
        this.getNoticeList();
      });
    },
    deleteItem(item) {
      if (!item.id) {
        return;
      }
      noticeResource.destroy([item.id]).then(() => this.getNoticeList());
    },
    handleBulkSelection(selectedVals) {
      this.bulkItems = selectedVals;
    },
    openEditDialog(item) {
      this.current = item;
      this.formVisible = true;
    },
    closeEditDialog() {
      this.current = null;
      this.formVisible = false;
    },
    clearForm() {
      this.current = null;
    },
    getEmptyItem() {
      return { ...this.emptyItem };
    },
    bulkDelete() {
      noticeResource
        .destroy(this.bulkItems.map((one) => one.id))
        .then(() => this.getNoticeList());
    },
  },
};
</script>

<style lang="scss" scoped>

.filter-item {
  margin-top: 7px;
}

.line-separate {
  margin: 20px 0;
  border-top: 1px solid #f6f6f6;
}

.text-center {text-align:center;}
form.dialog-form .el-form-item:not(.text-center) {margin-left: 117px;}
form.dialog-form .form-checkbox-wrp {float: left; width: 100%;}
</style>
