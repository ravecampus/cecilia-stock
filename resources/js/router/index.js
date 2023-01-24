
import {createWebHistory, createRouter} from "vue-router";
import Home from '../page/Home';

import Admin from '../admin/Mainpage';
import Department from '../admin/Department';
import AdminPosition from '../admin/Position';
import Leavetype from '../admin/LeaveType';
import Users from '../admin/Users';
import Administrator from '../admin/Administrator';

import User from '../user/Mainpage';
import Profile from '../user/Profile';
import LeaveApp from '../user/LeaveApplication';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home,
    },
    {
        name: 'admin',
        path: '/admin',
        component: Admin,
        children:[
            {
                name: 'admindepartment',
                path: 'department',
                component: Department,
            },
            {
                name: 'adminposition',
                path: 'position',
                component: AdminPosition,
            },
            {
                name: 'adminleavetype',
                path: 'leave-type',
                component: Leavetype,
            },
            {
                name: 'adminusers',
                path: 'employees',
                component: Users,
            },
            {
                name: 'administrator',
                path: 'administrators',
                component: Administrator,
            },
        ]
    },
    {
        name: 'user',
        path: '/user',
        component: User,
        children:[
            {
                name: 'profile',
                path: 'profile',
                component: Profile,
            },
            {
                name: 'leaveapp',
                path: 'leave-application',
                component: LeaveApp,
            }
        ]
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});
const openRoutes = [
    'home',
  
    
];

const userRoutes = [
    'user',
    'profile',
    'leaveapp'
   
];
const adminRoutes = [
    'admin',
    'admindepartment',
    'adminposition',
    'adminleavetype',
    'adminusers',
    'administrator',
    
];


router.beforeEach((to, from, next)=>{
    if(!window.Laravel.isLoggedin){
        if(openRoutes.includes(to.name)){
            return next();
        }
    }else{
        let user = window.Laravel.user;
        if(adminRoutes.includes(to.name) && (user.role == 2 || user.role == 1)){
            return next();
        }else if(userRoutes.includes(to.name) &&  user.role == 0){
            return next();
        }
    }
    if(openRoutes.includes(to.name)){
        return next();
    }else{
       // return next({name:'signin'});
    }
    
   
});
export default router;