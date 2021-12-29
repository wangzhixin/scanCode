import request from '@/utils/request'
export function getHospitalList(page) {
  var data = { 'page': page }
  return request({
    url: '/getHospitalList',
    method: 'post',
    data
  })
}
export function submitHospital(data) {
  return request({
    url: '/submitHospital',
    method: 'post',
    data
  })
}
export function deleteHospital(hospital_id) {
  var data = { 'hospital_id': hospital_id }
  return request({
    url: '/deleteHospital',
    method: 'post',
    data
  })
}
export function getDepartmentList(id, page) {
  var data = { 'id': id, 'page': page }
  return request({
    url: '/getDepartmentList',
    method: 'post',
    data
  })
}

export function submitDepartmentList(data) {
  return request({
    url: '/submitDepartmentList',
    method: 'post',
    data
  })
}

export function deleteDepartmentList(department_id) {
  var data = { 'department_id': department_id }
  return request({
    url: '/deleteDepartmentList',
    method: 'post',
    data
  })
}
