import request from '@/utils/request'
export function getUserDataList(page) {
  var data = { 'page': page }
  return request({
    url: '/getUserDataList',
    method: 'post',
    data
  })
}
export function exportUserData(ids) {
  var data = { 'ids': ids }
  return request({
    url: '/exportUserData',
    method: 'post',
    data
  })
}
