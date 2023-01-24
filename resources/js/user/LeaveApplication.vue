<template>
      <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Leave Application</h1>
        
        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Request your Leave Application</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Leave for:</label>
                            <select class="form-control" v-model="post.leave_type" @change="setLeaveType(post.leave_type)">
                                <option v-for="(lst, idx) in leave_types" :key="idx" :value="lst.id" >{{ lst.description }}</option>
                                
                            </select>
                            <span class="bg-success p-1 mt-1 text-white" v-if="availeave >= 0">{{ availeave + " credit/s available" }}</span>
                            <span class="errors-material" v-if="errors.leave_type">{{errors.leave_type[0]}}</span>
                        </div>
                        <small>Incase of Emergency / Name of Sick</small>
                        <hr class="mt-0">
                        <div class="form-group mb-3">
                            <label>State Reason</label>
                            <textarea class="form-control" v-model="post.state_reason" placeholder="State Reason"></textarea>
                            <span class="errors-material" v-if="errors.state_reason">{{errors.state_reason[0]}}</span>
                        </div>
                        <small>Duration of Leave</small> <span class="bg-success p-1 text-white">{{ post.number_of_days }}</span>
                        <hr class="mt-0">
                        <div class="row mb-3">
                            <div class="form-group col-md-4 pr-0">
                                <label>From:</label>
                                <Datepicker v-model="post.date_from" placeholder="Date" :format="format"/>
                                <span class="errors-material" v-if="errors.date_from">{{errors.date_from[0]}}</span>
                                <span class="errors-material" v-if="errors.ext_from">{{errors.ext_from[0]}}</span>
                            </div>
                            <div class="form-group col-md-2 pl-0">
                                <label></label>
                                <select class="form-control form-control-lg" v-model="post.ext_from">
                                    <option :value="1">AM</option>
                                    <option :value="2">PM</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 pr-0">
                                <label>To:</label>
                                <Datepicker v-model="post.date_to" :input="checkLeaveDate(post)" placeholder="Date" :format="format"  />
                                <span class="errors-material" v-if="errors.date_to">{{errors.date_to[0]}}</span>
                                <span class="errors-material" v-if="errors.ext_to">{{errors.ext_to[0]}}</span>
                            </div>
                            <div class="form-group col-md-2 pl-0">
                                <label></label>
                                <select class="form-control form-control-lg" v-model="post.ext_to">
                                    <option :value="1">AM</option>
                                    <option :value="2">PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3" v-if="post.number_of_days > availeave"> 
                            <label class="mb-2">Leave credits is not enough! You can use atleast one leave type availability!</label>
                            <div>
                            <span v-for="(ls, idx) in usable" :key="idx"> 
                                <input type="radio" v-model="post.usable" @change="setLeaveUsable(post.usable)"  :value="ls.id"> {{ ls.description }} &nbsp;
                            </span> 
                            <span class="bg-success p-1 mt-1 text-white" v-if="availuse >= 0">
                                {{ availuse + " credits" }}
                                
                            </span>
                            </div>
                             
                        </div>
                        <hr>
                        <span class="bg-secondary p-1 mt-1 text-white" v-if="post.number_of_days > 0">
                               You will apply for <strong class="text-warning">{{ post.number_of_days }}</strong>  and your credits is <strong class="text-danger">{{ availuse + availeave }}</strong>
                                
                        </span>
                        <div class="form-group mb-3">
                            <label>Other Remarks</label>
                            <textarea class="form-control" v-model="post.remarks" placeholder="Remarks"></textarea>
                            <span class="errors-material" v-if="errors.remarks">{{errors.remarks[0]}}</span>
                        </div>
                        <button type="button" @click="sendRequest()" class="btn btn-success"><i class="fa fa-send"></i> {{ btncap }}</button>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <!-- <h5 class="card-title mb-0">List of Department</h5> -->
                    </div>
                    <div class="card-body">
                        <h6>Leave Information</h6>
                        <hr class="mt-0">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label><small>NAME :</small> <span class="text-success">{{ user.first_name+" "+user.middle_name + " "+user.last_name }}</span> </label>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label><small>LEAVE TYPE :</small> <span class="text-success">{{ leave_type.des }}</span> </label>
                            </div>
                            <div class="col-md-12 mb-1">
                                <p><small>REASON :</small> <span class="text-success">{{ post.state_reason }}</span> </p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label v-if="post.date_from != null && post.date_to != null"><small>DATE RANGE :</small> <span class="text-success">{{ formatDate(post.date_from) }} - {{ formatDate(post.date_to) }}</span> </label>
                                <hr class="m-0">
                                <label><small>DURATION OF LEAVE :</small> <strong class="text-success">{{ post.number_of_days }}</strong> </label>
                            </div>
                            <div class="col-md-12 mb-1">
                                <p><small>REMARKS :</small> <span class="text-success">{{ post.remarks }}</span> </p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label><small> YEARLY LEAVE CREDITS :</small> </label>
                                <hr class="mt-0">
                                <div>
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="(lis, idex) in extractLeave(leave_types)" :key="idex">{{ lis.description+" : "+lis.number_of_days }}</li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5>List of Application</h5>
                        <input type="text" class="form-control" v-model="tableData.search"  @input="listLeave()" placeholder="Search....">
                        <hr>
                        <data-table class="mt-5" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                            <tbody class="">
                                <tr class="shadow-sm p-3 m-2"  v-for="(list) in leaves" :key="list.id">
                                    <td><strong class="text-success">{{ extractLeaveType(list.leave_type_id) }}</strong>
                                    </td>
                                    <td><span class="status--process">{{ list.state_reason }}</span></td>
                                    <td>
                                        <small>{{ formatDate(list.date_from)+" "+extractExt(list.ext_from) }} - {{ formatDate(list.date_to)+" "+extractExt(list.ext_to) }}</small>
                                        <hr class="mt-0">
                                        <span><strong class="text-primary">{{ list.leave }}</strong></span>
                                        <div v-if="list.borrow != null"><i class="text-success" > consume credits from {{ extractLeaveType(list.borrow.leave_type_id) }} is {{ list.borrow.credits }}</i></div>
                                        <hr class="m-0">
                                        <div v-if="list.borrow != null" class="">Total of : {{ list.leave + list.borrow.credits }}</div>
                                    </td>
                                    <td><span>{{ list.remarks }}</span></td>
                                    <td><span class="text-muted">{{ extractStatus(list.status) }}</span></td>
                                    <td><span class="text-muted">{{ formatDate(list.created_at) }}</span></td>
                                    <td>                                           
                                        <button class="btn btn-danger btn-sm " data-toggle="tooltip" @click="cancelRequest(list)" title="Edit">
                                            <i class="fa fa-close"></i> Cancel
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

        
        <div class="modal fade cancel-leave">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h6>Cancel Leave Request</h6>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                Do you want to cancel your Leave Application?
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <div class="btn-group">
                            <button type="button" @click="confirmCanceLeave(post)" class="btn btn-danger">Yes</button>
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
        {label:'Leave Type', name:null},
        {label:'Reason', name:null},
        {label:'Duration', name:null},
        {label:'Remarks', name:null},
        {label:'Status', name:null},
        {label:'Date File', name:null},
        {label:'Action ', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            post:{},
            errors:[],
            usable:[],
            consumeleave:[],
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
        cancel(){
            $('.cancel-leave').modal("hide"); 
        },
        cancelRequest(data){
            this.leave = data;
            $('.cancel-leave').modal("show");
        },
        listLeaveType(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/leave-type-list').then(res=>{
                    this.leave_types = res.data;
                });
            });
        },
        checkLeaveDate(date){

            var from = new Date(date.date_from);
            var to = new Date(date.date_to);
                if(from.getTime() == to.getTime()){
                    if(this.post.ext_from == 1 && this.post.ext_to == 2 ){
                        this.post.number_of_days = 1;
                    }else{
                        this.post.number_of_days = 0.5;
                    }

                }
              if(from < to){
                    var weeks, date_diff, iAdjust = 0;
                    if (to < from) return -1; // error code if dates transposed
                    var week_from = from.getDay(); // day of week
                    var week_to = to.getDay();
                    week_from = (week_from == 0) ? 7 : week_from; // change Sunday from 0 to 7
                    week_to = (week_to == 0) ? 7 : week_to;
                    // if ((week_from > 5) && (week_to > 5)) iAdjust = 1; // adjustment if both days on weekend
                    // week_from = (week_from > 5) ? 5 : week_from; // only count weekdays
                    // week_to = (week_to > 5) ? 5 : week_to;

                    // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
                    weeks = Math.floor((to.getTime() - from.getTime()) / 604800000)

                    // if (week_from <= week_to) {
                    //     date_diff = (weeks * 5) + (week_to - week_from)
                    // } else {
                    //     date_diff = ((weeks + 1) * 5) - (week_from - week_to)
                    // }

                    if (week_from <= week_to) {
                        date_diff = (weeks * 7) + (week_to - week_from)
                    } else {
                        date_diff = ((weeks + 1) * 7) - (week_from - week_to)
                    }
                    date_diff -= iAdjust // take into account both days on weekend

                    // vm.showHoliday = [];

                    // // HOLIDAYS
                    // angular.forEach(holidays, function(v, k){
                    //   var holi_ = new Date(v.date_of_holiday);
                    //   if(holi_ >= from && holi_ <= to && holi_.getDay() != 0 && holi_.getDay() != 6) {
                    //     vm.viewholiday = true;
                    //     vm.showHoliday.push({val:v.date_of_holiday, des:v.description});
                    //     date_diff--;
                    //   }
                    // });

                    this.post.number_of_days =  (date_diff + 1) - (date.ext_to == 1 ? 0.5 : 0) - (date.ext_from == 2 ? 0.5 : 0); //
                
                }

                 if(this.post.number_of_days > this.availeave){

                        let id = this.leave_type.id;
                        this.usable = this.extractNonLeaveType(id);
                        return;
                 }


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
                    this.availeave = val.number_of_days - this.checkConsume(val.id);
                }
            });
        },

        setLeaveUsable(id){
            this.leave_types.forEach((vl,idx) => {
                if(vl.id == id){
                    this.borrow = {'id':vl.id, 'des' : vl.description, 'num':vl.number_of_days };
                    this.availuse= vl.number_of_days - this.checkConsume(vl.id);

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
        sendRequest(){
            if(this.post.number_of_days > this.availeave){
                this.post.credits = this.post.number_of_days - this.availeave;
                this.post.leave = this.availeave;
                this.post.borrow_id = this.borrow.id;
            }else{
                this.post.leave = this.post.number_of_days;
            }

            let avail = this.availeave + this.availuse;
            if(avail >= this.post.number_of_days){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Sending ...";
                    this.$axios.post('api/leave', this.post).then(res=>{
                        this.btncap = "Send";
                        this.errors = [];
                        this.post = {};
                        this.$emit('show',{'message':'Leave Application sent!', 'status':4});
                        this.listLeave();
                        this.getConsumeLeave();
                        this.availeave = 0;
                    }).catch(err=>{
                        this.btncap = "Send";
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.$emit('show',{'message':'Insufficient credits!', 'status':4});
            }    
            
        },

        listLeave(url='api/leave'){
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
            return id == 0 ? "Pending" : "Approved"
        },
        extractExt(num){
            return num == 1 ? "AM" : "PM";
        },
        confirmCanceLeave(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.delete('api/leave/'+this.leave.id).then(res=>{
                    this.$emit('show',{'message':'Leave Application cancelled!', 'status':4});
                    this.listLeave();
                    this.getConsumeLeave();
                     $('.cancel-leave').modal("hide");
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



    },
    mounted() {
        this.listLeaveType();
        this.listLeave();
        this.getConsumeLeave();
        if(window.Laravel.isLoggedin){
            this.user = window.Laravel.user;
            this.auth = true;
        }
    },
}
</script>

<style>

</style>
