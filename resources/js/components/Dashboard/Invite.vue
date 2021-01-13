<template>
    <transition name="InviteModal">
        <div class="modal-section">
            <div class="modal-wrapper">
                <div class="modal-container col-md-6" v-if="show_form">
                    <div class="this-modal">
                        <div class="title-container">
                            <h1>Invite a friend</h1>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="sendInvite">
                                <div class="group">
                                    <!-- <label for="email">Invite User</label> -->
                                    <label></label>
                                    <input id="email" type="email" name="email" v-model="email" placeholder="Enter the email you want to Invite" autocomplete="off">
                                    <!-- <span v-if="msg.amount">{{msg.amount}}</span> -->
                                    <span>{{ isEmailValid() }}</span>
                                </div>

                                <button type="submit" class="active-btn" value="Submit">Send Invite</button>
                                <!-- <img :src="right_arrow"> -->
                            </form>
                            <button class="cancel-btn" @click="$emit('close')">Close</button>
                        </div>
                    </div>
                </div>

                <!-- DOWNLOAD SUCCESS VIEW -->
                <div class="modal-container col-md-6 success" v-if="show_success">
                    <div class="this-modal">
                        <img src="/svg/success.svg" > 
                        <div class="title">
                            <h1>Invitation Sent</h1>
                        </div>
                        <button type="submit" class="active-btn" value="Back" @click="show_form=true; show_success=false" v-if="error">Back</button>
                        <button class="cancel-btn" @click="$emit('close')">
                            Close
                        </button>
                    </div>
                </div>

                <div class="page_loader" id="page_loader" v-if="loading">
                    <div class="page_loader_cont">
                        <img src="/svg/loader.svg"> 
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        senreq: {
            type: String
        }
    },

    data() {
        return {
            show_form: true,
            email: this.senreq,
            show_success: false,
            loading: false,

            reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,

        }
    },

    methods: {
        sendInvite() {
            const self = this;
            self.loading = true;
            axios.post('api/send-invite', {
                email: self.email
            })
            .then(response => {
                self.show_form = false;
                self.show_success = true;
                self.loading = false;
            })
            .catch(errors => console.log(errors));
        },

        isEmailValid: function() {
            return (this.email == this.senreq) ? "" : (this.reg.test(this.email)) ? '' : 'Invalid Email Address';
        },
    }
}
</script>