<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light sticky-top navbar-section">
            <div class="container cts-container">
                <!-- <a href="#"><img class="" src="svg/full_logo.svg"></a> -->
                <router-link to="/"><img class="" src="svg/full_logo.svg"></router-link>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto nav-icons">
                        <li class="nav-link social-media-link">
                        <a class="" href="https://www.facebook.com/bitshareslabs/" target="_blank"><img src="/svg/landing-facebook.svg"></a>
                        </li>
                        <li class="nav-link twitter social-media-link">
                        <a class="" href="https://twitter.com/bitshareslabs" target="_blank"><img src="/svg/landing-twitter.svg"></a>
                        </li>
                        <li class="nav-link insta social-media-link">
                            <a class="" href="https://www.instagram.com/bitshareslabs" target="_blank"><img src="/svg/instagram.svg"></a>
                        </li>
                        <li class="nav-link social-media-link">
                            <a class="" href="https://www.linkedin.com/company/bitshareslabs" target="_blank"><img src="/svg/linkedin.svg"></a>
                        </li>
                        <li class="nav-link medium social-media-link">
                            <a class="" href="https://medium.com/bitshares-labs" target="_blank"><img src="/svg/medium.svg"></a>
                        </li>
                    </ul>
        
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-link">
                            <a class="nav-link" href="#">Download App</a> 
                        </li>
                        <li>
                            <div class="vl"></div>
                        </li>
                        <li class="nav-link">
                            <a class="nav-link"><router-link to="sign-up">Sign Up</router-link></a>
                        </li>
                        <li class="nav-link">
                            <a class="">
                                <router-link to="login">
                                <button class="active-btn">
                                    Go to My Wallet
                                </button>
                                </router-link>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    
}
</script>