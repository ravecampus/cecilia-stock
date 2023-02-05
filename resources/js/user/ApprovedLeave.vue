<template>
    <div class="container-fluid p-0">
        <div class="text-center d-none d-print-block mb-5">
            <h4>{{ title }}</h4>
            <h6>Davao City, Philippines</h6>
           
        </div>
        
        <h1 class="h3 mb-3">Leave Appication</h1>
        
        <div class="row">
            <div class="col-md-12">
                <div class="" v-if="employee_leave.id == undefined">Loading...</div>
                <div class="card">
                    <div class="card-body" v-if="employee_leave.id != undefined">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-muted d-print-none">Leave Infomation</h6> 
                            <button type="button" @click="printCredit()" class="btn btn-success d-print-none"><i class="fa fa-print"></i> Print</button>
                        </div>
                        <hr>
                        <div class="">
                            <h5 class="mb-1">Name of Employee: <strong>{{ user.first_name + " "+user.middle_name+" "+user.last_name}}</strong> </h5>
                            <div>Position: {{  pos_name }}</div>
                        </div>
                        <hr class="mt-0 d-none d-print-block">
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-between">
                                <span>
                                    Reference #: <strong class="text-success">{{ employee_leave.ref_number }}</strong>
                                </span>
                                <div>
                                  <strong>Date: {{ formatDate(employee_leave.created_at) }}</strong>
                                </div>
                            </div>
                            <div class="col-md-12">
                                Apply for: <strong>{{ extractLeaveType(employee_leave.leave_type_id) }}</strong>
                            </div>
                            <div class="col-md-12">
                                Date Range: <strong>{{ formatDate(employee_leave.date_from) }} {{ extractExt(employee_leave.ext_from) }} - {{ formatDate(employee_leave.date_to) }} {{ extractExt(employee_leave.ext_to) }}  </strong>
                            </div>
                             <div class="col-md-12" v-if="employee_leave.borrow != null">
                                Duration: <strong>{{ employee_leave.leave  + employee_leave.borrow.credits}} day/s</strong>
                            </div>
                            <div class="col-md-12" v-else>
                                Duration: <strong>{{ employee_leave.leave }} day/s</strong>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush mb-2">
                           
                            <li class="list-group-item">
                               <span class="text-secondary">Reason:</span> {{ employee_leave.state_reason }}
                            </li>
                             <li class="list-group-item">
                                <span class="text-secondary"> Remarks:</span> {{ employee_leave.remarks }}
                            </li>
                            <li class="list-group-item">
                              
                            </li>
                          

                        </ul>
                            <div v-if="employee_leave.status == 3">
                                Denied by: {{ employee_leave.approvern.first_name }} {{ employee_leave.approvern.last_name }} <small>({{ employee_leave.approvern.role == 1 ? "Supervisor" : "HR"}})</small>
                            </div>
                             <div v-if="employee_leave.status == 2">
                                Approved by: {{ employee_leave.approvern.first_name }} {{ employee_leave.approvern.last_name }} <small>({{ employee_leave.approvern.role == 1 ? "Supervisor" : "HR"}})</small>
                            </div>
                             <div v-if="employee_leave.status >= 2">
                                Feedback: {{ employee_leave.feedback }}
                            </div>
                        <hr>
                        <div>
                               Status: 
                               <small>{{ formatDate(employee_leave.approve_date) }}</small>
                               <h3 :class="employee_leave.status == 2 ? 'text-success' :'text-danger'">{{ extractStatus(employee_leave.status) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return{
                leave_credits:[],
                consumeleave:[],
                borrowleave:[],
                user:{},
                title:"",
                leave_types:[],
                departments:[],
                positions:[],
                pos_name:"",
                dep_name:"",
                employee_leave:{},
            }
        },
        methods: {
            getEmployeeLeave(id){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.$axios.get('api/leave/'+id).then(res=>{
                        this.employee_leave = res.data;
                    });
                });
            },
            getLeaveCredits(){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.$axios.get('api/leave-type-list').then(res=>{
                        this.leave_credits = res.data;
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
            printCredit(){
                window.print();
            },
            listOfDepartment(){
                this.$axios.get('sanctum/csrf-cookie').then(response => {
                    this.$axios.get('api/list-department').then(res=>{
                        this.departments = res.data;
                        this.setPosition(window.Laravel.user.department_id);
                    });
                });
            },   
            setPosition(id){
                this.departments.forEach(val => {
                    if(val.id == id){
                        this.positions = val.positions;
                        this.dep_name = val.name;
                        this.xtractPosition(val.positions);
                    }
                });
            },
            xtractPosition(data){
                let id = window.Laravel.user.position_id;
                data.forEach(val => {
                    if(val.id == id){
                        this.pos_name = val.name;
                    }
                });
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
            listLeaveType(){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.$axios.get('api/leave-type-list').then(res=>{
                        this.leave_types = res.data;
                    });
                });
            },
            extractExt(num){
                return num == 1 ? "AM" : "PM";
            },
            formatDate(da){
                let d = new Date(da);
                const day =("0" + d.getDate()).slice(-2);
                const month = ("0"+(d.getMonth()+1)).slice(-2);
                const year =  d.getFullYear();
                return  month+ "-" + day  + "-" + year;
            },
            extractStatus(id){
                return id == 0 ? "Sent" : (id == 1) ? "Received" : (id == 2 ) ? "Approved" :"Denied";
            },
        },
        mounted() {
            this.getLeaveCredits();
            this.getConsumeLeave();
            this.getBorrowLeave();
            this.listOfDepartment()
            this.listLeaveType();
            let id = this.$route.params.id;
            this.getEmployeeLeave(id);
            this.title = window.Title.app_name;
             if(window.Laravel.isLoggedin){
                this.user = window.Laravel.user;
                this.auth = true;
            }
        },
    }
</script>

<style>

</style>
