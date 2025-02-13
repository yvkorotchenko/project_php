<template>
  <div class="app-container">
    <div class="filter-item">
      <span class="demo-input-label">{{ $t('labels.gameConfiguration') }}</span>
      <el-button plain type="success" size="small">{{ $t('labels.addEventCode') }}</el-button>
    </div>
    <el-table
      :data="tableData"
      style="width: 100%"
    >
      <el-table-column
        width="180"
      >
        {{ $t('labels.activityCodingMultiple') }}
      </el-table-column>
      <el-table-column
        prop="name"
        width="180"
      />
      <el-table-column
        prop="address"
      />
      <el-table-column>
        <a onclick="closeRow(this)" class="layui-btn layui-btn-danger">X</a>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
// activityCodingMultiple
export default {
  name: 'BetAmountSetting',
  data() {
    return {
      tableData: [{
        // label: this.$t('labels.activityCodingMultiple'),
        propName: 'bindPhoneActivity',
        propValue: '20',
      }, {
        // date: this.$t('labels.activityCodingMultiple'),
        propName: 'sevenUserActivity',
        propValue: '50',
      }, {
        // date: this.$t('labels.activityCodingMultiple'),
        propName: 'taskCenterActivity',
        propValue: '80',
      }, {
        // date: this.$t('labels.activityCodingMultiple'),
        propName: 'signDaysActivity',
        propValue: '70',
      }],
    };
  },
};
</script>

<style lang="scss" scoped>
.layui-btn {
    display: inline-block;
    height: 38px;
    line-height: 38px;
    padding: 0 18px;
    background-color: #009688;
    color: #fff;
    white-space: nowrap;
    text-align: center;
    font-size: 14px;
    border: none;
    border-radius: 2px;
    cursor: pointer;
}
.layui-btn-danger {
    background-color: #FF5722;
}
</style>
