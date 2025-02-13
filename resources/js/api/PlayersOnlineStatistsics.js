import request from '@/utils/request';

export default class PlayersOnlineStatistsics {
  list(from, to) {
    return request({
      url: `statistics/players-online?from=${from}&to=${to}`,
      method: 'get',
    });
  }

  latest() {
    return request({
      url: 'statistics/players-online/latest',
      method: 'get',
    });
  }

  userInfoByGame(gameId) {
    return request({
      url: `statistics/players-online/user-info-in-game/${gameId}`,
      method: 'get',
    });
  }

  usersOnlineCountByDevices() {
    return request({
      url: 'statistics/players-online/count-by-devices',
      method: 'get',
    });
  }
}
