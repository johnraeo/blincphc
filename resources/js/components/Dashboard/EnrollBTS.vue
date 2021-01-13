<template>
<!-- ENROLL BTS AND CONNECT BTS MODAL -->
<transition name="EnrollBTSModal">
    <div class="modal-section">
        <div class="modal-wrapper">
            <div class="modal-container col-md-6" v-if="show_form">
                <div class="this-modal">
                    <div class="bts-title-section">
                        <div class="title" v-bind:class="{ bts_active: isActive }" @click="isActive=!isActive">
                            <a> Create Account </a>
                        </div>
                        <div class="title" v-bind:class="{ bts_active: !isActive }" @click="isActive=!isActive">
                            <a> Connect Account </a>
                        </div>
                    </div>

                    <div v-if="isActive">
                        <div class="title-container">
                            <h1>Create your Bitshares Account</h1>
                        </div>

                        <!-- CREATE BITSHARES MODAL BODY -->
                        <div class="modal-body">
                            <slot name="body">
                                <form @submit.prevent="CreateBTS">
                                    <div class="group">
                                        <label for="create_wallet_name">Account</label>
                                        <input  type="text"
                                                placeholder="Example: bts-test32" 
                                                v-model="create_wallet_name" 
                                                v-bind:class="{ change_color: hasError }"
                                                @input="CheckAccountName">
                                        <img :src="loader_svg" class="loader" :class="{ show_loader: hasLoader }"> 
                                        <span v-if="msg.create_wallet_name">{{msg.create_wallet_name}}</span>
                                    </div>
                                    <div class="group">
                                        <div class="d-flex justify-content-between">
                                            <label for="create_password">Create Password</label>
                                            <label>Click to copy</label>
                                        </div>
                                        <!-- <label for="create_password">Create Password</label> -->
                                        <input type="text" id="create_password" v-model="create_password" readonly>
                                        <img src="/svg/copy.svg" v-on:click="copyPassword()">
                                    </div>
                                    <div class="group">
                                        <label for="node">Confirm password</label>
                                        <input type="text" v-model="create_confirm_password">
                                        <span v-if="!msg.crepas">{{'\xa0'}}</span>
                                        <span v-if="msg.crepas">{{msg.crepas}}</span>
                                    </div>

                                    <div class="checkboxes-group">
                                        <div class="group">
                                            <input class="checkbox-radio" type="checkbox" id="1" value="PasswordReminder" v-model="reminder">
                                            <label for="1">I understand that I will <b>LOSE ACCESS</b> to my funds if I <b>LOSE MY PASSWORD.</b></label>
                                        </div>
                                        <div class="group">
                                            <input class="checkbox-radio" type="checkbox" id="2" value="RecoverReminder" v-model="reminder">
                                            <label for="2">I understand that <b>NO ONE</b> can recover it.</label>
                                        </div>
                                        <div class="group">
                                            <input class="checkbox-radio" type="checkbox" id="3" value="StoredReminder" v-model="reminder">
                                            <label for="3">I have <b>WRITTEN DOWN</b> or otherwise <b>STORED</b> my password.</label>
                                        </div>
                                    </div>

                                    <button type="submit"  class="active-btn" :disabled='!CreateStatus'>Continue</button>
                                </form>
                                <button class="cancel-btn" @click="$emit('close')">
                                    Close
                                </button>
                            </slot>
                        </div>
                    </div>

                    <!-- CONNECT BITSHARES MODAL BODY -->
                    <div v-if="!isActive">
                        <div class="title-container">
                            <h1>Connect your Bitshares Account</h1>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                            <form @submit.prevent="ConnectBTS">
                                <div class="group">
                                    <label for="connect_wallet_name">Account</label>
                                    <input type="text" v-model="connect_wallet_name" v-bind:class="{ change_color: hasError }" >
                                    <span v-if="!msg.connect_wallet_name">{{'\xa0'}}</span>
                                    <span v-if="msg.connect_wallet_name">{{msg.connect_wallet_name}}</span>
                                </div>
                                <div class="group">
                                    <label for="password">Input Password</label>
                                    <input type="text" class="" v-model="connect_password"  >
                                </div>
                                <div class="group">
                                    <label for="node">Select Node</label>
                                    <select v-model="connect_node">
                                        <option value="1">'wss://node.testnet.bitshares.e'</option>
                                    </select>
                                </div>

                                <button type="submit"  class="active-btn" :disabled='!ConnectStatus'>Continue</button>
                            </form>
                            <button class="cancel-btn" @click="$emit('close')">
                                Close
                            </button>

                            </slot>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL SUCCESS VIEW -->
            <div class="modal-container col-md-6 success" v-if="show_status">
                <div class="this-modal">
                    <div class="title">
                        <h1>{{ message }}</h1>
                    </div>
                    <button class="cancel-btn" @click="$emit('close')">
                        Close
                    </button>
                </div>
            </div>
            
            <!-- LOADING IMG -->
            <div class="page_loader" id="page_loader" v-if="loading">
                <div class="page_loader_cont">
                    <img :src="page_loader_svg" > 
                </div>
            </div>
        </div>
    </div>
