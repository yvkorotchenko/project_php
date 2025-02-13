<template>
  <PermissionChecker :permissions="[viewPermission.numberNewMembers, viewPermission.totalRecharge, viewPermission.totalWithdraw, viewPermission.totalProfitAndLoss]">
    <el-row :gutter="40" class="panel-group">
      <PermissionChecker :permissions="[viewPermission.numberNewMembers]">
        <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
          <div @click="() => redirectToRelatedRoute('RealTimeRegistration')">
            <div class="card-panel" @click="handleSetLineChartData('newVisitis')">
              <div class="card-panel-icon-wrapper icon-people">
                <svg-icon icon-class="peoples" class-name="card-panel-icon" />
              </div>
              <div class="card-panel-description">
                <div class="card-panel-text">
                  {{ $t('home.NumberOfNewMembers') }}
                </div>
                <count-to
                  :start-val="0"
                  :end-val="newMember"
                  :duration="newMemberDuration"
                  class="card-panel-num"
                />
              </div>
            </div>
          </div>
        </el-col>
      </PermissionChecker>
      <PermissionChecker :permissions="[viewPermission.totalRecharge]">
        <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
          <div class="card-panel" @click="handleSetLineChartData('messages')">
            <div class="card-panel-icon-wrapper icon-message">
              <svg-icon icon-class="dollar" class-name="card-panel-icon" />
            </div>
            <div class="card-panel-description">
              <div class="card-panel-text">
                {{ $t('home.TotalRecharge') }}
              </div>
              <count-to
                :start-val="0"
                :end-val="totalRecharge"
                :duration="totalRechargeDuration"
                class="card-panel-num"
              />
            </div>
          </div>
        </el-col>
      </PermissionChecker>
      <PermissionChecker :permissions="[viewPermission.totalWithdraw]">
        <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
          <div class="card-panel" @click="handleSetLineChartData('purchases')">
            <div class="card-panel-icon-wrapper icon-money">
              <svg-icon icon-class="dollar" class-name="card-panel-icon" />
            </div>
            <div class="card-panel-description">
              <div class="card-panel-text">
                {{ $t('home.TotalWithdrawal') }}
              </div>
              <count-to
                :start-val="0"
                :end-val="totalWithdrawal"
                :duration="totalWithdrawalDuration"
                class="card-panel-num"
              />
            </div>
          </div>
        </el-col>
      </PermissionChecker>
      <PermissionChecker :permissions="[viewPermission.totalProfitAndLoss]">
        <el-col :xs="12" :sm="12" :lg="6" class="card-panel-col">
          <div class="card-panel" @click="handleSetLineChartData('shoppings')">
            <div class="card-panel-icon-wrapper icon-shopping">
              <svg-icon icon-class="dollar" class-name="card-panel-icon" />
            </div>
            <div class="card-panel-description">
              <div class="card-panel-text">
                {{ $t('home.TotalProfitAndLoss') }}
              </div>
              <count-to
                :start-val="0"
                :end-val="totalProfitLoss"
                :duration="totalProfitLossDuration"
                class="card-panel-num"
              />
            </div>
          </div>
        </el-col>
      </PermissionChecker>
    </el-row>
  </PermissionChecker>
</template>

<script>
import CountTo from 'vue-count-to';
import PermissionChecker from '@/components/Permissions/index.vue';
import router from '@/router';
import viewsPermissions from '@/viewsPermissions.js';

export default {
  components: {
    CountTo,
    PermissionChecker,
  },
  props: {
    newMember: {
      type: Number,
      default: () => 0,
    },
    newMemberDuration: {
      type: Number,
      default: () => 1700,
    },
    totalRechargeDuration: {
      type: Number,
      default: () => 1700,
    },
    totalRecharge: {
      type: Number,
      default: () => 0,
    },
    totalWithdrawal: {
      type: Number,
      default: () => 0,
    },
    totalWithdrawalDuration: {
      type: Number,
      default: () => 1700,
    },
    totalProfitLoss: {
      type: Number,
      default: () => 0,
    },
    totalProfitLossDuration: {
      type: Number,
      default: () => 1700,
    },
  },
  data() {
    return {
      viewPermission: viewsPermissions.homePage.permissions.controls,
    };
  },
  methods: {
    handleSetLineChartData(type) {
      this.$emit('handleSetLineChartData', type);
    },
    redirectToRelatedRoute(ident) {
      const redirects = { RealTimeRegistration: { name: 'RealTimeRegistration' }};
      router.push(redirects[ident]);
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.panel-group {
  margin-top: 18px;
  .card-panel-col{
    margin-bottom: 32px;
  }
  .card-panel {
    height: 108px;
    cursor: pointer;
    font-size: 12px;
    position: relative;
    overflow: hidden;
    color: #666;
    background: #fff;
    box-shadow: 4px 4px 40px rgba(0, 0, 0, .05);
    border-color: rgba(0, 0, 0, .05);
    &:hover {
      .card-panel-icon-wrapper {
        color: #fff;
      }
      .icon-people {
         background: #40c9c6;
      }
      .icon-message {
        background: #36a3f7;
      }
      .icon-money {
        background: #f4516c;
      }
      .icon-shopping {
        background: #34bfa3
      }
    }
    .icon-people {
      color: #40c9c6;
    }
    .icon-message {
      color: #36a3f7;
    }
    .icon-money {
      color: #f4516c;
    }
    .icon-shopping {
      color: #34bfa3
    }
    .card-panel-icon-wrapper {
      float: left;
      margin: 14px 0 0 14px;
      padding: 16px;
      transition: all 0.38s ease-out;
      border-radius: 6px;
    }
    .card-panel-icon {
      float: left;
      font-size: 48px;
    }
    .card-panel-description {
      float: right;
      font-weight: bold;
      margin: 26px;
      margin-left: 0px;
      .card-panel-text {
        line-height: 18px;
        color: rgba(0, 0, 0, 0.45);
        font-size: 16px;
        margin-bottom: 12px;
      }
      .card-panel-num {
        font-size: 20px;
      }
    }
  }
}
</style>
