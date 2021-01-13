<template>
    <transition name="ConvertModal">
        <div class="modal-section">
            <div class="modal-wrapper">
                <div class="modal-container" v-if="show_form">
                    <div class="title-container">
                        <h1>Convert</h1>
                        <p>Balance: {{ php_balance }}PHP </p>
                        <p>{{ bts_balance }}BTS </p>
                    </div>
                    <div class="modal-body">
                        <form  @submit.prevent="Convert">
                            <div class="group">
                                <label>Convert From: </label>
                                <label>{{ label1 }}</label>
                                <input
                                type="text" 
                                :placeholder="[[placeholder1]]" 
                                v-model="input1"
                                :disabled = "loading"
                                @input="FirstInput">
                                <span v-if="msg.convert_err">{{msg.convert_err}}</span>
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
                </div>

                <div class="modal-container success" v-if="show_confirm">
                    <div class="modal-body">
                        <div class="title-container">
                            <h1>Input Password</h1>
                        </div>
                        <div class="group">
                            <input placeholder="Input yout Bitshares account password" v-model="password">
                            <span v-if="msg.password">{{msg.password}}</span>
                        </div>
                        <button type="submit" class="active-btn" @click=" show_confirm=false; verifyPassword()">Submit</button>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                    </div>
                    
                </div>

                <div class="modal-container success" v-if="show_status">
                    <img src="/svg/success.svg">
                    <p class="title"><b> {{ message }} </b></p>
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
    data() {
        return {
            label1: 'PHP',
            label2: 'BTS',
            label3: '',
            placeholder1: '0.00',
            placeholder2: '0.00000',
            placeholder3: '',
            input1: '',
            input2: '',
            input3: '',
            php_balance: '',
            bts_balance: '',
            transaction_fee: '',
            account_name: '',
            password: '',
            logged_in: {},
            page_loader_svg: '/svg/loader.svg',
            msg:[],
            loading: true,
            show_form: true,
            show_status: false,
            show_confirm: false,
            php: {rates:{PHP:0}},
            usd: [], 
        }
    },

    created(){
        const self = this;
        axios.get('./api/wallet')
        .then(response => {
            self.php_balance = parseFloat(response.data.data[0].balance);
            if(response.data.data[1]){
                self.account_name = response.data.data[1].account_name;
                self.getBTSBalance(self.account_name);
            }else{
                self.bts_balance = null;
            }
            console.log(self.bts_balance);  
        })
    },

    computed:{
        ConvertStatus(){
            const self = this;
            if(!self.php_balance){
                return false;
            }else if(!self.input1 || !self.input2 || self.input2=="NaN"){
                return false;
            }else if(self.label1 == 'PHP'){
                if(self.php_balance >= self.input1){
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
        }
    },

    methods: {
        async getBTSBalance(account_name){
            const self = this;
            self.bts_balance = await self.$blinc.getAccountBTSBalance(account_name);
            self.bts_balance = self.bts_balance.balance;
            console.log(self.account_name);
            console.log(self.bts_balance);
            delete axios.defaults.headers.common['X-Requested-With'];
            delete axios.defaults.headers.common['X-CSRF-TOKEN'];
            self.usd = await self.$blinc.getBTSExchangeRate();
            self.php = await self.$blinc.getFIATExchangeRate();
            if(self.usd && self.php){
                self.loading = false;
            }
        },

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
                const result = await self.$blinc.transferAsset(null,'TEST', self.account_name, self.input2,'test' );
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
                    self.loading=false;
                    self.show_form = false;
                    self.show_status = true;
                    self.message = response.data.message;
                }).catch(err => {
                    self.message = err.response.data.message;
                    self.loading=false;
                    self.show_form = false;
                    self.show_status = true;
                })
            }else{
                const result = await self.$blinc.transferAsset(self.logged_in,'TEST', null, self.input1,'test' );
                console.log(result);
                console.log(result[0].block_num);
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
                    self.loading=false;
                    self.show_form = false;
                    self.show_status = true;
                    self.message = response.data.message;
                }).catch(err => {
                    self.message = err.response.data.message;
                    self.loading=false;
                    self.show_form = false;
                    self.show_status = true;
                })

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
                self.Convert();
            }
        },

        reloadOnClose() {
            location.reload();
        }
    }
}
</script>