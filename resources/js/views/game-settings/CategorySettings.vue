<template>
  <div className="app-container">
    <h1>{{ $t('labels.cattegorySettings') }}</h1>
    <CrudComponent
      :fetch-items="fetchItems"
      :on-item-save="onItemSave"
      :on-item-create="onItemCreate"
      :on-item-delete="onItemDelete"
      :item-types="itemTypes"
      :fields-labels="fieldsLabels"
      :not-editable-fields="nonEditableFields"
      :not-required-fields="nonRequiredFields"
      :lists-permissions="[viewPermission.edit, viewPermission.delete]"
      :btn-edit-permissions="['category settings edit']"
      :btn-delete-permissions="['category settings delete']"
    />
  </div>
</template>

<script>
import GameCategoryResource from '@/api/gameCategory';
import CrudComponent from '@/components/CrudComponent';
import viewsPermissions from '@/viewsPermissions.js';

const resource = new GameCategoryResource();

export default {
  name: 'CategorySettings',
  components: {
    'CrudComponent': CrudComponent,
  },
  data() {
    return {
      viewPermission: viewsPermissions.categorySettings.permissions.controls,
      fetchItems: (queryParams) => resource.list(queryParams),
      onItemSave: (item) => resource.update(item.id, item),
      onItemCreate: (item) => {
        if (item.identity === '') {
          delete item.identity;
        }
        return resource.store(item);
      },
      onItemDelete: (item) => resource.destroy(item.id),
      nonEditableFields: ['id'],
      nonRequiredFields: ['identity'],
      itemTypes: {
        id: Number,
        identity: String,
        name: String,
        nameLocal: String,
        sequence: Number,
        visible: Boolean,
      },
      fieldsLabels: {
        id: this.$t('labels.categorySettingsId'),
        identity: this.$t('labels.categorySettingsIdentity'),
        name: this.$t('labels.categorySettingsName'),
        nameLocal: this.$t('labels.categorySettingsNameLocal'),
        sequence: this.$t('labels.categorySettingsSequence'),
        visible: this.$t('labels.categorySettingsVisible'),
      },
    };
  },
};
</script>

<style lang="scss" scoped>
  .bg-white {
    padding: 29px 20px 20px;
    background-color: #fff;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  }
</style>
