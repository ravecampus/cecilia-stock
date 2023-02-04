<template>
    	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar d-print-none">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="#">
					<span class="align-middle">{{ title }}&nbsp;</span>
					<small>(Employee)</small>
        		</a>
		
				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Pages
					</li>

					<li class="sidebar-item">
                        <router-link class="sidebar-link" :to="{'name':'homed'}">
                            <i class="align-middle" data-feather="home"></i> <span class="align-middle">Home</span>
                        </router-link>
					</li>

					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'profile'}">
                            <i class="align-middle" data-feather="user"></i> <span class="align-middle">Profile</span>
                        </router-link >
					</li>
					<li class="sidebar-header">
						Employee Leave
					</li>
					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'leaveapp'}">
                            <i class="align-middle" data-feather="send"></i> <span class="align-middle">Leave Application</span>
						</router-link>
					</li>

					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'leavecredit'}">
              				<i class="align-middle" data-feather="clipboard"></i> <span class="align-middle">Leave Credits</span>
						</router-link>
					</li>

					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'leavehistory'}">
							<i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Leave History</span>
						</router-link>
					</li>
					<li class="sidebar-item">
						<router-link class="sidebar-link" :to="{name:'cancelledleave'}">
							<i class="align-middle" data-feather="file-minus"></i> <span class="align-middle">Cancelled Leave</span>
						</router-link>
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
									<span class="indicator" v-if="notifications.length > 0">{{notifications.length}}</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header" v-if="notifications.length > 0">
									<span>{{ notifications.length }}</span> New Notifications
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item" v-for="(list, idx) in notifications" :key="idx" @click="receivedNotification(list)">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-success fa fa-file-text-o"></i>
											</div>
											<div class="col-10">
												<div class="text-dark"><strong>{{ list.leave.ref_number }}</strong></div>
												<!-- <div class="text-muted small mt-1">{{ list.user.first_name }} {{ list.user.last_name }}</div> -->
												<div class="text-success small mt-1"><strong> {{ list.type.description }}</strong></div>
												<small>
													<timeago :datetime="list.created_at"/>
												</small>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Notifications</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
						<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                            <i class="align-middle" data-feather="settings"></i>
                        </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <img :src="'/../img/profile.png'" class="avatar img-fluid rounded me-1" /> <span class="text-dark">{{ user.first_name+" "+user.last_name }}</span>
                            </a>
							<div class="dropdown-menu dropdown-menu-end">
								<router-link class="dropdown-item" :to="{name:'profile'}"><i class="align-middle me-1" data-feather="user"></i> Profile</router-link>
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
							<!-- <ul class="list-inline">
								<li class="list-inline-item">
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
								</li>
							</ul> -->
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
			auth:false,
			user:{},
			notifications:[],
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
				this.$axios.get('api/emp-notify').then(res=>{
					this.notifications = res.data;
				});
			});
		},
		receivedNotification(data){
			this.$axios.get('sanctum/csrf-cookie').then(response=>{
				this.$axios.post('api/change-notify', data).then(res=>{
					this.getNotification();
					this.$router.push({name:'viewleave', params:{'id':data.id}});
				});
			});
		}
	},
	mounted() {
        this.title = window.Title.app_name;
         if(window.Laravel.isLoggedin){
            this.user = window.Laravel.user;
			this.auth = true;
			this.getNotification();
        }
	},
}
</script>

<style>

</style>
