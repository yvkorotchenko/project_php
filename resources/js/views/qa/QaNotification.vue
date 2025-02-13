<template>
  <div class="app-container">
    <h1>{{ $t('labels.notificationTesting') }}</h1>
    <div>
      <el-col :span="18" class="row">
        <el-col :span="5" class="qa-notification-text">{{ $t('labels.youWonABet') }}</el-col>
        <el-col :span="8" class="qa-notification-input">
          <el-input v-model="wonAbetUid" :placeholder="$t('placeholders.userId')" />
        </el-col>
        <el-col :span="8" class="qa-notification-button">
          <PermissionChecker :permissions="['view menu qa notification sendWonBet']">
            <el-button type="primary" @click="sendWonBet">{{ $t('labels.send') }}</el-button>
          </PermissionChecker>
        </el-col>
      </el-col>
      <el-col :span="18" class="row">
        <el-col :span="5" class="qa-notification-text">{{ $t('labels.completedDailyTask') }}</el-col>
        <el-col :span="8" class="qa-notification-input">
          <el-input v-model="dailyTaskCompletedUid" :placeholder="$t('placeholders.userId')" />
        </el-col>
        <el-col :span="8" class="qa-notification-button">
          <PermissionChecker :permissions="['view menu qa notification sendDailyTaskCompleted']">
            <el-button type="primary" @click="sendDailyTaskCompleted">{{ $t('labels.send') }}</el-button>
          </PermissionChecker>
        </el-col>
      </el-col>
      <el-col :span="18" class="row">
        <el-col :span="5" class="qa-notification-text">{{ $t('labels.completedTimeLimitedTask') }}</el-col>
        <el-col :span="8" class="qa-notification-input">
          <el-input v-model="timeLimitedTaskCompletedUid" :placeholder="$t('placeholders.userId')" />
        </el-col>
        <el-col :span="8" class="qa-notification-button">
          <PermissionChecker :permissions="['view menu qa notification timeLimitedTaskCompletedUid']">
            <el-button type="primary" @click="sendTimeLimitedTaskCompleted">{{ $t('labels.send') }}</el-button>
          </PermissionChecker>
        </el-col>
      </el-col>
      <el-col :span="18" class="row">
        <el-col :span="5" class="qa-notification-text">{{ $t('labels.completeRechargeTask') }}</el-col>
        <el-col :span="8" class="qa-notification-input">
          <el-input v-model="timeLimitedTaskCompletedUid" :placeholder="$t('placeholders.userId')" />
        </el-col>
        <el-col :span="8" class="qa-notification-button">
          <PermissionChecker :permissions="['view menu qa notification sendRechargeTaskCompleted']">
            <el-button type="primary" @click="sendRechargeTaskCompleted">{{ $t('labels.send') }}</el-button>
          </PermissionChecker>
        </el-col>
      </el-col>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import PermissionChecker from '@/components/Permissions/index.vue';
const res = new Resource('mailing-list');

export default {
  name: 'QaNotification',
  components: {
    PermissionChecker,
  },
  data() {
    return {
      wonAbetUid: null,
      dailyTaskCompletedUid: null,
      timeLimitedTaskCompletedUid: null,
      rechargeTaskCompletedUid: null,
    };
  },
  methods: {
    sendWonBet() {
      this.sendData(
        this.$t('labels.youWonABet'),
        'Congratulations, you won a bet in Thunderfire game! The match started on 24/11/2021 at 11:25AM and finished at 1:20PM. Your win amount is: ¥500',
        this.wonAbetUid);
    },
    sendDailyTaskCompleted() {
      this.sendData(
        this.$t('labels.completedDailyTask'),
        'Congratulations, you have completed a Daily task!As a reward for completing the task we are granting you with ¥1000',
        this.wonAbetUid,
        1000);
    },
    sendTimeLimitedTaskCompleted() {
      this.sendData(
        this.$t('labels.completedTimeLimitedTask'),
        'Congratulations, you have completed a Time Limited task!As a reward for completing the task we are granting you with ¥800',
        this.wonAbetUid,
        800);
    },
    sendRechargeTaskCompleted() {
      this.sendData(
        this.$t('labels.completeRechargeTask'),
        'Congratulations, you have completed a Recharge task!As a reward for completing the task we are granting you with ¥1200',
        this.wonAbetUid,
        1200);
    },
    sendData(title, content, uid, amount = 500) {
      res.store({
        content: content,
        contentLocal: content,
        goldCoin: amount,
        increaseCoef: 1,
        increaseValue: 0,
        increaseWithdrawStandart: false,
        onTop: false,
        operatorId: 1,
        operatorName: 'Admin',
        title: title,
        titleLocal: title,
        uids: uid,
      })
        .then(response => this.$message({ type: 'success', message: 'Success', duration: 1 * 1000 }))
        .catch((error) => {
          console.log(error);
          // this.$message({ type: 'error', message: error, duration: 1 * 1000 });
        });
    },
  },
};

</script>

<style scoped>
.row {margin: 20px 0;}
.qa-notification-text{
  line-height: 36px;
}
.qa-notification-input{
  line-height: 36px;
}
.qa-notification-button{
  line-height: 36px;
}
</style>
