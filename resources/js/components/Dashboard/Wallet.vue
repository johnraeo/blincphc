<template>
<!-- wALLET SECTION -->
<div>
    <div class="wallet-section">
        <div class="container">
            <div class="wl-section-1">
                <h1>MY WALLET</h1>
                <p>{{ php_wallet }} <span> PHP </span> </p>
            </div>

            <hr> 

            <div class="wl-section-2" v-if="!bts_balance">
                <a href="#" @click="EnrollBTSModal=true">Enroll BTS Account</a>
            </div>

            <div class="wl-section-2-bts" v-if="bts_balance">
                <p> {{ bts_balance.balance ? bts_balance.balance : "0.00000" }}<span> BTS </span></p>
            </div>

        </div>
    </div>

    <!-- OPEN ENROLL BITSHARES ON CLICK -->
    <EnrollBTSModal v-if="EnrollBTSModal" @close="EnrollBTSModal = false"></EnrollBTSModal>
</div>
</template>

<script>
    export default {
        data(){
            return{
                php_wallet: [],
                bts_balance: [],
                EnrollBTSModal: false,
                hasWallet:'',
                db:'',
            }
        },

        created(){
            const self = this;
            self.$blinc.connect()
            axios.get('./api/wallet')
            .then(response => {
                self.php_wallet = self.addCommas(parseFloat(response.data.data[0].balance).toFixed(2));
                if(response.data.data[1]){
                    self.getBTSBalance(response.data.data[1].account_name);
                }else{
                    self.bts_balance = null;
                }
            })    
        },

        mounted(){
            document.addEventListener('click', this.closeModal)
        },

        beforeDestroy() {
            document.removeEventListener('click', this.EnrollBTSModal)
        },

        methods:{
            addCommas(value){
                 return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },

            async getBTSBalance(account_name){
                const self = this;
                self.bts_balance = await self.$blinc.getAccountBTSBalance(account_name);
            },

            closeModal(e) {
                if(e.target.className == 'modal-wrapper') {
                    this.EnrollBTSModal = false;
                }
            }
        },

        
    }
</script>
