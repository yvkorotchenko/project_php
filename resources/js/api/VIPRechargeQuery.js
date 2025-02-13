import request from '@/utils/request';
import Resource from '@/api/resource';

export default class VIPRechargeQuery extends Resource {
  constructor() {
    super('viprecharge');
  }
  list(query) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      params: query,
    });
  }
}
