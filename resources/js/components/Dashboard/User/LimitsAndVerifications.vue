<template>
<!-- LIMITS AND VERIFICATION COMPONENT -->
    <div class="container">
        <!-- LIMITS AND VERIFICATION SECTION -->
        <div class="limits-and-verifications">
            <div class="lv-section1">
                <h1>Limits & Verifications</h1>
                <p>Your current level: <b class="green">Level1</b></p>
            </div>
            <div class="row section2">
                <div class="col-lg-6">
                    <h1>Cash In</h1>
                    <div class="d-flex justify-content-between">
                        <p class="l-v-left">Daily (24 hours)</p>
                        <p class="l-v-right">100,000 PHP Remaining</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="sr-only">70% Complete</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="l-v-left">Monthly (30 days)</p>
                        <p class="l-v-right">Unlimited</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="sr-only">70% Complete</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="l-v-left">Annual</p>
                        <p class="l-v-right">Unlimited</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="sr-only">70% Complete</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h1>Cash Out</h1>
                    <div class="d-flex justify-content-between">
                        <p class="l-v-left">Daily (24 hours)</p>
                        <p class="l-v-right">100,000 PHP Remaining</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="sr-only">70% Complete</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="l-v-left">Monthly (30 days)</p>
                        <p class="l-v-right">Unlimited</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="sr-only">70% Complete</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="l-v-left">Annual</p>
                        <p class="l-v-right">Unlimited</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="sr-only">70% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lv-section3">
                <p><a href="#"><u>Click here</u></a> to learn how your cash in and cash out limits are tracked</p>
            </div>
        </div>

        <!-- REQUIREMENTS SECTION -->
        <div class="requirements">
            <div class="requirements-section1">
                <h1>Requirements</h1>
                <p>Complete all necessary requirements to increase youre limits.</p>
                <div class="btn-group">
                    <button class="button">Daily</button>
                    <button class="button">Monthly</button>
                    <button class="button">Annual</button>
                </div>
            </div>
            
            <div class="row section2">
                <div class="col-lg-12">
                    <table>
                        <tr>
                            <th>LEVELS</th>
                            <th>REQUIREMENTS</th> 
                            <th>CASH IN(PHP)</th>
                            <th>CASH OUT(PHP)</th>
                            <th>LIMITS</th>
                        </tr>
                        <tr>
                            <td>level 1</td>
                            <td>Phone or Email Verification</td>
                            <td>2,000 PHP</td>
                            <td>0 PHP</td>
                            <td class="green">✓</td>
                        </tr>
                        <tr>
                            <td>level 2</td>
                            <td>ID, Selfie, Phone or Email Verification</td>
                            <td>50,000 PHP</td>
                            <td>50,000 PHP</td>
                            <td class="green">✓</td>
                        </tr>
                        <tr>
                            <td>level 3</td>
                            <td>Address Verification</td>
                            <td>400,000 PHP</td>
                            <td>400,000 PHP</td>
                            <td class="green">✓</td>
                        </tr>
                        <tr>
                            <td>level 4</td>
                            <td>Custom Application</td>
                            <td>Up to 5 Million</td>
                            <td>Up to 5 Million</td>
                            <td class="green">✓</td>
                        </tr>
                        <tr>
                            <td>Business</td>
                            <td>Business Verification</td>
                            <td>Enterprise</td>
                            <td>Enterprise</td>
                            <td class="green">✓</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="lv-section3">
                <button class="active-btn">Submit requirements</button>
            </div>
        </div>

        <!-- CONNECTED ACCOUNTS SECTION -->
        <div class="connected-accounts">
            <div class="connected-accts-section1">
                <h1>Connected Accounts</h1>
            </div>
            <div class="row section2">
                <div class="col-sm-6">
                    <button class="active-btn" disabled>Facebook not connected</button>
                    <button class="active-btn" disabled >Twitter not connected</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    date() {
        return {
            twitter_logo: '/svg/landing-twitter.svg',
            facebook_logo: '/svg/landing-facebook.svg'
        }
    }
    
}
</script>

