<template>
  <div>
    <!-- -->
    <div class="header-box" />
    <el-form label-position="left" label-width="160px" style="width: 50%">
      <!-- -->
      <el-form-item>
        <el-input :placeholder="$t('placeholders.pleaseEnterTheOfficialWebsite')" />
      </el-form-item>
      <!-- -->
      <el-form-item :label="$t('labels.customerServiceAddress')">
        <el-input :placeholder="$t('placeholders.pleaseEnterTheCustomerServiceAddress')" />
      </el-form-item>
      <!-- -->
      <el-form-item>
        <el-button type="success">{{ $t('btn.confirmModification') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  name: 'CustomerServiceSetting',
  data() {
    return {
      delivery: false,
      formVisible: false,
    };
  },
  methods: {
    getDelivery() {
      return this.delivery;
    },
    setDelivery(val) {
      this.delivery = val;
    },
    onDelivery() {
      this.setDelivery(true);
    },
    offDelivery() {
      this.setDelivery(false);
    },
    getFormVisible() {
      return this.formVisible;
    },
    setFormVisible(val) {
      this.formVisible = val;
    },
    onForm() {
      this.setFormVisible(true);
    },
    offForm() {
      this.setFormVisible(false);
    },
    handlerSwitchForm() {
      this.onForm();
    },
    noSwitch() {
      // hide form
      this.offForm();
      // disable switch
      this.offDelivery();
    },
  },
};
</script>

<style lang="scss">
.wrapper-label {
  padding-right: 20px;
}
.right-padding {
  padding-right: 20px;
}
.margin-bottom {
  margin-bottom: 15px;
}
.header-box {
  border-bottom: 1px solid #f6f6f6;
  padding-bottom: 20px;
  margin-bottom: 23px;
}
.box-right {
  padding-right: 10px;
}
</style>
