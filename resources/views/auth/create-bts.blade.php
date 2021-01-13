<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous" ></script>
<script src="{{ asset('js/btsdex.min.js') }}" defer></script>


@extends('layouts.app')

@section('content')
<div class="log-section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-5.2">

                    <div class="row bts-connect">
                        <div class="col-md-12 d-flex justify-content-center">
                            <h1>Bitshares Account</h1>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center bts-connect-border">
                            <a href="/connect-bts">Connect Account</a>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center bts-connect-border active">
                            <a class="active" href="/create-bts">Create BTS Account</a>
                        </div>
                        
                    </div>
                    
                    @include('alert')

                    <form>
                        @CSRF

                        <div class="form-group row">
                            <label for="account" class="col-md-12 col-form-label">{{ __('Account Name') }}</label>

                            <div class="col-md-12">
                                <input id="bts_wallet_name" type="text" class=" @error('bts_wallet_name') is-invalid @enderror" name="bts_wallet_name" value="{{ old('bts_wallet_name') }}" required autocomplete="bts_wallet_name" autofocus placeholder="Bitshare Account Name">

                                @error('account')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">

                            {{-- <label for="password" class="col-md-12 col-form-label"> {{ __('Auto Generated Password') }} </label> --}}
                            <label for="password" class="col-md-12 col-form-label"> {{ __('Password') }} </label>

                            <div class="col-md-12">
                                <input id="password" type="password" class=" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="confirm-password" class="col-md-12 col-form-label">{{ __('Confirm Password') }}</label>

                            <div class="col-md-12">
                                <input id="confirm_password" type="password" class=" @error('confirm_password') is-invalid @enderror" name="password_confirmation" required autocomplete="current-password" placeholder="Copy & paste generated password here">

                                @error('confirm_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">I understand that I will <b>LOSE ACCESS</b> to my funds if I <b>LOSE MY PASSWORD.</b></label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                <label class="custom-control-label" for="customCheck2">I understand that <b>NO ONE</b> can recover my password if I <b>LOSE OR FORGET</b> it.</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck3">
                                <label class="custom-control-label" for="customCheck3">I have <b>WRITTEN DOWN</b> or otherwise <b>STORED</b> my password.</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <button id="addWallet" type="submit" class="btn btn-primary">
                                    Create BTS Account
                                    {{-- {{ __('Connect') }} --}}
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group row d-flex justify-content-center mb-0">
                            <div class="col-md-5 d-flex justify-content-center">
                                <a href="http://127.0.0.1:8000/home">Skip</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Script -->
<script type='text/javascript'>
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  $(document).ready(function(){
    
    // create new wallet
    
    $('#addWallet').click(function(e) {
      e.preventDefault(); // wait the script to finish 
      
      var bts_wallet_name = document.getElementById("bts_wallet_name").value;
     
      if(bts_wallet_name != ''){
        $.ajax({
          url: 'addBTSWallet',
          type: 'post',
          data: {_token: CSRF_TOKEN,bts_wallet_name: bts_wallet_name},
          beforeSend:function()
          {
             $('#addWallet').attr('disabled', 'disabled');
             $('#addWallet').val('Submitting...');
            CreateBtsWallet();
          },
          success:function(response){
            if(response > 0){
               alert('success response > 0  .');
              $('#addWallet').attr('disabled', false);
              $('#addWallet').val('Submit');
            }else{
              alert(response);
              $('#addWallet').attr('disabled', false);
              $('#addWallet').val('Submit');
              //alert(response);// account is already used
            }
          }
        });
      }else{
        alert('Fill all fields');
      }
    });
    
  });
  

    function CreateBtsWallet() { 
      // Account to register
      var bts_wallet_name = document.getElementById("bts_wallet_name").value;
      var wallet_password = document.getElementById("password").value;
      var confirm_password = document.getElementById("confirm_password").value;
      
      if (wallet_password == '') 
      alert("Please enter Password"); 
      
      // If confirm password not entered 
      else if (confirm_password == '')  
      alert("Please enter confirm password"); 
      
      // If Not same return False.     
      else if (wallet_password != confirm_password) { 
        alert("\nPassword did not match: Please try again...") 
        return false; 
      } 
      // continue if no error
     
        
      BitShares.connect('wss://node.testnet.bitshares.eu').then(function(res){
      console.log(res);
      BitShares.subscribe('connected', start);
    }).catch(function(err){
      console.log(err);
      BitShares.reconnect('wss://testnet.dex.trading');
    });
    
    // Account to authorize create account
    var accntName = 'asd-chad';
    var pass = 'P5HxoQwu7jbL6Ure87XsEqJxmYEU9tFjDqCFgg5276wh2';
    var privateActiveKey = '5KcHYUn84iaE7ykA7JjfnuSE5shgxVjE3tb8pkXWN8RXgiiMgEE';
    var ref_id = '1.2.23960';
    
    
    //var genPassword = "";
    //document.write("PASSWORD: ",genPassword);
    async function start(){
      //GENERATE KEYS FOR KEY_AUTHS
      var genKeys = await BitShares.generateKeys(bts_wallet_name,wallet_password);
      var GKactive = genKeys.pubKeys.active; 
      var GKowner = genKeys.pubKeys.owner;
      var GKmemo = genKeys.pubKeys.memo;
      
      var account = new BitShares(accntName,privateActiveKey);
      
      var params = {
        fee: {amount: 0, asset_id: "1.3.0"},
        name: bts_wallet_name,
        registrar: ref_id, //asd-chad
        referrer: ref_id, //
        referrer_percent: 5000,
        owner: {
          weight_threshold: 1,
          account_auths: [],
          key_auths: [[GKowner, 1]],
          address_auths: []
        },
        active: {
          weight_threshold: 1,
          account_auths: [],
          key_auths: [[GKactive, 1]],
          address_auths: []
        },
        options: {
          memo_key: GKmemo,
          voting_account: ref_id,
          num_witness: 0,
          num_committee: 0,
          votes: []
        },
        extensions: []
      };
      
      var tx = account.newTx()
      tx.account_create(params) 
      
      tx.broadcast().then(function(result){
        console.log("Success!");
        console.log("REGISTERED!");
        console.log(result);
        $('#addWallet').attr('disabled', false);
        $('#addWallet').val('Submit');
        alert("congratz! "+bts_wallet_name + " wallet account is created");
        //window.location.href = ('http://127.0.0.1:8000/home');
      }).catch(function (err){
        alert('Account is already used. Choose different account name.');
        $('#addWallet').attr('disabled', false);
        $('#addWallet').val('Submit');
        console.log(err);
      });
    }  
  }
    
    
    function myFunction() {
      var wallet = document.getElementById("password");
      if (wallet.type === "password") {
        wallet.type = "text";
      } else {
        wallet.type = "password";
      }
    }
    function myFunction2() {
      var x = document.getElementById("confirm_password");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }
    
    function myFunction() {
      var wallet = document.getElementById("password");
      if (wallet.type === "password") {
        wallet.type = "text";
      } else {
        wallet.type = "password";
      }
    }
    function myFunction2() {
      var x = document.getElementById("confirm_password");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }
    
    window.addEventListener('load', async function () {
            // await?
            // var  walletCheck = document.getElementById("walletOption").textContent;

            // if(walletCheck === 'BTS Wallet Connected'){
            //     walletOption.start();
            // }

            var password = {
            
            // Add another object to the rules array here to add rules.
            // They are executed from top to bottom, with callbacks in between if defined.
            rules: [
                //Take a combination of 12 letters and numbers, both lower and upper case.
                {
                    characters: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890',
                    max: 40
                },
                //Take 4 special characters, use the callback to shuffle the resulting 16 character string
                {
                    characters: 'bitshareslabs',
                    max: 5,
                    callback: function (s) {
                        var a = s.split(""),
                            n = a.length;

                        for (var i = n - 1; i > 0; i--) {
                            var j = Math.floor(Math.random() * (i + 1));
                            var tmp = a[i];
                            a[i] = a[j];
                            a[j] = tmp;
                        }
                        return a.join("");
                    }
                }
            ],
            generate: function () {
                var g = '';

                $.each(password.rules, function (k, v) {
                    var m = v.max;
                    for (var i = 1; i <= m; i++) {
                        g = g + v.characters[Math.floor(Math.random() * (v.characters.length))];
                    }
                    if (v.callback) {
                        g = v.callback(g);
                    }
                });
                return g;
            }
        }
            var generated_password = password.generate();
           // this.password = generated_password;
            console.log(generated_password);
            
            document.getElementById("password").innerHTML = generated_password;
            // not working --> // document.getElementById("bts_wallet_name").innerHTML = "test";
            
            // alert(generated_password);
        })

  </script>