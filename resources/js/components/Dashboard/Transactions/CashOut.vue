<template>
<!-- CASH OUT MODAL -->
<transition name="CashOutModal">
    <div class="modal-section">
        <div class="modal-wrapper">

            <!-- CASH OUT FIRST VIEW -->
            <div class="modal-container" v-if="show_form">
                <div class="title-container">
                    <h1>Cash Out</h1>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <div class="group">
                            <label for="method">Choose method</label>
                            <select v-model="method">
                                <option :value="null" disabled hidden >Select Method</option>
                                <option value="1">Union Bank </option>
                                <!-- <option value="2">Coins.ph</option> -->
                                <!-- <option value="3">Bitcoin</option> -->
                                <!-- <option value="4">Bitshares</option> -->
                            </select>
                        </div>
                        <div class="group" style="margin-bottom: 25px;">
                            <label for="amount">Amount</label>
                            <input id="amount" type="text" class="amount" name="amount" v-model="amount" placeholder="0.00" @input="AmountCheck">
                            <span style="color:#000; float: left"><small>Transaction Fee: {{ addCommas(transaction_fee) }} PHP</small></span>
                            <span v-if="msg.amount">{{msg.amount}}</span>
                        </div>
                        <div class="group">
                            <label for="memo">Remarks</label>
                            <input id="memo" type="text" name="memo" v-model="memo"  >
                        </div>
                        <button 
                        :disabled='!CashOutStatus' 
                        class="active-btn" 
                        type="submit"
                        @click="show_details=true; 
                                show_form=false;">
                            Proceed
                        </button>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </slot>
                </div>
            </div>

            <!-- CASH OUT CONFIRMATION VIEW -->
            <div class="modal-container" v-if="show_details">
                <div class="title-container">
                    <h1>Confirm Details</h1>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <!-- <form @submit.prevent="CashOut"> -->
                            <div class="group">
                                <label for="method">Choose method</label>
                                <input id="method" type="text" name="method" v-model="method"  disabled autocomplete="off" hidden>
                                <p v-if="method == 1">Union Bank</p>
                            </div>

                            <div class="group">
                                <label for="amount">Amount</label>
                                <input id="amount" type="text" class="amount" name="amount" v-model="amount" disabled autocomplete="off" hidden>
                                <p>{{ amount }}</p>
                            </div>

                            <div class="group">
                                <label for="memo">Remarks</label>
                                <input id="memo" type="text" name="memo" v-model="memo" disabled autocomplete="off" hidden>
                                <p>{{ memo }}</p>
                            </div>
                            <button :disabled='isDisabled' class="active-btn" type="submit" @click="show_steps = true; show_details = false">Proceed</button>
                        <!-- </form> -->
                        <!-- <button class="cancel-btn" @click="$emit('close')">Close</button> -->
                        <button class="cancel-btn" @click="show_form=true; show_details=false">Back</button>
                    </slot>
                </div>
            </div>
            
            <!-- CASH OUT INSTRUCTIONS -->
            <div class="modal-container-for-CI" v-if="show_steps">
                <div class="title-container">
                    <h1>Instructions</h1>
                </div>
                <div class="modal-body">
                    <div class="group">
                        <label>Lorem1</label>
                        <p>Fugiat officiis consequuntur ipsa facere animi magnam delectus harum, non, ducimus illum nulla ratione beatae atque deleniti porro error fugit, veniam quisquam?</p>
                    </div>
                    <div class="group">
                        <label>Lorem</label>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum, optio quisquam. Animi tempore, accusamus voluptate odio enim earum facere delectus explicabo corporis, vero quia, expedita a similique sint. Aut, facere.</p>
                    </div>
                    <div class="group-btn">
                        <button type="submit" class="active-btn" @click="CashOut">Submit</button>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </div>
                </div>
            </div>

            <!-- CASH OUT SUCCESS VIEW -->
            <div class="modal-container success" v-if="show_status">
                <p class="title">
                    <b>Cash Out</b>
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
                            <p>Cash Out</p>
                        </div>
                        <div class="right">
                            <label>Via</label>
                            <p>Union Bank</p>
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
                            <p class="orange">PENDING</p>
                        </div>
                    </div>

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
                amount: '',
                memo: '',
                method: '',
                balance: '',
                transaction_fee: 7,
                right_arrow: '/svg/right-arrow.svg',
                page_loader_svg: '/svg/loader.svg',
                success_svg: '/svg/success.svg',
                msg: [],
                errors: [],
                utility_companies: [],
                message: [],
                loading:false ,
                show_steps: false,
                show_status: false,
                show_form: true,
                show_details: false
            }
        },
        created(){
            axios.get('./api/wallet').then(response => this.balance = parseFloat(response.data.data[0].balance));
            
        },
        computed:{
            CashOutStatus(){
                const self = this;
                if(!self.method || !self.amount){
                    return false;
                }else if(self.balance <= self.amount){
                    return false;
                }else{
                    return true;
                }
            }
        },
        methods: {
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
            AmountCheck(){
                const self = this;
                self.amount = self.amount.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                if(self.balance < (self.amount + self.transaction_fee)){
                    self.msg['amount'] = "Insufficient Balance";
                }else{
                    self.msg['amount'] = "";
                }

            },

            CashOut(){
                const self = this;
                self.show_details = false;
                self.loading = true;
                if (!self.amount || self.amount <= 0) {
                    self.msg['amount'] = 'Amount required.';                    
                } else {
                    self.loading=true;
                    axios.post('./api/cash-out',{
                        amount:self.amount,
                        memo:self.memo,
                        meth:self.method,
                        transaction_fee: self.transaction_fee,
                        type: 'CO'
                    }).then(response => {
                        self.show_steps = false;
                        self.viewTransaction(response.data.message);

                    }).catch(err => {
                        self.show_steps = false;
                        self.message = err.response.data.message;
                        self.loading=false;
                        self.show_steps = true;
                    })
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