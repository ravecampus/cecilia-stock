<template>
    
<main class="content">
    <div class="container-fluid p-0">

        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Profile</h1>
            <!-- <a class="badge bg-dark text-white ms-2" href="upgrade-to-pro.html">
                Get more page examples
            </a> -->
        </div>
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Profile Details</h5>
                    </div>
                    <div class="card-body text-center">
                        <img :src="'../img/profile.png'" alt="Christina Mason" class="img-fluid mb-2" width="128" height="128" />
                        <h5 class="card-title mb-0">{{ post.first_name }} {{ post.middle }} {{ post.last_name }}</h5>
                        <div class="text-muted mb-2">{{ pos_name }}</div>

                        <div>
                            <a class="btn btn-success btn-sm" @click="changePassword()" href="#">Change Password?</a>
                            <!-- <a class="btn btn-primary btn-sm" href="#"><span data-feather="message-square"></span> Message</a> -->
                        </div>
                    </div>
                    <!-- <hr class="my-0" />
                    <div class="card-body">
                        <h5 class="h6 card-title">Skills</h5>
                        <a href="#" class="badge bg-primary me-1 my-1">HTML</a>
                        <a href="#" class="badge bg-primary me-1 my-1">JavaScript</a>
                        <a href="#" class="badge bg-primary me-1 my-1">Sass</a>
                        <a href="#" class="badge bg-primary me-1 my-1">Angular</a>
                        <a href="#" class="badge bg-primary me-1 my-1">Vue</a>
                        <a href="#" class="badge bg-primary me-1 my-1">React</a>
                        <a href="#" class="badge bg-primary me-1 my-1">Redux</a>
                        <a href="#" class="badge bg-primary me-1 my-1">UI</a>
                        <a href="#" class="badge bg-primary me-1 my-1">UX</a>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <h5 class="h6 card-title">About</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span> Lives in <a href="#">San Francisco, SA</a></li>

                            <li class="mb-1"><span data-feather="briefcase" class="feather-sm me-1"></span> Works at <a href="#">GitHub</a></li>
                            <li class="mb-1"><span data-feather="map-pin" class="feather-sm me-1"></span> From <a href="#">Boston</a></li>
                        </ul>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <h5 class="h6 card-title">Elsewhere</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-1"><a href="#">staciehall.co</a></li>
                            <li class="mb-1"><a href="#">Twitter</a></li>
                            <li class="mb-1"><a href="#">Facebook</a></li>
                            <li class="mb-1"><a href="#">Instagram</a></li>
                            <li class="mb-1"><a href="#">LinkedIn</a></li>
                        </ul>
                    </div> -->
                </div>
            </div>

            <div class="col-md-8 col-xl-9">
                <div class="card">
                    <div class="card-header">

                        <h5 class="card-title mb-0">Profile Setting</h5>
                    </div>
                    <div class="card-body h-100">
                        
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
                               <button type="button" class="btn btn-success"  @click="saveUser()">{{ btncap }}</button>
                            </div>
                        </div>

                        <!-- <hr />
                        <div class="d-flex align-items-start">
                            <img src="img/avatars/avatar.jpg" width="36" height="36" class="rounded-circle me-2" alt="Charles Hall">
                            <div class="flex-grow-1">
                                <small class="float-end text-navy">30m ago</small>
                                <strong>Charles Hall</strong> posted something on <strong>Christina Mason</strong>'s timeline<br />
                                <small class="text-muted">Today 7:21 pm</small>

                                <div class="border text-sm text-muted p-2 mt-1">
                                    Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus
                                    pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
                                </div>

                                <a href="#" class="btn btn-sm btn-danger mt-1"><i class="feather-sm" data-feather="heart"></i> Like</a>
                            </div>
                        </div>
           
                        <hr />
                        <div class="d-grid">
                            <a href="#" class="btn btn-primary" >Load more</a>
                        </div> -->
                    </div>
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
                        <button type="button" @click="changeDataPass()" class="btn btn-success">{{ btncap_ }}</button>
                        <!-- <button type="button" @click="cancelButton()" class="btn btn-secondary btn-sm">No</button> -->
                    </div>
                </div>
            </div>
        </div>

</main>
</template>

<script>
export default {
    data(){
        return{
            errors:[],
            post:{},
            departments:[],
            positions:[],
            pos_name:"",
            btncap:"Save",
            btncap_:"Save",
        }
    },
    methods: {
        changeDataPass(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.btncap_ = "Saving...";
                this.$axios.post('api/change-password', this.post).then(res=>{
                    this.btncap_ = "Save";
                    this.$emit('show',{'message':'Password has been changed!', 'status':4});
                    let data = window.Laravel.user;
                    this.post = data;
                    $('.change-pass').modal('hide');
                }).catch(err=>{
                    this.btncap_ = "Save";
                    this.errors = err.response.data.errors
                });
            });
        },
        changePassword(data){
            $('.change-pass').modal('show');
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
        saveUser(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving...";
                    this.$axios.put('api/user/'+this.post.id, this.post).then(res=>{
                        this.btncap = "Save";
                        this.$emit('show',{'message':'Profile has been modified!', 'status':4});
                        this.errors = [];
                        let data = window.Laravel.user;
                        this.post = data;

                    }).catch(err=>{
                        this.btncap = "Save";
                        this.errors = err.response.data.errors
                    });
                });
            }
            
        },
    },
    mounted(){
        this.listOfDepartment();
        let data = window.Laravel.user;
        this.post = data;
       
    }
}
</script>

<style>

</style>
