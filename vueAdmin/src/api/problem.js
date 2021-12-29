import request from '@/utils/request'
export function problemList() {
  return request({
    url: '/problemList',
    method: 'post'
  })
}
export function submitProblem(data) {
  return request({
    url: '/submitProblem',
    method: 'post',
    data
  })
}
export function deleteProblem(problem_id) {
  var data = { 'problem_id': problem_id }
  return request({
    url: '/deleteProblem',
    method: 'post',
    data
  })
}
