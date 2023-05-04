<template>
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3 d-print-none">Leave List</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <label>Name: </label>&nbsp;
                                <span class="bold h3"> {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}</span>
                            </div>
                            <div>
                                <label>Gender: </label>&nbsp;
                                <span class="bold h3"> {{ extractGender(user.gender) }}</span>
                            </div>
                            <div>
                                <label>Position: </label>&nbsp;
                                <span class="bold h3"> {{ user.position.name }}</span>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-4">
                                <div class="input-group">
                                    <label>Year &nbsp;</label>
                                    <select v-model="post.year" class="form-control">
                                        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success" @click="filterYearData()" type="button">
                                            <i class="fa fa-filter"></i>
                                            Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>REF #</th>
                                                <th>TYPE</th>
                                                <th>DURATION</th>
                                                <th>YEAR</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(ls, idx) in leaves" :key="idx">
                                                <td>
                                                    <strong class="text-success">{{ ls.ref_number }}</strong>
                                                </td>
                                                <td>{{ ls.type.description }}</td>
                                                <td>
                                                    <span v-if="ls.borrow != null">
                                                    {{ ls.leave + ls.borrow.credits }}
                                                    </span>
                                                    <span v-else>
                                                    {{ ls.leave }}
                                                    </span>

                                                </td>
                                                <td>{{  new Date(ls.created_at).getFullYear() }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" @click="viewprintReport(ls)" class="btn btn-success btn-sm">Print view</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr> 
                                                <td colspan="5" v-show="!noData(leaves)">
                                                    No Result Found!
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
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
            user:{
                position:{},
            },
            post:{},
            leaves:[],
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
        getUser(){
            let id = this.$route.params.id;
            this.$axios.get('sanction/csrf-cookie').then(response=>{
                this.$axios.get('api/user/'+id).then(res=>{
                    this.user = res.data;
                    this.filterAction();
                })
            });
        },
        extractGender(num){
            return num == 1 ? "Male" : "Female";
        }, 
        filterYearData(){
            this.leaves = [];
            this.getUser();
        },
        filterAction(){
            let list = [];
            this.user.leaves.forEach(val => {
                let yr = new Date(val.created_at).getFullYear();
                if(this.post.year == yr && val.status != 1){
                    list.push(val);
                }
            });
            this.leaves = list;
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        viewprintReport(data){
            this.$router.push({name:'adminprintreport', params:{'id':data.id}});
        }
    },
    mounted() {
        this.getUser();
    },
}
</script>

<style>

</style>
