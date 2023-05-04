<template>
    <div class="container-fluid p-0">
        <div class="text-center d-none d-print-block mb-5">
            <h4>{{ title }}</h4>
            <h6>Davao City, Philippines</h6>
        </div>
        <!-- <h1 class="h3 mb-3 d-print-none">Report</h1> -->
        <button type="button" class="btn btn-sm btn-success d-print-none" @click="print()">
            <i class="fa fa-print"></i> Print
        </button>
        <hr>
        <div class="row" v-if="user.id != undefined">
            <div class="col-12 row">
                <div class="col-12 mb-2">
                    <label>Name: </label>&nbsp;
                    <span class="bold h4"> {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}</span>
                </div>
                <div class="col-12 mb-3">
                    <label>Position: </label>&nbsp;
                    <span class="bold h4"> {{ position.name }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div class="mb-2"> 
                        <label>Ref. #: </label>&nbsp;
                        <span class="bold h4"> {{ leave.ref_number }} </span>
                    </div>
                    <div>
                        <label>Date: </label>&nbsp;
                        <span class="bold h4"> {{ formatDate(leave.created_at) }} </span>
                    </div>
                </div>
                 <div class="col-12 mb-2">
                    <label>Apply for: </label>&nbsp;
                    <span class="bold h4"> {{ leave_type.description }}</span>
                </div>
                <div class="col-12 mb-2">
                    <label>Duration: </label>&nbsp;
                    <span class="bold h4" v-if="borrow.credits != null"> {{ leave.leave + borrow.credits}} day/s</span>
                    <span class="bold h4" v-else> {{ leave.leave }} day/s</span>
                </div>
                <div class="col-12 mb-2">
                    <label>Date Range: </label>&nbsp;
                    <span class="bold h4">{{ formatDate(leave.date_from)+" "+extractExt(leave.ext_from) }} - {{ formatDate(leave.date_to)+" "+extractExt(leave.ext_to) }}</span>
                </div>
                <div class="col-md-12 mb-2">
                    <label>Reason: </label>&nbsp;
                    <span class="h4"> {{ leave.state_reason }}</span>
                </div>
                <div class="col-md-12 mb-2">
                    <label>Remarks: </label>&nbsp;
                    <span class="h4"> {{ leave.remarks }} day/s</span>
                </div>
                <hr>
                <div class="col-md-12">
                    <small>Status:</small>
                    <h4>{{ extractStatus(leave.status) }} by {{ leave.approvern.first_name }}  {{ leave.approvern.last_name }} <small>({{ leave.approvern.role == 1 ? "Supervisor" : "HR"}})</small> </h4>
                    <p>Feedback: {{ leave.feedback }}</p> 
                </div>
                
            </div>
        </div>
     </div>
</template>

<script>
export default {
    data(){
        return{
            title:"",
            leave:{
                approvern:{}
            },
            user:{},
            position:{},
            leave_type:{},
            borrow:{},
            post:{},
            errors:[],
        }
    },
    methods: {
        getEmoloyeeLeave(){
            let id = this.$route.params.id;
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/leave/'+id).then(res=>{
                    this.leave = res.data;
                    this.user = res.data.user;
                    this.position = res.data.user.position;
                    this.leave_type = res.data.type;
                    this.borrow = res.data.borrow;
                });
            });
       },
        formatDate(da){
            let d = new Date(da);
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();
            return  month+ "-" + day  + "-" + year;
        },
        extractExt(num){
            return num == 1 ? "AM" : "PM";
        },
        extractStatus(id){
            return id == 0 ? "Sent" : (id==1) ? "Received" :(id == 2) ? "Approved" :"Denied";
        },
        print(){
            window.print();
        }
        
    },
    mounted() {
      
        this.getEmoloyeeLeave();
        this.title = window.Title.app_name;
        setTimeout(() => {
            window.print();
        }, 2000);
    },
}
</script>

<style>

</style>
