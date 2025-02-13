<template>
  <div class="app-container">
    <!-- FILTERS -->
    <div class="filter-container">
      <!-- BOX -->
      <div class="box-line">
        <el-input v-model="searchParam" class="filter-item" prefix-icon="el-icon-search" style="width: 200px" />
        <el-button class="filter-item" type="success">{{ $t('btn.query') }}</el-button>
      </div>
    </div>
    <!-- CRUD TABLE -->
    <CrudComponent
      :fetch-items="fetchItems"
      :on-item-save="onItemSave"
      :on-item-delete="onItemDelete"
      :on-item-create="onItemCreate"
      :not-editable-fields="notEditableFields"
      :enumerable-fields="enumerableFields"
      :item-types="itemTypes"
      :fields-labels="fieldsLabels"
    />
  </div>
</template>

<script>
import CrudComponent from '@/components/CrudComponent';

export default {
  name: 'CurrencyWithdrawalConfiguration',
  components: {
    'CrudComponent': CrudComponent,
  },
  data() {
    return {
      fetchItems: (queryParams) => {
        return new Promise((resolve, reject) => resolve({
          data: [
            { id: 1, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 2, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 3, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 4, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 5, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 6, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 7, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 8, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 9, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
            { id: 10, loginTime: '2021-01-01 22:15:01', userIp: '127.0.0.1', phoneNumber: '21341244', operationType: 'CREATE', detailedRemarks: '', isSuccess: true },
          ],
          meta: { total: 10 },
        }));
      },
      onItemSave: (item) => console.log(item),
      onItemDelete: (item) => console.log(item),
      onItemCreate: (item) => console.log(item),
      notEditableFields: ['id', 'loginTime', 'userIp'],
      enumerableFields: { operationType: ['CREATE', 'READ', 'UPDATE', 'DELETE'] },
      itemTypes: {
        id: Number,
        loginTime: String,
        userIp: String,
        phoneNumber: String,
        operationType: String,
        detailedRemarks: String,
        isSuccess: Boolean,
      },
      fieldsLabels: {
        id: 'User Id',
        loginTime: 'Login time',
        userIp: 'User IP',
        phoneNumber: 'Phone',
        operationType: 'Operation',
        detailedRemarks: 'Detailed remarks',
        isSuccess: 'Whether the operation was success',
      },
    };
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
.btn-label {
  padding: 6px 9px;
}
</style>
