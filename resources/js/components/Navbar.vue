<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light navbar-section">
            <div class="container">
                <router-link to="/home"><img class="" src="/svg/full_logo.svg"></router-link>

                <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
        
                <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent"> -->
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto nav-icons">
                        <li class="nav-link">
                            <!-- <a href="#" @click="clickChecker()" v-bind:class="{clicked:isClicked}">Transact</a> -->
                            <span @click="clickChecker()" class="navbar-toggler-icon"></span>
                        </li>
                    </ul>
        
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- <li class="nav-link download-icon">
                            <img src="/svg/default.svg">
                        </li> -->
                        <!-- <li>
                            <div class="vl"></div>
                        </li> -->
                        <li class="nav-link">
                            <!-- <img src="/svg/default.svg">   -->
                        </li>
                        <li class="nav-link dropdown">
                            <div class="dropdown">

                                <div class="profile-img-container">
                                    <img @click.prevent="profileToggle" id="iconToggle" :src="default_svg">
                                    <span class="badge">0</span>    
                                </div>
                                <!-- <div class="dropdown-content" v-bind:class="{clicked:isActive}"> -->
                                <div class="dropdown-content" v-bind:class="{shown: profileDropdown }" v-show="profileDropdown">
                                    <div class="profile">
                                        <div class="dropdown-img-container">
                                            <img :src="default_svg">
                                        </div>
                                        <p class ="name">{{ profile.username }}</p>
                                        <p class="email">{{ profile.email }}</p>
                                    </div>

                                    <!-- <div  @click="isActive = false">
                                        <router-link to="/profile-settings">
                                            <img :src="profile_svg">
                                            Profile
                                        </router-link>  
                                    </div> -->

                                    <!-- <a href="#" class="dropdown-item">
                                        <img :src="messages_svg">
                                        
                                        Messages
                                        <span class="badge">12</span>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <img :src="star_svg">
                                        Limits & Verification
                                    </a> -->

                                    <div  @click="isActive = false">
                                        <router-link to="/limits-and-verifications" >
                                            <img :src="star_svg">
                                            Limits & Verification
                                        </router-link>
                                    </div>

                                    <hr>
                                    
                                    <router-link to="/settings">
                                        <img :src="settings_svg">
                                        Settings
                                    </router-link>

                                    <router-link to="/test">
                                        <img :src="help_svg">
                                        Help
                                    </router-link>


                                    <a class="dropdown-item" style="cursor:pointer" v-on:click="logout">                                    
                                        <img :src="logout_svg">
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                <!-- </div> -->
            </div>
        </nav>

        <TransactComponent v-if="TransactComponent" @close="TransactComponent=false"></TransactComponent> 
    </div>
</template>

<script>
    export default {
        data(){
            return{
                profile: [],
                profile_svg: '/svg/profile.svg',
                messages_svg: '/svg/messages.svg',
                star_svg: '/svg/star.svg',
                settings_svg: '/svg/settings.svg',
                help_svg: '/svg/help.svg',
                logout_svg: '/svg/logout.svg',
                default_svg: '/svg/default.svg',
                full_logo_svg: '/svg/full_logo.svg',
                smol_logo: '/svg/blinc_logo.svg',
                isActive: false,
                isClicked: false,
                TransactComponent: '',
                isSmol: false,
                profileDropdown: false
            }   
        },

        methods:{
            myFilter: function() {
              this.isActive = !this.isActive;
            },

            clickChecker: function() {
                this.isClicked = !this.isClicked;
                this.TransactComponent = !this.TransactComponent;
            },

            logout:function(){
               axios.post('logout').then(response => {
                  // if (response.status === 302 || 401) {
                  //   this.$router.push("/login")
                  //   window.location.href = '/login';
                  // }
                  // else {
                  //   // throw error and go to catch block
                  // }
                }).then(() => location.href = '/login')
               .catch(error => {
                    location.reload();
              });
            },

            closeTransact(e) {
                if((e.target.className == 'wrapper') || (e.target.className == 'modal-wrapper')) {
                    this.TransactComponent = false;
                    this.isClicked = false;
                }

                // console.log(e.target.className);
            },

            profileToggle() {
                this.profileDropdown = !this.profileDropdown;
            },

            closeProfileToggler(e) {
                if(!this.$el.contains(e.target)) {
                    this.profileDropdown = false;
                    // console.log(this.$el.contains(e.target) + ' test');
                }
                else if(e.target.className == 'router-link-exact-active router-link-active') {
                    this.profileDropdown = false;
                }
            }
        },

        created(){
            axios.get('./api/profile')
            .then(response => this.profile = response.data);
        },

        mounted () {
            // document.addEventListener('click', this.close)
            document.addEventListener('click', this.closeProfileToggler)
            document.addEventListener('click', this.closeTransact)
        },

        beforeDestroy () {
            // document.removeEventListener('click', this.close)
            document.removeEventListener('click', this.closeProfileToggler)
            document.removeEventListener('click', this.closeTransact)
        }
    }
</script>
