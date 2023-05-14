<template>
    <div class="container-fluid p-0">
        <div class="text-center d-none d-print-block mb-5">
            <h4>{{ title }}</h4>
            <h6>Davao City, Philippines</h6>
        </div>
        <h1 class="h3 mb-3 d-print-none">Report</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Employee's Leave ({{ this.post.year }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between d-print-none">
                        <div class="form-group col-md-6">
                            <small>Search...</small>
                            <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search....">
                        </div>
                        <div class="form-group col-md-3">
                            <small>Year</small>
                            <select v-model="post.year" class="form-control">
                                <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                            </select>
                        </div>
                        <div class="btn-group">
                            <button type="button"  @click="filterData()" class="btn btn-success">
                                <i class="fa fa-filter"></i> Generate
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" :disabled="printdis" @click="printData()" class="btn btn-success">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>

                    <hr>
                    <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                        <tbody>
                            <tr class="tr-shadow mb-2" v-for="(list, idx) in employees" :key="idx">
                                <td class="text-success">
                                    <strong>{{ list.last_name }}, {{list.first_name}} {{ list.middle_name }}
                                    </strong>
                                </td>
                                <td class="desc">{{ extractGender(list.gender) }}</td>
                                <td class="desc">{{ list.position }}</td>
                                <td>
                                <li class="list-group-item" v-for="(ls,idx) in leave_types" :key="idx">
                                    <div class="d-flex justify-content-between">
                                        <strong class="">{{ ls.description }}</strong>
                                        <div>Credits:
                                            <strong>{{ ls.number_of_days }}</strong>
                                        </div>
                                        <div>Earned:
                                            <strong >{{ leaveConsume(list.leaves, ls.id) + leaveBorrow(list.borrows, ls.id) }}</strong>
                                            <ul class="m-0">
                                                <li v-for="(lss, idxx) in listLeave(list.leaves, ls.id)" :key="idxx">
                                                 <strong>({{ lss.leave }})</strong>   {{ formatDate(lss.created_at) }}
                                                </li>
                                                <li v-for="(lss, idxx) in listLeavebarrow(list.borrows, ls.id)" :key="idxx">
                                                 <strong>({{ lss.credits }})</strong>   {{ formatDate(lss.created_at) }} - <small>borrowed</small>
                                                </li>
                                            </ul>
                                        </div>
                                        <div>Available:
                                            <strong >{{ ls.number_of_days - (leaveConsume(list.leaves, ls.id) + leaveBorrow(list.borrows, ls.id)) }}</strong>
                                        </div>
                                    </div>
                                    <hr>
                                </li>
                                </td>
                            </tr>
                            <tr> 
                                <td colspan="6" v-show="!noData(employees)">
                                    No Result Found!
                                </td>
                            </tr>

                            <tr class="spacer"></tr>
                            <tr class="p-3">
                                <td colspan="6">
                                <strong class="mt-2">{{ employees.length}}</strong> Employee /s
                                </td>
                            </tr>
                            
                        </tbody>
                    </data-table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

import DataTable from '../table/DataTable'
import PaginationVue from '../table/Pagination';
export default {
     components:{
        dataTable:DataTable,
        pagination:PaginationVue
    },
    data(){

        let sortOrders = {};
        let columns =[
        {label:'Name', name:null},
        {label:'Gender', name:null},
        {label:'Position', name:null},
        {label:'Leave Credits', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            title:"",
            leave_types:[],
            employees:[],
            post:{},
            columns:columns,
            sortOrders:sortOrders,
            sortKey:'created_at',
            printdis: true,
            tableData:{
                year:"",
                draw:0,
                length:10,
                search:'',
                column:0,
                archive:0,
                dir:'desc',
                filter:1,
            },
            pagination:{
                lastPage:'',
                currentPage:'',
                total:'',
                lastPageUrl:'',
                nextPageurl:'',
                prevPageUrl:'',
                from:'',
                to:''
            },
        }
    },
    computed : {
        years () {
            const year = new Date().getFullYear()
            const date_ = 2019;
            return Array.from({length: year - date_}, (value, index) => (date_+ 1) + index)
        }
    },
    methods: {
        listOfUser(urls='api/report'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.employees = data.data;
                        // this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    }
                
                }).catch(err=>{
                
                });
            });
        },
        configPagination(data){
            this.pagination.lastPage = data.last_page;
            this.pagination.currentPage = data.current_page;
            this.pagination.total = data.total;
            this.pagination.lastPageUrl = data.last_page_url;
            this.pagination.nextPageUrl = data.next_page_url;
            this.pagination.prevPageUrl = data.prev_page_url;
            this.pagination.from = data.from;
            this.pagination.to = data.to;
        },
        sortBy(key){
            if(key != null){
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, 'name', key);
                this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
                this.listOfUser();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        formatDate(da){
            let d = new Date(da);
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();
            return  month+ "-" + day  + "-" + year;
        },
        extractGender(num){
            return num == 1 ? "Male" : "Female";
        },  
         listLeaveType(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/leave-type-list').then(res=>{
                    this.leave_types = res.data;
                });
            });
        },
        leaveConsume(data, id){
            let num = 0;
            data.forEach(val=>{
                let yr = new Date(val.created_at).getFullYear();
                let yrc = this.post.year;
                if(val.leave_type_id == id && yr == yrc){
                    num += val.leave;
                }
            });
            return num;
        },
        leaveBorrow(data, id){
            let ret = 0;
            data.forEach(val=>{
                let yr = new Date(val.created_at).getFullYear();
                let yrc = this.post.year;
                if(val.leave_type_id == id && yr == yrc){
                    ret += val.credits;
                }
            });
            return ret;
        },
        printData(){
            window.print();
        },
        filterData(){
            if(this.post.year != null){
                this.listOfUser();
                this.printdis = false;
            }else{
                this.$emit('show',{'message':'Please select Year!', 'status':3});
            }
            
        },
        listLeave(data,id){
            let ret = [];
           data.forEach(val=>{
                let yr = new Date(val.created_at).getFullYear();
                let yrc = this.post.year;
                if(val.leave_type_id == id && yr == yrc){
                    ret.push(val);
                }
           });
           return ret;
        },
        listLeavebarrow(data,id){
            let ret = [];
           data.forEach(val=>{
                let yr = new Date(val.created_at).getFullYear();
                let yrc = this.post.year;
                if(val.leave_type_id == id && yr == yrc){
                    ret.push(val);
                }
           });
           return ret;
        }
    },
    mounted() {
        
        this.listLeaveType();
        this.title = window.Title.app_name;
        if(window.Laravel.isLoggedin){
            // this.user = window.Laravel.user;
            // this.auth = true;
        }
    },
}
</script>

<style>
@page {size: landscape;}
</style>
