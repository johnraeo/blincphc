<template>
<!-- CASH IN MODAL -->
<transition name="CashInModal">
    <div class="modal-section">
        <div class="modal-wrapper">

            <!-- CASH IN FIRST SECOND VIEW -->
            <div class="modal-container" v-if="show_form">
                <div class="title-container">
                    <h1>Cash In</h1>
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
                        <div class="group">
                            <label for="amount">Amount</label>
                            <input id="amount" type="text" class="amount" name="amount" v-model="amount" placeholder="0.00" :class="{ change_color: hasAmountError}" @input="AmountCheck" autocomplete="off">
                            <span v-if="msg.amount">{{msg.amount}}</span>
                        </div>
                        <div class="group">
                            <label for="memo">Remarks</label>
                            <input id="memo" type="text" name="memo" v-model="memo"   autocomplete="off">
                        </div>
                        <button :disabled='isDisabled' class="active-btn" @click="show_details=true;show_form=false;">Proceed</button>
                        <button class="cancel-btn" @click="$emit('close')">
                            Close
                        </button>
                    </slot>
                </div>
            </div>

            <!-- CASH IN CONFIRMATION VIEW -->
            <div class="modal-container" v-if="show_details">
                <div class="title-container">
                    <h1>Confirm Details</h1>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <!-- <form @submit.prevent="CashIn"> -->
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

            <!-- CASH IN INSTRUCTIONS -->
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
                        <button type="submit" class="active-btn" @click="CashIn">Submit</button>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </div>
                </div>
            </div>

            <!-- CASH IN SUCCESS VIEW -->
            <div class="modal-container success" v-if="show_status">
                <img :src="success_svg" > 
                <p class="title"><b> {{ message }} </b></p>
                <button type="submit" class="active-btn"  value="Back" @click="show_form=true; show_status=false" v-if="error" autocomplete="off">Back</button>
                <button class="cancel-btn" @click="$emit('close'); reloadOnClose()">Close</button>
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
                errors: [],
                msg: [],
                utility_companies: [],
                message: [],
                page_loader_svg: '/svg/loader.svg',
                success_svg: '/svg/success.svg',
                isDisabled: true,
                error: false,
                show_steps: false,
                show_form: true,
                show_status: false,
                show_details: false,
                hasAmountError: false,
                loading:false ,
            }
        },

        methods: {
            AmountCheck(){
                this.amount = this.amount.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                this.validNumber(this.amount);
            },

            CashIn(){
                this.show_details = false;
                this.loading = true;
                if (!this.amount || this.amount <= 0) {
                    this.msg['amount'] = 'Amount required.';                    
                } else if (this.validNumber(this.amount)){
                    this.msg['amount'] = 'Invalid Amount';
                } else {
                    this.loading=true;
                    axios.post('./api/cash-in',{
                        amount:this.amount,
                        memo:this.memo,
                        meth:this.method,   
                        type: 'CI'
                    }).then(response => {
                        this.success = true;
                        this.loading=false;
                        this.message = response.data.message;
                        this.show_status = true;
                        this.show_steps = false;
                    }).catch(err => {
                        this.message = err.response.data.message;
                        this.loading=false;
                        this.show_status = true;
                        this.show_steps = false;
                    })
                }
            },

            validNumber(value){
                if(isNaN(value)){
                    this.msg['amount'] = 'Invalid Amount';
                    this.isDisabled = true;
                    this.hasAmountError = true;
                }else{
                    if(!value){
                        this.msg['amount'] = '';
                        this.isDisabled = true;        
                    }else{
                        axios.get('./api/balance').then(response => this.balance = response.data);

                        if(!this.msg['method']){
                            this.msg['amount'] = '';
                            this.hasAmountError = false;
                            this.isDisabled = false;
                        }else{
                            this.isDisabled = false;
                            this.hasAmountError = false;
                        }
                    }
                }
            },

            reloadOnClose() {
                location.reload();
            }
        }
    }
</script>