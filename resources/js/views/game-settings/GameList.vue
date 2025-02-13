<template>
  <div className="app-container">
    <h1>{{ $t('table.categorySettingsGameList') }}</h1>
    <CrudComponent
      :fetch-items="fetchItems"
      :on-item-save="onItemSave"
      :on-item-create="onItemCreate"
      :on-item-delete="onItemDelete"
      :item-types="itemTypes"
      :fields-labels="fieldsLabels"
      :nested-fields-display="nestedFieldsDisplay"
      :not-editable-fields="nonEditableFields"
      :enumerable-fields="enumerableFields"
      :on-item-file-uploads="onFileUploads"
      :lists-permissions="[viewPermission.edit, viewPermission.delete]"
      :btn-edit-permission="['game list edit']"
      :btn-delete-permission="['game list delete']"
    /></div>
</template>

<script>
import CrudComponent from '@/components/CrudComponent';
import GameResource from '@/api/game';
import PlatformResource from '@/api/platform';
import GameCategoryResource from '@/api/gameCategory';
import viewsPermissions from '@/viewsPermissions.js';

const resource = new GameResource();
const platformResource = new PlatformResource();
const gameCategoryResource = new GameCategoryResource();

const onItemFileUpload = (item, file, fieldName) => {
  return resource.uploadFile(item.id, fieldName, file);
};

export default {
  name: 'GameList',
  components: {
    'CrudComponent': CrudComponent,
  },
  data() {
    return {
      viewPermission: viewsPermissions.gameList.permissions.controls,
      fetchItems: (queryParams) => resource.list(queryParams),
      onItemSave: (item) => resource.update(item.id, item),
      onItemCreate: (item) => resource.store(item),
      onItemDelete: null,
      nonEditableFields: ['id'],
      enumerableFields: {
        orientation: ['VERTICAL', 'HORIZONTAL'],
        category: [],
        platform: [],
      },
      nestedFieldsDisplay: { category: 'name', platform: 'name' },
      onFileUploads: {
        icon: onItemFileUpload,
        coverPic: onItemFileUpload,
      },
      itemTypes: {
        id: Number,
        gameCode: String,
        name: String,
        nameLocal: String,
        icon: 'Image',
        coverPic: 'Image',
        sequence: Number,
        visible: Boolean,
        canTry: Boolean,
        category: 'Nested',
        platform: 'Nested',
      },
      fieldsLabels: {
        id: this.$t('table.categorySettingsId'),
        gameCode: this.$t('table.categorySettingsGameCode'),
        name: this.$t('table.categorySettingsName'),
        nameLocal: this.$t('table.categorySettingsNameLocal'),
        icon: this.$t('table.categorySettingsIcon'),
        coverPic: this.$t('table.categorySettingsCoverPic'),
        sequence: this.$t('table.categorySettingsSequence'),
        visible: this.$t('table.categorySettingsVisible'),
        canTry: this.$t('table.categorySettingsCanTry'),
        category: this.$t('table.categorySettingsCategory'),
        platform: this.$t('table.categorySettingsPlatform'),
      },
    };
  },
  beforeCreate() {
    platformResource.list().then(res => {
      this.enumerableFields.platform = res.data;
    });
    gameCategoryResource.list().then(res => {
      this.enumerableFields.category = res.data;
    });
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