</transition> 
</template>

<script>
    export default {
        
        data(){
            return{
                create_wallet_name: '',
                create_password: '',
                create_confirm_password: '',
                connect_wallet_name: '',
                connect_password: '',
                connect_node: '',
                loader_svg: '/svg/loader.svg',
                page_loader_svg: '/svg/loader.svg',
                reminder: [],
                message: [],
                msg:[],
                loading:false,
                isActive: true,
                show_form: true,
                show_status: false,
                hasLoader: false,
                hasError: false,
                cwn_err: false,
            }
        },

        mounted() {
            const self = this;
            self.generatePassword();
        },

        computed:{
            CreateStatus(){
                const self = this;
                if(!self.create_wallet_name || self.reminder.length != 3 || !self.create_password){
                    return false;

                }else if(self.create_password != self.create_confirm_password){
                    self.msg['conpas'] = "Password Mismatch";
                    return false;
                }else{
                    return true;
                }
            },

            ConnectStatus(){
                const self = this;
                if(!self.connect_wallet_name  &&
                    !self.connect_password  &&
                    !self.connect_node  &&
                    !self.msg['connect_wallet_name']){
                    return false;
                }else{
                    return true;
                }
            }
        },

        methods:{
            CheckAccountName(){
                const self = this;
                self.create_wallet_name = self.create_wallet_name.toLowerCase();
                var re = new RegExp("^[a-z0-9-]+$");

                if(!self.create_wallet_name){
                    self.msg['create_wallet_name'] = '';
                    self.hasLoader = false;
                    self.hasError = false;
                }else if(self.create_wallet_name.length < 8){
                    self.msg['create_wallet_name'] = 'Must contain at least 8 characters';
                    self.hasLoader = true;
                    self.hasError = true;
                }else if(!re.test(self.create_wallet_name)){
                    self.msg['create_wallet_name'] = "Must contain only lowercase, numbers and '-'";
                    self.hasLoader = true;
                    self.hasError = true;
                }else {
                    self.msg['create_wallet_name'] = '';
                    axios.post('./api/check-account',{
                        account_name:self.create_wallet_name
                    }).then(response => {   
                        self.loader_svg = '/svg/check.svg';
                        self.msg['create_wallet_name'] = '';
                        self.hasError = false;
                    }).catch(err => {
                        self.msg['create_wallet_name'] = err.response.data.message;
                        switch (err.response.status) {
                            case 404:
                                self.loader_svg = '/svg/loader.svg';
                                self.hasError = true;
                                self.msg['create_wallet_name'] = err.response.data.message;
                                break;
                            case 500:
                                self.loader_svg = '/svg/loader.svg';
                                self.hasError = true;
                                self.msg['create_wallet_name'] = "Server Error";
                                break;
                            default:
                                self.loader_svg = '/svg/loader.svg';
                                self.hasError = true;
                                self.msg['create_wallet_name'] = "Please Try Again Later";
                                break;
                        }
                    })
                }
            },

            generatePassword(){
                const self = this;
                self.create_password = self.$blinc.generateRandomString(40);
            },

            async CreateBTS(){
                const self = this;
                self.loading = true;
                var result = '';
                if(self.create_password != self.create_confirm_password){
                    self.loading = false;
                    self.CreateStatus = false;
                    self.msg['create_confirm_password'] = 'Password Mismatch';
                }else{
                    result = await self.$blinc.createAccount(self.create_wallet_name, self.create_password);
                    if(result.error){
                        self.msg['create_wallet_name'] = 'Account name already exists';
                        this.loading=false;
                    }else{
                        axios.post('./api/link-wallet',{
                            type: 'BTS',
                            acct_name: self.create_wallet_name
                        }).then(response => {
                            self.show_form = false;
                            self.show_status = true;
                            self.loading = false;
                            self.message = response.data.message;
                        }).catch(err => {
                            self.show_form = false;
                            self.show_status = true;
                            self.loading = false;
                            self.message = err.response.message
                        })
                    }
                }
            },
            
            async ConnectBTS(){
                const self = this;
                self.loading = true;
                let result ={}
                try{
                    result = await self.$blinc.loginViaPassword(self.connect_wallet_name, self.connect_password);
                }catch(err){
                    result.error = err;
                }

                if(result.error){
                    self.msg['connect_wallet_name'] = "Invalid Account Name or Password";
                    self.loading=false;
                }else{

                    axios.post('./api/link-wallet',{
                        type: 'BTS',
                        acct_name: self.connect_wallet_name
                    }).then(response => {
                        self.show_form = false;
                        self.show_status = true;
                        self.loading = false;
                        self.message = response.data.message;
                    }).catch(err => {
                        self.show_form = false;
                        self.show_status = true;
                        self.loading = false;
                        self.message = err.response.message
                    })
                }
            }, 

            copyPassword() {
                const copyPassword = document.querySelector('#create_password');

                copyPassword.select();
                // copyPassword.setSelectionRange(0, 99999); // For mobile devices

                document.execCommand("copy");

                // alert('copied ' + copyPassword.value);
            }
        }
    }
</script>
