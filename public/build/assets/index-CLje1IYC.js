import{b as r,p as pe,F as ve,r as m,o as E,c as I,C as e,a as l,B as o,K as _e,L as fe,E as i,G as n,H as he}from"./_plugin-vue_export-helper-DRuSU7Sp.js";import{e as ye,b as be,F as ge}from"./app-SQe7OV8o.js";import{R as U,U as we}from"./user-BG8TCbkm.js";import{D as Ce}from"./diagnostic-2-gVixzI.js";class xe extends U{constructor(){super("cie10")}}class Se extends U{constructor(){super("prescription")}}class ke extends U{constructor(){super("medicament")}}const De={class:"grid"},Re={class:"col-12"},Ve={class:"card"},Ee={class:"flex flex-column md:flex-row md:justify-content-between md:align-items-center"},Ie=e("h5",{class:"m-0"},"Seguimineto de Pacientes",-1),Pe=e("span",{class:"p-column-title"},"Documento",-1),Me=e("span",{class:"p-column-title"},"Apellidos y Nombres",-1),Ne=e("span",{class:"p-column-title"},"Edad",-1),Fe=e("span",{class:"p-column-title"},"Sexo",-1),Ue=e("span",{class:"p-column-title"},"IPRESS",-1),qe={class:"p-3"},Le={class:"my-2"},Te={class:"m-0"},Be=e("b",null,"Diagnostico:",-1),Ae={class:"m-0"},$e=e("b",null,"IPRESS:",-1),je={class:"field"},Ge=e("b",null,"Paciente:",-1),ze={class:"formgrid grid"},Ke={class:"field col"},Oe=e("b",null,"Edad:",-1),We={class:"field col"},Ye=e("b",null,"Sexo:",-1),He={class:"field"},Je=e("b",null,"Dirección:",-1),Qe={class:"field"},Xe=e("b",null,"IPRESS:",-1),Ze={class:"field"},et=e("label",null,[e("b",null,"Diagnostico:")],-1),tt=e("div",{class:"flex align-items-center justify-content-center"},[e("i",{class:"pi pi-exclamation-triangle mr-3",style:{"font-size":"2rem"}}),e("span",null,[i("El paciente no se encuentra registrado en tu centro de salud, "),e("br"),i(),e("b",null,"¿Deseas hacer el cambio?"),i("?")])],-1),at={class:"field"},lt=e("label",null,[e("b",null,"Medicamento:")],-1),st={key:0,class:"p-invalid"},ot={class:"formgrid grid"},it={class:"field col"},nt=e("label",null,[e("b",null,"Cantidad:")],-1),dt={class:"field col"},rt=e("label",null,[e("b",null,"Frecuencia:")],-1),ct={class:"field"},ut=e("label",null,[e("b",null,"Fecha de inicio:")],-1),mt=e("div",{class:"flex align-items-center justify-content-center"},[e("i",{class:"pi pi-exclamation-triangle mr-3",style:{"font-size":"2rem"}}),e("span",null,"Are you sure you want to delete this medicament?")],-1),yt={__name:"index",setup(P){const p=ye(),q=r(null),_=r({}),h=r({}),d=r({}),x=r({}),S=r(null),b=r(null),k=r(null),g=r(null),L=r(null),T=r(null),M=r([]),N=r([]),B=r([]),w=r(!1),A=new we,W=new xe,Y=new Ce,H=new ke,$=new Se,J=be(),F=pe(()=>J.ipress.id);ve(()=>{D(),te()});const D=async()=>{try{const s=await A.follow();q.value=s.data}catch{p.add({severity:"error",summary:"Error",detail:"Error al cargar los usuarios",life:3e3})}},Q=async()=>{try{const s=await W.list();L.value=s}catch{p.add({severity:"error",summary:"Error",detail:"Error al cargar los diagnosticos",life:3e3})}},X=async()=>{try{const s=await H.list();T.value=s}catch{p.add({severity:"error",summary:"Error",detail:"Error al cargar los medicaments",life:3e3})}},Z=s=>{const t=s.query.toLowerCase().split(" ");M.value=L.value.filter(f=>t.every(C=>f.name.toLowerCase().includes(C)))},ee=s=>{const t=s.query.toLowerCase().split(" ");N.value=T.value.filter(f=>t.every(C=>f.name.toLowerCase().includes(C)))},te=()=>{x.value={global:{value:null,matchMode:ge.CONTAINS}}},ae=s=>{S.value=!0,Q(),h.value.patient=s,_.value=s,_.value.fullname=`${s.name} ${s.lastname}`},R=()=>{S.value=!1,M.value=[]},j=()=>{b.value=!1,R()},G=async()=>{const s={user_id:h.value.patient.id,ipress_id:F.value,cie10_id:h.value.cie10.id};console.log(s);try{let t;t=await Y.store(s),t&&(p.add({severity:"success",summary:"Guardado",detail:"Diagnostico guardado",life:3e3}),R(),D())}catch{p.add({severity:"error",summary:"Error",detail:"Error al guardar el diagnostico",life:3e3})}},le=()=>{if(F.value!=h.value.patient.ipress.id){b.value=!0;return}else G()},se=async()=>{const s={ipress:{id:F.value}};try{let t;t=await A.update(h.value.patient.id,s),t&&(b.value=!1,G())}catch{j(),p.add({severity:"error",summary:"Error",detail:"Error al cambiar la IPRESS",life:3e3})}},oe=s=>{k.value=!0,w.value=!1,d.value.diagnostic=s,X()},ie=async()=>{if(w.value=!0,!!d.value.medicament)try{let s;s=await $.store(d.value),s&&(p.add({severity:"success",summary:"Guardado",detail:"Prescripción guardada",life:3e3}),ne(),D())}catch{p.add({severity:"error",summary:"Error",detail:"Error al guardar la prescripción",life:3e3})}},ne=()=>{k.value=!1,d.value={},w.value=!1,N.value=[]},de=s=>{console.log(s),d.value=s,g.value=!0},re=()=>{$.destroy(d.value.id).then(s=>{const{status:t,message:f}=s;t==="success"?(g.value=!1,D(),p.add({severity:t,summary:t,detail:f,life:3e3})):p.add({severity:t,summary:t,detail:f,life:3e3})})};return(s,t)=>{const f=m("InputIcon"),C=m("InputText"),ce=m("IconField"),c=m("Column"),u=m("Button"),ue=m("Toolbar"),z=m("DataTable"),K=m("AutoComplete"),V=m("Dialog"),O=m("InputNumber"),me=m("Calendar");return E(),I("div",De,[e("div",Re,[e("div",Ve,[l(z,{value:q.value,expandedRows:B.value,"onUpdate:expandedRows":t[1]||(t[1]=a=>B.value=a),dataKey:"id",paginator:!0,rows:50,filters:x.value,paginatorTemplate:"FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown",rowsPerPageOptions:[5,10,50,100],currentPageReportTemplate:"Mostrando {first} a {last} de {totalRecords} pacientes"},{header:o(()=>[e("div",Ee,[Ie,l(ce,{iconPosition:"left",class:"block mt-2 md:mt-0"},{default:o(()=>[l(f,{class:"pi pi-search"}),l(C,{class:"w-full sm:w-auto",modelValue:x.value.global.value,"onUpdate:modelValue":t[0]||(t[0]=a=>x.value.global.value=a),placeholder:"Buscar..."},null,8,["modelValue"])]),_:1})])]),expansion:o(a=>[e("div",qe,[(E(!0),I(_e,null,fe(a.data.diagnostics,y=>(E(),I("div",{key:y.id},[l(ue,{class:"mb-0 mt-1"},{start:o(()=>[e("div",Le,[e("p",Te,[Be,i(" "+n(y.cie10),1)]),e("p",Ae,[$e,i(" "+n(y.ipress.name),1)])])]),end:o(()=>[l(u,{label:"Agregar Medicamento",icon:"pi pi-plus",severity:"success",onClick:v=>oe(y),class:"mr-2 inline-block"},null,8,["onClick"])]),_:2},1024),l(z,{value:y.prescriptions},{default:o(()=>[l(c,{field:"medicament",header:"Medicamento",sortable:!1,headerStyle:"width:25%; min-width:10rem;"},{body:o(v=>[i(n(v.data.medicament),1)]),_:2},1024),l(c,{field:"date",header:"Fecha",sortable:!1,headerStyle:"width:25%; min-width:10rem;"},{body:o(v=>[i(n(v.data.created_at),1)]),_:2},1024),l(c,{field:"quantity",header:"Cantidad",sortable:!1,headerStyle:"width:25%; min-width:10rem;"},{body:o(v=>[i(n(v.data.quantity),1)]),_:2},1024),l(c,{field:"frequency",header:"Frecuencia",sortable:!1,headerStyle:"width:25%; min-width:10rem;"},{body:o(v=>[i(" Cada "+n(v.data.frequency)+" horas ",1)]),_:2},1024),l(c,{headerStyle:"min-width:10rem;"},{body:o(v=>[l(u,{icon:"pi pi-trash",class:"mt-2",severity:"warning",rounded:"",onClick:pt=>de(v.data)},null,8,["onClick"])]),_:2},1024)]),_:2},1032,["value"])]))),128))])]),default:o(()=>[l(c,{expander:!0,headerStyle:"width: 3rem"}),l(c,{field:"document",header:"Documento",sortable:!1,headerStyle:"width:14%; min-width:10rem;"},{body:o(a=>[Pe,i(" "+n(a.data.document),1)]),_:1}),l(c,{field:"name",header:"Nombre",sortable:!1,headerStyle:"width:31%; min-width:10rem;"},{body:o(a=>[Me,i(" "+n(a.data.lastname)+", "+n(a.data.name),1)]),_:1}),l(c,{field:"age",header:"Edad",sortable:!1,headerStyle:"width:14%; min-width:10rem;"},{body:o(a=>[Ne,i(" "+n(a.data.age),1)]),_:1}),l(c,{field:"sex",header:"Sexo",sortable:!1,headerStyle:"width:10%; min-width:10rem;"},{body:o(a=>[Fe,i(" "+n(a.data.sex_format),1)]),_:1}),l(c,{field:"ipress",header:"Ipress",sortable:!1,headerStyle:"width:31%; min-width:10rem;"},{body:o(a=>[Ue,i(" "+n(a.data.ipress?a.data.ipress.name:""),1)]),_:1}),l(c,{headerStyle:"min-width:10rem;"},{body:o(a=>[l(u,{label:"Nuevo",severity:"success",onClick:y=>ae(a.data),class:"mr-2 inline-block"},null,8,["onClick"])]),_:1})]),_:1},8,["value","expandedRows","filters"]),l(V,{visible:S.value,"onUpdate:visible":t[3]||(t[3]=a=>S.value=a),style:{width:"850px"},header:"Nuevo Diagnostico",modal:!0,class:"p-fluid"},{footer:o(()=>[l(u,{label:"Cancel",icon:"pi pi-times",text:"",onClick:R}),l(u,{label:"Save",icon:"pi pi-check",text:"",onClick:le})]),default:o(()=>[e("div",je,[e("label",null,[Ge,i(" "+n(_.value.fullname),1)])]),e("div",ze,[e("div",Ke,[e("label",null,[Oe,i(" "+n(_.value.age)+" años",1)])]),e("div",We,[e("label",null,[Ye,i(" "+n(_.value.sex_format),1)])])]),e("div",He,[e("label",null,[Je,i(" "+n(_.value.address)+", "+n(_.value.district.name),1)])]),e("div",Qe,[e("label",null,[Xe,i(" "+n(_.value.ipress?_.value.ipress.name:""),1)])]),e("div",Ze,[et,l(K,{id:"cie10",dropdown:!1,modelValue:h.value.cie10,"onUpdate:modelValue":t[2]||(t[2]=a=>h.value.cie10=a),optionValue:"id",suggestions:M.value,onComplete:Z,field:"name",placeholder:"Seleccione un diagnóstico"},null,8,["modelValue","suggestions"])])]),_:1},8,["visible"]),l(V,{visible:b.value,"onUpdate:visible":t[4]||(t[4]=a=>b.value=a),style:{width:"450px"},header:"Confirm",modal:!0},{footer:o(()=>[l(u,{label:"No",icon:"pi pi-times",text:"",onClick:j}),l(u,{label:"Yes",icon:"pi pi-check",text:"",onClick:se})]),default:o(()=>[tt]),_:1},8,["visible"]),l(V,{visible:k.value,"onUpdate:visible":t[9]||(t[9]=a=>k.value=a),style:{width:"850px"},header:"Nuevo Medicamento",modal:!0,class:"p-fluid"},{footer:o(()=>[l(u,{label:"Cancel",icon:"pi pi-times",text:"",onClick:R}),l(u,{label:"Save",icon:"pi pi-check",text:"",onClick:ie})]),default:o(()=>[e("div",at,[lt,l(K,{id:"cie10",dropdown:!1,modelValue:d.value.medicament,"onUpdate:modelValue":t[5]||(t[5]=a=>d.value.medicament=a),optionValue:"id",suggestions:N.value,onComplete:ee,field:"name",required:"true",invalid:w.value&&!d.value.medicament,placeholder:"Seleccione un medicamento"},null,8,["modelValue","suggestions","invalid"]),w.value&&!d.value.medicament?(E(),I("small",st,"Medicamento es requerido.")):he("",!0)]),e("div",ot,[e("div",it,[nt,l(O,{modelValue:d.value.quantity,"onUpdate:modelValue":t[6]||(t[6]=a=>d.value.quantity=a),mode:"decimal",minFractionDigits:2},null,8,["modelValue"])]),e("div",dt,[rt,l(O,{modelValue:d.value.frequency,"onUpdate:modelValue":t[7]||(t[7]=a=>d.value.frequency=a),mode:"decimal",minFractionDigits:2},null,8,["modelValue"])])]),e("div",ct,[ut,l(me,{modelValue:d.value.start_date,"onUpdate:modelValue":t[8]||(t[8]=a=>d.value.start_date=a),dateFormat:"dd/mm/yy"},null,8,["modelValue"])])]),_:1},8,["visible"]),l(V,{visible:g.value,"onUpdate:visible":t[11]||(t[11]=a=>g.value=a),style:{width:"450px"},header:"Confirm",modal:!0},{footer:o(()=>[l(u,{label:"No",icon:"pi pi-times",text:"",onClick:t[10]||(t[10]=a=>g.value=!1)}),l(u,{label:"Yes",icon:"pi pi-check",text:"",onClick:re})]),default:o(()=>[mt]),_:1},8,["visible"])])])])}}};export{yt as default};