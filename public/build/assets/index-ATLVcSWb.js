import{b as c,d as y,z as _,w,r as o,o as k,c as C,C as t,a as e,B as a,a7 as S,E as d,G as D}from"./_plugin-vue_export-helper-DRuSU7Sp.js";import{P as j}from"./ProductService-L1ghk7-k.js";import{d as B}from"./app-SQe7OV8o.js";const N={class:"grid"},T=S('<div class="col-12 lg:col-6 xl:col-3"><div class="card mb-0"><div class="flex justify-content-between mb-3"><div><span class="block text-500 font-medium mb-3">Orders</span><div class="text-900 font-medium text-xl">152</div></div><div class="flex align-items-center justify-content-center bg-blue-100 border-round" style="width:2.5rem;height:2.5rem;"><i class="pi pi-shopping-cart text-blue-500 text-xl"></i></div></div><span class="text-green-500 font-medium">24 new </span><span class="text-500">since last visit</span></div></div><div class="col-12 lg:col-6 xl:col-3"><div class="card mb-0"><div class="flex justify-content-between mb-3"><div><span class="block text-500 font-medium mb-3">Revenue</span><div class="text-900 font-medium text-xl">$2.100</div></div><div class="flex align-items-center justify-content-center bg-orange-100 border-round" style="width:2.5rem;height:2.5rem;"><i class="pi pi-map-marker text-orange-500 text-xl"></i></div></div><span class="text-green-500 font-medium">%52+ </span><span class="text-500">since last week</span></div></div><div class="col-12 lg:col-6 xl:col-3"><div class="card mb-0"><div class="flex justify-content-between mb-3"><div><span class="block text-500 font-medium mb-3">Customers</span><div class="text-900 font-medium text-xl">28441</div></div><div class="flex align-items-center justify-content-center bg-cyan-100 border-round" style="width:2.5rem;height:2.5rem;"><i class="pi pi-inbox text-cyan-500 text-xl"></i></div></div><span class="text-green-500 font-medium">520 </span><span class="text-500">newly registered</span></div></div><div class="col-12 lg:col-6 xl:col-3"><div class="card mb-0"><div class="flex justify-content-between mb-3"><div><span class="block text-500 font-medium mb-3">Comments</span><div class="text-900 font-medium text-xl">152 Unread</div></div><div class="flex align-items-center justify-content-center bg-purple-100 border-round" style="width:2.5rem;height:2.5rem;"><i class="pi pi-comment text-purple-500 text-xl"></i></div></div><span class="text-green-500 font-medium">85 </span><span class="text-500">responded</span></div></div>',4),V={class:"col-12 xl:col-6"},L={class:"card"},J=t("h5",null,"Recent Sales",-1),M=["src","alt"],O={class:"col-12 xl:col-6"},P={class:"card"},R=t("h5",null,"Sales Overview",-1),G={__name:"index",setup(U){const{isDarkTheme:m}=B(),r=c(null),p=y({labels:["January","February","March","April","May","June","July"],datasets:[{label:"First Dataset",data:[65,59,80,81,56,55,40],fill:!1,backgroundColor:"#2f4860",borderColor:"#2f4860",tension:.4},{label:"Second Dataset",data:[28,48,40,19,86,27,90],fill:!1,backgroundColor:"#00bb7e",borderColor:"#00bb7e",tension:.4}]});c([{label:"Add New",icon:"pi pi-fw pi-plus"},{label:"Remove",icon:"pi pi-fw pi-minus"}]);const n=c(null),u=new j;_(()=>{u.getProductsSmall().then(s=>r.value=s)});const v=s=>s.toLocaleString("en-US",{style:"currency",currency:"USD"}),b=()=>{n.value={plugins:{legend:{labels:{color:"#495057"}}},scales:{x:{ticks:{color:"#495057"},grid:{color:"#ebedef"}},y:{ticks:{color:"#495057"},grid:{color:"#ebedef"}}}}},x=()=>{n.value={plugins:{legend:{labels:{color:"#ebedef"}}},scales:{x:{ticks:{color:"#ebedef"},grid:{color:"rgba(160, 167, 181, .3)"}},y:{ticks:{color:"#ebedef"},grid:{color:"rgba(160, 167, 181, .3)"}}}}};return w(m,s=>{s?x():b()},{immediate:!0}),(s,A)=>{const l=o("Column"),f=o("Button"),g=o("DataTable"),h=o("Chart");return k(),C("div",N,[T,t("div",V,[t("div",L,[J,e(g,{value:r.value,rows:5,paginator:!0,responsiveLayout:"scroll"},{default:a(()=>[e(l,{style:{width:"15%"}},{header:a(()=>[d(" Image ")]),body:a(i=>[t("img",{src:"demo/images/product/"+i.data.image,alt:i.data.image,width:"50",class:"shadow-2"},null,8,M)]),_:1}),e(l,{field:"name",header:"Name",sortable:!0,style:{width:"35%"}}),e(l,{field:"price",header:"Price",sortable:!0,style:{width:"35%"}},{body:a(i=>[d(D(v(i.data.price)),1)]),_:1}),e(l,{style:{width:"15%"}},{header:a(()=>[d(" View ")]),body:a(()=>[e(f,{icon:"pi pi-search",type:"button",class:"p-button-text"})]),_:1})]),_:1},8,["value"])])]),t("div",O,[t("div",P,[R,e(h,{type:"line",data:p,options:n.value},null,8,["data","options"])])])])}}};export{G as default};