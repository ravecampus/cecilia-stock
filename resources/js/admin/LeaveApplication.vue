<template>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Employee Leave Application</h1>
        {{}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body shadow">
                        <input type="text" class="form-control" v-model="tableData.search"  @input="listLeave()" placeholder="Search....">
                        <hr>
                        <data-table class="mt-5" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                            <tbody class="">
                                <tr class="shadow-sm p-3 m-2"  v-for="(list) in leaves" :key="list.id">
                                    <td><strong class="text-primary">{{ list.ref_number }}</strong></td>
                                    <td><strong class="text-success">{{ extractLeaveType(list.leave_type_id) }}</strong></td>
                                    <!-- <td><span class="status--process">{{ list.state_reason }}</span></td> -->
                                    <td>
                                        <strong>{{ formatDate(list.date_from)+" "+extractExt(list.ext_from) }} - {{ formatDate(list.date_to)+" "+extractExt(list.ext_to) }}</strong>
                                        <hr class="mt-0">
                                        <span><strong class="text-primary">{{ list.leave }} day/s</strong></span>
                                        <div v-if="list.borrow != null"><i class="text-success" > consume credits from {{ extractLeaveType(list.borrow.leave_type_id) }} is</i> <strong>{{ list.borrow.credits }} day/s</strong></div>
                                        <hr class="m-0">
                                        <div v-if="list.borrow != null" class="">Total of : <strong>{{ list.leave + list.borrow.credits }}</strong></div>
                                    </td>
                                    <!-- <td><span>{{ list.remarks }}</span></td> -->
                                    <td><span class="text-muted"> <strong>{{ extractStatus(list.status) }}</strong></span></td>
                                    <td><span class="text-muted">{{ formatDate(list.updated_at) }}</span></td>
                                    <td>                                           
                                        <button class="btn btn-success btn-sm" data-toggle="tooltip" @click="viewLeaveApp(list)" title="View">
                                            <i class="fa fa-eye"></i> View
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
    </div>
</template>

<script>
import DataTable from '../table/DataTable';
import PaginationVue from '../table/Pagination';

export default {
 components:{
        dataTable:DataTable,
        pagination:PaginationVue
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
            user:{},
            auth:false,
            leaves:[],
            leave_types:[],
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
        listLeave(url='api/applications'){
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
        extractLeave(data){
            let ret = [];
            data.forEach(val => {
                if(val.usable == 1){
                    ret.push(val);
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
        viewLeaveApp(data){
            this.$router.push({name:'adminviewleave', params:{'id':data.id}});
        },
    },

    mounted() {
        this.listLeave();
        this.listLeaveType();
    },
}
</script>

<style>

</style>
