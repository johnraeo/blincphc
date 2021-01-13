<template>
    <div>
        <wallet-component></wallet-component>

        <div class="container">
            <div class="tips">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="tip-wrapper">
                            <p>
                                Lorems ipsum dolor sit amet consectetur adipisicing elit. Tenetur maiores sit culpa voluptate minima illo possimus rem explicabo reprehenderit, eius reiciendis odit harum, quisquam in aperiam deserunt delectus accusamus provident?
                            </p>
                        </div>
                        <div class="tip-wrapper d-flex justify-content-center">
                            <button class="active-btn" @click="InviteModal = true">Send Invite</button>
                            <InviteModal v-if="InviteModal" @close="InviteModal = false"></InviteModal>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TRANSACTION HISTORY COMPONENT -->
        <div class="container">
            <div class="transactions-section">
                <p class="recent_activity">
                    <b>RECENT ACTIVITY</b> 
                    <!-- <a href="#" @click="DownloadModal = true">
                        <img :src="download_icon">
                    </a> -->
                </p>
                
                <!-- NO TRANSACTION VIEW -->
                <div class="no-transactions" v-if="noTransaction">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <h1>No transactions, yet</h1>
                            <!-- <p>Get started by <a href="#">funding your wallet</a></p> -->
                        </div>
                    </div>
                </div>
            
                <!-- TRANSACTION TABLE LIST -->
                <table>
                    <tbody>
                        <tr v-for="pending in pending_transactions" v-bind:key="pending.transact_id" @click="viewTransaction(pending.transact_id)">
                            <td class="left">
                                <div v-if="pending.transact_type == 'BP'">
                                {{ getBiller(pending.bills_payment.biller_id) }}
                                </div>
                                <div v-else-if="pending.transact_type == 'CI' || pending.transact_type =='CO' ">
                                    Union Bank
                                </div>
                                <div v-else>
                                    {{ pending.user.email}}
                                </div>

                                <div v-if="pending.transact_type == 'RF'"> 
                                    <small>
                                        Request Funds -
                                        <b v-bind:class="setStatusColorWord(pending)">{{ setStatus(pending) }}</b>
                                    </small>
                                </div>
                                <div v-else-if="pending.transact_type == 'SF'"> 
                                    <small>
                                        Send Funds - 
                                        <b v-bind:class="setStatusColorWord(pending)">{{ setStatus(pending) }}</b>
                                    </small>
                                </div>
                                <div v-else-if="pending.transact_type == 'CI'"> 
                                    <small>
                                        Cash In - 
                                        <b v-bind:class="setStatusColorWord(pending)">{{ setStatus(pending) }}</b>
                                    </small>
                                </div>
                                <div v-else-if="pending.transact_type == 'CO'"> 
                                    <small>
                                        Cash Out - 
                                        <b v-bind:class="setStatusColorWord(pending)">{{ setStatus(pending) }}</b>
                                    </small>
                                </div>
                                <div v-else-if="pending.transact_type == 'BP'"> 
                                    <small>
                                        Pay Bill - 
                                        <b v-bind:class="setStatusColorWord(pending)">{{ setStatus(pending) }}</b>
                                    </small>
                                </div>
                                <div v-else-if="pending.transact_type == 'CF'"> 
                                    <small>
                                        Convert - 
                                        <b v-bind:class="setStatusColorWord(pending)">{{ setStatus(pending) }}</b>
                                    </small>
                                </div>
                            </td>

                            <td class="right">
                                <div :class="setStatusColorBalance(pending) + ''">
                                    {{ setMovement(pending) }} PHP
                                </div>
                            
                                <small> {{ convertMonth(pending.transact_date.substring(5,7)) }} {{ pending.transact_date.substring(8,10) }}, {{ pending.transact_date.substring(0,4) }}</small>
                            </td>
                        </tr>     

                        <tr v-for="transaction in transactions" v-bind:key="transaction.transact_id" @click="viewTransaction(transaction.transact_id)">
                            <div v-if= "transaction.status != 3" style="width: 100%;justify-content: space-between;display: flex;">
                                <td class="left">
                                    <div v-if="transaction.transact_type == 'BP'">
                                        {{ getBiller(transaction.bills_payment.biller_id) }}
                                    </div>
                                    <div v-else>
                                        {{ transaction.user.email ? transaction.user.email : '' }}
                                    </div>
                                    <div>
                                        <div v-if="transaction.transact_type == 'RF'"> 
                                            <small>
                                                <div v-if="transaction.status == 0">
                                                    Received Funds - 
                                                </div>
                                                <div v-else>
                                                    Request Funds - 
                                                </div>
                                                <b v-bind:class="setStatusColorWord(transaction)">{{ setStatus(transaction) }}</b>
                                            </small>
                                        </div>
                                        <div v-else-if="transaction.transact_type == 'SF'"> 
                                            <small>
                                                Send Funds - 
                                                <b v-bind:class="setStatusColorWord(transaction)">{{ setStatus(transaction) }}</b>
                                            </small>
                                        </div>
                                        <div v-else-if="transaction.transact_type == 'CI'"> 
                                            <small>
                                                Cash In - 
                                                <b v-bind:class="setStatusColorWord(transaction)">{{ setStatus(transaction) }}</b>
                                            </small>
                                        </div>
                                        <div v-else-if="transaction.transact_type == 'CO'"> 
                                            <small>
                                                Cash Out - 
                                                <b v-bind:class="setStatusColorWord(transaction)">{{ setStatus(transaction) }}</b>
                                            </small>
                                        </div>
                                        <div v-else-if="transaction.transact_type == 'BP'"> 
                                            <small>
                                                Pay Bill - 
                                                <b v-bind:class="setStatusColorWord(transaction)">{{ setStatus(transaction) }}</b>
                                            </small>
                                        </div>
                                        <div v-else-if="transaction.transact_type == 'CF'"> 
                                            <small>
                                                Convert - 
                                                <b v-bind:class="setStatusColorWord(transaction)">{{ setStatus(transaction) }}</b>
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td class="right">
                                    <div :class="setStatusColorBalance(transaction) + ''">
                                        {{ setMovement(transaction) }} {{ convertCurrency(transaction) }}
                                    </div>
                                
                                    <!-- <small> {{ convertMonth(transaction.transact_date.substring(5,7)) }} {{ transaction.transact_date.substring(8,10) }}, {{ transaction.transact_date.substring(0,4) }} {{ transaction.transact_date.substring(11,13) }} {{transaction.transact_date.substring(14,16) }}</small> -->
                                    <small> {{ convertMonth(transaction.transact_date.substring(5,7)) }} {{ transaction.transact_date.substring(8,10) }}, {{ transaction.transact_date.substring(0,4) }}</small>
                                </td>
                            </div>
                        </tr>
                    </tbody>
                </table>

                <!-- LOAD MORE TRANSACTIONS BUTTON -->
                <nav v-if="showLoadMore == !pagination.next_page_url" aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#" v-on:click.prevent="fetchTransactions(pagination.next_page_url)">LOAD MORE</a></li>
                    </ul>
                </nav>
            </div>

            <!-- SHOW DOWNLOAD MODAL -->
            <DownloadModal v-if="DownloadModal" @close="DownloadModal = false"></DownloadModal>

            <!-- <InviteModal v-if="InviteModal" @close="InviteModal = false"></InviteModal> -->
        </div>

        <!-- TRANSACTION DETAILS MODAL -->
        <transition name="DetailsModal" v-if="view_transaction">
            <div class="modal-section" >
                <div class="modal-wrapper" >
                    <div class="modal-container col-md-6" v-if="show_details">
                        <div class="this-modal">
                            <p class="title">
                                <b v-if="transact.transact_type == 'SF' ">Send Funds to {{transact.user.email}}</b>
                                <b v-else-if="transact.transact_type == 'RF' ">
                                    <div v-if="transact.status == 0">
                                        Received Funds from {{transact.user.email}}
                                    </div>
                                    <div v-else>
                                        Request Funds to {{transact.user.email}}
                                    </div>
                                </b>
                                <b v-else-if="transact.transact_type == 'CI' ">Cash In</b>
                                <b v-else-if="transact.transact_type == 'CO' ">Cash Out</b>
                                <b v-else-if="transact.transact_type == 'CF' ">Convert</b>
                                <b v-else-if="transact.transact_type == 'BP' ">Pay Bills to 
                                {{ getBiller(transact.bills_payment.biller_id) }}</b>
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
                                <button class="cancel-btn" @click="view_transaction = false">
                                    Close
                                </button> 
                            </div>
                        </div>

                        <div class="page_loader" id="page_loader" v-if="loading">
                            <div class="page_loader_cont">
                                <img src="/svg/loader.svg" > 
                            </div>
                        </div>
                    </div>


            <!-- Status -->
            <div class="modal-container col-md-6 success" v-if="show_status">
                <div class="this-modal">
                    <p class="title"><b> {{ message }} </b></p>
                    <button class="active-btn" value="Back" @click="show_confirm=true; show_status=false;  success=false">Back</button>
                    <button class="cancel-btn" @click="show_status = false">
                        Close
                    </button>
                </div>
            </div>


            <!-- PASSWORD INPUT -->
            <div class="modal-container col-md-6 success" v-if="show_confirm">
                <div class="this-modal">
                    <div class="title-container">
                        <h1>Input Password</h1>
                    </div>
                    <div class="group">
                        <input placeholder="********" v-model="password">
                        <span v-if="msg.password">{{msg.password}}</span>
                    </div>
                    <button type="submit" class="active-btn" value="Back" @click=" show_confirm=false; verifyPassword()">Submit</button>
                    <button class="cancel-btn" @click="show_confirm = false">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

        </transition>

        <!-- PAGE LOADER -->
        <div class="page_loader" id="page_loader" v-if="loading">
            <div class="page_loader_cont">
                <img src="/svg/loader.svg" > 
            </div>
        </div>

    </div>
