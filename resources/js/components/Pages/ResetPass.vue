<template>
    <div class="log-section d-flex align-items-center">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">

                    <span class="invalid-text" v-if="errorMsg">{{ errorMsg }}</span>

                    <h1>Reset Password</h1> 

                    <!-- <form> -->
                        <div class="input-container">
                            <label for="login">E-mail Address</label>
                            <input v-model="email">
                            <span class="invalid-text">{{ isEmailValid() }}</span>
                        </div>

                        <div class="input-container">
                            <label for="login">New password</label>
                            <input id="input_password" class="eye_password" type="password" v-model="password">
                            <img class="eye_password" src="/svg/show_eye_password.svg" @click="showPassword()">
                            <!-- <span class="invalid-text">{{ isEmailValid() }}</span> -->
                        </div>

                        <div class="input-container">
                            <label for="login">Confirm new password</label>
                            <input id="input_password2" class="eye_password2" type="password" v-model="password2">
                            <img class="eye_password2" src="/svg/show_eye_password.svg" @click="showPassword2()">
                            <!-- <span class="invalid-text">{{ isEmailValid() }}</span> -->
                        </div>

                        <div class="input-container">
                            <button class="active-btn">Reset Password</button>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                errorMsg: '',
                password: '',
                password2: '',
                
                reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
            }   
        },

        methods: {
            isEmailValid: function() {
                return (this.email == "") ? "" : (this.reg.test(this.email)) ? '' : 'Invalid Email Address';
            },

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
                const inputPassword2 = document.querySelector('#input_password2');
                if(inputPassword2.type == 'password') {
                    inputPassword2.type = 'text'
                }
                else {
                    inputPassword2.type = 'password'
                }
            }

        },
    }
</script>