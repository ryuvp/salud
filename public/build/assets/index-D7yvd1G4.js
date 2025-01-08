import{b as u,F as te,z as le,r,o as d,c,C as a,a as o,B as n,E as y,G as p,K as ae,L as oe,I as D,H as h,D as F}from"./_plugin-vue_export-helper-DRuSU7Sp.js";import{e as se,F as ie}from"./app-SQe7OV8o.js";import{P as ne}from"./ProductService-L1ghk7-k.js";import{R as ue}from"./roles-DGNZBkQD.js";const de={class:"grid"},re={class:"col-12"},ce={class:"card"},ve=a("div",{class:"my-2"},[a("h5",{class:"m-0"},"Manage Roles")],-1),me={class:"flex flex-column md:flex-row md:justify-content-start md:align-items-center"},pe=a("span",{class:"p-column-title"},"Name",-1),fe=a("span",{class:"p-column-title"},"Permissions",-1),_e=["src","alt"],ye={class:"field"},ge=a("label",{for:"name"},"Name",-1),be={key:0,class:"p-invalid"},he={class:"field"},ke=a("label",{for:"description"},"Description",-1),Se={class:"field"},Ve=a("label",{for:"inventoryStatus",class:"mb-3"},"Inventory Status",-1),we={key:0},Ce={key:1},xe={key:2},Pe={class:"field"},De=a("label",{class:"mb-3"},"Category",-1),Te={class:"formgrid grid"},Ne={class:"field-radiobutton col-6"},Ie=a("label",{for:"category1"},"Accessories",-1),Ue={class:"field-radiobutton col-6"},Re=a("label",{for:"category2"},"Clothing",-1),Be={class:"field-radiobutton col-6"},Fe=a("label",{for:"category3"},"Electronics",-1),Le={class:"field-radiobutton col-6"},Me=a("label",{for:"category4"},"Fitness",-1),qe={class:"formgrid grid"},Oe={class:"field col"},Ae=a("label",{for:"price"},"Price",-1),Ke={key:0,class:"p-invalid"},ze={class:"field col"},Ee=a("label",{for:"quantity"},"Quantity",-1),je={class:"flex align-items-center justify-content-center"},$e=a("i",{class:"pi pi-exclamation-triangle mr-3",style:{"font-size":"2rem"}},null,-1),Ye={key:0},Ge={class:"flex align-items-center justify-content-center"},He=a("i",{class:"pi pi-exclamation-triangle mr-3",style:{"font-size":"2rem"}},null,-1),Qe={key:0},tt={__name:"index",setup(We){const k=se(),v=u(null),T=u(null),_=u(!1);u(!1);const g=u(!1),b=u(!1);u(!1),u(!1);const e=u({});u({});const S=u(null),N=u(null),L=u(null),V=u({}),f=u(!1),M=u([{label:"INSTOCK",value:"instock"},{label:"LOWSTOCK",value:"lowstock"},{label:"OUTOFSTOCK",value:"outofstock"}]),I=new ne,q=new ue;te(()=>{H()}),le(()=>{I.getProducts().then(s=>v.value=s),I.getProducts().then(s=>{v.value=s}),q.get().then(s=>{T.value=s.data})});const O=()=>{e.value={},f.value=!1,_.value=!0},A=()=>{_.value=!1,f.value=!1},K=()=>{f.value=!0,e.value.name&&e.value.name.trim()&&e.value.price&&(e.value.id?(e.value.inventoryStatus=e.value.inventoryStatus.value?e.value.inventoryStatus.value:e.value.inventoryStatus,v.value[$(e.value.id)]=e.value,k.add({severity:"success",summary:"Successful",detail:"Product Updated",life:3e3})):(e.value.id=U(),e.value.code=U(),e.value.image="product-placeholder.svg",e.value.inventoryStatus=e.value.inventoryStatus?e.value.inventoryStatus.value:"INSTOCK",v.value.push(e.value),k.add({severity:"success",summary:"Successful",detail:"Product Created",life:3e3})),_.value=!1,e.value={})},z=s=>{e.value={...s},_.value=!0},E=s=>{e.value=s,g.value=!0},j=()=>{v.value=v.value.filter(s=>s.id!==e.value.id),g.value=!1,e.value={},k.add({severity:"success",summary:"Successful",detail:"Product Deleted",life:3e3})},$=s=>{let l=-1;for(let i=0;i<v.value.length;i++)if(v.value[i].id===s){l=i;break}return l},U=()=>{let s="";const l="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";for(let i=0;i<5;i++)s+=l.charAt(Math.floor(Math.random()*l.length));return s},Y=()=>{b.value=!0},G=()=>{v.value=v.value.filter(s=>!S.value.includes(s)),b.value=!1,S.value=null,k.add({severity:"success",summary:"Successful",detail:"Products Deleted",life:3e3})},H=()=>{V.value={global:{value:null,matchMode:ie.CONTAINS}}};return(s,l)=>{const i=r("Button"),Q=r("Toolbar"),W=r("InputIcon"),R=r("InputText"),J=r("IconField"),w=r("Column"),x=r("Tag"),X=r("DataTable"),Z=r("Textarea"),ee=r("Dropdown"),C=r("RadioButton"),B=r("InputNumber"),P=r("Dialog");return d(),c("div",de,[a("div",re,[a("div",ce,[o(Q,{class:"mb-2"},{start:n(()=>[ve]),end:n(()=>[o(i,{label:"New",icon:"pi pi-plus",class:"mr-2",severity:"success",onClick:O}),o(i,{label:"Delete",icon:"pi pi-trash",severity:"danger",onClick:Y,disabled:!S.value||!S.value.length},null,8,["disabled"])]),_:1}),o(X,{ref_key:"dt",ref:L,value:T.value,selection:N.value,"onUpdate:selection":l[1]||(l[1]=t=>N.value=t),dataKey:"id",paginator:!0,rows:10,filters:V.value,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,25],currentPageReportTemplate:"Showing {first} to {last} of {totalRecords} products"},{header:n(()=>[a("div",me,[o(J,{iconPosition:"left",class:"block mt-2 md:mt-0"},{default:n(()=>[o(W,{class:"pi pi-search"}),o(R,{class:"w-full sm:w-auto",modelValue:V.value.global.value,"onUpdate:modelValue":l[0]||(l[0]=t=>V.value.global.value=t),placeholder:"Search..."},null,8,["modelValue"])]),_:1})])]),default:n(()=>[o(w,{selectionMode:"multiple",headerStyle:"width: 3rem"}),o(w,{field:"name",header:"Name",headerStyle:"width:14%; min-width:10rem;"},{body:n(t=>[pe,y(" "+p(t.data.name),1)]),_:1}),o(w,{field:"route",header:"Permissions",headerStyle:"width:70%; min-width:10rem;"},{body:n(t=>[fe,(d(!0),c(ae,null,oe(t.data.permissions,m=>(d(),c("span",{key:m},[m.name.includes(".")?(d(),D(x,{key:0,severity:"success"},{default:n(()=>[y(p(m.description),1)]),_:2},1024)):m.name.split(".").length>1?(d(),D(x,{key:1,severity:"warning"},{default:n(()=>[y(p(m.description),1)]),_:2},1024)):(d(),D(x,{key:2,severity:"info"},{default:n(()=>[y(p(m.description),1)]),_:2},1024))]))),128))]),_:1}),o(w,{headerStyle:"min-width:10rem;"},{body:n(t=>[o(i,{icon:"pi pi-pencil",class:"mr-2",severity:"success",rounded:"",onClick:m=>z(t.data)},null,8,["onClick"]),o(i,{icon:"pi pi-trash",class:"mt-2",severity:"warning",rounded:"",onClick:m=>E(t.data)},null,8,["onClick"])]),_:1})]),_:1},8,["value","selection","filters"]),o(P,{visible:_.value,"onUpdate:visible":l[11]||(l[11]=t=>_.value=t),style:{width:"450px"},header:"Product Details",modal:!0,class:"p-fluid"},{footer:n(()=>[o(i,{label:"Cancel",icon:"pi pi-times",text:"",onClick:A}),o(i,{label:"Save",icon:"pi pi-check",text:"",onClick:K})]),default:n(()=>[e.value.image?(d(),c("img",{key:0,src:"/demo/images/product/"+e.value.image,alt:e.value.image,width:"150",class:"mt-0 mx-auto mb-5 block shadow-2"},null,8,_e)):h("",!0),a("div",ye,[ge,o(R,{id:"name",modelValue:e.value.name,"onUpdate:modelValue":l[2]||(l[2]=t=>e.value.name=t),modelModifiers:{trim:!0},required:"true",autofocus:"",invalid:f.value&&!e.value.name},null,8,["modelValue","invalid"]),f.value&&!e.value.name?(d(),c("small",be,"Name is required.")):h("",!0)]),a("div",he,[ke,o(Z,{id:"description",modelValue:e.value.description,"onUpdate:modelValue":l[3]||(l[3]=t=>e.value.description=t),required:"true",rows:"3",cols:"20"},null,8,["modelValue"])]),a("div",Se,[Ve,o(ee,{id:"inventoryStatus",modelValue:e.value.inventoryStatus,"onUpdate:modelValue":l[4]||(l[4]=t=>e.value.inventoryStatus=t),options:M.value,optionLabel:"label",placeholder:"Select a Status"},{value:n(t=>[t.value&&t.value.value?(d(),c("div",we,[a("span",{class:F("product-badge status-"+t.value.value)},p(t.value.label),3)])):t.value&&!t.value.value?(d(),c("div",Ce,[a("span",{class:F("product-badge status-"+t.value.toLowerCase())},p(t.value),3)])):(d(),c("span",xe,p(t.placeholder),1))]),_:1},8,["modelValue","options"])]),a("div",Pe,[De,a("div",Te,[a("div",Ne,[o(C,{id:"category1",name:"category",value:"Accessories",modelValue:e.value.category,"onUpdate:modelValue":l[5]||(l[5]=t=>e.value.category=t)},null,8,["modelValue"]),Ie]),a("div",Ue,[o(C,{id:"category2",name:"category",value:"Clothing",modelValue:e.value.category,"onUpdate:modelValue":l[6]||(l[6]=t=>e.value.category=t)},null,8,["modelValue"]),Re]),a("div",Be,[o(C,{id:"category3",name:"category",value:"Electronics",modelValue:e.value.category,"onUpdate:modelValue":l[7]||(l[7]=t=>e.value.category=t)},null,8,["modelValue"]),Fe]),a("div",Le,[o(C,{id:"category4",name:"category",value:"Fitness",modelValue:e.value.category,"onUpdate:modelValue":l[8]||(l[8]=t=>e.value.category=t)},null,8,["modelValue"]),Me])])]),a("div",qe,[a("div",Oe,[Ae,o(B,{id:"price",modelValue:e.value.price,"onUpdate:modelValue":l[9]||(l[9]=t=>e.value.price=t),mode:"currency",currency:"USD",locale:"en-US",invalid:f.value&&!e.value.price,required:!0},null,8,["modelValue","invalid"]),f.value&&!e.value.price?(d(),c("small",Ke,"Price is required.")):h("",!0)]),a("div",ze,[Ee,o(B,{id:"quantity",modelValue:e.value.quantity,"onUpdate:modelValue":l[10]||(l[10]=t=>e.value.quantity=t),integeronly:""},null,8,["modelValue"])])])]),_:1},8,["visible"]),o(P,{visible:g.value,"onUpdate:visible":l[13]||(l[13]=t=>g.value=t),style:{width:"450px"},header:"Confirm",modal:!0},{footer:n(()=>[o(i,{label:"No",icon:"pi pi-times",text:"",onClick:l[12]||(l[12]=t=>g.value=!1)}),o(i,{label:"Yes",icon:"pi pi-check",text:"",onClick:j})]),default:n(()=>[a("div",je,[$e,e.value?(d(),c("span",Ye,[y("Are you sure you want to delete "),a("b",null,p(e.value.name),1),y("?")])):h("",!0)])]),_:1},8,["visible"]),o(P,{visible:b.value,"onUpdate:visible":l[15]||(l[15]=t=>b.value=t),style:{width:"450px"},header:"Confirm",modal:!0},{footer:n(()=>[o(i,{label:"No",icon:"pi pi-times",text:"",onClick:l[14]||(l[14]=t=>b.value=!1)}),o(i,{label:"Yes",icon:"pi pi-check",text:"",onClick:G})]),default:n(()=>[a("div",Ge,[He,e.value?(d(),c("span",Qe,"Are you sure you want to delete the selected products?")):h("",!0)])]),_:1},8,["visible"])])])])}}};export{tt as default};
