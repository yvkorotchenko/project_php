import Resource from '@/api/resource';
import request from '@/utils/request';

export default class extends Resource {
  constructor() {
    super('finance');
  }

  currencyList() {
    return request({
      url: 'finance/currencies',
      method: 'get',
    });
  }

  userInfo(uid) {
    return request({
      url: `finance/user-info/${uid}`,
      method: 'get',
    });
  }

  currencyWithdraw(id) {
    return request({
      url: `finance/currencies/${id}/withdraw`,
      method: 'get',
    });
  }

  createWithdraw(data) {
    return request({
      url: 'finance/withdraw',
      method: 'post',
      data: data,
    });
  }

  withdrawList({ page = 1, size = 10, ...other }) {
    const additionalParams =
      Object.entries(other)
        .reduce((subResult, curr) => subResult + '&' + curr[0] + '=' + curr[1], '');

    return request({
      url: `finance/withdraw?page=${page}&size=${size}${additionalParams.length > 0 ? additionalParams : ''}`,
      method: 'get',
    });
  }

  changeStatus(withdrawId, stateAction) {
    return request({
      url: 'finance/withdraw/change-status',
      method: 'put',
      data: {
        withdrawId: withdrawId,
        stateAction: stateAction,
      },
    });
  }

  withdrawStates() {
    return request({
      url: 'finance/withdraw/states',
      method: 'get',
    });
  }
}
