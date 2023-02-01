<template>
    	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar d-print-none">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="#">
					<span class="align-middle">{{ title }}&nbsp;</span>
					<small>(Administrator)</small>
        		</a>
		
				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Pages
					</li>

					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'admindashboard'}">
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
						</router-link>
					</li>

					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{'name':'adminleaveapp'}">
							<i class="align-middle" data-feather="file"></i> <span class="align-middle">Leave Application</span>
						</router-link>
					</li>
					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{'name':'adminleavestatus'}">
							<i class="align-middle" data-feather="file-text"></i> <span class="align-middle">L.A. Status</span>
						</router-link>
					</li>
					<li class="sidebar-header">
						Settings
					</li>
					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'admindepartment'}">
                            <i class="align-middle" data-feather="layout"></i> <span class="align-middle">Department Setup</span>
						</router-link>
					</li>

					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'adminposition'}">
              				<i class="align-middle" data-feather="clipboard"></i> <span class="align-middle">Position Setup</span>
						</router-link>
					</li>

					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'adminleavetype'}">
							<i class="align-middle" data-feather="book"></i> <span class="align-middle">Leave Type Setup</span>
						</router-link>
					</li>
					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'adminusers'}">
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Employee Setup</span>
						</router-link>
					</li>
					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'administrator'}">
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Administrator</span>
						</router-link>
					</li>

					<li class="sidebar-header">
						Report
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="ui-buttons.html">
             		    	<i class="align-middle" data-feather="square"></i> <span class="align-middle">Report</span>
            			</a>
					</li>			
				</ul>
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg d-print-none">
				<a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									<span class="indicator" v-if="notes.length > 0">{{ notes.length }}</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									<span v-if="notes.length > 0">{{ notes.length }}</span> New Notifications
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item" v-for="(list, idx) in notes" :key="idx" @click="receivedNotification(list)">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-success fa fa-file-text-o"></i>
											</div>
											<div class="col-10">
												<div class="text-dark"><strong>{{ list.ref_number }}</strong></div>
												<div class="text-muted small mt-1">{{ list.user.first_name }} {{ list.user.last_name }}</div>
												<div class="text-success small mt-1"><strong> {{ list.type.description }}</strong></div>
											</div>
										</div>
									</a>
								
								</div>
								<div class="dropdown-menu-footer">
									<div class="text-muted">notifications</div>
								</div>
							</div>
						</li>
					
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                				<i class="align-middle" data-feather="settings"></i>
              				</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <img :src="'/../img/profile.png'" class="avatar img-fluid rounded me-1" /> 
								<span class="text-dark">{{ user.first_name+" "+user.last_name }}	
								</span>								
								
							  </a>
							<div class="dropdown-menu dropdown-menu-end">
								
								<div class="p-2">
									<span class="text-dark">{{ user.first_name+" "+user.last_name }}	
									</span>	
									<div class="small">{{ user.role == 1 ? "Supervisor" : "HR"}}</div>
								</div>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" @click="logout()" href="#">Log out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>
			<flashmessage :message="message" :status="status"></flashmessage>
			<main class="content">
				
				<router-view @show="flashMessage"></router-view>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="#" target="_blank"><strong>{{title }}</strong></a>								&copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<!-- <li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Help Center</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Terms</a>
								</li> -->
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

</template>

<script>
import FlashMessage from '../page/FlashMessage.vue';

export default {
	components:{
        flashmessage: FlashMessage,
  	},
	data(){
		return{
			message:'',
			title:'',
      		status:0,
			user:{},
			auth:false,
			notes:[],
		}
	},
	methods: {
		flashMessage(data){
             this.showMessage(data)
        },
        showMessage(data){
            this.message = data.message;
            if(data.status == 1){
                this.status = 'alert-success';
            }else if(data.status == 2){
                this.status = 'alert-warning';
            }else if(data.status == 3){
                this.status = 'alert-danger';
            }else if(data.status == 4){
                this.status = 'alert-secondary';
            }else if(data.status == 5){
                this.status = 'alert-dark';
            }else if(data.status == 6){
                this.status = 'alert-light';
            }else if(data.status == 7){
                this.status = 'alert-primary';
            }
            $('.fm-body').show();
            setTimeout(() => {
                $('.fm-body').fadeOut("slow");
            }, 500);
		},
		logout(){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } 
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
		getNotification(){
			this.$axios.get('sanctum/csrf-cookie').then(response=>{
				this.$axios.get('api/leave-notification').then(res=>{
					this.notes = res.data;
				});
			});
		},
		receivedNotification(data){
			this.$axios.get('sanctum/csrf-cookie').then(response=>{
				this.$axios.post('api/leave-received',data).then(res=>{
					this.getNotification()
					this.$router.push({name:'adminviewleave', params:{'id':data.id}})
				});
			});
		}
	},
	mounted() {
		this.getNotification();

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
