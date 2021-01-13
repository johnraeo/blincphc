<template>
  <div class="div">
    <!-- Profile Sections -->
    <div class="container">
      <div class="profile-section">
        <div class="profile-title">
          <p>
            <b v-on:click.prevent="fetchProfile()">PROFILE</b>
          </p>
        </div>
        <div class="profile-container photo-profile">
          <p>Photo</p>
          <a>
            <img :src="chevron_right" />
          </a>
        </div>
        <div class="profile-container">
          <p>Name</p>
          <ul>
            <li class="profile-details">Barney Stinson</li>
            <!-- <a href="#"><li>edit</li></a> -->
          </ul>
        </div>
        <div class="profile-container">
          <p>Email</p>
          <ul>
            <li class="profile-details">barney@outlook.com</li>
            <!-- <a href="#"><li>edit</li></a> -->
          </ul>
        </div>
        <div class="profile-container">
          <p>Birthdate</p>
          <ul>
            <li class="profile-details">January 02, 2001</li>
            <!-- <a href="#"><li>edit</li></a> -->
          </ul>
        </div>
        <div class="profile-container">
          <p>Address</p>
          <ul>
            <li class="profile-details">East 84st St. & 3rd Ave. Manhattan, NY</li>
            <!-- <a href="#"><li>edit</li></a> -->
          </ul>
        </div>
        <div class="profile-container">
          <p>Country</p>
          <ul>
            <li class="profile-details">Canada</li>
            <!-- <a href="#"><li>edit</li></a> -->
          </ul>
        </div>
      </div>
    </div>
    <!-- Settings Section -->
    <div class="container">
      <div class="settings-section">
        <div class="settings-title">
          <p>
            <b>SETTINGS</b>
          </p>
        </div>
        <div class="st-section-1">
          <p>Enable Dark Mode</p>
          <label class="switch">
            <input name="dark-mode" type="checkbox" />
            <span class="slider round"></span>
          </label>
        </div>
        <!-- <div class="st-section-1">
          <p>Two Factor Authentication</p>
          <label class="switch">
            <input name="2FA" type="checkbox" />
            <span class="slider round"></span>
          </label>
        </div> -->
        <div class="st-section-2">
          <p>Password</p>
          <a href="#" @click="ChangePasswordModal = true">Change</a>
        </div>
      </div>
      <ChangePasswordModal v-if="ChangePasswordModal" @close="ChangePasswordModal = false"></ChangePasswordModal>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      ChangePasswordModal: false,
      profile: [],
      chevron_right: "/svg/chevron-right.svg",
    };
  },

  created() {
    this.fetchProfile();
  },

  methods: {
    fetchProfile() {
      axios
        .get("/api/profile")
        .then((response) => {
          this.profile = response.data;
          console.log(this.profile);
        })
        .catch((error) => console.log(error));
    },
  },
};
</script>

<style >
</style>