!function(){var a="",s="",r="1.0",e=new Date,t=e.getYear()+"_"+e.getMonth()+"_"+e.getDate(),d="pc",n=navigator.userAgent.toLowerCase(),p="",g=!1,u="",_="web",o=!0;function c(e,n,o,t,i,a,s,r,d){if(s||(s="POST"),!e)throw new Error("url is not find...");if(r||(r="JSON"),d||(d="application/x-www-form-urlencoded"),n="h5_data="+m(n),1==o)var p=function(e){var n=[];for(var o in e)n.push(encodeURIComponent(o)+"="+encodeURIComponent(e[o]));return n.push(("v="+Math.random()).replace(".","")),n.join("&")}(n);else p=n;if(window.XMLHttpRequest)var g=new XMLHttpRequest;else g=new ActiveXObject("Microsoft.XMLHTTP");g.onreadystatechange=function(){if(4==g.readyState){var e=g.status;if(200<=e&&e<300){var n=JSON.parse(g.responseText);t(n,a,g.responseXML)}else i(e)}},"GET"==s?(g.open("GET",e+"?"+p,!0),g.send(null)):"POST"==s&&(g.open("POST",e,!0),g.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),g.send(p))}function m(e){e.ut=sessionStorage.getItem("gn_h5_token_"+s);var n=JSON.stringify(e),o=window.btoa(unescape(encodeURIComponent(n)));return S.encrypt(o,i,w)}function l(e){b("网络连接错误")}function f(e){if("success"==e.msg){var n=e.data;switch(n.callback_type){case"init":return sessionStorage.setItem("gn_h5_token_"+s,n.ut),p=n.game_url,v("初始化成功."),void(1==n.change&&function(e){if(e){v("自动登录.");var n=a+"/api/v1/web/automaticLogin",o={game_version:r,sign:"",app_id:sessionStorage.getItem("gn_h5_appid_"+s),ag:"",ua:_,rs:"1",tt:h(),df:_,ut:sessionStorage.getItem("gn_h5_token_"+s),deviceType:d};c(n,o,!1,f,l)}}(!0));case"login":if(v("登录成功"),function(e){var n=e.userlist;if(""==n)return!1;var o="";for(var t in n){var i=n[t].username;v(i),o+='<a href="javascript:;" >'+i+"</a>"}return $("#loadAccountSelect").html(o),$(".gray").show(),$("#userInfoDiv").hide(),$("#loadaccountPayDiv").show(),!0}(n))return void v("手机下多个账号");var o=null==(i=n.userid)?"登陆错误,userid is null!":(window.sdkModule.LoginUserid=i,!1);return o?void b(o):(SuspendedBall.Add(),SuspendedBall.Move(),v("悬浮球初始化成功"),b("登录成功!"),SuspendedBall.ShowBall(),sessionStorage.setItem(u,I(p,n)),t=I(p,n),$(".wrapper").removeClass("index_bg"),$("#userInfoDiv").hide(),$("#gameframe iframe").prop("src",t),$("#gameframe").show(),window.sdkModule.Isloaded=g=!0,void(g&&($("#CloseUserIcon").click(),SuspendedBallContent.init())));case"register":return v("账号注册成功."),void b("账号注册成功");case"regMobile":return v("手机注册成功."),void b("手机注册成功");case"update":v("修改密码成功"),$("#findPwdWinpop").hide(),$("#loginWinpop").show();case"bindmobile":v("绑定成功"),$(".no_bindphone").hide(),$("#bindPhoneBackA").click()}v(n)}else"automaticLoginFail"==e.msg?b("登陆失败请重新登录"):(b(e.msg),"用户名已存在"==e.msg&&$("#reg_code").val(""),v(e));var t,i}"ipad"==(d="ipad"==n.match(/ipad/i)?"ipad":"pc")||"iphone"==(d="iphone"==n.match(/iphone/i)?"iphone":"pc")||(d="android"==n.match(/android/i)?"android":"pc");var i="G$a*/a%9#2D^cfOj",w="GNwl$%^&X#4Df!@#";function v(e){o&&console.log(e)}function h(){var e=Date.parse(new Date).toString();return time_s=e.substr(0,10),time_s}var S={encrypt:function(e,n,o){n=CryptoJS.enc.Utf8.parse(n),o=CryptoJS.enc.Utf8.parse(o);return CryptoJS.AES.encrypt(e,n,{iv:o,mode:CryptoJS.mode.CBC,padding:CryptoJS.pad.ZeroPadding})},decrypt:function(e,n,o){n=CryptoJS.enc.Utf8.parse(n),o=CryptoJS.enc.Utf8.parse(o);return CryptoJS.AES.decrypt(e,n,{iv:o,mode:CryptoJS.mode.CBC,padding:CryptoJS.pad.ZeroPadding}).toString(CryptoJS.enc.Utf8)}};function y(e,n){"pc"==d&&$(".gray").show(),$("#gameframe").hide(),$("#payframe").show();var o=m(n),t=document.createElement("form");t.action=e,t.method="post",t.target="payWindow",t.style.display="none";var i=document.createElement("textarea");return i.name="h5_data",i.value=o,t.appendChild(i),document.body.appendChild(t),t.submit(),t}function b(e){$("#noticeDiv").html(e),$("#noticeDiv").show(),$("#noticeDiv").delay(2e3).fadeOut()}function I(e,n){var o=[];for(var t in n)"game_url"!=t&&"userlist"!=t&&o.push(encodeURIComponent(t)+"="+encodeURIComponent(n[t]));return e+"?"+o.join("&")}var k=function(){c(a+"/api/v1/web/logout",{game_version:r,sign:"",app_id:sessionStorage.getItem("gn_h5_appid_"+s),ag:"",ua:_,rs:"1",tt:h(),df:_,ut:sessionStorage.getItem("gn_h5_token_"+s),deviceType:d},!1,f,l),location.reload()},M=function(e,n){null!=e&&""!=e&&window.frames[0].postMessage({event:e,data:n},"*")};window.addEventListener("message",function(e){var n,o=e.data;switch(o.event){case"ready":break;case"fastPay":n=o.data,y(a+"/api/v7/pay/webpay",{game_version:r,oi:{pn:n.orderinfo.product_name,pi:n.orderinfo.product_id,pd:n.orderinfo.product_desc,rt:n.orderinfo.exchange_rate,crn:n.orderinfo.currency_name,ext:n.orderinfo.ext,pc:n.orderinfo.product_count,pp:n.orderinfo.product_price,coi:n.orderinfo.cp_order_id},ua:_,app_id:sessionStorage.getItem("gn_h5_appid_"+s),ut:sessionStorage.getItem("gn_h5_token_"+s),ag:"",tt:n.timestamp,df:_,ri:{pn:n.roleinfo.party_name,rt:"5",rv:n.roleinfo.role_vip,rb:n.roleinfo.role_balence,rct:n.roleinfo.rolelevel_ctime,rmt:n.roleinfo.rolelevel_mtime,sn:n.roleinfo.server_name,rn:n.roleinfo.role_name,sd:n.roleinfo.server_id,rd:n.roleinfo.role_id,rl:n.roleinfo.role_level},sign:n.sign});break;case"logout":k()}},!1),window.sdkModule={},window.sdkModule.deviceType=d,window.sdkModule.initGnSdk=function(e,n){window.sdkModule.appid=e,_=window.atob(n),MiniSite.JsLoader.load("index/js_lib/aes.js",function(){MiniSite.JsLoader.load("index/js_lib/pad-zeropadding.js",function(){MiniSite.JsLoader.load("index/js_lib/crypto-js.js",function(){s=e,sessionStorage.setItem("gn_h5_appid_"+s,e),c(a+"/api/v1/web/open",{game_version:r,app_id:e,tt:h(),df:_},!1,f,l)})})})},window.sdkModule.UserClosepay=function(){$("#payframe iframe").prop("src",""),$("#payframe").hide(),$(".gray").hide(),$("#gameframe").show(),v("支付完成"),M("onPayComplete","PayCompleted")},window.sdkModule.send_sms_ph=function(e){var n="gn_h5_sms_"+t,o=sessionStorage.getItem(n);c(a+"/api/v1/web/sms/send_bindphone",{game_version:r,sign:"",ua:_,app_id:sessionStorage.getItem("gn_h5_appid_"+s),ut:sessionStorage.getItem("gn_h5_token_"+s),ag:"",tt:h(),df:_,mb:e},!1,f,l),o++,sessionStorage.setItem(n,o)},window.sdkModule.sdkLogin=function(e,n){c(a+"/api/v1/web/login",{game_version:r,sign:"",app_id:sessionStorage.getItem("gn_h5_appid_"+s),ag:"",ua:_,tt:h(),df:_,ut:sessionStorage.getItem("gn_h5_token_"+s),deviceType:d,uname:e,pwd:n},!1,f,l)},window.sdkModule.register=function(e,n){c(a+"/api/v1/web/register",{game_version:r,sign:"",app_id:sessionStorage.getItem("gn_h5_appid_"+s),ag:"",tt:h(),df:_,uname:e,ua:_,pwd:n},!1,f,l)},window.sdkModule.update_password=function(e,n,o){c(a+"/api/v1/web/forgetpwd",{game_version:r,sign:"",app_id:sessionStorage.getItem("gn_h5_appid_"+s),ag:"",tt:h(),df:_,mb:e,sc:o,ut:sessionStorage.getItem("gn_h5_token_"+s),st:"3",ua:_,pwd:n},!1,f,l)},window.sdkModule.send_sms=function(e){var n="gn_h5_sms_"+t,o=sessionStorage.getItem(n);c(a+"/api/v1/web/sms/send",{game_version:r,sign:"",ua:_,app_id:sessionStorage.getItem("gn_h5_appid_"+s),ut:sessionStorage.getItem("gn_h5_token_"+s),ag:"",tt:h(),df:_,mb:e,st:"1"},!1,f,l),o++,sessionStorage.setItem(n,o)},window.sdkModule.send_sms_up=function(e){var n="gn_h5_sms_"+t,o=sessionStorage.getItem(n);c(a+"/api/v1/web/sms/send_up",{game_version:r,sign:"",ua:_,app_id:sessionStorage.getItem("gn_h5_appid_"+s),ut:sessionStorage.getItem("gn_h5_token_"+s),ag:"",tt:h(),df:_,mb:e,st:"3"},!1,f,l),o++,sessionStorage.setItem(n,o)},window.sdkModule.update_bindmobile=function(e,n,o){c(a+"/api/v1/web/update_bindphone",{game_version:r,sign:"",app_id:sessionStorage.getItem("gn_h5_appid_"+s),ag:"",tt:h(),df:_,mb:e,sc:o,ut:sessionStorage.getItem("gn_h5_token_"+s),st:"3",ua:_,mbd:n},!1,f,l)},window.sdkModule.mobile_register=function(e,n,o){c(a+"/api/v1/web/regmobile",{tt:h(),df:_,st:"1",mb:e,sc:o,app_id:sessionStorage.getItem("gn_h5_appid_"+s),ag:"",game_version:"1.0",ua:_,pwd:n,ut:sessionStorage.getItem("gn_h5_token_"+s),sign:""},!1,f,l)},window.sdkModule.SDKLogout=k,window.sdkModule.WechatPay=function(e){window.parent.document.getElementById("weixinPayDiv").style.display="block",window.parent.document.getElementById("weixinpay_url").setAttribute("href",e)},window.sdkModule.WechatPay_close=function(){$("weixinPayDiv").hide(),$("#weixinpay_url").prop("src","")}}();