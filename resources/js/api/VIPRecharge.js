import request from '@/utils/request';
import Resource from '@/api/resource';

export default class VIPRecharge extends Resource {
  constructor() {
    super('viprecharge');
  }

  post(data) {
    return request({
      url: 'viprecharge',
      method: 'post',
      data: data,
    });
  }
}
