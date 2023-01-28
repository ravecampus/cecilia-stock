<template>
    <div class="container-fluid p-0">
        <div class="text-center d-none d-print-block mb-5">
            <h4>{{ title }}</h4>
            <h6>Davao City, Philippines</h6>
           
        </div>
        
        <h1 class="h3 mb-3">Leave Credits</h1>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted d-print-none">Leave Credits Infomation</h6> 
                        <div class="d-none d-print-block">
                            <h6 class="mb-1"><strong>{{ user.first_name + " "+user.middle_name+" "+user.last_name}}</strong> </h6>
                            <div>{{ dep_name+" ("+ pos_name+") " }}</div>
                        </div>
                        <hr class="mt-0 d-none d-print-block">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" v-for="(ls,idx) in leave_credits" :key="idx">
                                <div class="d-flex justify-content-between">
                                    <div><strong class="text-success">{{ ls.description }} </strong></div>
                                    <div>Credits
                                        <span class="badge bg-success badge-pill">{{ ls.number_of_days }}</span>
                                    </div>
                                    <div>Earned
                                         <span class="badge bg-primary badge-pill">{{ checkConsume(ls.id) + checkBorrow(ls.id) }}</span>
                                    </div>
                                    <div>Available
                                         <span class="badge bg-warning badge-pill">{{ ls.number_of_days - (checkConsume(ls.id) + checkBorrow(ls.id)) }}</span>
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
                departments:[],
                positions:[],
                pos_name:"",
                dep_name:"",
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
        },
        mounted() {
            this.getLeaveCredits();
            this.getConsumeLeave();
            this.getBorrowLeave();
            this.listOfDepartment()
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
