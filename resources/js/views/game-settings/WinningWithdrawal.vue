<template>
  <div>
    <PermissionChecker :permissions="[viewPermission.listDelete, viewPermission.create]">
      <PermissionChecker :permissions="[viewPermission.listDelete]">
        <el-button type="danger" size="small" plain @click="hendlerDeleteMultipleSelectionMessages">{{ $t('btn.delete') }}</el-button>
      </PermissionChecker>
      <PermissionChecker :permissions="[viewPermission.create]">
        <el-button
          v-if="!withdrawalBroadcast || !winningBroadcast"
          type="success"
          plain
          size="small"
          @click="handlerAddMessage"
        >
          {{ $t('btn.add') }}
        </el-button>
      </PermissionChecker>
    </PermissionChecker>
    <div class="white-space" />
    <!-- TABLE -->
    <el-table
      ref="multipleTable"
      v-loading="loading"
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
      >
        <template slot-scope="scope">
          {{ scope.row.type }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.priority')"
      >
        <template slot-scope="scope">
          {{ scope.row.priority }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.englishContent')"
      >
        <template slot-scope="scope">
          {{ scope.row.content }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.thaiContent')"
      >
        <template slot-scope="scope">
          {{ scope.row.contentLocal }}
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        :label="$t('table.amountThaiBaht')"
      >
        <template slot-scope="scope">
          {{ scope.row.amount }}
        </template>
      </el-table-column>
      <PermissionChecker :permissions="[viewPermission.delete]">
        <el-table-column
          fixed="right"
          align="center"
          width="120"
          :label="$t('table.operation')"
        >
          <template slot-scope="scope">
            <el-button
              type="danger"
              size="small"
              plain
              @click="handlerDeleteMessage(scope.row.id)"
            >{{ $t('btn.delete') }}</el-button>
          </template>
        </el-table-column>
      </PermissionChecker>
    </el-table>
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
            prop="type"
            :rules="{required: true, message: $t('placeholders.selectedItem'), trigger: 'blur'}"
          >
            <el-select
              v-model="current.type"
              :placeholder="$t('placeholders.selectedItem')"
            >
              <el-option v-if="!winningBroadcast" :value="$t('labels.winningBroadcast', 'en')">
                {{ $t('labels.winningBroadcast') }}
              </el-option>
              <el-option v-if="!withdrawalBroadcast" :value="$t('labels.withdrawalBroadcast', 'en')">
                {{ $t('labels.withdrawalBroadcast') }}
              </el-option>
            </el-select>
          </el-form-item>
          <!-- -INPUT- -->
          <el-form-item
            :label="$t('labels.goldCoinLowerLimit')"
            prop="amount"
            :rules="{required: true, message: $t('placeholders.pleaseInputGoldCoinLowerLimit'), trigger: 'blur'}"
          >
            <el-input-number v-model="current.amount" :min="1" />
          </el-form-item>
          <!-- -NICK- -->
          <el-form-item
            :label="$t('labels.englishBroadcastContent')"
            prop="content"
            :rules="{required: true, message: $t('placeholders.pleaseInputEnglishBroadcastContent'), trigger: 'blur'}"
          >
            <el-input v-model="current.content" type="textarea" rows="7" />
          </el-form-item>
          <!-- -IP- -->
          <el-form-item
            :label="$t('labels.vietnameseBroadcastContent')"
            prop="contentLocal"
            :rules="{required: true, message: $t('placeholders.pleaseInputLocalBroadcastContent'), trigger: 'blur'}"
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
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions';
import viewsPermissions from '@/viewsPermissions.js';

const winWithdrawMessages = new Resource('win-withdraw-message');

export default {
  name: 'WinningWithdrawal',
  components: {
    PermissionChecker,
  },
  data() {
    return {
      viewPermission: viewsPermissions.winningWithdrawal.permissions.controls,
      withdrawalBroadcast: false,
      winningBroadcast: false,
      multipleSelectionMessage: [],
      current: {
        id: undefined,
        type: '',
        priority: 3,
        content: '',
        contentLocal: '',
        amount: 0,
      },
      loading: false,
      list: null,
      query: {
        limit: 20,
        page: 1,
      },
      total: 0,
      title: '',
      dialogVisible: false,
    };
  },
  created() {
    this.getWinWithdrawMessages();
  },
  methods: {
    async getWinWithdrawMessages() {
      const { limit, page } = this.query;
      this.loading = true;
      await winWithdrawMessages.list(this.query)
        .then(response => {
          this.list = (response.data === '') ? [] : response.data;
          if (this.list) {
            this.list.forEach((item, index) => {
              item['index'] = (page - 1) * limit + index + 1;
            });
            this.checkIssetItem();
          }
          this.total = response.meta.total;
          this.loading = false;
        });
    },
    handlerResetCurrentData() {
      this.current = {
        id: undefined,
        type: '',
        priority: 3,
        content: '',
        contentLocal: '',
        amount: 0,
      };
    },
    handlerAddMessage() {
      this.title = this.$t('btn.add');
      this.dialogVisible = true;
    },
    handlerSaveMessage() {
      this.$refs['winWithdrawMessage'].validate((valid) => {
        if (valid) {
          winWithdrawMessages
            .store(this.current)
            .then(() => {
              this.$message({
                type: 'success',
                message: this.$t('messages.dataAddedSuccessfully'),
                duration: 1 * 1000,
              });
              this.checkIssetItem();
            })
            .catch(response => {
              // this.$message({
              //   type: 'error',
              //   message: response.error,
              //   duration: 1 * 1000,
              // });
            })
            .finally(() => {
              this.dialogVisible = false;
              this.getWinWithdrawMessages();
              this.handlerResetCurrentData();
            });
        } else {
          return false;
        }
      });
    },
    checkIssetItem() {
      if (this.list) {
        this.list.map((item) => {
          if (item.type === 'Withdrawal Broadcast') {
            this.withdrawalBroadcast = true;
          }
          if (item.type === 'Winning Broadcast') {
            this.winningBroadcast = true;
          }
        });
      }
    },
    handlerDeleteMessage(id) {
      this.$confirm(this.$t('messages.deleteConfirmMessage') + '?', 'Warning', {
        confirmButtonText: this.$t('btn.ok'),
        cancelButtonText: this.$t('btn.cancel'),
        type: 'warning',
      })
        .then(() => {
          winWithdrawMessages
            .destroy(id)
            .then(() => {
              this.$message({
                type: 'success',
                message: this.$t('messages.delCompleteUser'),
                duration: 1 * 1000,
              });
            })
            .finally(() => {
              this.getWinWithdrawMessages();
            });
          if (this.list[0]['id'] === id) {
            if (this.list[0]['type'] === 'Withdrawal Broadcast') {
              this.withdrawalBroadcast = false;
            }
            if (this.list[0]['type'] === 'Winning Broadcast') {
              this.winningBroadcast = false;
            }
          }
          if (this.list[1]['id'] === id) {
            if (this.list[1]['type'] === 'Withdrawal Broadcast') {
              this.withdrawalBroadcast = false;
            }
            if (this.list[1]['type'] === 'Winning Broadcast') {
              this.winningBroadcast = false;
            }
          }
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
            winWithdrawMessages
              .destroy(ids.toString())
              .then(() => {
                this.$message({
                  type: 'success',
                  message: this.$t('messages.delCompleteUser'),
                });
              })
              .finally(() => {
                this.getWinWithdrawMessages();
              });
            ids.map((item) => {
              if (this.list[0]['id'] === item) {
                if (this.list[0]['type'] === 'Withdrawal Broadcast') {
                  this.withdrawalBroadcast = false;
                }
                if (this.list[0]['type'] === 'Winning Broadcast') {
                  this.winningBroadcast = false;
                }
              }
              if (this.list[1]['id'] === item) {
                if (this.list[1]['type'] === 'Withdrawal Broadcast') {
                  this.withdrawalBroadcast = false;
                }
                if (this.list[1]['type'] === 'Winning Broadcast') {
                  this.winningBroadcast = false;
                }
              }
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
