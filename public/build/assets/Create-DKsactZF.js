import{o as m,f as p,a as r,u as c,w as u,F as _,Z as f,b as e,i as l,k as a,t as i,l as s,m as h,d as v}from"./app-SjQgPyui.js";import{_ as b}from"./GuestLayout-C30Lhk1k.js";import{s as g,t as y,a as V}from"./toast-DN51TBCR.js";import"./ApplicationLogo-COZLwfcU.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const U=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"Dashboard",-1),C={class:"py-12"},k={class:"row"},N={class:"col-md-12"},D=e("h1",null," Sign Up ",-1),E=e("legend",null,[e("span",{class:"number"},"1"),v(" Your Basic Info")],-1),S={class:"form-input-container"},B=e("label",{for:"name"},"Name:",-1),M={class:"form-input-container"},O=e("label",{for:"email"},"Email:",-1),P={class:"form-input-container"},w=e("label",{for:"phone"},"Phone:",-1),F={class:"form-input-container"},I=e("label",null,"Gender:",-1),R={class:"radio_container"},T=e("label",{for:"male",class:"light"},"Male",-1),j=e("label",{for:"female",class:"light"},"Female",-1),q=e("label",{for:"Other",class:"light"},"Others",-1),A={class:"form-input-container"},G=e("label",{for:"address"},"Address:",-1),Y={class:"form-input-container"},Z={class:"form-input-container"},$=e("label",{for:"nationality"},"Nationality:",-1),z=e("option",{value:"Nepal"},"Nepal",-1),H=e("option",{value:"India"},"India",-1),J=e("option",{value:"China"},"China",-1),K=[z,H,J],L={class:"form-input-container"},Q=e("label",{for:"dob"},"Date of birth:",-1),W={class:"form-input-container"},X=e("label",{for:"education_background"},"Education Background:",-1),x={class:"form-input-container"},ee=e("label",null,"Preferred mode of contact:",-1),te={class:"radio_container"},ne=e("label",{for:"phone",class:"light"},"Phone",-1),oe=e("label",{for:"email",class:"light"},"Email",-1),le=e("label",{for:"none",class:"light"},"None",-1),ie=e("button",{class:"button-success",type:"submit"},"Sign Up",-1),ae={name:"CreateClient",data(){return{client:{},validation_errors:{}}},methods:{createClient(d){d.preventDefault(),g("/api/v1/clients",this.client).then(t=>{console.log(t.data),this.resetClient(),this.validation_errors={},y(t.data.message)}).catch(t=>{if(t.response.status==422){this.validation_errors=t.response.data.errors;return}console.log(t.response),V("Could not save client info!")})},resetClient(){this.client={name:"",email:"",phone:"",gender:"",address:"",nationality:"",dob:"",education_background:"",preferred_contact_mode:""}}}},pe=Object.assign(ae,{setup(d){return(t,n)=>(m(),p(_,null,[r(c(f),{title:"Dashboard"}),r(b,null,{header:u(()=>[U]),default:u(()=>[e("div",C,[e("div",k,[e("div",N,[e("form",{onSubmit:n[13]||(n[13]=(...o)=>t.createClient&&t.createClient(...o))},[D,e("fieldset",null,[E,e("div",S,[B,l(e("input",{type:"text","onUpdate:modelValue":n[0]||(n[0]=o=>t.client.name=o),id:"name"},null,512),[[a,t.client.name]]),e("small",null,i(t.validation_errors.name),1)]),e("div",M,[O,l(e("input",{type:"email",id:"mail","onUpdate:modelValue":n[1]||(n[1]=o=>t.client.email=o)},null,512),[[a,t.client.email]]),e("small",null,i(t.validation_errors.email),1)]),e("div",P,[w,l(e("input",{type:"text",id:"phone",name:"phone","onUpdate:modelValue":n[2]||(n[2]=o=>t.client.phone=o)},null,512),[[a,t.client.phone]]),e("small",null,i(t.validation_errors.phone),1)]),e("div",F,[I,e("div",R,[e("div",null,[l(e("input",{type:"radio",id:"male",value:"Male","onUpdate:modelValue":n[3]||(n[3]=o=>t.client.gender=o)},null,512),[[s,t.client.gender]]),T]),e("div",null,[l(e("input",{type:"radio",id:"female",value:"Female","onUpdate:modelValue":n[4]||(n[4]=o=>t.client.gender=o)},null,512),[[s,t.client.gender]]),j]),e("div",null,[l(e("input",{type:"radio",id:"Other",value:"Other","onUpdate:modelValue":n[5]||(n[5]=o=>t.client.gender=o)},null,512),[[s,t.client.gender]]),q])]),e("small",null,i(t.validation_errors.gender),1)]),e("div",A,[G,l(e("input",{type:"text",id:"address","onUpdate:modelValue":n[6]||(n[6]=o=>t.client.address=o)},null,512),[[a,t.client.address]]),e("small",null,i(t.validation_errors.address),1)]),e("div",Y,[e("div",Z,[$,l(e("select",{"onUpdate:modelValue":n[7]||(n[7]=o=>t.client.nationality=o),id:"nationality"},K,512),[[h,t.client.nationality]]),e("small",null,i(t.validation_errors.nationality),1)])]),e("div",L,[Q,l(e("input",{type:"date",id:"dob","onUpdate:modelValue":n[8]||(n[8]=o=>t.client.dob=o)},null,512),[[a,t.client.dob]]),e("small",null,i(t.validation_errors.dob),1)]),e("div",W,[X,l(e("input",{type:"text",id:"education_background","onUpdate:modelValue":n[9]||(n[9]=o=>t.client.education_background=o)},null,512),[[a,t.client.education_background]]),e("small",null,i(t.validation_errors.education_background),1)]),e("div",x,[ee,e("div",te,[e("div",null,[l(e("input",{type:"radio",id:"phone",value:"Phone","onUpdate:modelValue":n[10]||(n[10]=o=>t.client.preferred_contact_mode=o)},null,512),[[s,t.client.preferred_contact_mode]]),ne]),e("div",null,[l(e("input",{type:"radio",id:"email",value:"Email","onUpdate:modelValue":n[11]||(n[11]=o=>t.client.preferred_contact_mode=o)},null,512),[[s,t.client.preferred_contact_mode]]),oe]),e("div",null,[l(e("input",{type:"radio",id:"none","onUpdate:modelValue":n[12]||(n[12]=o=>t.client.preferred_contact_mode=o)},null,512),[[s,t.client.preferred_contact_mode]]),le])]),e("small",null,i(t.validation_errors.preferred_contact_mode),1)])]),ie],32)])])])]),_:1})],64))}});export{pe as default};
