<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class=" col-2">
            <div class="card m-0">
                <div class="card-header">
                    Usuarios conectados
                </div>
                <ul class="list-group list-group-flush">
                    <li v-for="user in usuarios" :key="user.id" class="list-group-item" @click="asignTo(user.id, user.name)">
                        <img :src="'img/'+user.avatar" alt="" width="60" height="60" class="rounded-circle mr-1" style="background:gray"><span class="badge badge-dark text-white">{{user.name}}</span>
                        <span :id="user.id" class=" float-right badge badge-danger badge-pill text-white d-none parpadea">new</span></li>               
                </ul>
                <span v-if="usuarios.length == 0" class="alert-danger">No hay usuarios conectados</span>
            </div>
            </div>
            <div class=" col-6">
            <div class="card m-0">
                <div class="card-header navbar-dark bg-dark text-white">Bienvenid@ al chat, {{user.name}} <span class="float-right">estás chateando con  
                    <span class="badge alert-info">{{actualUser}}</span></span>
                                     
                </div>
                <div  id="chat-area" class="card-body chat-list-area">
                    <li v-if="history" v-for="words in history_chat" class="list-unstyled">
                            <span :class="words.from == user.id ? 'badge-dark':'badge-primary'" class="badge badge-pill text-white">{{words.from}}</span><span class="ml-1">{{words.text}}</span>
                            <span v-if="timeMessage == true" class="text-muted float-right"><em>{{ words.created_at | timeago }}</em></span>
                    </li>
                    <li v-if="onseeMessages" v-for="words in messageNotsee" class="list-unstyled">
                            <span :class="words.from == user.id ? 'badge-dark':'badge-primary'" class="badge badge-pill text-white">{{words.from.name}}</span><span class="ml-1">{{words.text}}</span>
                            <span v-if="timeMessage == true" class="text-muted float-right"><em>{{ words.created_at | timeago }}</em></span>
                    </li>
                    <li v-for="words in chats" class="list-unstyled">
                            <span :class="words.from.name == user.name ? 'badge-dark':'badge-primary'" class="badge badge-pill text-white">{{words.from.name}}</span><span class="ml-1">{{words.text}}</span>
                            <span v-if="timeMessage == true" class="text-muted float-right"><em>{{ words.created_at | timeago }}</em></span>
                    </li>
            </div>        
            </div>
              
                <div v-if="keyboard==true" class="form-group">
                    <form @submit.prevent="send()">
                        <div class="form-group">
                            <input type="text" class="form-control chat-text-area" id="" rows="3" v-model="message" @click="starTyping()">
                            <!-- <button type="submit" class="btn btn-primary btn-sm">Send</button> -->
                        </div>
                    </form>
                </div>
            </div>
            <div class=" col-2">
            <div class="card m-0" >
                <div class="card-header">
                    Opciones
                </div>
                <ul  class="list-group list-group-flush">
                    <li class="list-group-item">
                         <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" @click="history=!history">
                                Mostrar historial
                            </label>
                        </div>
                        
                    </li>    
                     <li class="list-group-item">
                         <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" @click="timeMessage=!timeMessage">
                                No mostrar tiempo en mensajes
                            </label>
                        </div>                       
                    </li>   
                </ul>
            </div>
             </div>

            
        </div>
    </div>
</template>

