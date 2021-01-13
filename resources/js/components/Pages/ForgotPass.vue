<template>
    <div class="log-section d-flex align-items-center">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">

                    <h1>Reset Password</h1> 

                    <!-- <form> -->
                        <div class="input-container">
                            <label for="email">E-mail Address</label>
                            <input v-model="email">
                            <transition name="fade">
                                <span class="invalid-text" v-if="msg.email">{{ msg.email }}</span>
                            </transition>
                            <span class="invalid-text">&nbsp</span>
                        </div>

                        <div class="input-container">
                            <button class="active-btn" @click="sendForgotPass">Send Password Reset Link</button>
                        </div>
                    <!-- </form> -->
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
        data() {
            return {
                email: '',
                errorMsg: '',
                msg: [],
                loading: false,
                page_loader_svg: '/svg/loader.svg',

                
                reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
            }   
        },

        methods: {
            isEmailValid: function() {
                return (this.email == "") ? "" : (this.reg.test(this.email)) ? '' : 'Invalid Email Address';
            },

            sendForgotPass: function() {
                const self = this;
                self.loading = true;
                if (!self.email) {
                    self.msg['email'] = 'Please provide an E-mail Address';
                    self.loading = false;
                }
                else if (!self.reg.test(self.email)) {
                    self.msg['email'] = 'Invalid E-mail Address';
                    this.loading = false;
                }
                else {
                    self.loading = true;
                    // axios.post('api/send-reset', {
                    //     email: self.email
                    // })
                    // .then(response => {
                    //     self.show_form = false;
                    //     self.show_success = true;
                    //     self.loading = false;
                    // })
                    // .catch(errors => console.log(errors));
                    axios.post('password/email', {
                        email: self.email
                    })
                    .then(response => {
                        self.show_form = false;
                        self.show_success = true;
                        self.loading = false;
                    })
                    .catch(errors => console.log(errors));

                    self.show_success = true;
                    self.loading = false;
                }

                setTimeout(function() {
                    self.msg = [];
                }, 2000); // hide the message after 2 seconds

            }

        },
    }
</script>