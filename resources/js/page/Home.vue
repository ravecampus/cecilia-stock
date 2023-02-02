<template>
    <main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-6 col-lg-4 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2">CECLIA STOCK FARM INC.</h1>
							<p class="lead">
							Online Leave Management System
							</p>
						</div>

						<div class="card">
							<div class="card-body">
								
								<div class="m-sm-4">
									<div class="text-center">
										<img :src="'img/logo.png'" alt="Cecilia" class="img-fluid rounded-circle" width="132" height="132" />
									</div>
									<div class="alert alert-secondary p-0" v-if="errors.errs">
										<div class="alert errors-material m-0">
											<div v-if="errors.errs"><strong>*</strong>{{errors.errs[0]}}</div>
										</div>
									</div>
									<form>
										<div class="mb-3">
											<label class="form-label">Username</label>
											<input class="form-control form-control-lg" @keyup.enter="signin" type="text" v-model="post.username" placeholder="Enter your Username" />
											<span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
										</div>
										<div class="mb-3">
											<label class="form-label">Password</label>
											<input class="form-control form-control-lg"  @keyup.enter="signin" type="password" v-model="post.password" placeholder="Enter your password" />
											   <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
											<!-- <small>
											<a href="index.html">Forgot password?</a>
											</small> -->
										</div>
										<div>
											<label class="form-check">
												<input class="form-check-input" type="checkbox" value="remember-me" name="remember-me" checked>
												<span class="form-check-label">
												Remember me next time
												</span>
											</label>
										</div>
										<div class="text-center mt-3">
											<!-- <a href="index.html" class="btn btn-lg btn-success">Sign in</a> -->
											<button type="button"  @click="signin()" :disabled="btn_dis" class="btn btn-lg btn-success">{{ btn_txt }}</button>
										</div>
									</form>
								</div>
							</div>
						</div>

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
            post:{},
            errors:[],
            btn_txt:"Sign in",
            btn_dis: false
        }
    },
    methods:{
        signin(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.btn_txt = "Signing..."
                this.btn_dis = true;
                this.$axios.post('api/signin', this.post).then(res=>{
                    this.btn_txt = "Sign in"
                    this.btn_dis = false;
                    let data = res.data;
                    if(data.role == 1 ||data.role == 2){
                        window.location.href="/admin"
                    }else{
                        window.location.href="/user"
                    }
                  
                }).catch(err=>{
                    this.btn_dis = false;
                    this.btn_txt = "Sign in"
                    this.errors = err.response.data.errors
                });
            });
        },
        linkSignup(){
            this.$router.push({name:'signup'});
		},
		
    },
    mounted() {
        if(window.Laravel.isLoggedin){
            let user = window.Laravel.user;
            this.auth = true;
            if(user.role == 1 || user.role == 2){
                this.$router.push({name:'admin'})
            }else {
               this.$router.push({name:'user'})
			}
        }
	},
}
</script>

<style>

</style>
