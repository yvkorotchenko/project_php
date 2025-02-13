<template>
  <div className="app-container">
    <el-row>
      <el-col :span="24"><div class="grid-content bg-purple-dark bg-white filter-container">
        <!-- SELECT -->
        <el-select class="filter-item" :placeholder="$t('placeholders.selectedItem')" clearable style="width: 250px;">
          <el-option />
        </el-select>
        <!-- INPUT -->
        <el-input placeholder="Please input" class="filter-item" style="width: 250px;" />
        <!-- BUTTON -->
        <el-button type="primary" class="filter-item">{{ $t('btn.query') }}</el-button>
      </div></el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'RebateConfiguration',
};
</script>

<style lang="scss" scoped>
  .bg-white {
    padding: 29px 20px 20px;
    background-color: #fff;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  }
</style>
