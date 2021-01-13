<template>
<!-- CHANGE PASSWORD MODAL -->
<transition name="ChangePasswordModal">
    <div class="modal-section">
        <div class="modal-wrapper">

            <!-- CHANGE PASSWORD VIEW -->
            <div class="modal-container" v-if="show_form">
                <div class="title-container">
                    <h1>Change Password</h1>
                </div>
                <div class="modal-body">
                    <slot name="body">
                    <form method="">
                        <div class="group">
                            <label for="existing_password">Existing password</label>
                            <input id="existing_password" type="password" name="existing_password" v-model="existing_password" placeholder="">
                            <span></span>
                        </div>
                        <div class="group">
                            <label for="new_password">Enter new password</label>
                            <input id="new_password" type="password" name="new_password" v-model="new_password" placeholder="">
                            <!-- <span v-if="msg.amount">{{msg.amount}}</span> -->
                        </div>
                        <div class="group">
                            <label for="confirm_password">Confirm password</label>
                            <input id="confirm_password" type="password" name="confirm_password" v-model="confirm_password" placehodler="">
                        </div>
                        <input type="submit" :disabled='isDisabled' class="active-btn" value="Submit" @click=" show_form = false" >
                        <img :src="right_arrow">
                    </form>
                    <button class="cancel-btn" @click="show_form=true; show_confirm=false">
                        Back
                    </button>
                    </slot>
                </div>
            </div>

            <div class="modal-container success" v-if="show_success">
                <img :src="success_svg" > 
                <p class="title"><b> {{ message.message }} </b></p>
                <button type="submit" class="active-btn" value="Back" @click="show_form=true; show_success=false" v-if="error">Back</button>
                <button class="cancel-btn" @click="$emit('close')"> Close</button>
            </div>

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
                existing_password: '',
                new_password: '',
                confirm_password: '',
                errors: [],
                msg: [],
                right_arrow: '/svg/right-arrow.svg',
                selected: '',
                show_success: '',
                show_form: true,
                message: [],
                success_svg: '/svg/success.svg',
                isDisabled: true,
                error: false,
                page_loader_svg: '/svg/loader.svg',
                loading:false ,
            }
        },
        watch:{     
            amount(value){
                this.validNumber(value);
            }
        },
        methods: {
            // cashIn() {
            //     if (!this.msg['amount'] && !this.msg['cash_in_method']) {
            //         this.loading=true,
            //         axios.post('./api/cash-in',{
            //             cash_in_method:this.selected,
            //             amount:this.amount,
            //             memo:this.memo,
            //         })
            //         .then(
            //             response => {
            //                 this.message = response.data;
            //                 if(response.data.notif == "success"){
            //                     this.success = true;
            //                     this.loading=false;
            //                     this.show_success = true;

            //                 }else{
            //                     this.error = true;
            //                     this.loading=false; 
            //                 }
            //             })
            //         .catch(this.$set('errors', response.data))
            //     }
            // },
            // validNumber(value){
            //     if(isNaN(value)){
            //         this.msg['amount'] = 'Invalid Amount';
            //         this.isDisabled = true;
            //         this.hasAmountError = true;
            //     }else{
            //         if(!value){
            //             this.msg['amount'] = '';
            //             this.isDisabled = true;        
            //         }else{
            //             axios.get('./api/balance').then(response => this.balance = response.data);

            //             if(!this.msg['send_to']){
            //                 this.msg['amount'] = '';
            //                 this.hasAmountError = false;
            //                 this.isDisabled = false;
            //             }else{
            //                 this.isDisabled = false;
            //                 this.hasAmountError = false;
            //             }
            //         }
            //     }
            // }
        }
    }
</script>

<style scoped>

</style>    