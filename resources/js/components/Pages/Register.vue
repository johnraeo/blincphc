<template>
<div class="log-section d-flex align-items-center">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <h1>Sign Up</h1>

                    <div class="input-container">
                        <label for="email">E-Mail</label>
                        <input id="email" type="text" class=" @error('email') is-invalid @enderror" v-model="email" autocomplete="off" autofocus placeholder="Email" @input="EmailCheck">
                        <transition name="fade">
                            <span class="invalid-text" v-if="msg.email">{{ msg.email }}</span>
                        </transition>
                        <span class="invalid-text" v-if="!msg.email">&nbsp</span>
                    </div>

                    <div class="input-container">
                        <label for="password">Password</label>
                        <input id="input_password" type="password" class=" @error('password') is-invalid @enderror" v-model="password"  autocomplete="current-password" placeholder="Password" @input="PasswordCheck">
                        <img class="eye_password" src="/svg/show_eye_password.svg" @click="showPassword()">
                        <transition name="fade">       
                            <span class="invalid-text" v-if="msg.password"> {{ msg.password }} </span>
                        </transition>
                        <span class="invalid-text" v-if="!msg.password">&nbsp</span>
                    </div>

                    <div class="input-container">
                        <label for="password">Confirm Password</label>
                        <input id="password_confirmation" type="password" v-model="password_confirmation"  placeholder="Confirm Password" @input="PasswordCheck">
                        <img class="eye_password2" src="/svg/show_eye_password.svg" @click="showPassword2()">
                    </div>

                    <div class="input-container">
                        <button type="submit" 
                         class="active-btn" @click="Register">
                            Sign up
                        </button>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                            <p hidden>Or continue via</p>
                    </div>

                    <div class="d-flex justify-content-first" hidden>
                        <img src="/svg/facebook.svg" hidden>
                        <img src="/svg/twitter.svg" hidden>
                    </div>
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
    data(){
        return{
            email: '',
            password: '',
            password_confirmation: '',
            msg: [],
            page_loader_svg: '/svg/loader.svg',
            loading: false,
        }   
    },
    computed: {
        EmailCheck(){
            const self = this;
            if(!self.email){
                self.msg['email'] = '';
            }else if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/.test(self.email)){
                self.msg['email'] = '';
            }else{
                self.msg['email'] = "Invalid Email";
            }
        },
        PasswordCheck(){
            const self = this;
            var re = new RegExp("(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}");
            if(!self.password || !self.password_confirmation){
                self.msg['password'] = '';
            }else if(self.password.length < 8){
                self.msg['password'] = 'Must contain at least 8 characters';
            }else if(!re.test(self.password)){
                self.msg['password'] = "Must contain at least one number and one uppercase and lowercase letter";
            }else if (self.password != self.password_confirmation){
                self.msg['password'] = "Password Mismatch";
            }else{
                self.msg['password'] = '';
            }
        },
        RegisterStatus(){
            console.log(self.msg)
            if(!self.email || 
                !self.password || 
                !self.password_confirmation){
                return false;
            }else if (self.msg['email'] && self.msg['password']){
                return false;
            }else if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/.test(self.email)){
                return true;
            }
        }
    },
    methods: {
        showPassword() {
            const eyePassword = document.querySelector('.eye_password');
            const inputPassword = document.querySelector('#input_password');
            if(inputPassword.type == 'password') {
                inputPassword.type = 'text'
            }
            else {
                inputPassword.type = 'password'
            }
        },
        showPassword2() {
            const eyePassword2 = document.querySelector('.eye_password2');
            const inputPassword2 = document.querySelector('#password_confirmation');
            if(inputPassword2.type == 'password') {
                inputPassword2.type = 'text'
            }
            else {
                inputPassword2.type = 'password'
            }
        },
        Register() {
            const self = this;
            var re = new RegExp("(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}");
            self.loading = true;
            if(!self.email) {
                self.msg['email'] = "Email required";
                self.loading = false;
            }else if(!self.password){
                self.msg['password'] = "Password required";
                self.loading = false;
            }else if(self.password.length < 8){
                self.msg['password'] = 'Must contain at least 8 characters';
                self.loading = false;
            }else if(!re.test(self.password)){
                self.msg['password'] = "Must contain at least one number and one uppercase and lowercase letter";
                self.loading = false;
            }else if(self.password != self.password_confirmation){
                self.msg['password'] = "Password Mismatch";
                self.loading = false;
            }else{
                console.log("registering");
                axios.post('/register',{
                    email:self.email,
                    password:self.password,
                    password_confirmation:self.password_confirmation
                }).then(response => {   
                    window.location.replace('/login');
                }).catch(err => {
                    self.msg['email'] = err.response.data.errors.email[0];
                    self.loading = false;
                })
            }

            setTimeout(function() {
                self.msg = [];
            }, 2000); // hide the message after 2 seconds
        }
    }
}
</script>