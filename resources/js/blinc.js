const BitShares = require("btsdex")
const axios = require('axios')

/*Registrar account credentials*/
const accountName ='asd-chad'
const password = 'P5HxoQwu7jbL6Ure87XsEqJxmYEU9tFjDqCFgg5276wh2'

let registrar = {}


export default class Blinc {
  /**
  * Constructor
  */
  constructor(params){
		this.$registrar_account_name = params.account_name
		this.$registrar_password = params.password
		this.$registrar_private_key = params.private_key
		this.$node = params.node || 'wss://node.testnet.bitshares.eu'
		this.$isConnected = false
		this.$registrar = {}
		this.$account_name = null
		this.$account = {}
		this.$account_logged_in = false
		this.BitShares = BitShares
  }




  /**
  * Getter/Setter
  *
  * To check whether connected to BTS Nodes
  */
  get isConnected(){
		return this.$isConnected
  }




  /**
  * Account Name Validator
  *
  * @param {string} account_name - The account name of the wallet to create
  * @returns {boolean} valid - the accounts name is valid or not
  */
  validateAccountName(account_name){
		const maxUsernameLenght = 8
		var re = new RegExp("(?!.*[ \\t\\n])(?=.*^[a-z])(?=.*\\d)(?=.*[-])(?=.*[a-z]).{"+maxUsernameLenght+",}");
		var result = re.test(String(account_name).toLowerCase());
		// var result = /(?!.*[ \t\n!@#$%"'?:,^&*\-/\\+=()\[\]\{\}])^(([a-zA-Z]{1,}).*([-0-9a-zA-Z]))*$/.test(account_name)
		if (result) {
		  if (account_name.length<maxUsernameLenght) {
				return false
		  }else{
				return true
		  }
		}else{
		  return false
		}
  }




  /**
  * Search for account name 
  *
  * @param {string} account_name - the account name to search
  * @returns {array} accounts - the accounts retrieved
  */
  async searchAccountName(account_name){
    const accounts = await blinc.BitShares.db.lookup_account_names([account_name])
    return accounts
  }




