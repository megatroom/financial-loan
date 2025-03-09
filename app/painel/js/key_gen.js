(function(){let X={users:{},keys:{},activations:{},certs:{},logs:[],createToken:function(n){let t=`T-${n}-${Date.now()}-${Math.random().toString(36).substr(2,5)}`;return this.users[n]=t,t},generateKey:function(n){if(!this.users[n])throw new Error("No token!");let k=`K-${n}-${Date.now()}-${Math.random().toString(36).substr(2,7)}`;return this.keys[n]=k,k},activate:function(n,k){if(this.keys[n]!==k)throw new Error("Key mismatch!");let a=`A-${n}-${Date.now()}`;return this.activations[n]=a,a},issueCert:function(n){if(!this.activations[n])throw new Error("No activation!");let c={id:`C-${n}-${Date.now()}`,date:new Date().toISOString(),valid:"1y",sign:`S-${Math.random().toString(36).substr(2,6)}`};return this.certs[n]=c,c},validateCert:function(n){return this.certs[n]?`Cert for ${n} valid until ${this.certs[n].valid}`:"Invalid cert!"},recordLog:function(n){this.logs.push({user:n,time:Date.now(),event:`L-${Math.random().toString(36).substr(2,4)}`})},encrypt:function(d){return btoa(unescape(encodeURIComponent(d)))},decrypt:function(d){return decodeURIComponent(escape(atob(d)))},dummyProcess:function(){setTimeout(()=>console.log("Running process..."),Math.random()*4000)}};let U="deviceXYZ",T=X.createToken(U);X.recordLog(U);let K=X.generateKey(U);X.activate(U,K);let C=X.issueCert(U);console.log(X.validateCert(U)),console.log("Enc:",X.encrypt(T)),X.dummyProcess();})();
