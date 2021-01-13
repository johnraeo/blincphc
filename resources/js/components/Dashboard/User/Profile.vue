<template>
<div class="container">
    <div class="profile-section">
        <div class="profile-title">
            <p><b v-on:click.prevent="fetchProfile()">PROFILE</b></p>
        </div>
        <div class="profile-container photo-profile">
            <p>Photo</p>
            <a><img :src="chevron_right"> </a>
        </div>
        <div class="profile-container">
            <p>Name</p>
            <ul>
                <li class="profile-details">Barney Stinson</li>
                <!-- <a href="#"><li>edit</li></a> -->
            </ul>
        </div>
        <div class="profile-container">
            <p> Email </p>
            <ul>
                <li class="profile-details">barney@outlook.com</li>
                <!-- <a href="#"><li>edit</li></a> -->
            </ul>
        </div>  
         <div class="profile-container">
            <p>Birthdate </p>
            <ul>
                <li class="profile-details">January 02, 2001</li>
                <!-- <a href="#"><li>edit</li></a> -->
            </ul>
        </div>
         <div class="profile-container">
            <p> Address </p>
            <ul>
                <li class="profile-details">East 84st St. & 3rd Ave. Manhattan, NY</li>
                <!-- <a href="#"><li>edit</li></a> -->
            </ul>
        </div>
        <div class="profile-container">
            <p> Country </p>
            <ul>
                <li class="profile-details">Canada</li>
                <!-- <a href="#"><li>edit</li></a> -->
            </ul>
        </div>      
    </div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        created() {
          this.fetchProfile();
        },
        data() {
            return {
              profile: [],
              chevron_right: '/svg/chevron-right.svg',
            };
        },
        methods: {
            // formSubmit(e) {
            //     e.preventDefault();
            //     let currentObj = this;
            //     axios.post('/formSubmit', {
            //         name: this.name,
            //         description: this.description
            //     })
            //     .then(function (response) {
            //         currentObj.output = response.data;
            //     })
            //     .catch(function (error) {
            //         currentObj.output = error;
            //     });
            // }
            fetchProfile() {
              axios.get('/api/profile')
                .then(response => {
                  this.profile = response.data;
                  console.log(this.profile);
                })
                .catch(error => console.log(error));
            }
        }
    }
</script>