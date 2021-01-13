<template>
    <div class="log-section d-flex align-items-center">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">

                    <h1>Login</h1> 
                    <h1>{{ message }}</h1>
                    <form @submit.prevent="Login">
                        <div class="input-container">
                            <label for="login">Email</label>
                            <input type="text" v-model="login" placeholder="johndoe@blinc.ph">
                            <transition name="fade">
                                <span class="invalid-text" v-if="msg.login">{{ msg.login }}</span>
                            </transition>
                            <span class="invalid-text" v-if="!msg.login">&nbsp</span>
                        </div>

                        <div class="input-container">
                            <label for="password">Password</label>
                            <input id="input_password" type="password" v-model="password" autocomplete="current-password" placeholder="Password">
                            <img @click="showPassword()" class="eye_password" src="/svg/show_eye_password.svg">
                            <transition name="fade">
                                <span class="invalid-text" v-if="msg.password">{{ msg.password }}</span>
                            </transition>
                            <span class="invalid-text" v-if="!msg.password">&nbsp</span>
                        </div>

                        <div class="input-container">
                            <button class="active-btn" type="submit">Login</button>
                        </div>

                        <div class="d-flex justify-content-between">
                            <p hidden>Or continue via</p>
                            <router-link to="reset"><u>Forgot password</u></router-link>
                        </div>

                        <div class="d-flex justify-content-first" hidden>
                            <img src="/svg/facebook.svg" hidden>
                            <img src="/svg/twitter.svg" hidden>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- LOADING -->
        <div class="page_loader" id="page_loader" v-if="loading">
            <div class="page_loader_cont">
                <img :src="page_loader_svg" > 
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            message: {
                type: String,
            }
        },
        data(){
            return{
                login: '',
                password: '',
                loading: false,
                page_loader_svg: '/svg/loader.svg',
                msg: [],
            }   
        },
        computed: {
            
        },

        methods: {
            showPassword() {
                const eyePassword = document.querySelector('.eye_password');
                const inputPassword = document.querySelector('#input_password');

                if(inputPassword.type == 'password') {
                    inputPassword.type = 'text';
                }
                else {
                    inputPassword.type = 'password';
                }
            },

            Login() {
                const self = this;
                self.loading = true;
                if(self.login == '') {
                    self.msg['login'] = "Email required";
                    self.loading = false;
                }else if(self.password == ''){
                    self.msg['password'] = "Password required";
                    self.loading = false;
                }else{
                    console.log("logging in");
                    axios.post('/login',{
                        login:self.login,
                        password:self.password,
                    }).then(response => {   
                        window.location.replace('/home');
                    }).catch(err => {
                        self.msg['login'] = err.response.data.message;
                        self.loading = false;
                    })
                }

                setTimeout(function() {
                    self.msg = [];
                }, 2000); // hide the message after 2 seconds
            }
        },
    }
</script>