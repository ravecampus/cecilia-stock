<template>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Employees</h1>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">List of Employee</h5>
                    </div>
                    <div class="card-body">
                    <div class="d-flex justify-content-between">
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" v-model="tableData.search"  @input="listOfUser()" placeholder="Search....">
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
                            <tr class="tr-shadow" v-for="(list, idx) in users" :key="idx">
                                <td class="text-primary">
                                    <router-link :to="{name:'adminuser', params:{'id':list.id}}">
                                        <strong>{{ list.last_name }}, {{list.first_name}} {{ list.middle_name }}
                                        </strong>
                                    </router-link>
                                </td>
                                <td class="desc">{{ extractGender(list.gender) }}</td>
                                <td class="desc">{{ list.department }}</td>
                                <td class="desc">{{ list.position }}</td>
                                <td class="text-secondary">{{ formatDate(list.created_at) }}</td>
                                <td>
                                    <div class="table-data-feature">
                                        <button class="btn btn-success btn-sm" @click="changePassword(list)" data-toggle="tooltip" title="Change Password">
                                            <i class="fa fa-asterisk"></i> Change Password
                                        </button>
                                        <button class="btn btn-warning btn-sm" data-toggle="tooltip" @click="editUser(list)" title="Edit">
                                            <i class="fa fa-pencil"></i> Edit
                                        </button>
                                        
                                    </div>
                                </td>
                            </tr>
                            <tr> 
                                <td colspan="6" v-show="!noData(users)">
                                    No Result Found!
                                </td>
                            </tr>

                            <tr class="spacer"></tr>
                            
                        </tbody>
                    </data-table>
                    <div class="pull-right">
                        <pagination :pagination="pagination"
                            @prev="listOfUser(pagination.prevPageUrl)"
                            @next="listOfUser(pagination.nextPageUrl)"
                            v-show="noData(users)">
                        </pagination>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade user" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4>Employee</h4>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <small>Information</small>
                                <hr class="mt-0">
                                <div class="row mb-3">
                                    <div class="form-group col-md-4">
                                        <label>First name</label>
                                        <input type="text" v-model="post.first_name" class="form-control form-control-user" placeholder="Enter First name">
                                        <span class="errors-material" v-if="errors.first_name">{{errors.first_name[0]}}</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Middle name</label>
                                        <input type="text" v-model="post.middle_name" class="form-control form-control-user" placeholder="Enter Middle name">
                                        <span class="errors-material" v-if="errors.middle_name">{{errors.middle_name[0]}}</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Last name</label>
                                        <input type="text" v-model="post.last_name" class="form-control form-control-user" placeholder="Enter Last name">
                                        <span class="errors-material" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-md-4">
                                        <label>Gender</label>
                                        <select class="form-control" v-model="post.gender">
                                            <option :value="1">Male</option>
                                            <option :value="2">Female</option>
                                        </select>
                                        <span class="errors-material" v-if="errors.first_name">{{errors.first_name[0]}}</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Department</label>
                                        <select class="form-control" v-model="post.department_id" @change="setPosition(post.department_id)">
                                            <option v-for="(ls, idx) in departments" :key="idx" :value="ls.id">{{ ls.name }}</option>
                                        </select>
                                        <span class="errors-material" v-if="errors.department_id">{{errors.department_id[0]}}</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Position</label>
                                        <select class="form-control" v-model="post.position_id">
                                            <option v-for="(ls, idx) in positions" :key="idx" :value="ls.id">{{ ls.name }}</option>
                                        </select>
                                        <span class="errors-material" v-if="errors.position_id">{{errors.position_id[0]}}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-md-7">
                                        <label>Address</label>
                                        <input type="text" v-model="post.address" class="form-control form-control-user" placeholder="Enter Address">
                                        <span class="errors-material" v-if="errors.address">{{errors.address[0]}}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-md-7">
                                        <label>Contact</label>
                                        <input type="text" v-model="post.contact" class="form-control form-control-user" placeholder="Enter Contact">
                                        <span class="errors-material" v-if="errors.contact">{{errors.contact[0]}}</span>
                                    </div>
                                  
                                </div>
                                <small>Account</small>
                                <hr class="mt-0">
                                
                                 <div class="row mb-3">
                                    <div class="form-group col-md-6">
                                        <label>Username</label>
                                        <input type="text" v-model="post.username" class="form-control form-control-user" placeholder="Enter Username">
                                        <span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
                                    </div>
                                 </div>
                                <div class="row mb-3" v-if="post.id == undefined">
                                    <div class="form-group col-md-6">
                                        <label>Password</label>
                                        <input type="password" v-model="post.password" class="form-control form-control-user" placeholder="Enter Password">
                                        <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Password Confirmation</label>
                                        <input type="password" v-model="post.password_confirmation" class="form-control form-control-user" placeholder="Enter Password Confirmation">
                                        <span class="errors-material" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="button" @click="saveUser()" class="btn btn-success">{{ btncap }}</button>
                        <!-- <button type="button" @click="cancelButton()" class="btn btn-secondary btn-sm">No</button> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade change-pass">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4>Change Password <small>{{ post.last_name }}</small></h4>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                               <div class="form-group mb-3">
                                    <label>New Password</label>
                                    <input type="password" v-model="post.password" class="form-control form-control-user" placeholder="Enter Password">
                                    <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                                </div>
                                 <div class="form-group mb-3">
                                    <label>Password Confirmation</label>
                                    <input type="password" v-model="post.password_confirmation" class="form-control form-control-user" placeholder="Enter Password Confirmation">
                                    <span class="errors-material" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="button" @click="changeDataPass()" class="btn btn-success">{{ btncap }}</button>
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
        {label:'Name', name:'last_name'},
        {label:'Gender', name:'gender'},
        {label:'Department', name:'department'},
        {label:'Position', name:'position'},
        {label:'Date Created', name:null},
        {label:'Action', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return {
            errors:[],
            post:{},
            btncap:"Save",
            users:[],
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
        changeDataPass(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.btncap = "Saving...";
                this.$axios.post('api/change-password', this.post).then(res=>{
                    this.btncap = "Save";
                    this.$emit('show',{'message':'Password has been changed!', 'status':4});
                    this.post ={};
                    this.errors = [];
                    $('.change-pass').modal('hide');
                    this.listOfUser();
                }).catch(err=>{
                    this.btncap = "Save";
                    this.errors = err.response.data.errors
                });
            });
        },
        changePassword(data){
            this.post = data;
            $('.change-pass').modal('show');
        },
        editUser(data){
            this.post = data;
            this.setPosition(data.department_id);
            $('.user').modal('show');
        },
        showModal(){
            this.post = {};
            $('.user').modal('show');
        },
        saveUser(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving...";
                    this.$axios.put('api/user/'+this.post.id, this.post).then(res=>{
                        this.btncap = "Save";
                        this.$emit('show',{'message':'Employee has been modified!', 'status':4});
                        this.post = {};
                        this.errors = [];
                        $('.user').modal('hide');
                        this.listOfUser();
                    }).catch(err=>{
                        this.btncap = "Save";
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving...";
                    this.$axios.post('api/user', this.post).then(res=>{
                        this.btncap = "Save";
                        this.$emit('show',{'message':'Employee has been saved!', 'status':4});
                        this.post ={};
                        this.errors = [];
                        $('.user').modal('hide');
                        this.listOfUser();
                    }).catch(err=>{
                        this.btncap = "Save";
                        this.errors = err.response.data.errors

                    });
                });
            }
            
        },
        listOfUser(urls='api/user'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.users = data.data.data;
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
                this.listOfUser();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
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
        listOfDepartment(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-department').then(res=>{
                    this.departments = res.data;
                
                });
            });
        },   
        setPosition(id){
            this.departments.forEach(val => {
                if(val.id == id){
                    this.positions = val.positions;
                }
            });
        }  

    },
    mounted() {
        this.listOfDepartment();
        this.listOfUser();

    },
}
</script>

<style>

</style>
