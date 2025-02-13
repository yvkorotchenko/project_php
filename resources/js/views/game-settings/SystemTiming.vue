<template>
  <div>
    <PermissionChecker :permissions="[viewPermission.listDelete, viewPermission.create]">
      <PermissionChecker :permissions="[viewPermission.listDelete]">
        <el-button type="danger" size="small" plain @click="hendlerDeleteMultipleSelectionMessages">{{ $t('btn.delete') }}</el-button>
      </PermissionChecker>
      <PermissionChecker :permissions="[viewPermission.create]">
        <el-button type="success" plain size="small" @click="handlerAddMessage">{{ $t('btn.add') }}</el-button>
      </PermissionChecker>
    </PermissionChecker>
    <div class="white-space" />
    <!-- TABLE -->
    <el-table
      ref="multipleTable"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
      height="650"
      @selection-change="handlerMultipleSelectionMessages"
    >
      <el-table-column
        align="center"
        type="selection"
        width="57"
      />
      <el-table-column
        align="center"
        :label="$t('table.broadcastType')"
        width="133"
      >
        <template slot-scope="scope">
          {{ scope.row.type }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.priority')"
        width="57"
      >
        <template slot-scope="scope">
          {{ scope.row.priority }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.intervalMinutes')"
        width="77"
      >
        <template slot-scope="scope">
          {{ scope.row.priority }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.englishContent')"
        width="450"
      >
        <template slot-scope="scope">
          {{ scope.row.content }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.thaiContent')"
        width="450"
      >
        <template slot-scope="scope">
          {{ scope.row.contentLocal }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.startTimeCreateTime')"
        width="143"
      >
        <template slot-scope="scope">
          {{ scope.row.startTime }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.EndTimeCreateTime')"
        width="143"
      >
        <template slot-scope="scope">
          {{ scope.row.endTime }}
        </template>
      </el-table-column>
      <PermissionChecker :permissions="[viewPermission.status]">
        <el-table-column
          fixed="right"
          align="center"
          :label="$t('table.status')"
          width="77"
        >
          <template slot-scope="scope">
            <el-switch
              v-model="scope.row.status"
              active-color="#13ce66"
              inactive-color="#c6c3c3"
              @change="handlerChangeStatus(scope.row.id)"
            />
          </template>
        </el-table-column>
      </PermissionChecker>
      <PermissionChecker :permissions="[viewPermission.repeat, viewPermission.edit, viewPermission.delete]">
        <el-table-column
          fixed="right"
          align="center"
          width="277"
          :label="$t('table.operation')"
        >
          <template slot-scope="scope">
            <PermissionChecker :permissions="[viewPermission.repeat]">
              <el-button
                type="success"
                size="small"
                plain
                :disabled="checkDisable(scope.row.status)"
                @click="handlerRepeatMessage(scope.row.id)"
              >
                {{ $t('btn.repeat') }}
              </el-button>
            </PermissionChecker>
            <PermissionChecker :permissions="[viewPermission.edit]">
              <el-button
                type="primary"
                size="small"
                plain
                @click="handlerUpdateMessage(scope.row.id)"
              >
                {{ $t('btn.edit') }}
              </el-button>
            </PermissionChecker>
            <PermissionChecker :permissions="[viewPermission.delete]">
              <el-button
                type="danger"
                size="small"
                plain
                @click="handlerDeleteMessage(scope.row.id)"
              >
                {{ $t('btn.delete') }}
              </el-button>
            </PermissionChecker>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
    <!-- PAGINATION -->
    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      :page-sizes="[10, 15, 20, 30, 70, 90, 150, 200, 250]"
      @pagination="getsystemTiming"
    />
    <!-- DIALOG -->
    <el-dialog
      :title="title"
      :visible.sync="dialogVisible"
      width="77%"
    >
      <div>
        <el-form
          ref="winWithdrawMessage"
          :model="current"
          label-position="rigth"
          label-width="237px"
          style="width: 100%"
        >
          <!-- -NAME- -->
          <el-form-item
            :label="$t('labels.broadcastType')"
          >
            <el-select
              v-model="current.type"
              :placeholder="$t('placeholders.selectedItem')"
              prop="type"
              :rules="{required: true, message: $t('placeholders.selectedItem'), trigger: 'blur'}"
            >
              <el-option
                v-for="item in broadcast"
                :key="item.value"
                :label="item.name"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <div v-if="current.type === 'Timed Broadcast'">
            <!-- -INTERVAL- -->
            <el-form-item
              :label="$t('table.intervalMinutes')"
            >
              <el-input-number v-model="current.interval" :min="0" />
            </el-form-item>
            <!-- -START TIME- -->
            <el-form-item
              :label="$t('table.startTimeCreateTime')"
            >
              <el-date-picker
                v-model="current.startTime"
                type="datetime"
                :placeholder="$t('placeholders.selectDateAndTime')"
                format="yyyy-MM-dd hh:mm:ss"
                value-format="yyyy-MM-ddTHH:mm:ss"
              />
            </el-form-item>
            <!-- -END TIME- -->
            <el-form-item :label="$t('table.EndTimeCreateTime')">
              <el-date-picker
                v-model="current.endTime"
                type="datetime"
                :placeholder="$t('placeholders.selectDateAndTime')"
                format="yyyy-MM-dd hh:mm:ss"
                value-format="yyyy-MM-ddTHH:mm:ss"
              />
            </el-form-item>
          </div>
          <!-- -NICK- -->
          <el-form-item
            :label="$t('labels.englishBroadcastContent')"
            prop="content"
            :rules="{required: true, message: $t('placeholders.englishBroadcastContent'), trigger: 'blur'}"
          >
            <el-input v-model="current.content" type="textarea" rows="7" />
          </el-form-item>
          <!-- -IP- -->
          <el-form-item
            :label="$t('labels.vietnameseBroadcastContent')"
            prop="contentLocal"
            :rules="{required: true, message: $t('placeholders.vietnameseBroadcastContent'), trigger: 'blur'}"
          >
            <el-input v-model="current.contentLocal" type="textarea" rows="7" />
          </el-form-item>
        </el-form>
        <!-- FOOTER -->
        <div slot="footer" class="dialog-footer" align="center">
          <el-button type="success" size="medium" plain @click="handlerSaveMessage">
            {{ $t('btn.save') }}
          </el-button>
          <el-button type="primary" size="medium" plain @click="dialogVisible = false">
            {{ $t('btn.cancel') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions';
import viewsPermissions from '@/viewsPermissions.js';
// import havePermission from '@/utils/permission';
// import Resource from '@/api/resource';

const systemTiming = new Resource('system-timing');

export default {
  name: 'SystemTiming',
  components: {
    Pagination,
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.systemTiming.permissions.controls,
      multipleSelectionMessage: [],
      current: {
        id: undefined,
        type: 'System Broadcast',
        priority: 1,
        interval: 7,
        content: '',
        contentLocal: '',
        startTime: '',
        endTime: '',
        status: true,
      },
      loading: false,
      list: [],
      query: {
        limit: 20,
        page: 1,
      },
      total: 0,
      title: '',
      dialogVisible: false,
    };
  },
  computed: {
    broadcast: function() {
      return [
        { name: this.$t('select.systemBroadcast'), value: this.$t('select.systemBroadcast', 'en') },
        { name: this.$t('select.timedBroadcast'), value: this.$t('select.timedBroadcast', 'en') },
      ];
    },
  },
  created() {
    this.getsystemTiming();
  },
  methods: {
    async getsystemTiming() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await systemTiming.list(this.query);
      this.list = data;
      this.list.forEach((item, index) => {
        item['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    checkDisable(status) {
      return status === true;
    },
    handlerResetCurrentData() {
      this.current = {
        id: undefined,
        type: 'System Broadcast',
        priority: 1,
        interval: 0,
        content: '',
        contentLocal: '',
        startTime: '',
        endTime: '',
        status: true,
      };
    },
    handlerAddMessage() {
      this.title = this.$t('btn.add');
      this.dialogVisible = true;
    },
    handlerSaveMessage() {
      if (this.current.type !== 'System Broadcast') {
        this.current.priority = 2;
      }
      if (this.current.id === undefined) {
        this.$refs['winWithdrawMessage'].validate((valid) => {
          if (valid) {
            systemTiming
              .store(this.current)
              .then(response => {
                if (response.data.message === 'Success') {
                  this.$message({
                    type: 'success',
                    message: this.$t('messages.dataAddedSuccessfully'),
                  });
                }
              })
              .catch(error => {
                console.log(error);
                // this.$message({
                //   type: 'error',
                //   message: error.error,
                //   duration: 1 * 1000,
                // });
              })
              .finally(() => {
                this.dialogVisible = false;
                this.handlerResetCurrentData();
                this.getsystemTiming();
              });
          } else {
            return false;
          }
        });
      } else {
        this.$refs['winWithdrawMessage'].validate((valid) => {
          if (valid) {
            systemTiming
              .update(this.current.id, this.current)
              .then(response => {
                if (response.data.message === 'Success') {
                  this.$message({
                    type: 'success',
                    message: this.$t('messages.dataAddedSuccessfully'),
                  });
                }
              })
              .catch(error => {
                console.log(error);
                // this.$message({
                //   type: 'error',
                //   message: error.error,
                //   duration: 1 * 1000,
                // });
              })
              .finally(() => {
                this.dialogVisible = false;
                this.handlerResetCurrentData();
                this.getsystemTiming();
              });
          } else {
            return false;
          }
        });
      }
    },
    handlerChangeStatus(id) {
      const row = this.list.find(item => item.id === id);
      row.startTime = row.startTime.replace(' ', 'T');
      row.endTime = row.endTime.replace(' ', 'T');
      this.current = row;
      this.handlerChangeStatusAndUpdate();
    },
    handlerRepeatMessage(id) {
      const row = this.list.find(item => item.id === id);
      row.startTime = row.startTime.replace(' ', 'T');
      row.endTime = row.endTime.replace(' ', 'T');
      this.current = row;
      this.current.status = true;
      this.handlerChangeStatusAndUpdate();
    },
    handlerChangeStatusAndUpdate(){
      systemTiming
        .update(this.current.id, this.current)
        .then(response => {
          if (response.data.message === 'Success') {
            this.$message({
              type: 'success',
              message: this.$t('messages.dataAddedSuccessfully'),
            });
          }
        })
        .catch(error => {
          console.log(error);
          // this.$message({
          //   type: 'error',
          //   message: error.error,
          //   duration: 1 * 1000,
          // });
        })
        .finally(() => {
          this.dialogVisible = false;
          this.handlerResetCurrentData();
          this.getsystemTiming();
        });
    },
    handlerUpdateMessage(id) {
      this.title = this.$t('btn.edit');
      this.dialogVisible = true;
      const row = this.list.find(item => item.id === id);
      this.current = row;
    },
    handlerDeleteMessage(id) {
      this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
        type: 'warning',
      })
        .then(() => {
          systemTiming
            .destroy(id)
            .then(() => {
              this.$message({
                type: 'success',
                message: this.$t('messages.delCompleteUser'),
              });
            })
            .finally(() => {
              this.getsystemTiming();
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: this.$t('messages.delCancelUser'),
          });
        });
    },
    handlerMultipleSelectionMessages(val) {
      this.multipleSelectionMessage = val;
    },
    hendlerDeleteMultipleSelectionMessages() {
      const ids = [];
      if (this.multipleSelectionMessage.length === 0) {
        // this.$message({
        //   type: 'error',
        //   message: this.$t('messages.noDeleteItems'),
        //   duration: 1 * 1000,
        // });
      } else {
        this.multipleSelectionMessage.forEach((item, index) => {
          ids.push(item.id);
        });
        this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
          confirmButtonText: this.$t('btn.ok'),
          cancelButtonText: this.$t('btn.cancel'),
          type: 'warning',
        })
          .then(() => {
            systemTiming
              .destroy(ids.toString())
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                });
              })
              .finally(() => {
                this.getsystemTiming();
              });
          })
          .catch(() => {
            this.$message({
              type: 'info',
              message: this.$t('messages.delCancelUser'),
            });
          });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
$colorLine: #dfe6ec;
.white-space {
  margin: 11px 0;
  height: 1px;
}
.separate-line {
  height: 1px;
  border-top: 1px solid $colorLine;
  margin: 20px 0;
}
</style>
