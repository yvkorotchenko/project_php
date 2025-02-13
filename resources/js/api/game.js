import Resource from '@/api/resource';
import request from '@/utils/request';

export default class Game extends Resource {
  constructor() {
    super('games');
  }

  uploadFile(gameId, type, file) {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('type', type);

    return request({
      url: this.uri + `/${gameId}/upload-image`,
      method: 'POST',
      data: formData,
      headers: {
        Accept: 'application/json',
        'Content-Type': 'multipart/form-data',
      },
    });
  }
}
