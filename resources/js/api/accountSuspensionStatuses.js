import Resource from '@/api/resource';
import request from '@/utils/request';

export default class AccountSuspensionStatuses extends Resource {
  constructor() {
    super('account-suspension');
  }
  list() {
    return request({
      url: 'account-suspension/statuses',
      method: 'get',
    });
  }
}
