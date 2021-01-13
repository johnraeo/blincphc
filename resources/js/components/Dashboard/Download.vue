<template>
<!-- DOWNLOAD MODAL -->
    <transition name="DownloadModal">
        <div class="modal-section">
            <div class="modal-wrapper">
                
                <!-- DOWNLOAD MODAL VIEW -->
                <div class="modal-container" v-if="show_form">
                    <div class="title-container">
                        <h1>Download Transaction History</h1>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                        <form method="POST" @submit.prevent="exportWithDateToPDF()">
                            <div class="group">
                                <input class="checkbox-radio" type="radio" id="one" value="Download All" v-model="picked">
                                <label for="one">Download All</label>
                                <br>
                                <input class="checkbox-radio" type="radio" id="two" value="Select Month" v-model="picked">
                                <label for="two">Select specific month</label>
                                <br>
                                <!-- <span>Picked: {{ picked }}</span> -->
                            </div>
                            <div class="choose-date" v-if="picked == 'Select Month'">
                                <div class="group">
                                    <label for="send_to">Month</label>
                                    <select v-model="month">
                                        <option :value="null" disabled hidden>Select Month</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                                <div class="group">
                                    <label for="send_to">Year</label>
                                    <select v-model="year">
                                        <option :value="null" disabled hidden >Select Year</option>
                                        <option v-for="year in years" :value="year" v-bind:key="year">{{ year }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" :disabled='isDisabled' class="active-btn" value="Submit" @click.prevent=" show_form = false; picked == 'Download All' ? exportToPDF() : exportWithDateToPDF()"  >Submit</button>
                            <img :src="right_arrow">
                            <!-- <button class="modal-default-button" @click="$emit('close')">
                                Close
                            </button> -->
                        </form>
                        <button class="cancel-btn" @click="$emit('close')">Close</button>
                        </slot>
                    </div>
                </div>

                <!-- DOWNLOAD SUCCESS VIEW -->
                <div class="modal-container success" v-if="show_success">
                    <img :src="success_svg" > 
                    <div class="title-container">
                        <h1>{{ message.message }}</h1>
                    </div>
                    <button type="submit" class="active-btn" value="Back" @click="show_form=true; show_success=false" v-if="error">Back</button>
                    <button class="cancel-btn" @click="$emit('close')">
                        Close
                    </button>
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
                right_arrow: '/svg/right-arrow.svg',
                selected: '',
                success_svg: '/svg/success.svg',
                loader_svg: '/svg/loader.svg',
                success_svg: '/svg/success.svg',
                error_svg: '/svg/error.svg',
                isDisabled: true,
                show_form: true,
                show_success: false,
                success: false,
                error: false,
                picked: '',
                month: '',
                year: '',
                loading: false ,
                page_loader_svg: '/svg/loader.svg',

            }
        },
        watch:{     
            picked(value){
                this.pickedDownloadType(value);
            }
        },
        computed : {
            years () {
                const years = new Date().getFullYear()
                return Array.from({length: years - 1900}, (value, index) => 1901 + index)
            }
        },
        methods: {
            pickedDownloadType(value) {
                this.isDisabled = false;
                // this.downloadTransactions();
                // if(value == 'Download All') {
                //     // this.downloadTransactions()
                //     this.exportToPDF();
                // } 
            },
            downloadTransactions() {
                this.loading = true,

                axios({
                        url: '/api/download',
                        method: 'GET',
                        responseType: 'blob'
                    })
                    .then(response => {
                        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                        var fileLink = document.createElement('a');

                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'balance.xlsx');
                        document.body.appendChild(fileLink);
                        
                        this.loading = false;
                        fileLink.click();
                        
                    })
                    .catch(error => console.log(error));
            },
            exportToPDF() {
                axios({
                    url: './download-pdf',
                    method: 'GET',
                    responseType: 'blob'
                })
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => console.log(error));
            },
            exportWithDateToPDF() {
                if(this.month == '' || this.year == '') {
                    alert('Fill in the fields');
                }
                else {
                    axios.post('./download-with-date-pdf', {
                        month: this.month,
                        year: this.year
                    })
                    .then(response => {
                        console.log(response.data)
                    })
                    .catch(errors => console.log(errors));
                }
                // axios.post('./download-with-date-pdf', {
                //     month: this.month,
                //     year: this.year
                // })
                // .then(response => {
                //     console.log(response.data)
                // })
                // .catch(errors => console.log(errors));
                
            }
        }
    }
</script>

<style scoped>

</style>    
