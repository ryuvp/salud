import request from '@/app/utils/request';

class DashboardResource {
    summary() {
        return request({
            url: '/dashboard/summary',
            method: 'get',
        });
    }
}

export { DashboardResource as default };
