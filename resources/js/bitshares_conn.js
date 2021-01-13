import BitShares from 'btsdex';

window.addEventListener("load" ,function(){
	BitShares.connect('wss://node.testnet.bitshares.eu').then(function(res){
		console.log(res);
		console.log("connected to BitShares")
		BitShares.subscribe('connected');
	}).catch(function(err){
		console.log(err);
		BitShares.reconnect('wss://node.testnet.bitshares.eu');
	});
});