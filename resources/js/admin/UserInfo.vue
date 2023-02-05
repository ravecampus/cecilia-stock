<template>
    <div class="container-fluid p-0">
        <div class="text-center d-none d-print-block mb-5">
            <h4>{{ title }}</h4>
            <h6>Davao City, Philippines</h6>
           
        </div>
        <h1 class="h3 mb-3">Employee Information</h1>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body shadow" v-if="user.id != null">
                        <div class="row mb-4 mt-4" >
                         
                            <div class="col-md-4">
                                <label>First Name: </label>&nbsp;
                                <span class="bold h4"> {{ user.first_name }} </span>
                            </div>
                                <div class="col-md-4">
                                <label>Middle Name: </label>&nbsp;
                                <span class="bold h4"> {{ user.middle_name }} </span>
                            </div>
                            <div class="col-md-4">
                                <label>Last Name: </label>&nbsp;
                                <span class="bold h4"> {{ user.last_name }} </span>
                            </div>
                           
                            <!-- <div class="col-md-4">
                                <label>Name: </label>&nbsp;
                                <span class="bold h4"> {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}</span>
                            </div>
                            <div class="col-md-8">
                                <label>Position: </label>&nbsp;
                                <span class="bold h4"> {{ position.name }}</span>
                            </div> -->
                          
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Department: </label>&nbsp;
                                <span class="bold h4"> {{ user.department.name }}</span>
                            </div>
                            <div class="col-md-8">
                                <label>Position: </label>&nbsp;
                                <span class="bold h4"> {{ user.position.name }}</span>
                            </div>
                        </div>
                         <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Gender: </label>&nbsp;
                                <span class="bold h4"> {{ extractGender(user.gender) }}</span>
                            </div>
                            <div class="col-md-8">
                                <label>Contact: </label>&nbsp;
                                <span class="bold h4"> {{ user.contact }}</span>
                            </div>
                           
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <label>Address: </label>&nbsp;
                                <span class="bold h4"> {{ user.address }}</span>
                            </div>
                        </div>
                        <small class="mt-5">Leave Credits</small>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item" v-for="(ls,idx) in leave_credits" :key="idx">
                                        <div class="d-flex justify-content-between">
                                            <div><strong class="text-success">{{ ls.description }} </strong></div>
                                            <div>Credits:
                                                <strong >{{ ls.number_of_days }}</strong>
                                            </div>
                                            <div>Earned:
                                                <strong>{{ checkConsume(ls.id) + checkBorrow(ls.id) }}</strong>
                                            </div>
                                            <div>Available:
                                                <strong>{{ ls.number_of_days - (checkConsume(ls.id) + checkBorrow(ls.id)) }}</strong>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-print-none">
                                        <button type="button" @click="printCredit()" class="btn btn-success"><i class="fa fa-print"></i> Print</button>
                                    </li>
                                
                                </ul>
                            </div>
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
            post:{},
            errors:[],
            leave_credits:[],
            consumeleave:[],
            borrowleave:[],
            auth:false,
            title:""
        }
    },
    methods: {
        getLeaveCredits(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/leave-type-list').then(res=>{
                    this.leave_credits = res.data;
                });
            });
        },
        getEmployee(id){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/user/'+id).then(res=>{
                    this.user = res.data;
                    let data = res.data;
                    this.consumeleave = data.leave;
                    this.borrowleave = data.borrow;
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
        extractGender(num){
            return num == 1 ? "Male" : "Female";
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

    //    formatDate(da){
    //         let d = new Date(da);
    //         const day =("0" + d.getDate()).slice(-2);
    //         const month = ("0"+(d.getMonth()+1)).slice(-2);
    //         const year =  d.getFullYear();
    //         return  month+ "-" + day  + "-" + year;
    //     },
    //     extractExt(num){
    //         return num == 1 ? "AM" : "PM";
    //     },
    //     extractStatus(id){
    //         return id == 0 ? "Sent" : (id==1) ? "Received" :(id == 2) ? "Approved" :"Denied";
    //     },
    },
    mounted() {
        let id = this.$route.params.id;
        this.getEmployee(id);
        this.getLeaveCredits();

         this.title = window.Title.app_name;
        if(window.Laravel.isLoggedin){
            // this.user = window.Laravel.user;
            this.auth = true;
        }
    },
}
</script>

<style>

</style>
