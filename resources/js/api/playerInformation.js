import Resource from '@/api/resource';
import request from '@/utils/request';

export default class PlayerInformation extends Resource {
  constructor() {
    super('member');
  }

  get(fieldName, fieldValue) {
    return request({
      url: '/member/info/' + fieldName + '/' + fieldValue,
      method: 'get',
    });
  }

  updateRemark(id, remark) {
    return request({
      url: '/member/info/remark',
      method: 'post',
      data: { id: id, remark: remark },
    });
  }
}
