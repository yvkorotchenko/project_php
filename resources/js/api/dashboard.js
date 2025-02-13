import request from '@/utils/request';
import Resource from '@/api/resource';

class DashboardResource extends Resource {
  constructor() {
    super('dashboard');
  }

  newMembers() {
    return request({
      url: '/' + this.uri + '/new-members',
      method: 'get',
    });
  }
  usersCountsRegistration() {
    return request({
      url: '/' + this.uri + '/user-registration',
      method: 'get',
    });
  }
  usersCountsLogin() {
    return request({
      url: '/' + this.uri + '/user-login',
      method: 'get',
    });
  }
  totalStatistics() {
    return request({
      url: '/' + this.uri + '/total-statistics',
      method: 'get',
    });
  }
  //
  betAmountPlatform() {
    return request({
      url: '/' + this.uri + '/bet-amount-of-each-platform',
      method: 'get',
    });
  }
  //
  betAmountPlatformLists() {
    return request({
      url: '/' + this.uri + '/bet-amount-platform-lists',
      method: 'get',
    });
  }
  // recharge withdrawal
  rechargeWithdrawal() {
    return request({
      url: '/' + this.uri + '/recharge-withdrawal',
      method: 'get',
    });
  }
  //
  platformProfitLossPlayerBetting(id) {
    return request({
      url: '/' + this.uri + '/platform-profitLoss-player-betting/' + id,
      method: 'get',
    });
  }
}

export { DashboardResource as default };
