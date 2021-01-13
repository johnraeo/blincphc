<template>
<!-- TRANSACT DROPDOWN -->
<transition name="TransactComponent">
    <div class="wrapper">
        <div class="transact">  
            <a href="#" class="transact_container" @click="ModalComponent = true; selectedOption = 'Send Funds'">
                <div class="base_container">
                    <div class="img-container">
                    <img :src="send">
                    </div>
                </div>
                <div class="txt-container">
                    <p class="title">Send</p>
                    <p class="description">To an</p>
                </div>
            </a>
            <a href="#" class="transact_container" @click="ModalComponent = true; selectedOption = 'Request Funds'">
                <div class="base_container">
                    <div class="img-container">
                    <img :src="request">
                    </div>
                </div>
                <div class="txt-container">
                    <p class="title">Request</p>
                    <p class="description">From an</p>
                </div>
            </a>

            <a href="#" class="transact_container" @click="ModalComponent = true; selectedOption = 'Cash In'">
                <div class="base_container">
                    <div class="img-container">
                    <img :src="cash_in">
                    </div>
                </div>
                <div class="txt-container">
                    <p class="title">Cash In</p>
                    <p class="description">Add Funds to</p>
                </div>
            </a>

            <a href="#" class="transact_container" @click="ModalComponent = true; selectedOption = 'Cash Out'">
                <div class="base_container">
                    <div class="img-container">
                    <img :src="cash_out">

                    </div>
                </div>
                <div class="txt-container">
                    <p class="title">Cash Out</p>
                    <p class="description">Withdraw</p>
                </div>
            </a>
            <!-- CONVERT MODAL ACTIVE -->
            <a href="#" class="transact_container"  @click="ModalComponent = true; selectedOption = 'Convert'" v-if="hasWallet">
                <div class="base_container">
                    <div class="img-container">
                    <img :src="convert">

                    </div>
                </div>
                <div class="txt-container" >
                    <p class="title">Convert</p>
                    <p class="description">Convert</p>
                </div>
            </a>

            <!-- CONVERT MODAL INACTIVE -->
            <a href="#" class="transact_container"  @click="EnrollBTSModal = true; selectedOption = ''" v-if="!hasWallet">
                <div class="base_container">
                    <div class="img-container">
                    <img :src="convert">
                    </div>
                </div>
                <div class="txt-container" >
                    <p class="title">Convert</p>
                    <p class="description">Convert</p>
                </div>
            </a>
            <a href="#" class="transact_container"  @click="ModalComponent = true; selectedOption = 'Pay Bills'">
                <div class="base_container">
                    <div class="img-container">
                    <img :src="pay_bills">
                    </div>
                </div>
                <div class="txt-container">
                    <p class="title">Pay Bills</p>
                    <p class="description">Water, Electricity</p>
                </div>
            </a>

        </div>
        <!-- GLOBAL MODAL ON CLICK -->
        <ModalComponent :selectedOption="selectedOption" v-if="ModalComponent" @close="ModalComponent = false"></ModalComponent>

        <!-- OPEN BILLS PAYMENT MODAL ON CLICK -->
        <BillsPaymentModal v-if="BillsPaymentModal" @close="BillsPaymentModal = false"></BillsPaymentModal>

        <!-- OPEN SEND FUNDS MODAL ON CLICK -->
        <SendFundsModal v-if="SendFundsModal" @close="SendFundsModal = false"></SendFundsModal>  

        <!-- OPEN REQUEST FUNDS MODAL ON CLICK -->
        <RequestFundsModal v-if="RequestFundsModal" @close="RequestFundsModal = false"></RequestFundsModal> 

        <!-- OPEN CASH OUT MODAL ON CLICK -->
        <CashOutModal v-if="CashOutModal" @close="CashOutModal = false"></CashOutModal> 

        <!-- OPEN CASH IN MODAL ON CLICK -->
        <CashInModal v-if="CashInModal" @close="CashInModal = false"></CashInModal>

        <!-- OPEN CONVERT MODAL ON CLICK -->
        <ConvertModal v-if="ConvertModal" @close="ConvertModal = false"></ConvertModal>

        <!-- OPEN ENROLL BITSHARES ON CLICK -->
        <EnrollBTSModal v-if="EnrollBTSModal" @close="EnrollBTSModal = false"></EnrollBTSModal>     
    </div>
</transition>
</template>

<script>
    export default {  
        data(){
            return{
                cash_in: '/svg/cash_in.svg',
                cash_out: '/svg/cash_out.svg',
                send: '/svg/send.svg',
                request: '/svg/request.svg',
                convert: '/svg/convert.svg',
                pay_bills: '/svg/pay_bills.svg',
                BillsPaymentModal: false,
                SendFundsModal: false,
                RequestFundsModal: false,
                CashOutModal: false,
                CashInModal: false,
                ConvertModal: false,
                EnrollBTSModal: false,
                ModalComponent: false,
                hasWallet: '',
                selectedOption: '',
            }
        },
        created(){
            const self = this;
            axios.get('./api/wallet')
            .then(response => {
                self.php_wallet = self.addCommas(parseFloat(response.data.data[0].balance).toFixed(2));
                if(response.data.data[1]){
                    self.hasWallet = true;
                }else{
                    self.hasWallet = false;
                }
            })    
        },
        mounted(){
                
        },
        methods:{
            addCommas(value){
                 return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
        }
    }
</script>