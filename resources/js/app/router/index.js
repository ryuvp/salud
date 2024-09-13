import { createRouter, createWebHistory } from 'vue-router';
import Layout from '../views/layout/Layout.vue';
import { isLogged } from '@/app/utils/auth';

const constantRoutes = [
    {
        path: '/login',
        component: () => import('../views/login/index.vue'),
        hidden: true,
        meta: { constant: true }
    },
    {
        path: '/',
        component: Layout,
        redirect: '/dashboard',
        meta: { constant: true },
        children: [
            {
                path: 'dashboard',
                component: () => import('../views/home/dashboard/index.vue'),
                name: 'dashboard',
                meta: { title: 'Dashboard' }
            },
            {
                path: 'users',
                component: () => import('../views/manage/users/index.vue'),
                name: 'users',
                meta: { title: 'Users' }
            },
            {
                path: 'roles',
                component: () => import('../views/manage/roles/index.vue'),
                name: 'roles',
                meta: { title: 'Roles' }
            },
            {
                path: 'permissions',
                component: () => import('../views/manage/permissions/index.vue'),
                name: 'permissions',
                meta: { title: 'Permissions' }
            },
            {
                path: 'profile',
                component: () => import('../views/profile/user/index.vue'),
                name: 'profile',
                meta: { title: 'Profile' }
            },
            {
                path: 'patients',
                component: () => import('../views/patient/register/index.vue'),
                name: 'patients',
                meta: { title: 'Patients' }
            },
            {
                path: 'patients/follow',
                component: () => import('../views/patient/follow/index.vue'),
                name: 'follow',
                meta: { title: 'Patients Follow' }
            },
            {
                path: 'patients/report',
                component: () => import('../views/patient/report/index.vue'),
                name: 'report',
                meta: { title: 'Patients Report' }
            }
        ]
    },
    { path: '/:pathMatch(.*)*', name: 'NotFound', redirect: '/404', hidden: true }
];

const router = createRouter({
    history: createWebHistory(),
    routes: constantRoutes
});

router.beforeEach((to, from, next) => {
    if (isLogged() || to.path === '/login') {
        next();
    } else {
        next('/login');
    }
});

export function resetRouter() {
    router.getRoutes().forEach(route => {
        // Excluye las rutas que tienen `meta.constant` o las rutas que no tienen nombre
        if (!route.meta.constant && route.name) {
            router.removeRoute(route.name);
        }
    });
}

export { constantRoutes };
export default router;
