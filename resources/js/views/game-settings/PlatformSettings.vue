<template>
  <div className="app-container">
    <h1>{{ $t('route.platformSettings') }}</h1>
    <CrudComponent
      :fetch-items="fetchItems"
      :on-item-save="onItemSave"
      :on-item-delete="onItemDelete"
      :on-item-create="onItemCreate"
      :not-editable-fields="notEditableFields"
      :enumerable-fields="enumerableFields"
      :item-types="itemTypes"
      :fields-labels="{
        id: $t('labels.platformNumber'),
        name: $t('labels.platformName'),
        parentName: $t('labels.parentName'),
        visible: $t('labels.whetherToDisplay'),
      }"
      :required-fields="requiredFields"
      :lists-permissions="[viewPermission.edit, viewPermission.categories,viewPermission.delete]"
      :btn-edit-permission="['platform settings edit']"
      :btn-slot-permission="['platform settings categories']"
      :btn-delete-permission="['platform settings delete']"
    >
      <template
        slot="additional-buttons"
        slot-scope="{ item }"
      >
        <el-button @click="() => onCategoriesOpen(item.id)">{{ $t('btn.categories') }}</el-button>
      </template>
    </CrudComponent>
    <el-dialog
      title="Platform Game-categories management"
      :visible.sync="modalVisibile"
      @close="onModalClose"
    >
      <div class="modal-wrp">
        <el-col
          v-for="(one, key) in activeItemCategories"
          :key="one.id"
          :span="24"
          class="modal-row-wrp"
        >
          <el-col :span="10">
            <el-select
              v-model="activeItemCategories[key]"
              value-key="id"
              :label="one.name"
            >
              <el-option
                v-for="item in availableCategories"
                :key="item.id"
                :value="item"
                :label="item.name"
              >
                {{ item.name }}
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="10">
            <el-input-number v-model="activeItemCategorySequences[one.id]" :max="999" />
          </el-col>
          <el-col :span="4">
            <PermissionChecker :permissions="[viewPermission.delete]">
              <el-button type="danger" plain @click="() => onCategoryDelete(one.id)">{{ $t('btn.delete') }}</el-button>
            </PermissionChecker>
          </el-col>
        </el-col>
        <el-col
          v-if="activeAddedCategory !== null"
          :span="24"
          class="modal-row-wrp"
        >
          <el-col :span="10">
            <el-select
              v-model="activeAddedCategory"
              value-key="id"
              :label="activeAddedCategory.name"
            >
              <el-option
                v-for="item in activeAddedAvailableCategories"
                :key="item.id"
                :value="item"
                :label="item.name"
              >
                {{ item.name }}
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="10">
            <el-input-number v-model="activeAddedCategorySequence" :max="999" />
          </el-col>
          <el-col :span="2">
            <PermissionChecker :permissions="[viewPermission.save]">
              <el-button plain @click="onSaveNewCategory">{{ $t('btn.add') }}</el-button>
            </PermissionChecker>
          </el-col>
          <el-col :span="2">
            <el-button plain @click="onAddCategoryCancel">{{ $t('btn.cancel') }}</el-button>
          </el-col>
        </el-col>
        <el-col v-if="activeAddedCategory === null" :span="24" class="modal-add-category">
          <PermissionChecker :permissions="[viewPermission.create]">
            <el-button type="success" icon="el-icon-plus" plain @click="onAddNewCategory" />
          </PermissionChecker>
        </el-col>
        <el-col :span="24" class="modal-btn-wrp">
          <PermissionChecker :permissions="[viewPermission.save]">
            <el-button type="success" plain @click="onCategoriesSave">{{ $t('btn.save') }}</el-button>
          </PermissionChecker>
        </el-col>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Vue from 'vue';
import CrudComponent from '@/components/CrudComponent';
import PlatformResource from '@/api/platform';
import GameCategoryResource from '@/api/gameCategory';
import PermissionChecker from '@/components/Permissions/index.vue';
import viewsPermissions from '@/viewsPermissions.js';

