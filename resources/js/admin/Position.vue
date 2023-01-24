<template>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Positions</h1>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">List of Position</h5>
                    </div>
                    <div class="card-body">
                    <div class="d-flex justify-content-between">
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" v-model="tableData.search"  @input="listOfPosition()" placeholder="Search....">
                    </div>
                    <div class="btn-group">
                        <button type="button" @click="showModal()" class="btn btn-primary">
                            <i class="fa fa-plus"></i> ADD
                        </button>
                    </div>
                    </div>

                    <hr>
                    <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                        <tbody>
                            <tr class="tr-shadow" v-for="(list, idx) in positions" :key="idx">
                                <td class="desc">{{ list.name}}</td>
                                <td class="desc">{{ list.department }}</td>
                                <td>
                                    <div class="table-data-feature">
                                       
                                        <button class="btn btn-warning btn-sm" data-toggle="tooltip" @click="editPosition(list)" title="Edit">
                                            <i class="fa fa-pencil"></i> Edit
                                        </button>
                                        
                                    </div>
                                </td>
                            </tr>
                            <tr> 
                                <td colspan="6" v-show="!noData(positions)">
                                    No Result Found!
                                </td>
                            </tr>

                            <tr class="spacer"></tr>
                            
                        </tbody>
                    </data-table>
                    <div class="pull-right">
                        <pagination :pagination="pagination"
                            @prev="listOfDepartment(pagination.prevPageUrl)"
                            @next="listOfDepartment(pagination.nextPageUrl)"
                            v-show="noData(positions)">
                        </pagination>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade position" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4>Position</h4>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                             
                                <div class="row mb-3">
                                    <div class="form-group mb-3">
                                        <label>Name</label>
                                        <input type="text" v-model="post.name" class="form-control form-control-user" placeholder="Enter Position">
                                        <span class="errors-material" v-if="errors.name">{{errors.name[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select v-model="post.department_id" class="form-control">
                                            <option v-for="(lst,idx) in departments" :key="idx" :value="lst.id">{{ lst.name }}</option>
                                        </select>
                                        <span class="errors-material" v-if="errors.name">{{errors.name[0]}}</span>
                                    </div>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="button" @click="savePosition()" class="btn btn-success">{{ btncap }}</button>
                        <!-- <button type="button" @click="cancelButton()" class="btn btn-secondary btn-sm">No</button> -->
                    </div>
                </div>
            </div>
        </div>

	</div>
</template>

<script>

import DataTable from '../table/DataTable'
import PaginationVue from '../table/Pagination';

export default {
    components:{
        dataTable:DataTable,
        pagination:PaginationVue
    },

    data(){
        
        let sortOrders = {};
        let columns =[
        {label:'Name', name:'name'},
        {label:'Department', name:'department'},
        {label:'Action', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return {
            errors:[],
            post:{},
            btncap:"Save",
            departments:[],
            positions:[],
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
                filter:1,
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
        editPosition(data){
            this.post = data;
            $('.position').modal('show');
        },
        showModal(){
            this.post = {};
            $('.position').modal('show');
        },
        savePosition(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving...";
                    this.$axios.put('api/position/'+this.post.id, this.post).then(res=>{
                        this.btncap = "Save";
                        this.$emit('show',{'message':'Position has been modified!', 'status':4});
                        this.post ={};
                        this.errors = [];
                        $('.position').modal('hide');
                        this.listOfPosition();
                    }).catch(err=>{
                        this.btncap = "Save";
                        this.errors = err.response.data.errors

                    });
                });
            }else{
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving...";
                    this.$axios.post('api/position', this.post).then(res=>{
                        this.btncap = "Save";
                        this.$emit('show',{'message':'Position has been saved!', 'status':4});
                        this.post ={};
                        this.errors = [];
                        $('.position').modal('hide');
                        this.listOfPosition();
                    }).catch(err=>{
                        this.btncap = "Save";
                        this.errors = err.response.data.errors

                    });
                });
            }
            
        },
        listOfPosition(urls='api/position'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.positions = data.data.data;
                        this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    }
                
                }).catch(err=>{
                
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
                this.listOfPosition();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        listOfDepartment(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-department').then(res=>{
                    this.departments = res.data;
                
                });
            });
        },
    },
    mounted() {
        this.listOfDepartment();
        this.listOfPosition();
    },
}
</script>

<style>

</style>