<script>
moment.locale('es');
//import ConectedUsersComponent from '../components/ConectedUsersComponent.vue'
    export default {
     
        data(){                    
                return{
                    incomming:{},
                    chats:[],
                    message:"",
                    usuarios:[],
                    usuario:'',
                    to:'',
                    keyboard:false,
                    from:'',
                    history_chat:[],
                    history:false,
                    onseeMessages:false,
                    messageNotsee:[],
                    actualUser:'NADIE comienza ya!!!',
                    timeMessage:true,
                    firstFrom:'',
                    change:false
                }
        },
        filters: {
            timeago(value) {
                return moment.utc(value).local().fromNow()
            },
           
        },
        props:['user'],
        components:{

    //        ConectedUsersComponent
        },
        mounted() {
            this.getConnectedUsers();
            Echo.channel('DemoChannel')
            .listen('WebsocketDemoEvent', (e) => { 
     
                    if (e.to==this.user.id && (e.from.id==this.to || this.to=='') ) {
                        this.chats.push({
                        'from':e.from,
                        'text':e.message,
                        'to':e.to,                   
                    }); 
                    this.to=e.from.id;
                    this.firstFrom=e.from.id;
                    $("#chat-area").animate({ scrollTop: $('#chat-area')[0].scrollHeight});
                    this.keyboard=true;                  
                    }
                    else if (e.to==this.user.id ) { 
                        if (this.incomming[e.from.id]) {
                             this.incomming[e.from.id].push({
                                'from':e.from,
                                'text':e.message,
                                'to':e.to,                   
                                }); 
                        }
                        else{
                            this.$set(this.incomming, e.from.id, [{
                                'from':e.from,
                                'text':e.message,
                                'to':e.to,                   
                                }])
                        }
                                             
                    }
                    
                     if(e.to==this.user.id ){
                          $('#'+ e.from.id).removeClass('d-none');
                     }
                     this.from=e.from.id;
                
             })
              Echo.channel('user-channel')
            .listen('UserEvent', (e) => {
                                       
                if (e.action=="insert") {
                    this.usuarios.push(e.user); 
                } 
                else{
                    for (let index = 0; index < this.usuarios.length; index++) {
                        if(this.usuarios[index].name == e.user.name){
                                this.usuarios.splice(index,1);
                        }
                        
                    }
                }                
             }) 
        },
        methods:{
                getChats(from,to){

                    var url = "messages/"+from+"/"+to;
                    axios.get(url).then(response => 
                    {
                        this.history_chat = response.data;
                    }
                    
                    )
                },
                send(){

                    var m = {
                        text:this.message,
                        to:this.to,
                        from:this.user
                    };
                    
                    var url = "messages";
                    axios.post(url,
                    {
                        text:this.message,
                        from:this.user.id,
                        to:this.to

                    }
                    ).then(response => 
                    {
                        this.message="";
                        this.chats.push(m);
                        $("#chat-area").animate({ scrollTop: $('#chat-area')[0].scrollHeight});

                    }
                    
                    )
                },
                asignTo(id, name){
                    this.keyboard=true;
                    this.to=id;
                    this.getChats(this.user.id,id);
                    $('#'+ id).addClass('d-none');                    
                    this.messageNotsee=this.incomming[id];    
                    this.incomming[id]='',          
                    this.onseeMessages=true;                   
                    this.actualUser = name;
                    console.log('id'+id+'-firstfrom'+this.firstFrom+'-from:'+this.from+'-to:'+this.to);
                    if (id!=this.firstFrom && id!=this.to) {
                        this.chats=[];
                    }
                     if (id!=this.firstFrom && this.from==this.to) {
                        this.chats=[];
                    }
                    if (id==this.to && this.from==this.firstFrom && id!=this.firstFrom ) {
                        this.chats=[];
                    }
                },
                starTyping(id){
                    if (this.to==this.from) {
                        $('#'+ this.from).addClass('d-none');
                    }
                },
                getConnectedUsers(){
                    var url = "get_connected";
                    axios.get(url).then(response => 
                    {                        
                        this.usuarios= response.data;
                    }                    
                    )
                },
        },
        
    }
</script>
<style>
    .chat-list-area{
        height: 400px; 
        overflow: auto;  
    }
    .chat-text-area{
        
        margin-top:0.5rem;
        border-color:green;

    }
    @-webkit-keyframes rotating {
        from{
            -webkit-transform: rotate(0deg);
        }
        to{
            -webkit-transform: rotate(360deg);
        }
    }

    .rotating {
        -webkit-animation: rotating 2s linear infinite;
    }   
    .parpadea {
    
    animation-name: parpadeo;
    animation-duration: 1s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;

    -webkit-animation-name:parpadeo;
    -webkit-animation-duration: 1s;
    -webkit-animation-timing-function: linear;
    -webkit-animation-iteration-count: infinite;
    }

    @-moz-keyframes parpadeo{  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
    }

    @-webkit-keyframes parpadeo {  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
    }

    @keyframes parpadeo {  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
    }
        
</style>

