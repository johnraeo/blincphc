<template>
<!-- MODAL FOR SEND FUNDS -->
<transition name="ModalComponent">
    <div class="modal-section">
        <div class="modal-wrapper">
            <!-- SEND FUNDS FIRST VIEW -->
            <div class="modal-container col-md-6" v-if="show_form">
                <div class="this-modal">
                    <div class="title-container">
                        <h1> {{ selectedOption }}</h1>
                        <p v-if="selectedOption == 'Cash In' || selectedOption == 'Cash Out' || selectedOption == 'Pay Bills'">Balance: {{ balance }} PHP</p>
                        <p v-if="currency == 0">{{ balance }} PHP </p>    
                        <p v-if="bts_balance && (currency == 1)">{{ bts_balance }} BTS </p>
                        <p v-if="selectedOption == 'Convert'">{{ balance }} PHP</p>
                        <p v-if="selectedOption == 'Convert'">{{ bts_balance }} BTS</p>
                    </div>
                    <div class="modal-body" v-if=" selectedOption == 'Send Funds' || selectedOption == 'Request Funds' ">
                        <slot name="body">
                            <div class="group" >
                                <label for="currency">Currency</label>
                                <select v-model="currency">
                                    <option value=0>PHP</option>
                                    <option value=1 v-if="bts_balance">BTS</option>
                                </select>
                                <span v-if="msg.currency">{{msg.currency}}</span>
                                <span v-if="!msg.currency">{{'\xa0'}}</span>
                            </div>
                            
                            <div class="group" v-if="this.currency == 0">
                                <label for="senreq">Email</label>
                                <input type="text" 
                                    v-model="senreq" 
                                    :class="{ change_color: hasError }" 
                                    @input="EmailCheck">
                                <img :src="loader_svg" class="loader" :class="{ show_loader: hasLoader }"> 
                                <span v-if="msg.senreq">{{msg.senreq}}</span>
                                <button class="active-btn" v-if="msg.senreq == 'Email is not registered in Blinc.ph Would you like to invite?'" @click="InviteModal = true">Send invite</button>
                                <!-- <InviteModal v-bind:senreq="senreq" v-if="InviteModal" @close="InviteModal = false"></InviteModal> -->
                                <span v-if="!msg.senreq">{{'\xa0'}}</span>
                            </div>
                            <div class="group" v-else-if="this.currency==1">
                                <label>Account Name</label>
                                <input type="text" v-model="rcvr_account_name" placeholder="test-123" autocomplete="off" @input="AccountCheck">
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
                                :disabled='!SendStatus' 
                                class="active-btn" 
                                type="submit" 
                                @click="show_form=false;
                                        show_details=true"
                                v-if="this.currency == 0 && emailValid">
                                Proceed
                            </button>
                            <button 
                                :disabled='!SendStatus' 
                                class="active-btn" 
                                type="submit" 
                                @click="show_form=false; 
                                        show_confirm=true;" 
                                v-if="this.currency == 1">
                                Proceed
                            </button>
                            <button class="cancel-btn" @click="$emit('close')">Close</button>
                        </slot> 
                    </div>
                    <div class="modal-body" v-if=" selectedOption == 'Cash In' || selectedOption == 'Cash Out' ">
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
                            <button :disabled='!CashInOutStatus' class="active-btn" @click="show_details=true;show_form=false;">Proceed</button>
                            <button class="cancel-btn" @click="$emit('close')">
                                Close
                            </button>
                        </slot>
                    </div>
                    <div class="modal-body" v-if=" selectedOption == 'Convert' ">
                        <form  @submit.prevent="Convert">
                            <div class="group">
                                <label>From: </label>
                                <label>{{ label1 }}</label>
                                <input
                                type="text" 
                                :placeholder="[[placeholder1]]" 
                                v-model="input1"
                                :disabled = "loading"
                                @input="FirstInput">
                                <span v-if="msg.convert">{{msg.convert}}</span>
                            </div>

                            <div class="input-group-middle">
                                <img id="interchange-input" src="/svg/interchange.svg" v-on:click="interchangeInput">
                            </div>

                            <div class="group">
                                <label>To: </label>
                                <label>{{ label2 }}</label>
                                <input
                                type="text"
                                :placeholder="[[placeholder2]]"
                                v-model="input2"
                                @input="SecondInput"
                                disabled>
                                <span></span>
                            </div>
                            <small>*transaction fee included</small>
                            <button
                                type="submit" 
                                class="active-btn" 
                                value="Convert now" 
                                :disabled="!ConvertStatus"
                                v-if="this.label1 == 'PHP'">
                                Convert now
                            </button>
                        </form>
                        <button type="submit" 
                            class="active-btn" 
                            value="Convert now" 
                            :disabled="!ConvertStatus"
                            @click="show_form = false;
                            show_confirm = true;"
                            v-if="this.label1 == 'BTS'">
                                Convert now
                            </button>
                        <button class="cancel-btn" @click="$emit('close')">
                            Cancel
                        </button>
                    </div>
                    <div class="modal-body" v-if=" selectedOption == 'Pay Bills' ">
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
            </div>
            <!-- CONFIRM DETAILS -->
            <div class="modal-container col-md-6" v-if="show_details">
                <div class="this-modal">
                <div class="title-container">
                    <h1>Confirm Details</h1>
                </div>
                <div class="modal-body" v-if=" selectedOption == 'Send Funds' || selectedOption == 'Request Funds' ">
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
                            <button :disabled='!SendStatus' 
                            class="active-btn" 
                            type="submit" 
                            v-if="this.currency == 0">
                            Proceed
                        </button>
                        </form>
                        <button 
                            :disabled='!SendStatus' 
                            class="active-btn" 
                            type="submit" 
                            @click="show_details=false; 
                                    show_confirm=true;" 
                            v-if="this.currency==1">
                            Proceed
                        </button>
                        <button class="cancel-btn" @click="$emit('close')">
                            Cancel
                        </button>
                    </slot>
                </div>
                <div class="modal-body" v-if=" selectedOption == 'Cash In' || selectedOption == 'Cash Out' ">
                    <slot name="body">
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
                        <button class="active-btn" type="submit" @click="show_steps = true; show_details = false">Proceed</button>
                        <button class="cancel-btn" @click="show_form=true; show_details=false">Back</button>
                    </slot>
                </div>
                <div class="modal-body" v-if=" selectedOption == 'Pay Bills' ">
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
            </div>
            <!-- SHOW TRANSACTION -->
            
            <div class="modal-container col-md-6" v-if="show_status">
                <div class="this-modal">
                    <div class="title-container">
                        <b v-if="transact.transact_type == 'SF' ">Send Funds to {{transact.user.email}}</b>
                        <b v-else-if="transact.transact_type == 'RF' ">Request Funds to {{transact.user.email}}</b>
                        <b v-else-if="transact.transact_type == 'CI' ">Cash In</b>
                        <b v-else-if="transact.transact_type == 'CO' ">Cash Out</b>
                        <b v-else-if="transact.transact_type == 'CF' ">Convert</b>
                        <b v-else-if="transact.transact_type == 'BP' ">Pay Bills to {{ getBiller(transact.bills_payment.biller_id) }} </b>

                        <div>
                            <p v-if="transact.transact_type == 'SF' ">Sent Success</p>
                            <p v-else-if="transact.transact_type == 'RF' ">Request Success</p>
                            <p v-else-if="transact.transact_type == 'CI' ">Cash In Success</p>
                            <p v-else-if="transact.transact_type == 'CO' ">Cash Out Success</p>
                            <p v-else-if="transact.transact_type == 'CF' ">Convert Success</p>
                            <p v-else-if="transact.transact_type == 'BP' ">Pay Bills Success</p>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="tr-group">
                            <div class="left">
                                <label for="transact_id">Transaction ID</label>
                                <p>20200700{{transact.wallet_id}}0000{{ transact.transact_id }}</p>
                            </div>
                            <div class="right">
                                <label for="date">Date</label>
                                <p>
                                {{ convertMonth(transact.transact_date.substring(5,7)) }} 
                                {{ transact.transact_date.substring(8,10) }}, 
                                {{ transact.transact_date.substring(0,4) }}
                                </p>
                            </div>
                        </div>
                        <div class="tr-group">
                            <div class="left">
                                <div v-if="transact.transact_type == 'CF' ">
                                    <label>From/To</label>
                                    <div v-if="transact.convert.convert_from == 0">
                                        PHP to BTS
                                    </div>
                                    <div v-else>
                                        BTS to PHP
                                    </div>
                                </div>
                                <div v-else>
                                    <label for="type">Type</label>
                                    <p v-if="transact.transact_type == 'SF' ">Send</p>
                                    <p v-else-if="transact.transact_type == 'RF' ">Request</p>
                                    <p v-else-if="transact.transact_type == 'CI' ">Cash In</p>
                                    <p v-else-if="transact.transact_type == 'CO' ">Cash Out</p>
                                    <p v-else-if="transact.transact_type == 'BP' ">Paid Bills</p>
                                </div>
                            </div>
                            <div class="right">
                                <div v-if="transact.transact_type == 'SF' ">
                                    <label>To</label>
                                    <p>{{ transact.user.email}}</p>
                                </div>
                                <div v-else-if="transact.transact_type == 'BP' ">
                                    <label>To</label>
                                    <p> {{ getBiller(transact.bills_payment.biller_id) }}</p>
                                </div>
                                <div v-else-if="transact.transact_type == 'RF' ">
                                    <label>To</label>
                                    <p>{{ transact.user.email}}</p>
                                </div>
                                <div v-else-if="transact.transact_type == 'CI' ">
                                    <label>Via</label>
                                    <p>Union Bank</p>
                                </div>
                                <div v-else-if="transact.transact_type == 'CO' ">
                                    <label>Via</label>
                                    <p>Union Bank</p>
                                </div>
                                <div v-else-if="transact.transact_type == 'CF' ">
                                    <!-- <label>From/To</label>
                                    <div v-if="transact.convert.convert_from == 0">
                                        PHP to BTS
                                    </div>
                                    <div v-else>
                                        BTS to PHP
                                    </div> -->
                                    <label for="status">Status</label>
                                    <p v-bind:class="setStatusColorWord(transact)">{{ setStatus(transact) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="tr-group">
                            <div class="left">
                                <div v-if="transact.transact_type == 'CF'">
                                    <label v-if="transact.convert.convert_from == 0">PHP</label>
                                    <label v-else>BTS</label>
                                    <p class="red">-{{ addCommas(transact.convert.amount_to_convert, transact.convert.convert_from) }}</p>
                                </div>
                                <div v-else>
                                    <label for="amount">Amount</label>
                                    <p>
                                        {{ addCommas(transact.amount) }} 
                                        <small>
                                        {{ transact_type }}
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="right">
                                <div v-if="transact.transact_type == 'CF'">
                                    <label v-if="transact.convert.convert_to == 0">PHP</label>
                                    <label v-else>BTS</label>
                                    <p class="green">+{{ addCommas(transact.convert.amount_converted,transact.convert.convert_to) }}</p>
                                </div>
                                <div v-else>
                                    <label for="status">Status</label>
                                    <p v-bind:class="setStatusColorWord(transact)">{{ setStatus(transact) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- <input type="submit"  class="submit" value="Download"> -->
                        <div v-if="transact.status == 3">
                            <div v-if="transact.transact_type == 'SF' ">
                                <button 
                                    class="active-btn" 
                                    @click="AcceptRequest(transact.transact_id, transact.wallet[0].account_name)">
                                    Accept
                                </button>
                                <button class="deny-btn" @click="DenyRequest(transact.transact_id);">Deny</button>
                            </div>
                            <div v-else>
                                <button class="deny-btn" @click="CancelRequest(transact.transact_id);">Cancel</button>
                            </div>
                        </div>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </div>

                    <div class="page_loader" id="page_loader" v-if="loading">
                        <div class="page_loader_cont">
                            <img src="/svg/loader.svg" > 
                        </div>
                    </div>
                </div>
            </div>
            <!-- PASSWORD INPUT -->
            <div class="modal-container col-md-6 success" v-if="show_confirm">
                <div class="this-modal">
                    <div class="modal-body">
                        <div class="title-container">
                            <h1>Input Password</h1>
                        </div>
                        <div class="group">
                            <input placeholder="Input yout Bitshares account password" v-model="password">
                            <span v-if="msg.password">{{msg.password}}</span>
                        </div>
                        <button class="active-btn" @click="verifyPassword">Submit</button>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </div>
                </div>
            </div>
            <!-- INSTRUCTIONS -->
            <div class="modal-container col-md-6" v-if="show_steps" id="instructions">
                <div class="this-modal">
                    <div class="title-container">
                        <h1>Instructions</h1>
                        <div class="d-flex">
                            <button class="active-btn" @click="sendInstructions">Send to mail</button>
                            <a href="#" @click="downloadInstructions">
                                <img src="/svg/download_icon.svg">
                            </a>
                        </div>
                        
                    </div>
                    <div class="modal-body">
                        <div class="group">
                            <label>Lorem1</label>
                            <p>Fugiat officiis consequuntur ipsa facere animi magnam delectus harum, non, ducimus illum nulla ratione beatae atque deleniti porro error fugit, veniam quisquam?</p>
                            <img class="instructions-img" src="/svg/Payments.png" alt="">
                        </div>
                        <div class="group">
                            <label>Lorem</label>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum, optio quisquam. Animi tempore, accusamus voluptate odio enim earum facere delectus explicabo corporis, vero quia, expedita a similique sint. Aut, facere.</p>
                        </div>
                        <div class="group">
                            <label>Lorem</label>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et eius sint eveniet illo, temporibus aspernatur distinctio debitis optio possimus laborum quod deleniti nisi aut! Temporibus quisquam amet blanditiis aliquam sit.</p>
                            <img class="instructions-img" src="/svg/Payments.png" alt="">
                        </div>
                        <div class="group-btn">
                            <button type="submit" class="active-btn" v-if="selectedOption == 'Pay Bills' " @click="PayBill">Submit</button>
                            <button type="submit" class="active-btn" v-else @click="CashInOut">Submit</button>
                            <button class="cancel-btn" @click="$emit('close')">Close</button>
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
            <InviteModal v-bind:senreq="senreq" v-if="InviteModal" @close="InviteModal = false"></InviteModal>
        </div>
    </div>
</transition>
</template>



<script>
    import jsPDF from "jspdf";

    export default {
        props: ['selectedOption'],

        data(){
            return{
                testtest: 'tetestestest',
                currency: null,
                account_name: '',
                amount: '',
                balance: '',
                biller: '',
                bts_balance: '',
                company_name: '',
                due_date: '',
                emailStatus: '',
                memo: '',
                method: '',
                password: '',
                rcvr_account_name: '',
                ref_no: '',
                senreq: '',
                transact: '',
                msg: [],
                message: [],
                biller_companies: [],
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
                show_steps: false,
                success: false,
                error: false,
                loading:true ,
                emailValid: false,
                transaction_fee: 7,

                label1: 'PHP',
                label2: 'BTS',
                label3: '',
                placeholder1: '0.00',
                placeholder2: '0.00000',
                placeholder3: '',
                input1: '',
                input2: '',
                input3: '',
                account_name: '',
                password: '',
                logged_in: {},
                msg:[],
                php: {rates:{PHP:0}},
                usd: [], 

                InviteModal: false,
            }
        },
        created(){
            this.getBalance();
        },
        computed:{
            SendStatus(){
                const self = this;
                if (self.selectedOption == 'Send Funds') {
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
                else {
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
                        }
                        return true;
                    }
                }
                
            },
            ConvertStatus(){
                const self = this;
                if(!self.input1 || !self.input2 || self.input2=="NaN"){
                    return false;
                }else if(self.label1 == 'PHP'){
                    if(self.balance >= self.input1){
                        return true;
                    }else{
                        return false;
                    }
                }else if(self.label1 == 'BTS'){
                    if(self.bts_balance >= self.input1){
                        return true;
                    }else{
                        return false;
                    }
                }
            },
            CashInOutStatus(){
                const self = this;
                if(!self.method || !self.amount){
                    return false;
                }else{
                    if(self.selectedOption == 'Cash Out' && self.balance < (self.amount + self.transaction_fee)){
                        return false;
                    }
                    return true;
                }
            },
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
        methods: {
            //GLOBAL
            getBalance() {
                const self = this;
                axios.get('./api/wallet')
                .then(response => {
                    self.balance = parseFloat(response.data.data[0].balance);
                    if(response.data.data[1]){
                        self.account_name = response.data.data[1].account_name;
                        self.getBTSBalance(self.account_name);
                    }else{
                        self.loading = false;
                        self.bts_balance = null;
                    }
                })
                if(self.selectedOption == 'Pay Bills'){
                    axios.get('/api/biller-companies').then(response => self.biller_companies = response.data);
                }
            },
            getBiller(id){
                switch(id){
                    case 1:
                        return "Globe";
                        break;
                    case 2:
                        return "PLDT";
                        break;
                    case 3:
                        return "Converge";
                        break;
                    case 4:
                        return "BENECO";
                        break;
                    default:
                        break;
                }
            },
            async getBTSBalance(account_name){
                const self = this;
                self.bts_balance = await self.$blinc.getAccountBTSBalance(account_name);
                self.bts_balance = self.bts_balance.balance;
                if(self.selectedOption == 'Convert'){
                    delete axios.defaults.headers.common['X-Requested-With'];
                    delete axios.defaults.headers.common['X-CSRF-TOKEN'];
                    self.usd = await self.$blinc.getBTSExchangeRate();
                    self.php = await self.$blinc.getFIATExchangeRate();
                    if(self.usd && self.php){
                        self.loading = false;
                    }
                }else{
                    if(self.account_name && self.bts_balance){
                    self.loading = false;
                    }
                }
                
            },
            viewTransaction(transaction_id){
                const self = this;
                self.show_steps = false;
                self.loading=true;
                axios.post('./api/view-transaction',{
                    id:transaction_id
                })
                .then(response => {
                    self.transact = response.data;
                    // self.loading = false;
                    if(self.transact.transact_type == "SF" || self.transact.transact_type == "RF"){
                        self.transact_type = self.transact.send_req.currency_type == 0 ? "PHP" : "BTS";
                    }else{
                        self.transact_type = "PHP";
                    }
                    self.show_status = true;
                    self.loading = false;

                })
                .catch(error => console.log(error));
            },
            EmailCheck(){
                const self = this;
                if(!self.senreq){
                    self.hasLoader = false
                    self.hasError = false;
                    self.msg['senreq'] = '';
                }else if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/.test(self.senreq)){
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
                    self.hasError = true;
                    self.hasLoader = true;
                    self.loader_svg = '/svg/loader.svg';
                    self.emailValid = false;
                    self.msg['senreq'] = 'Invalid Email Address';
                }
            },
            CheckAccountName(){
                const self = this;
                self.rcvr_account_name = self.rcvr_account_name.toLowerCase();
                var re = new RegExp("^[a-z0-9-]+$");

                if(!self.rcvr_account_name){
                    self.msg['rcvr_account_name'] = '';
                    self.hasLoader = false;
                }else{
                    
                }
            },
            AmountCheck(){
                const self = this;
                self.amount = self.amount.replace(/[^0-9.]/g, '.').replace(/(\..*)\./g, '$1');
                if(!self.amount){
                    self.msg['amount'] = null;
                }else{
                    self.amount = parseFloat(self.amount);
                    if(isNaN(self.amount)){
                        self.msg['amount'] = 'Invalid Amount';
                        self.hasAmountError = true;
                    }else{
                        if(self.currency == 0){
                            if(self.selectedOption != 'Request Funds'){
                                if(self.balance <= self.amount){
                                    self.hasAmountError = true;
                                    self.msg['amount'] = 'Insufficient Balance';
                                }else if(!self.msg['senreq'] && !self.senreq){
                                        self.msg['amount'] = '';
                                        self.hasAmountError = false;
                                }else{
                                    self.hasAmountError = false;
                                }
                            }
                            if(!self.msg['senreq'] && !self.senreq){
                                    self.msg['amount'] = '';
                                    self.hasAmountError = false;
                            }else{
                                self.hasAmountError = false;
                            }
                        }else if(self.currency == 1){
                            if (self.amount > self.bts_balance){
                                self.hasAmountError = true;
                                self.msg['amount'] = "Insufficient Balance";
                            }else{
                                self.msg['amount'] = null;
                                self.hasAmountError = false;
                            }
                        }
                    }
                }
            },

            AcceptRequest(transaction_id, account_name){
                const self = this;
                self.loading=true;
                self.show_details = false;
                self.transaction_id = transaction_id;
                self.rcvr_account_name = account_name;
                if(self.transact_type == "BTS"){
                    self.show_confirm = true;
                }else{
                    axios.post('./api/accept-request',{
                    id: self.transaction_id
                    }).then(response => {
                        self.viewTransaction(response.data.message);
                    }).catch(err => {
                        self.message = err.response.data.message;
                        self.show_status = true;
                        self.loading=false;
                        self.show_details = false;
                    })
                }
                
            },
            DenyRequest(transaction_id){
                axios.post('./api/deny-request',{
                    id:transaction_id
                }).then(response => {
                    self.viewTransaction(response.data.message);
                }).catch(err => {
                    self.message = err.response.data.message;
                    self.loading=false;
                    self.show_status = true;
                })
            },
            CancelRequest(transaction_id){
                const self = this;
                self.show_details = false;
                self.loading = true;
                axios.post('./api/cancel-transaction',{
                    id: transaction_id
                }).then(response => {
                   self.viewTransaction(response.data.id);
                }).catch(err => {
                    self.message = err.response.data.message;
                    self.show_status = true;
                    self.loading=false;
                })
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
            setStatus(item) {
                if(item.status == 0) {
                    return "SUCCESS"
                }
                else if(item.status == 1) {
                    return "DECLINE"
                }
                else if(item.status == 2) {
                    return "PROCESSING"
                }
                else if(item.status == 3) {
                    return "PENDING"
                }
                else if(item.status == 4) {
                    return "CANCELLED"
                }
            },
            setStatusColorBalance(item) {
                if(item.status == 1 || item.status == 4) { // DENIED or CANCELLED
                    return "grey"
                }
                else if(item.status == 3) { // PENDING
                    return "orange"
                }
                else if (item.status == 0) { // SUCCESS
                    if(item.movement == 0) {
                        return "green"
                    }
                    else {
                        return "red"
                    }
                }
            },
            setStatusColorWord(item) {
                if(item.status == 0) {
                    return "green"
                }
                else if(item.status == 1) {
                    return "blue"
                }
                else if(item.status == 2) {
                    return ""
                }
                else if(item.status == 3) {
                    return "orange"
                }
                else if(item.status == 4) {
                    return "red"
                }
            },
            reloadOnClose() {
                location.reload();
            },

            //SEND FUNDS
            async SendReq(){
                const self = this;
                self.show_details = false;
                self.loading = true;
                if (!self.senreq) {
                    self.msg['senreq'] = 'Recipient required.';
                } else if (!self.amount || self.amount <= 0) {
                    self.msg['amount'] = 'Amount required.';                    
                } else if (self.currency == 1 && !self.rcvr_account_name){
                    self.msg['rcvr_account_name'] = "Account Name Required";
                } else {
                    self.loading=true;
                    let result ={}
                    if(self.currency == 1){
                        try{
                            result = await self.$blinc.transferAsset(self.logged_in,'TEST', self.rcvr_account_name, self.amount, self.memo );
                        }catch(err){
                            console.log(err);
                            result.error = err;
                        }
                    }
                    if(result.error){
                        self.show_confirm=true;
                        self.message = "Error";
                        self.loading=false;
                    }else{
                        axios.post('./api/send-funds',{
                        senreq:self.senreq,
                        amount:self.amount,
                        memo:self.memo,
                        currency:self.currency,
                        type: self.selectedOption == 'Send Funds' ? 'SF' : 'RF',
                        php_balance: self.balance,
                        bts_balance: self.bts_balance,
                        block_no: self.currency == 0 ? '' : result[0].block_num,
                        }).then(response => {
                            self.viewTransaction(response.data.message);
                            self.getBalance();
                        }).catch(err => {
                            self.message = err.response.data.message;
                            self.loading=false;
                            self.show_status = true;
                        })
                    }
                }
            },
            //REQUEST FUNDS

            //CONVERT
            interchangeInput() {           
                const self = this;

                self.label3 = self.label1;
                self.label1 = self.label2;
                self.label2 = self.label3;

                self.placeholder3 = self.placeholder1;
                self.placeholder1 = self.placeholder2;
                self.placeholder2 = self.placeholder3;

                self.input3 = self.input1;
                self.input1 = self.input2;
                self.input2 = self.input3;
            },
            FirstInput(){
                const self = this;
                self.input1 = self.input1.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                if(self.label2 == 'BTS'){
                    self.input2 = (self.input1 / self.php.rates.PHP  / self.usd.exchange_rate);
                    console.log(self.input2);
                    self.transaction_fee = self.input2 *0.01;
                    self.input2 = (self.input2 - self.transaction_fee).toFixed(5);
                }else{
                    self.input2 = (self.input1 * self.php.rates.PHP  * self.usd.exchange_rate); 
                    console.log(self.input2);
                    self.transaction_fee = self.input2 *0.01;
                    self.input2 = (self.input2 - self.transaction_fee).toFixed(2);

                }
            },
            SecondInput(){
                const self = this;
                self.input2 = self.input2.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            },
            async Convert() {
                const self = this;
                self.loading = true;
                if(self.label1 == "PHP"){
                    let result ={}
                    try{
                        result = await self.$blinc.transferAsset(null,'TEST', self.account_name, self.input2,'test' );
                    }catch(err){
                        console.log(err);
                        result.error = err;
                    }
                    if (result.error) {
                        // self.msg['convert'] = result.error;
                    }else if(result){
                        axios.post('./api/convert',{
                            account_name: self.account_name,
                            amount: self.input1,
                            total_amount: self.input2,
                            convert_from: self.label1,
                            convert_to: self.label2,
                            exchange_rate:  self.usd.exchange_rate,
                            block_no: self.currency == 0 ? '' : result[0].block_num,
                            transaction_fee: self.transaction_fee,
                        
                        }).then(response => {
                            self.viewTransaction(response.data.message);
                            self.show_form = false;
                        }).catch(err => {
                            self.message = err.response.data.message;
                            self.loading=false;
                            self.show_form = true;
                        })
                    }
                }else{
                    let result ={}
                    try{
                        result = await self.$blinc.transferAsset(self.logged_in,'TEST', null, self.input1,'test' );
                    }catch(err){
                        console.log(err);
                        result.error = err;
                    }
                    if (result.error) {

                    }else if(result){
                        axios.post('./api/convert',{
                            account_name: self.account_name,
                            amount: self.input1,
                            total_amount: self.input2,
                            convert_from: self.label1,
                            convert_to: self.label2,
                            exchange_rate:  self.usd.exchange_rate,
                            block_no: result[0].block_num,
                            transaction_fee: self.transaction_fee,
                        
                        }).then(response => {
                            self.viewTransaction(response.data.message);
                            self.show_form = false;
                            self.show_confirm = false;
                        }).catch(err => {
                            self.message = err.response.data.message;
                            self.loading=false;
                            self.show_form = false;
                            self.show_status = true;
                        })

                    }
                }
            },
            async verifyPassword() {
                const self = this;
                self.loading = true;
                let result ={}
                try{
                    result = await self.$blinc.loginViaPassword(self.account_name, self.password);
                }catch(err){
                    console.log(err);
                    result.error = err;
                }

                if(result.error){
                    self.show_confirm=true;
                    self.msg['password'] = "Invalid Password";
                    self.loading=false;
                }else{
                    self.logged_in = result;
                    if(self.selectedOption == 'Send Funds') {
                        self.SendReq();
                    }else if (self.selectedOption == 'Convert') {
                        self.Convert();
                    }
                    
                }
            },

            //CASH IN
            CashInOut(){
                const self = this;
                self.show_details = false;
                self.loading = true;
                if (!self.amount || self.amount <= 0) {
                    self.msg['amount'] = 'Amount required.';                    
                } else {
                    self.loading=true;
                    axios.post('./api/cash-in',{
                        amount:self.amount,
                        memo:self.memo,
                        meth:self.method,   
                        transaction_fee: self.transaction_fee,
                        type: self.selectedOption == 'Cash In' ? 'CI' : 'CO',
                    }).then(response => {
                        self.viewTransaction(response.data.message);
                    }).catch(err => {
                        self.message = err.response.data.message;
                        self.loading=false;
                        self.show_status = true;
                        self.show_steps = false;
                    })
                }
            },

            //BILLS PAYMENT
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
                            self.viewTransaction(response.data.message);
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
            getName(id){
                return this.biller_companies[id-1].company_name;
            },

            downloadInstructions() {
                // TEST BASIS OF JSPDF
                // const doc = new jsPDF();
                // doc.text("Hello world!", 10, 10);
                // doc.save("a4.pdf");

                var pdf = new jsPDF('p', 'pt', 'letter');
                // source can be HTML-formatted string, or a reference
                // to an actual DOM element from which the text will be scraped.
                let source = document.querySelector('#instructions');

                // we support special element handlers. Register them with jQuery-style 
                // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
                // There is no support for any other type of selectors 
                // (class, of compound) at this time.
                let specialElementHandlers = {
                    // element with id of "bypass" - jQuery style selector
                    '#bypassme': function (element, renderer) {
                        // true = "handled elsewhere, bypass text extraction"
                        return true
                    }
                };
                let margins = {
                    top: 80,
                    bottom: 60,
                    left: 40,
                    width: 522
                };
                // all coords and widths are in jsPDF instance's declared units
                // 'inches' in this case
                pdf.fromHTML(
                    source, // HTML string or DOM elem ref.
                    margins.left, // x coord
                    margins.top, { // y coord
                        'width': margins.width, // max width of content on PDF
                        'elementHandlers': specialElementHandlers
                    },

                    function (dispose) {
                        // dispose: object with X, Y of the last line add to the PDF 
                        //          this allow the insertion of new lines after html
                        pdf.save('Test.pdf');
                    }, margins
                );
                
            },
            sendInstructions(){
                const self = this;
                self.loading = false;
                axios.post('./api/send-instructions',{
                }).then(response => {
                    self.loading=false;
                }).catch(err => {
                    self.loading=false;
                    self.show_steps = false;
                })
            }
        }
    }
</script>