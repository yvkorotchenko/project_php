<template>
  <div id="presetTable">
    <!-- FILTERS -->
    <div class="filter-container filter-preset-table">
      <el-button-group class="control-btn-command">
        <el-tooltip class="item" effect="dark" :content="messageColumns" placement="left">
          <el-button type="primary" icon="el-icon-s-operation" plain />
        </el-tooltip>
        <el-tooltip class="item" effect="dark" :content="messageExcel" placement="top">
          <el-button type="primary" icon="el-icon-folder-opened" plain />
        </el-tooltip>
        <el-tooltip class="item" effect="dark" :content="messagePrint" placement="top">
          <el-button v-print="printObj" type="primary" icon="el-icon-printer" plain />
        </el-tooltip>
      </el-button-group>
    </div>
    <!-- TABLE -->
    <el-table
      v-loading="loading"
      :data="data"
      :height="height"
      border
      fit
      highlight-current-row
      class="preset-table"
    >
      <el-table-column
        v-for="column in configColumns"
        :key="column.name"
        :align="column.align"
        :label="column.label"
        :width="column.width"
        :prop="column.name"
      >
        <template slot-scope="scope">
          <template v-if="column.tag">
            <el-tag
              :type="column.tag.type"
              :effect="column.tag.effect"
              :closable="column.tag.closable"
            >{{ scope.row[column.name] }}
            </el-tag>
          </template>
          <span v-else>{{ scope.row[column.name] }}</span>
        </template>
      </el-table-column>
      <!--  -->
      <el-table-column align="center" :label="$t('table.balance')" prop="name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
export default {
  name: 'PresetTable',
  props: {
    messagePrint: {
      type: String,
      default: 'Print this table',
    },
    messageExcel: {
      type: String,
      default: 'Export records to Excel',
    },
    messageColumns: {
      type: String,
      default: 'Hide columns for display',
    },
    height: {
      type: String,
      default: '650',
    },
    configColumns: {
      type: Array,
      default: () => [],
    },
    data: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      loading: false,
      output: null,
      printObj: {
        id: '#presetTable',
        popTitle: 'Analysis of the anti-money laundering sanctions list system',
        extraCss: 'http://localhost:8888/css/print.css',
      },
    };
  },
  created() {
    console.log(this);
  },
  methods: {
    handlePrint() {
      window.print();
    },
  },
};
</script>

<style lang="scss">
$border-color: #e6e6e6;
$bgcolor: #f2f2f2;

#presetTable {
  background-color: $bgcolor;
}
.filter-preset-table {
  padding: 15px;
  overflow: auto;
  border: 1px solid $border-color;
  border-bottom-width: 0;
  border-radius: 7px 7px 0 0;
  .control-btn-command {
      float: right;
  }
}
.el-tag.el-tag--purple {
  background-color: #3F00D8;
  border-color: #3300ad;
  color: #f1f1f1;
}
</style>