</template>

<script>

import Wallet from '../../Dashboard/Wallet.vue'

export default {
    components: {
        'wallet-component': Wallet, 
    },

    data() {
        return {
            id: '',
            transact_type: '',
            password: '',
            account_name: '',
            rcvr_account_name: '',
            msg: [],
            transactions: [],
            pending_transactions: [],
            transact: [],
            nextTransaction: [],
            pagination: {},
            download: {},
            page_loader_svg: '/svg/loader.svg',
            download_icon: '/svg/download_icon.svg',
            DownloadModal: false,
            view_transaction: false,
            show_details: false,
            showLoadMore: false,
            loading: true ,
            show_status: false,
            show_confirm: false,
            receiver_email: '',
            message: '',
            transact: null,
            noTransaction: true,
            InviteModal: false
        }
    },

    created() {
        const self = this;
        self.fetchPending();
        self.fetchTransactions();
    },

    methods: {
        viewTransaction(transaction_id){
            const self = this;
            self.loading=true;
            self.view_transaction = true;
            self.show_details = true;
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
            })
            .catch(error => console.log(error));
        },

        addCommas(x, type){
            const self = this;
            if(self.transact){
                if(type == 0){
                    return parseFloat(x).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }else if (type == 1){
                    return parseFloat(x).toFixed(5).replace(/\B(?=(\d{3})+(?!\d))/g, "");
                }
            }
            
            return parseFloat(x).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            
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
            const self = this;
            self.loading=true;
            self.show_details = false;
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
        fetchPending(){
            axios.get('/api/pending-transactions')
            .then(response => {
                this.pending_transactions = response.data.data;
            })
            .catch(error => console.log(error));
        },

        fetchTransactions(page_url) {
            let vm = this;
            page_url = page_url || '/api/transactions';
            axios.get(page_url)
                .then(response => {
                    this.nextTransaction = response.data.data;
                    this.loading = false;
                    for(let i = 0; i < this.nextTransaction.length; i++) {
                        this.transactions.push(this.nextTransaction[i])
                    }

                    vm.makePagination(response.data.meta, response.data.links);

                    // Check transactions length
                    if (this.transactions.length == 0 && this.pending_transactions.length == 0) {
                        this.noTransaction = true;
                    }
                    else {
                        this.noTransaction = false;
                    }

                })
                .catch(error => console.log(error));
        },

        makePagination(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            }
            this.pagination = pagination;
            this.loading=false;

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
                let result ={}
                try{
                    result = await self.$blinc.transferAsset(self.logged_in,'TEST', self.rcvr_account_name, self.transact.amount, self.transact.memo );
                }catch(err){
                    result.error = err;
                }
                if(result.error){
                    self.show_confirm=true;
                    self.msg['password'] = "Failed to accept request";
                    self.loading=false;
                    console.log(result.error);
                }else{
                    console.log(result);
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
            }
        },

        setMovement(item) {
            const sign = item.movement == 0 ? "+" : "-";
            if (item.transact_type == 'CF') {
                return sign +  item.convert.amount_converted;
            }
            else {
                return sign + this.addCommas(item.amount);
            }
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

        convertCurrency(item) {
            if (item.convert) {
                if (item.convert.convert_to == 0) {
                    return 'PHP';
                }
                else {
                    return 'BTS';
                }
            }
            else {
                return 'PHP';
            }
        }
    }
}
</script>