const resource = new PlatformResource();
const gameCategoriesResource = new GameCategoryResource();

export default {
  name: 'PlatformSettings',
  components: {
    'CrudComponent': CrudComponent,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.rewardsManagement.permissions.controls,
      fetchItems: (queryParams) => resource.list(queryParams),
      onItemSave: (item) => resource.update(item.id, item),
      onItemDelete: (item) => resource.destroy(item.id),
      onItemCreate: (item) => resource.store(item),
      notEditableFields: ['id'],
      enumerableFields: { parentName: ['SG', 'DT', 'PG', 'PrettyGaming', 'SA', 'QT'] },
      itemTypes: {
        id: Number,
        name: String,
        parentName: String,
        visible: Boolean,
      },
      requiredFields: ['name', 'parentName', 'visible'],
      modalVisibile: false,
      activeItemId: 0,
      activeItemCategories: [],
      activeItemCategorySequences: {},
      availableCategories: [],
      activeAddedCategory: null,
      activeAddedCategorySequence: 999,
      activeAddedAvailableCategories: [],
    };
  },
  computed: {
    language: function() {
      return this.$store.getters.language;
    },
  },
  methods: {
    categoryName(id) {
      const ndx = this.activeItemCategories.findIndex(el => el.id === id);
      if (ndx >= 0) {
        return this.activeItemCategories[ndx]['name'];
      }

      return '';
    },
    resetActiveAddedCategory() {
      this.activeAddedCategory = null;
      this.activeAddedCategorySequence = 999;
      this.activeAddedAvailableCategories = [];
    },
    onCategoriesOpen(id) {
      gameCategoriesResource.list().then((res) => {
        this.availableCategories = res.data;
      });

      resource.gameCategories(id)
        .then((platformCategoryResult) => {
          const categorySequences = {};
          const activeItemCategories = [];
          platformCategoryResult.forEach(el => {
            categorySequences[el.id] = el.sequence;
            activeItemCategories.push({ id: el.id, name: el.name });
          });
          this.activeItemCategories = activeItemCategories;
          this.activeItemCategorySequences = categorySequences;
          this.activeItemId = id;
        })
        .finally(() => {
          this.modalVisibile = true;
        });
    },
    onCategoriesSave() {
      const categores = [];
      this.activeItemCategories.forEach(el => categores.push({
        id: el.id,
        sequence: this.activeItemCategorySequences[el.id],
      }));

      resource.updateGameCateogries(this.activeItemId, categores)
        .then(() => {
          this.onModalClose();
        });
    },
    onCategoryDelete(id) {
      const ndx = this.activeItemCategories.findIndex(el => el.id === id);
      if (ndx !== -1) {
        this.activeItemCategories.splice(ndx, 1);
      }
      Vue.delete(this.activeItemCategorySequences, id);
    },
    onAddNewCategory() {
      this.activeAddedCategory = {
        id: 0,
        name: '',
      };
      this.activeAddedAvailableCategories = this.availableCategories.filter(el => !(el.id in this.activeItemCategorySequences));
    },
    onModalClose() {
      this.resetActiveAddedCategory();
      this.modalVisibile = false;
    },
    onAddCategoryCancel() {
      this.resetActiveAddedCategory();
    },
    onSaveNewCategory() {
      Vue.set(
        this.activeItemCategories,
        this.activeItemCategories.length,
        this.activeAddedCategory
      );

      Vue.set(
        this.activeItemCategorySequences,
        this.activeAddedCategory.id,
        this.activeAddedCategorySequence
      );
      this.resetActiveAddedCategory();
    },
  },
};
</script>

<style lang="scss" scoped>
  .bg-white {
    padding: 29px 20px 20px;
    background-color: #fff;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  }
  .modal-wrp {
    display: inline-block;
    width: 100%;
  }
  .modal-btn-wrp {
    margin-top: 40px;
  }
  .modal-btn-wrp > * {
    float: right;
  }
  .modal-row-wrp {
    margin-top: 20px;
  }
  .modal-add-category {
    text-align: center;
    margin-top: 20px;
  }
</style>
