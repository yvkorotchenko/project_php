import request from '@/utils/request';
import Resource from '@/api/resource';

export default class VIPRechargeStatus extends Resource {
  constructor() {
    super('viprecharge/statuses');
  }
  list() {
    return request({
      url: '/' + this.uri,
      method: 'get',
    });
  }
}
