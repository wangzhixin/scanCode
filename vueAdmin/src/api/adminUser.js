import request from '@/utils/request'
export function getAdminUserList(page) {
  var data = { 'page': page }
  return request({
    url: '/getAdminUserList',
    method: 'post',
    data
  })
}
export function deleteAdminUser(admin_id) {
  var data = { 'admin_id': admin_id }
  return request({
    url: '/deleteAdminUser',
    method: 'post',
    data
  })
}
export function submitAdminUser(data) {
  return request({
    url: '/submitAdminUser',
    method: 'post',
    data
  })
}
