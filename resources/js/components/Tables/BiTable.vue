<template>
  <div>
    <el-table
      ref="taskListTable"
      :v-loading="loading"
      :data="data"
      :border="border"
      :fit="fit"
      :highlight-current-row="highlightCurrentRow"
      :style="width"
      :height="height"
    >
      <el-table-column align="center" :label="$t('title_1')">
        44
        <!--            <template scope="scope"></template>-->
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  name: 'BiTable',
  props: {
    loading: {
      type: Boolean,
      default: () => true,
    },
    data: {
      type: Array,
      default: () => [],
    },
    border: {
      type: Boolean,
      default: () => true,
    },
    fit: {
      type: Boolean,
      default: () => true,
    },
    highlightCurrentRow: {
      type: Boolean,
      default: () => true,
    },
    width: {
      type: String,
      default: 'width: 100%',
    },
    height: {
      type: String,
      default: '650',
    },
    columns: {
      type: Object,
      default: () => {},
    },
  },
  data() {},
};
</script>

<style scoped>

</style>
