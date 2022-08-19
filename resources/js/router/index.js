import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'
/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
/* Guest Component */
/* Layouts */
const SideBarLayout = () => import('@/components/layouts/SideBar.vue')
/* Layouts */
/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')
const Posts = () => import('@/components/Posts.vue')
const Users = () => import('@/components/Users.vue')
const Admins = () => import('@/components/Admins.vue')
/* Authenticated Component */
const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: SideBarLayout,
        meta: {
            middleware: "auth:sanctum"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            },
            {
                name: "Posts",
                path: '/posts',
                component: Posts,
                meta: {
                    title: `Posts`
                }
            },
            {
                name: "Users",
                path: '/users',
                component: Users,
                meta: {
                    title: `Users`
                }
            },
            {
                name: "Admins",
                path: '/admins',
                component: Admins,
                meta: {
                    title: `Admins`
                }
            }
        ]
    }
    
]
const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})
router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
         if (store.state.auth.authenticated) {
             next({ name: "dashboard" })
         }
         next()
     } else {
         if (store.state.auth.authenticated) {
             next()
         } else {
             next({ name: "login" })
         }
    
    }next()
})
export default router