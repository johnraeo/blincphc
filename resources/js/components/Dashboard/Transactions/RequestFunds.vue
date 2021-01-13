<template>
<!-- MODAL FOR SEND FUNDS -->
<transition name="SendFundsModal" >
    <div class="modal-section">
        <div class="modal-wrapper">
            
            <!-- REQUEST FUNDS FIRST VIEW -->
            <div class="modal-container" v-if="show_form">
                <div class="title-container">
                    <h1>Request</h1>
                    <p>Balance: {{ balance }} PHP</p>
                </div>
                <div class="modal-body" >
                    <slot name="body">
                        <div class="group">
                            <label for="currency">Currency</label>
                            <select v-model="currency">
                                <option value=0>PHP</option>
                                <option value=1>BTS</option>
                            </select>
                            <span v-if="msg.currency">{{msg.currency}}</span>
                            <span v-if="!msg.currency">{{'\xa0'}}</span>
                        </div>
                        <div class="group" v-if="this.currency == 0">
                            <label for="senreq">Email</label>
                            <input type="text" v-model="senreq" :class="{ change_color: hasError }" @input="EmailCheck">
                            <img :src="loader_svg" class="loader" :class="{ show_loader: hasLoader }"> 
                            <span v-if="msg.senreq">{{msg.senreq}}</span>
                            <span v-if="!msg.senreq">{{'\xa0'}}</span>
                        </div>
                        <div class="group" v-else-if="this.currency==1">
                            <label>Account Name</label>
                            <input type="text" v-model="rcvr_account_name" placeholder="test-123" autocomplete="off">
                            <span v-if="msg.rcvr_account_name">{{msg.rcvr_account_name}}</span>
                            <span v-if="!msg.rcvr_account_name">{{'\xa0'}}</span>
                        </div>
                        <div class="group" v-if="this.currency == 0 && emailValid || this.currency == 1 && this.rcvr_account_name">
                            <label for="amount">Amount</label>
                            <input id="amount" type="text" class="amount" name="amount" v-model="amount" placeholder="0.00" :class="{ change_color: hasAmountError}" @input="AmountCheck" autocomplete="off">
                            <span v-if="msg.amount">{{msg.amount}}</span>
                            <span v-if="msg.amount">{{'\xa0'}}</span>
                        </div>
                        <div class="group" v-if="this.amount">
                            <label for="memo">Remarks(Optional)</label>
                            <input id="memo" type="text" name="memo" v-model="memo"  >
                        </div>
                        <button 
                            :disabled='!RequestStatus' 
                            class="active-btn" 
                            type="submit" 
                            @click="show_form=false; 
                                    show_details=true;" 
                            v-if="this.currency == 0 && emailValid">
                            Proceed
                        </button>
                        <button 
                            :disabled='!RequestStatus' 
                            class="active-btn" 
                            type="submit" 
                            @click="show_form=false; 
                                    show_details=true;" 
                            v-if="this.currency == 1">
                            Proceed
                        </button>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </slot> 
                </div>
            </div>
            <div class="modal-container" v-if="show_details">
                <div class="title-container">
                    <h1>Confirm Details</h1>
                </div>
                <div class="modal-body" >
                    <slot name="body">
                        <form @submit.prevent="SendReq">
                            <div class="group">
                                <label for="senreq">Email</label>
                                <input type="text" v-model="senreq" disabled hidden>
                                <p> {{ senreq }} </p>
                            </div>
                            <div class="group">
                                <label for="amount">Amount</label>
                                <input id="amount" type="text" name="amount" v-model="amount" disabled hidden>
                                <input id="currency" type="text" name="currency" v-model="currency" disabled hidden>
                                <p> {{ amount }} {{ convertValueOfCurrency(currency) }} </p>
                            </div>
                            <div class="group">
                                <label for="memo">Memo</label>
                                <input id="memo" type="text" name="memo" v-model="memo"  disabled hidden>
                                <p> {{ memo }} </p>
                            </div>
                            <button 
                            class="active-btn" 
                            type="submit" 
                            @click="success=true" 
                            v-if="this.currency == 0">
                                Submit
                            </button>
                        </form>
                        <button 
                            class="active-btn" 
                            type="submit" 
                            @click="show_details=false; 
                                    show_confirm=true;" 
                            v-if="this.currency==1">
                            Submit
                        </button>
                        <button class="cancel-btn" @click="show_form=true; show_details=false">Back</button>
                    </slot>
                </div>
            </div>
            <!-- REQUEST FUNDS SECOND VIEW -->
            <div class="modal-container success" v-if="show_status">
                <p class="title">
                    <b>Request Funds to {{transact.user.email}}</b>
                </p>
                <div class="modal-body">
                    <div class="tr-group">
                        <div class="left">
                            <label for="transact_id">Transaction ID</label>
                            <p>20200700{{transact.wallet_id}}0000{{ transact.transact_id }}</p>
                        </div>
                        <div class="right">
                            <label for="date">Date</label>
                            <p>{{ convertMonth(transact.transact_date.substring(5,7)) }} {{ transact.transact_date.substring(8,10) }}, {{ transact.transact_date.substring(0,4) }}</p>
                        </div>
                    </div>
                    <div class="tr-group">
                        <div class="left">
                            <label for="type">Type</label>
                            <p>Request</p>
                        </div>
                        <div class="right">
                            <label>To</label>
                            <p>{{ transact.user.email}}</p>
                        </div>
                    </div>
                    <div class="tr-group">
                        <div class="left">
                            <label for="amount">Amount</label>
                            <p>
                                {{ addCommas(transact.amount) }} 
                                <small>
                                {{ transact_type }}
                                </small>
                            </p>
                        </div>
                        <div class="right">
                            <label for="status">Status</label>
                            <p class="green">PENDING</p>
                        </div>
                    </div>
                    <button class="deny-btn" @click="processTransaction(transact.transact_id); show_status=true;" >Cancel</button>
                    <button class="cancel-btn" @click="show_details=false">Close</button>
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
                currency: null,
                senreq: '',
                amount: '',
                memo: '',
                balance: '',
                bts_balance: '',
                emailStatus: '',
                password: '',
                account_name: '',
                rcvr_account_name: '',
                transact: '',
                msg: [],
                loader_svg: '/svg/loader.svg',
                page_loader_svg: '/svg/loader.svg',
                success_svg: '/svg/success.svg',
                error_svg: '/svg/error.svg',
                hasError: false,
                hasAmountError: false,
                hasLoader: false,
                show_form: true,
                show_details: false,
                show_confirm: false,
                show_status: false,
                message: [],
                success: false,
                error: false,
                loading:true ,
                emailValid: false,
            }
        },

        created(){
            const self = this;
            axios.get('./api/wallet')
            .then(response => {
                self.balance = parseFloat(response.data.data[0].balance);
                if(response.data.data[1]){
                    self.account_name = response.data.data[1].account_name;
                    self.loading = false;
                }else{
                    self.loading = false;
                    self.bts_balance = null;
                }
            })
        },

        computed:{
            viewTransaction(transaction_id){
                const self = this;
                self.loading=true;
                axios.post('./api/view-transaction',{
                        id:transaction_id
                    })
                .then(response => {
                    self.transact = response.data;
                    self.loading = false;
                    if(self.transact.transact_type == "SF" || self.transact.transact_type == "RF"){
                        self.transact_type = self.transact.send_req.currency_type == 0 ? "PHP" : "BTS";
                    }else{
                        self.transact_type = "PHP";
                    }
                    self.show_status = true;

                })
                .catch(error => console.log(error));
            },
            RequestStatus(){
                const self = this;
                if (self.currency == 1){
                    if(!self.currency || !self.amount || !self.rcvr_account_name){
                        return false;
                    }else if(!self.rcvr_account_name){
                        return false;
                    }else if (self.amount > self.bts_balance){
                        return false;
                    }
                    return true;
                }else if(self.currency == 0){
                    if(!self.currency || !self.amount || !self.senreq){
                        return false;
                    }else if(self.amount > self.balance){
                        return false;
                    }
                    return true;
                }
            }
        },

        methods: {
            async processTransaction(transaction_id){
                const self = this;
                self.show_details = false;
                axios.post('./api/deny-request',{
                    id:transaction_id
                }).then(response => {
                    self.show_status = true;
                    self.loading=false;
                    self.show_details = false;
                    self.message = response.data.message;
                }).catch(err => {
                    self.message = err.response.data.message;
                    self.show_status = true;
                    self.loading=false;
                    self.show_details = false;
                })
            },
            EmailCheck(){
                const self = this;
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/.test(self.senreq)){
                    axios.post('./api/check-email',{
                        email:self.senreq
                    }).then(response => {   
                        self.loader_svg = '/svg/check.svg';
                        self.msg['senreq'] = '';
                        self.hasError = false;
                        self.emailValid = true;
                    }).catch(err => {
                        self.msg['senreq'] = err.response.data.message;
                        switch (err.response.status) {
                            case 404:
                                self.loader_svg = '/svg/loader.svg';
                                self.emailValid = false;
                                self.hasError = true;
                                self.msg['senreq'] = err.response.data.message;
                                break;
                            case 500:
                                self.msg['senreq'] = "Server Error";
                                break;
                            default:
                                self.msg['senreq'] = "Please Try Again Later";
                                break;
                        }
                    })
                }else{
                    if(!self.senreq){
                        self.hasLoader = false
                        self.hasError = false;
                        self.msg['senreq'] = '';
                    }else{
                        self.hasError = true;
                        self.hasLoader = true;
                        self.loader_svg = '/svg/loader.svg';
                        self.emailValid = false;
                        self.msg['senreq'] = 'Invalid Email Address';
                    }
                }
            },

            AmountCheck(){
                const self = this;
                self.amount = self.amount.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                if(!self.amount){
                    self.msg['amount'] = null;
                }else{
                    self.amount = parseFloat(self.amount);
                    if(isNaN(self.amount)){
                        self.msg['amount'] = 'Invalid Amount';
                        self.hasAmountError = true;
                    }else{
                        if(self.currency == 0){
                            if(!self.msg['senreq'] && !self.senreq){
                                self.msg['amount'] = '';
                                self.hasAmountError = false;
                                self.loading=false;
                            }else{
                                self.hasAmountError = false;
                                self.loading=false;
                            }
                        }else if(self.currency == 1){

                                self.msg['amount'] = null;
                                self.hasAmountError = false;

                        }
                    }
                }
            },
            
            SendReq(){
                const self = this;
                self.loading = true;
                if (!self.senreq) {
                    self.msg['senreq'] = 'Recipient required.';
                } else if (!self.amount || self.amount <= 0) {
                    self.msg['amount'] = 'Amount required.';                    
                } else {
                    axios.post('./api/send-funds',{
                        senreq:self.senreq,
                        amount:self.amount,
                        memo:self.memo,
                        currency:self.currency,
                        type: 'RF'
                    }).then(response => {
                        self.viewTransaction(response.data.message);
                    }).catch(err => {
                        // self.message = err.response.data.message;
                        self.show_details = false;
                        self.loading=false;
                        self.show_status = true;
                    })
                }
            },
            
            convertValueOfCurrency(value) {
                if(value == 0) {
                    return 'PHP'
                }
                else if(value == 1) {
                    return 'BTS'
                }
            },
            convertMonth(value) {
                switch(value) {
                    case '01':
                        return 'January';
                        break;
                    case '02': 
                        return 'February';
                        break;
                    case '03': 
                        return 'March';
                        break;
                    case '04': 
                        return 'April';
                        break;
                    case '05': 
                        return 'May';
                        break;
                    case '06': 
                        return 'June';
                        break;
                    case '07': 
                        return 'July';
                        break;
                    case '08': 
                        return 'August';
                        break;
                    case '09': 
                        return 'September';
                        break;
                    case '10': 
                        return 'October';
                        break;
                    case '11': 
                        return 'November';
                        break;
                    case '12': 
                        return 'December';
                        break;
                }
            },
            addCommas(x){
                return parseFloat(x).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            reloadOnClose() {
                location.reload();
            }
        }
    }
</script>