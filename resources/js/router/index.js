
import {createWebHistory, createRouter} from "vue-router";
import Home from '../page/Home';

import Admin from '../admin/Mainpage';
import Department from '../admin/Department';
import AdminPosition from '../admin/Position';
import Leavetype from '../admin/LeaveType';
import Users from '../admin/Users';
import UserInfo from '../admin/UserInfo';
import Administrator from '../admin/Administrator';
import AdminApplication from '../admin/LeaveApplication';
import AdminViewApp from '../admin/ViewApp';
import LeaveStatus from '../admin/LeaveStatus';
import AdminDashboard from '../admin/Dashboard';
import Report from '../admin/Report';

import User from '../user/Mainpage';
import Profile from '../user/Profile';
import LeaveApp from '../user/LeaveApplication';
import LeaveCredit from '../user/LeaveCredit';
import LeaveHistory from '../user/History';
import ApprovedLeave from '../user/ApprovedLeave';
import Homed from '../user/Home';


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
                name: 'admindashboard',
                path: '',
                component: AdminDashboard,
            },
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
                name: 'adminuser',
                path: 'employee/:id',
                component: UserInfo,
            },
            {
                name: 'administrator',
                path: 'administrators',
                component: Administrator,
            },
            {
                name: 'adminleaveapp',
                path: 'applications',
                component: AdminApplication,
            },
            {
                name: 'adminviewleave',
                path: 'view-application/:id',
                component: AdminViewApp,
            },
            {
                name: 'adminleavestatus',
                path: 'leave-status',
                component: LeaveStatus,
            },
            {
                name: 'adminreport',
                path: 'report',
                component: Report,
            }
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
                name: 'homed',
                path: '',
                component: Homed,
            },
            {
                name: 'leaveapp',
                path: 'leave-application',
                component: LeaveApp,
            },
            {
                name: 'leavecredit',
                path: 'leave-credits',
                component: LeaveCredit,
            },
            {
                name: 'leavehistory',
                path: 'leave-history',
                component: LeaveHistory,
            },
            {
                name: 'viewleave',
                path: 'view-leave/:id',
                component: ApprovedLeave,
            },
           
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
    'leaveapp',
    'leavecredit',
    'leavehistory',
    'viewleave',
    'homed'
   
];
const adminRoutes = [
    'admin',
    'admindepartment',
    'adminposition',
    'adminleavetype',
    'adminusers',
    'adminuser',
    'administrator',
    'adminleaveapp',
    'adminviewleave',
    'adminleavestatus',
    'admindashboard',
    'adminreport'
    
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