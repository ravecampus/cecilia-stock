<template>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Leave Application Request</h1>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body shadow" v-if="leave.id != null">
                        <div class="row mb-3 mt-4" >
                            <div class="col-md-12 mb-5 d-flex justify-content-between">
                                <div>
                                    <label>Ref. #: </label>&nbsp;
                                    <span class="bold h4"> {{ leave.ref_number }} </span>
                                </div>
                                 <div>
                                    <label>Date: </label>&nbsp;
                                    <span class="bold h4"> {{ formatDate(leave.created_at) }} </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Name: </label>&nbsp;
                                <span class="bold h4"> {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}</span>
                            </div>
                            <div class="col-md-8">
                                <label>Position: </label>&nbsp;
                                <span class="bold h4"> {{ position.name }}</span>
                            </div>
                          
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label>Apply for: </label>&nbsp;
                                <span class="bold h4"> {{ leave_type.description }}</span>
                            </div>
                            <div class="col-md-2">
                                <label>Duration: </label>&nbsp;
                                <span class="bold h4" v-if="borrow != null"> {{ leave.leave + borrow.credits}} day/s</span>
                                <span class="bold h4" v-else> {{ leave.leave }} day/s</span>
                            </div>
                            <div class="col-md-6">
                                <label>Date Range: </label>&nbsp;
                                <span class="bold h4">{{ formatDate(leave.date_from)+" "+extractExt(leave.ext_from) }} - {{ formatDate(leave.date_to)+" "+extractExt(leave.ext_to) }}</span>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-12 mb-3">
                                <label>Reason: </label>&nbsp;
                                <span class="h4"> {{ leave.state_reason }}</span>
                            </div>
                            <div class="col-md-12 mb-4">
                                <label>Remarks: </label>&nbsp;
                                <span class="h4"> {{ leave.remarks }} day/s</span>
                            </div>
                            <div class="col-md-12">
                                <label>Feedback: </label>&nbsp;
                                <textarea class="form-control" placeholder="Enter Feedback..."></textarea>
                            </div>
                           
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success">Approve</button>
                            <button type="button" class="btn btn-danger">Deny</button>
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
            leave:{},
            user:{},
            position:{},
            leave_type:{},
            borrow:{},
        }
    },
    methods: {
       getEmoloyeeLeave(id){
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
    },
    mounted() {
        let id = this.$route.params.id;
        this.getEmoloyeeLeave(id);
    },
}
</script>

<style>

</style>
