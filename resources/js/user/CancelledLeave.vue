<template>
      <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Cancelled Leave</h1>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Leave Application</h5>
                        <input type="text" class="form-control" v-model="tableData.search"  @input="listLeave()" placeholder="Search....">
                        <hr>
                        <data-table class="mt-5" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                            <tbody class="">
                                <tr class="shadow-sm p-3 m-2"  v-for="(list) in leaves" :key="list.id">
                                    <td><strong class="text-primary">{{ list.ref_number }}</strong></td>
                                    <td><strong class="text-success">{{ extractLeaveType(list.leave_type_id) }}</strong></td>
                                    <td>
                                        <strong>{{ formatDate(list.date_from)+" "+extractExt(list.ext_from) }} - {{ formatDate(list.date_to)+" "+extractExt(list.ext_to) }}</strong>
                                        <hr class="mt-0">
                                        <span><strong class="text-primary">{{ list.leave }} day/s</strong></span>
                                        <div v-if="list.borrow != null"><i class="text-success" > consume credits from {{ extractLeaveType(list.borrow.leave_type_id) }} is</i> <strong>{{ list.borrow.credits }} day/s</strong></div>
                                        <hr class="m-0">
                                        <div v-if="list.borrow != null" class="">Total of : <strong>{{ list.leave + list.borrow.credits }}</strong></div>
                                    </td>
                                    <td><span :class="list.deleted == 1 ? 'text-danger' :'text-muted'"> <strong>{{ extractStatus(list.deleted) }}</strong></span></td>
                                    <td><span class="text-muted">{{ formatDate(list.updated_at) }}</span></td>
                                    <td>                                           
                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip" @click="deleteLeaveApp(list)" title="View">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" v-show="!noData(leaves)">
                                        No Result Found!
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                                
                            </tbody>
                        </data-table>
                        <div class="col-md-12 mt-3">
                            <div class="pull-right">
                                <pagination :pagination="pagination"
                                    @prev="listLeave(pagination.prevPageUrl)"
                                    @next="listLeave(pagination.nextPageUrl)"
                                    v-show="noData(leaves)">
                                </pagination>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        
        <div class="modal fade delete-leave">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h6>Delete</h6>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                Do you want to Delete <strong>{{leave.ref_number}}</strong>?
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <div class="btn-group">
                            <button type="button" @click="confirmDeleteLeave(post)" class="btn btn-danger">Yes</button>
                            <button type="button" @click="cancel()" class="btn btn-secondary">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
      
</template>

<script>
import DataTable from '../table/DataTable';
import PaginationVue from '../table/Pagination';

import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
    components:{
        Datepicker,
        dataTable:DataTable,
        pagination:PaginationVue
    },
    setup() {
        // In case of a range picker, you'll receive [Date, Date]
        const format = (d) => {
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();

            return  month+ "-" + day  + "-" + year;
        }
        return {
            format,
        }
    },
    data(){
        let sortOrders = {};
        let columns =[
        {label:'REF. #', name:null},
        {label:'LEAVE TYPE', name:null},
        {label:'DURATION', name:null},
        {label:'STATUS', name:null},
        {label:'DATE', name:null},
        {label:'ACTION ', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            post:{},
            errors:[],
            usable:[],
            consumeleave:[],
            borrowleave:[],
            btncap:"Send",
            leave_types:[],
            leave_type:{},
            availeave:0,
            availuse:0,
            user:{},
            auth:false,
            leaves: [],
            leave:{},
            borrow:{},
            columns:columns,
            sortOrders:sortOrders,
            sortKey:'created_at',
            tableData:{
                draw:0,
                length:10,
                search:'',
                column:0,
                archive:0,
                dir:'desc',
                filter:0,
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
    methods: {
        viewLeaveApp(data){
            this.$router.push({name:'viewleave', params:{'id':data.id}});
        },
        cancel(){
            $('.delete-leave').modal("hide"); 
        },
        deleteLeaveApp(data){
            this.leave = data;
            $('.delete-leave').modal("show");
        },
        listLeaveType(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/leave-type-list').then(res=>{
                    this.leave_types = res.data;
                });
            });
        },
        extractNonLeaveType(id){
            let _use = [];
            this.leave_types.forEach(vl=>{
                if(id != vl.id){
                    _use.push(vl);
                }
            });
            return _use;
        },
        extractLeaveType(id){
            let ret = "";
            this.leave_types.forEach(val => {
                if(val.id == id){
                    ret = val.description;
                }
            });
            return ret;
        },
        setLeaveType(id){
            this.leave_types.forEach(val => {
                if(val.id == id){
                    this.leave_type = {'id':val.id, 'des' : val.description, 'num':val.number_of_days };
                    this.availeave = val.number_of_days - (this.checkConsume(val.id) + this.checkBorrow(val.id));
                }
            });
        },

        setLeaveUsable(id){
            this.leave_types.forEach((vl,idx) => {
                if(vl.id == id){
                    this.borrow = {'id':vl.id, 'des' : vl.description, 'num':vl.number_of_days };
                    this.availuse= vl.number_of_days - (this.checkConsume(vl.id) + this.checkBorrow(vl.id));

                }
            });
        },
        checkConsume(id){
            let ret = 0;
            this.consumeleave.forEach(val=>{
                if(id == val.leave_type_id){
                    ret += val.leave;
                }
            });

            return ret;
        },
        checkBorrow(id){
            let ret = 0;
            this.borrowleave.forEach(val=>{
                if(id == val.leave_type_id){
                    ret += val.credits;
                }
            });

            return ret;
        },
        formatDate(da){
            let d = new Date(da);
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();
            return  month+ "-" + day  + "-" + year;
        },
        extractLeave(data){
            let ret = [];
            data.forEach(val => {
                if(val.usable == 1){
                    ret.push(val);
                }
            });

            return ret;
        },
  
        listLeave(url='api/cancelled-leave'){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.tableData.draw ++;
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                    let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.leaves = data.data.data;
                        this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    }
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
                this.listleave();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        extractStatus(id){
            return id == 1 ? "Cancelled" : "";
        },
        extractExt(num){
            return num == 1 ? "AM" : "PM";
        },
        confirmDeleteLeave(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.post('api/leave-deleted/',this.leave).then(res=>{
                    this.$emit('show',{'message':'Leave Application Deleted!', 'status':4});
                    this.listLeave();
                    this.getConsumeLeave();
                     $('.delete-leave').modal("hide");
                });
            });
        },
        getConsumeLeave(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/consume-leave').then(res=>{
                    this.consumeleave = res.data;
                });
            });
        },
        getBorrowLeave(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/borrow-leave').then(res=>{
                    this.borrowleave = res.data;
                });
            });
        },

    },
    mounted() {
        this.listLeaveType();
        this.listLeave();
        this.getConsumeLeave();
        this.getBorrowLeave();
        if(window.Laravel.isLoggedin){
            this.user = window.Laravel.user;
            this.auth = true;
        }
    },
}
</script>

<style>

</style>
