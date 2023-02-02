<template>
     <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Leave Information</h1>

            <div class="row">
                <div class="col-md-12 d-flex">
                    <div class="w-100">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title">Leave Application</h5>
                                            </div>

                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle fa fa-send"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <h1 class="mt-1 mb-3">{{ employees.length }}</h1> -->
                                        <div class="mb-0">
                                            <router-link class="btn btn-sm btn-success btn-block" :to="{name:'leaveapp'}">
                                                view <i class="fa fa-arrow-right"></i> 
                                            </router-link>
                                         
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title">Leave Credits</h5>
                                            </div>

                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle fa fa-clipboard"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <h1 class="mt-1 mb-3">{{ leavestatus.length }}</h1> -->
                                        <div class="mb-0">
                                             <router-link class="btn btn-sm btn-success btn-block" :to="{name:'leavecredit'}">
                                                view <i class="fa fa-arrow-right"></i> 
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title">Leave History</h5>
                                            </div>

                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle fa fa-file-text"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <h1 class="mt-1 mb-3">{{ leaves.length }}</h1> -->
                                        <div class="mb-0">
                                           <router-link class="btn btn-sm btn-success btn-block" :to="{name:'leavehistory'}">
                                                view <i class="fa fa-arrow-right"></i> 
                                            </router-link>
                                        </div>
                                    </div>
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
            leaves:[],
            employees:[],
            leavestatus:[]
        }
    },
    methods: {
        // employeesData(){
        //     this.$axios.get('sanctum/csrf-cookie').then(response=>{
        //         this.$axios.get('api/emp-count').then(res=>{
        //             this.employees = res.data;
        //         });
        //     });
        // },
        // leavesData(){
        //     this.$axios.get('sanctum/csrf-cookie').then(response=>{
        //         this.$axios.get('api/lev-count').then(res=>{
        //             this.leaves = res.data;
        //         });
        //     });
        // },
        // leavestatusData(){
        //     this.$axios.get('sanctum/csrf-cookie').then(response=>{
        //         this.$axios.get('api/levstat-count').then(res=>{
        //             this.leavestatus = res.data;
        //         });
        //     });
        // },
    },
    mounted() {
        // this.employeesData();
        // this.leavesData()
        // this.leavestatusData();
    },
}
</script>

<style>

</style>
