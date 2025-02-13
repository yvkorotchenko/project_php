<template>
  <div class="app-container">
    <!-- FILTERS -->
    <div class="filter-container">
      <!-- BOX -->
      <PermissionChecker :permissions="['customer service operator add']">
        <div class="box-line">
          <!-- Add -->
          <el-button class="filter-item" type="primary" plain @click="onAccountForm">{{ $t('btn.add') }}</el-button>
        </div>
      </PermissionChecker>
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
    <!-- DIALOG -->
    <el-dialog :title="$t('dialog.accountSuspension')" :visible.sync="accountVisibleForm" width="57%">
      <!-- PRE LOADER -->
      <div class="form-container">
        <!-- FORM -->
        <el-form label-position="left" label-width="130px">
          <!-- OPERATOR NAME -->
          <el-form-item :label="$t('table.operatorName')">
            <el-input v-model="newOperatorName" :placeholder="$t('placeholders.operatorName')" />
          </el-form-item>
          <!-- SELECT COMPANY NAME -->
          <el-form-item :label="$t('table.operatorCompanyName')">
            <el-select v-model="curentCompanyID" :placeholder="$t('placeholders.operatorCompanyName')">
              <el-option v-for="company in companiesName" :key="company.id" :label="company.name" :value="company.id" />
            </el-select>
          </el-form-item>
          <!-- OPERATOR EMAIL -->
          <el-form-item :label="$t('table.operatorEmail')">
            <el-input v-model="newOperatorEmail" value="" :placeholder="$t('placeholders.operatorEmail')" />
          </el-form-item>
          <!-- OPERATOR PASSWORD -->
          <el-form-item :label="$t('table.operatorPassword')">
            <el-input v-model="newOperatorPassword" value="" :placeholder="$t('placeholders.operatorPassword')" />
          </el-form-item>
        </el-form>
        <!-- FOOTER -->
        <div slot="footer" class="dialog-footer" align="left" style="margin-left: 130px;">
          <el-button type="success" icon="el-icon-plus" @click="createNewOperator">
            {{ $t('btn.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import CrudComponent from '@/components/CrudComponent';
import OperatorResource from '@/api/operators';
import CustomerServicesResource from '@/api/customerServices';
import PermissionChecker from '@/components/Permissions/index.vue';

const operatorResource = new OperatorResource();
const customerServicesResource = new CustomerServicesResource();
export default {
  name: 'CustomerServiceOperator',
  components: {
    'CrudComponent': CrudComponent,
    PermissionChecker,
  },
  data() {
    return {
      fetchItems: (queryParams) => operatorResource.list(queryParams).then((res) => {
        const allComponies = this.companiesName;
        res.data.map(function(value) {
          if (allComponies){
            allComponies.map(function(val) {
              if (value.customer_service_id === val.id) {
                value.customer_service_id = val.name;
              }
            });
          }
          return value;
        });
        return res;
      }),
      onItemSave: (item) => {
        this.companiesName.forEach(val => {
          if (val.name === item.customer_service_id) {
            item.customer_service_id = val.id;
          }
        });
        return operatorResource.update(item.id, item).then((res) => {
          this.$message({
            type: 'success',
            message: 'Operator Updated',
            duration: 1000,
          });
        });
      },
      onItemDelete: (item) => {
        return operatorResource.destroy(item.id).then((res) => {
          this.$message({
            type: 'success',
            message: 'Operator Deleted',
            duration: 1000,
          });
        });
      },
      onItemCreate: (item) => console.log(item),
      notEditableFields: ['id', 'customer_service_id', 'created_at', 'updated_at'],
      enumerableFields: { operationType: ['CREATE', 'READ', 'UPDATE', 'DELETE'] },
      itemTypes: {
        id: Number,
        name: String,
        customer_service_id: Number,
        created_at: String,
        updated_at: String,
        email: String,
      },
      fieldsLabels: {
        id: this.$t('table.id'),
        name: this.$t('table.name'),
        customer_service_id: this.$t('table.customerServicesName'),
        phoneNumber: this.$t('table.phoneNumber'),
        created_at: this.$t('table.createTime'),
        updated_at: this.$t('table.updateTime'),
        email: this.$t('table.email'),
      },
      companiesName: [],
      curentCompanyID: '',
      newOperatorName: '',
      newOperatorEmail: '',
      newOperatorPassword: '',
      // visibleForm
      accountVisibleForm: false,
    };
  },
  beforeCreate() {
    customerServicesResource.list().then((res) => {
      this.companiesName = res;
    });
  },
  methods: {
    createNewOperator() {
      const resource = {
        'name': this.newOperatorName,
        'customer_service_id': this.curentCompanyID,
        'email': this.newOperatorEmail,
        'password': this.newOperatorPassword,
        'nick': this.newOperatorEmail,
        'avatar': '216742.jpg',
      };
      operatorResource.store(resource).then((res) => {
        this.$message({
          type: 'success',
          message: 'Operator added',
          duration: 1000,
        });
        this.newOperatorName = '';
        this.newOperatorEmail = '';
        this.newOperatorPassword = '';
        this.newOperatorId = '';
        this.curentCompanyID = '';
        this.fetchItems;
      })
        // .catch(error => {
        // this.$message({
        //   type: 'error',
        //   message: error,
        //   duration: 1 * 1000,
        // });
        // })
        .finally(() => {
          this.accountVisibleForm = false;
        });
    },
    getAccountVisibleForm() {
      return this.accountVisibleForm;
    },
    setAccountVisibleForm(val) {
      this.accountVisibleForm = val;
    },
    onAccountForm() {
      this.setAccountVisibleForm(true);
    },
    offAccountForm() {
      this.setAccountVisibleForm(false);
    },
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
.add-row {
    display: none;
}
</style>
