<template>
<!-- BILLS PAYMENT MODAL -->
<transition name="BillsPaymentModal">
    <div class="modal-section">
        <div class="modal-wrapper">

            <!-- BILLS PAYMENT FIRST VIEW -->


            <!-- BILLS PAYMENT SECOND VIEW -->
            <div class="modal-container" v-if="show_form">
                <div class="title-container">
                    <h1>Pay Bills</h1>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <div class="group">
                            <label for="biller">Biller</label>
                            <select v-model="biller">
                                <option :value="null" disabled hidden >Select Company</option>
                                <option v-for="bill in biller_companies" v-bind:key="bill.biller_id" v-bind:value="bill.biller_id">
                                    {{ bill.company_name }}
                                </option>
                            </select>
                            <span v-if="msg.company">{{msg.company}}</span>
                        </div>
                        <div class="group">
                            <label for="ref_no">Reference Number</label>
                            <div>
                                <input id="ref_no" type="text" class="" name="ref_no" v-model="ref_no"   required autocomplete="off">
                                <span v-if="msg.ref_no">{{msg.ref_no}}</span>
                            </div>
                        </div>
                        <div class="group">
                            <label for="due_date">Due Date</label>
                            <div>
                                <input type="date" id="due_date" name="due_date"  v-model="due_date" required autocomplete="off">
                                <span v-if="msg.due_date">{{msg.due_date}}</span>
                            </div>
                        </div>
                        <div class="group" style="margin-bottom: 25px;">
                            <label for="amount">Amount</label>
                            <input id="amount" type="text" class="amount" name="amount" v-model="amount" placeholder="0.00" @input="AmountCheck">
                            <span style="color:#000; float: left"><small>Transaction Fee: {{ addCommas(transaction_fee) }} PHP</small></span>
                            <span v-if="msg.amount">{{msg.amount}}</span>
                        </div>
                        <div class="group">
                            <label for="memo">Remarks</label>
                            <div>
                                <input id="memo" type="text" class="" name="memo" v-model="memo"   required autocomplete="off">
                            </div>
                        </div>
                        <button type="submit" :disabled='!BillStatus' class="active-btn" @click="show_details=true; show_form=false" required autocomplete="off">Proceed</button>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </slot>
                </div>
            </div>

            <!-- BILLS PAYMENT THIRD VIEW -->
            <div class="modal-container" v-if="show_details">
                <div class="title-container">
                    <h1>Confirm Details</h1>
                </div>
                <div class="modal-body" >
                    <slot name="body">
                        <!-- <form @submit.prevent="PayBill"> -->
                            <div class="group">
                                <label for="bi_co">Biller</label>
                                <input type="text" id="bi_co" name="bi_co"  v-model="biller" disabled required autocomplete="off" hidden>
                                <p style ="font-size: 21px">{{ getName(biller) }} </p>
                            </div>
                            <div class="group">
                                <label for="due_date">Due Date</label>
                                <input type="date" id="due_date" name="due_date"  v-model="due_date" disabled required autocomplete="off" hidden>
                                <p>{{ due_date }}</p>
                            </div>
                            <div class="group">
                                <label for="ref_no">Reference Number</label>
                                <input id="ref_no" type="text" class="" name="ref_no" v-model="ref_no"  disabled required autocomplete="off" hidden>
                                <p>{{ ref_no }}</p>
                            </div>
                            <div class="group">
                                <label for="amount">Amount</label>
                                <input id="amount" type="text" class="" name="amount" v-model="amount"  disabled required autocomplete="off" hidden>
                                <p>{{ amount }}</p>
                            </div>
                            <div class="group">
                                <label for="memo">Remarks</label>
                                <input id="memo" type="text" class="" name="memo" v-model="memo"  disabled="" required autocomplete="off" hidden>
                                <p>{{ memo }}</p>
                            </div>

                            <button :disabled='!BillStatus' class="active-btn" type="submit" @click="show_steps = true; show_details = false">Proceed</button>
                        <!-- </form> -->
                        <button class="cancel-btn" @click="show_details=false; show_form=true;">Back</button>
                    </slot>
                </div>
            </div>

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
                        <button type="submit" class="active-btn" @click="PayBill">Submit</button>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </div>
                </div>
            </div>

            <!-- BILLS PAYMENT SUCCESS VIEW -->
            <div class="modal-container success" v-if="show_status">
                <img :src="success_svg" v-if="success"> 
                <p class="title"><b> {{ message }} </b></p>
                <button type="submit" class="active-btn" value="Back" @click="show_details=true; show_status=false; error=false; success=false" v-if="error" required autocomplete="off">Back</button>
                <button class="cancel-btn" @click="$emit('close'); reloadOnClose()">
                    Close
                </button>
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
                biller_companies: [],
                ref_no: '',
                memo: '',
                amount: '',
                biller: '',
                due_date: '',
                balance: '',
                company_name: '',
                transaction_fee: 7,
                msg: [],
                loader_svg: '/svg/loader.svg',
                success_svg: '/svg/success.svg',
                page_loader_svg: '/svg/loader.svg',
                error_svg: '/svg/error.svg',
                show_steps: false,
                show_form: true,
                show_details: false,
                show_status: false,
                message: [],
                success: false,
                error: false,
                loading:false ,

            }
        },

        created(){
            axios.get('/api/biller-companies').then(response => this.biller_companies = response.data);
            axios.get('./api/wallet').then(response => this.balance = parseFloat(response.data.data[0].balance));
            
        },

        computed:{
            BillStatus(){
                const self = this;
                if(!self.biller || !self.ref_no || !self.due_date || !self.amount){
                    return false;
                }else if(self.balance < self.amount){
                    return false;
                }else{
                    return true;
                }
            }
        },

        methods:{
            getName(id){
                return this.biller_companies[id-1].company_name;
            },
            AmountCheck(){
                const self = this;
                self.amount = self.amount.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                if(self.balance < self.amount){
                    self.msg['amount'] = "Insufficient Balance";
                }else{
                    self.msg['amount'] = "";
                }

            },

            PayBill(){
                const self = this;
                self.loading=true;
                self.show_details=false;
                if (!self.msg['bi_co'] && !self.msg['due_date'] && !self.msg['ref_no'] && !self.msg['amount']) {
                    if(self.biller && self. due_date && self.ref_no ){
                        axios.post('./api/pay-bill',{
                            biller:self.biller,
                            amount:self.amount,
                            memo:self.memo,
                            ref_no:self.ref_no,
                            due_date:self.due_date
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
                            this.error = true;

                        })
                    }
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

