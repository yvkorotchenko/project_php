<template>
  <div>
    <!-- DATA PICKER -->
    <div class="filter-container header-box">
      <span class="right-padding">{{ $t('btn.noTranslate') }}</span><el-button type="primary">{{ $t('btn.noTranslate') }}</el-button>
    </div>
    <!-- -->
    <el-form label-position="left" label-width="100px" style="width: 50%">
      <!-- -->
      <el-form-item>
        <el-input style="width: 200px;" class="box-right" :placeholder="$t('placeholders.codeKey')" />
        <el-input style="width: 200px;" class="box-right" :placeholder="$t('placeholders.codingMultiple')" />
        <el-button type="danger">X</el-button>
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
  name: 'GameConfiguration',
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