  /**
  * Random String Generator
  *
  * @param {string} length - number of characters to generate
  * @returns {string} passphrase - the generated string
  */
  generateRandomString(length){
		let result = ''
		let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
		let charactersLength = characters.length
		for ( let i = 0; i < length; i++ ) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength))
		}
		const base64Seed = result.toString('base64')
		const passphrase = base64Seed.substr(0,length)
		return passphrase
  }




  /**
  * Connect to BTS Nodes
  *
  * @param {function} cb - callback function which receives an object
  * @return {object} res - response object once connected to nodes
  */
  async connect(cb){
		const self = this
		let result = {}
		BitShares.connect(self.$node).then(async function(res){
		  /*try to login as registrar*/
		  try{
		  	if(self.$registrar_private_key){
					self.$registrar = await BitShares(self.$registrar_account_name, self.$registrar_private_key)
		  	}else{
					self.$registrar = await BitShares.login(self.$registrar_account_name, self.$registrar_password)
		  	}
		  }catch(err){
				console.log('error occured')
		  }

		  console.log('connected to a BTS Node')
		  self.$isConnected = true
		  if(typeof cb == 'function'){
				result = res
				cb(result)
		  }

		/*something went wrong*/
		}).catch(function(err){
			  result.error = err
			  cb(result)
			  if(!self.$isConnected){
				BitShares.reconnect(self.$node)
		  }
		})
  }




  /**
  * Generate Public, Memo and Private Keys
  *
  * @param {string} account_name - the account name of the user
  * @return {object} generated - the generated keys and passphrase 
  * @return {object} generated.keys - the generated keys inlcuding private, public and memo keys 
  * @return {object} generated.passphrase - the generated passphrase 
  */
  async generateBTSKeys(account_name, password){
		const self = this
	   /*auto generate passphrase if password does not exist*/
		const passphrase = password || self.generateRandomString(40)
		console.log(passphrase)
		const keys = await BitShares.generateKeys(account_name, passphrase, ["active", "owner", "memo"])
		return {keys, passphrase}
  }




  /**
  * Login Via Password
  *
  * @param {string} account_name - the registered account_name of the user in bts
  * @param {string} password - the user's password
  * @return {object} account - the user's bts account
  */
  async loginViaPassword(account_name, password){
		const self = this
		let account = {} 
		try{
		  account = BitShares.login(account_name, password)
		}catch(err){
			account = {} 
			account.error = err

		}
		if(account.error){
			return account
		}
		self.$account_logged_in = true
		self.$account = account
		self.$account_name = account_name
		return account
  }




  /**
  * Login Via Private Key
  *
  * @param {string} account_name - the registered account_name of the user in bts
  * @param {string} private_key - the user's private_key
  * @return {object} account - the user's bts account
  */
  async loginViaPrivateKey(account_name, private_key){
		const self = this
		let account = {} 
		try{
		  account = new BitShares(account_name, private_key)
		}catch(err){
		  account.error = err
		  return account
		}

		self.$account_logged_in = true
		self.$account = account
		self.$account_name = account_name
		return account
  }



  /**
  * Get Account BTS Balance
  *
  * @param {string} account_name - the registered account_name of the user in bts
  * @return {object} bts - the user's bts balance
  * @return {object} bts.amount - the user's bts balance without decimal point
  * @return {object} bts.precision - the bts precision/number of decimal points
  * @return {object} bts.balance - the bts actual balance with precision or decimal points
  */
  async getAccountBTSBalance(account_name){
		const self = this
		const account = account_name || self.$account_name
		if(!account){
		  return new Error('Account name is required!')
		}

		/*get balance*/
		let account_balance = {}
		try{
		  account_balance = await BitShares.db.get_account_balances(account,[`1.3.0`])
		}catch(err){
		  account_balance.error = err
		  return account_assets
		}

		/*get assets*/
		let account_assets = {}
		try{
		  account_assets = await BitShares.db.get_assets([`1.3.0`])
		}catch(err){
		  account_assets.error = err
		  return account_assets
		}

		const amount = account_balance[0].amount
		const precision = account_assets[0].precision
		const final_amount = amount/Math.pow(10, precision)

		return {amount, precision, balance:final_amount}
  }




  /**
  * Get Bitshares Exchange Rate in USD
  *
  * @return {object} data - the data object containing the exchange rate
  * @return {object} data.exchange_rate - the actual exchange rate in USD
  */
  async getBTSExchangeRate(){
		let response = {}

		return new Promise((resolve, reject)=>{
		 axios.get('https://api.coincap.io/v2/assets/bitshares')
            .then(response => {
               const result = response.data;
           		if(result.data&&result.data.priceUsd){
				  resolve({exchange_rate:result.data.priceUsd})
				}else{
				  reject(result)
				}
            })
            .catch(err =>{
				reject(err)
            })
		 //  axios(options, function (error, response) {
			// if (error) reject(error)
			// const result = JSON.parse(response.body)
			// if(result.data&&result.data.priceUsd){
			//   resolve({exchange_rate:result.data.priceUsd})
			// }else{
			//   reject(result)
			// }
		 //  })
		})
  }




  /**
  * Get FIAT Exchange Rate
  *
  * @return {object} data - the data object containing the FIAT exchange rate
  * @return {object} data.rates - the actual exchange rates array
  * @return {object} data.base - the base or basis of the exchange[the currency to exchange]
  * @return {object} data.date - the date of requesting the rates data[this request]
  * @return {object} data.time_last_updated - the last timestamp the rate is updated
  */
  async getFIATExchangeRate(){
		// var options = {
		//   'method': 'GET',
		//   'url': 'https://api.exchangeratesapi.io/latest?base=USD',
		// }
		// let response = {}

		// return new Promise((resolve, reject)=>{
		//   axios(options, function (error, response) {
		// 	if (error) reject(error)
		// 	resolve(JSON.parse(response.body))
		//   })
		// })
		return new Promise((resolve, reject)=>{
			axios.get('https://api.exchangeratesapi.io/latest?base=USD')
			.then(response => {
			   const result = response.data;
			    if(result){
			      resolve(result)
			    }else{
			      reject(result)
			    }
			})
			.catch(err =>{
			    reject(err)
			})
		})

  }




  /**
  * Convert
  *
  * @param {string} currency - the currency to convert, default is 'PHP'
  * @param {number} amount - the amount to convert
  * @return {object} data - the data object
  * @return {object} data.amount - the amount to convert
  * @return {object} data.currency - the currency to convert
  * @return {object} data.exchange_rate - the exchange rate of BTS
  * @return {object} data.converted - the converted amount
  */
  async convertBTS(currency='PHP', amount=0){
		const self = this
		const fiat = await self.getFIATExchangeRate()
		const result = {}
		if(!fiat.rates){
		  return result.error = {message:'No rates fetched!'}
		}

		/*get the currency if exist*/
		const currency_selected = fiat.rates[currency.toUpperCase()]
		if(!currency_selected){
		  return result.error = {message:'Currency not found!'}
		}

		const bitshares_to_usd = await self.getBTSExchangeRate()
		/*check if data exists*/
		const usd_price = bitshares_to_usd?(bitshares_to_usd.exchange_rate?bitshares_to_usd.exchange_rate:null):null
		if(!usd_price){
		  return result.error = {message:'Record not found!'}
		}

		const exchange_rate = Number(usd_price) * Number(currency_selected)
		const converted = (exchange_rate) * Number(amount) 
		result.amount = amount
		result.currency = currency
		result.exchange_rate = exchange_rate
		result.converted = converted
		return result
  }




  /**
  * Get Asset
  *
  * @param {string} asset - the asset to fetch from BTS database
  * @return {object} asset - the asset fetched
  */
  async getAsset(asset){
		const asset_result = await BitShares.get_asset(asset)
		return asset_result
  }




  /**
  * Transfer amount
  *
  * @param {object} sender - the account holder who will send the amount
  * @param {string} asset - the asset to send i.e. BTS
  * @param {string} receiver_account - the account name of the receiver
  * @param {number} amount - the asset to send i.e. BTS
  * @param {string} memo - optional remarks or message
  * @return {object} transcation - the transaction object containing the block number and transaction id
  */
  async transferAsset(sender, asset, receiver_account, amount=0, memo=''){
		const self = this 
		const sender_account = sender || self.$registrar
		const asset_name = asset
		const receiver_account_name = receiver_account || "asd-chad"
        let result = {} 
        try{
            result = await sender_account.transfer(receiver_account_name, asset_name, amount, memo)
        }catch(err){
            result.error = err
        }
        return result
  }




  /**
  * Create Account
  *
  * @param {string} account_name - the account name to create
  * @param {string} password - the password of the account
  * @returns {object} block - returns the block record from the blockchain where the account is inserted 
  */
  async createAccount(account_name, password){
		const self = this
		let result = {}
		const {keys, passphrase} = await self.generateBTSKeys(account_name, password)
		const OWNER_PUBKEY = keys.pubKeys.owner
		const ACTIVE_PUBKEY = keys.pubKeys.active
		const MEMO_PUBKEY = keys.pubKeys.memo
		/*account params*/
		let params = {
		  fee: {amount: 0, asset_id: "1.3.0"},
		  name: account_name, // name to be created
		  registrar: "1.2.23960", // registrars id
		  referrer: "1.2.23960", // referrer id
		  referrer_percent: 5000,
		  owner: {
				weight_threshold: 1,
				account_auths: [],
				key_auths: [[OWNER_PUBKEY, 1]], // generated owner public key
				address_auths: []
		  },
		  active: {
				weight_threshold: 1,
				account_auths: [],
				key_auths: [[ACTIVE_PUBKEY, 1]], //generated active public key
				address_auths: []
		  },
		  options: {
				memo_key: MEMO_PUBKEY, // generated memo key
				voting_account: "1.2.5",
				num_witness: 0,
				num_committee: 0,
				votes: []
		  },
		  extensions: []
		}

		/*for broadcasting*/
		let tx = {} 
		try{
		  tx = self.$registrar.newTx()
		}catch(err){
		  result.error = err
		  return result  
		}

		try{
		  tx.account_create(params) // 'account_create' is name operation
		}catch(err){
		  result.error = err
		  return result  
		}

		try{
		  result = await tx.broadcast()
		}catch(err){
		  result.error = err
		  return result  
		}

		return result
  } 

}