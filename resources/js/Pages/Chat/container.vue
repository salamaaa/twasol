<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <chat-room-selection :rooms="chatRooms"
                                     :current-room="currentRoom"
                                     v-if="currentRoom.id"
                                     v-on:roomChanged="setRoom($event)"
                />
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <messages-container :messages="messages"/>
                    <input-message :room="currentRoom"
                                   v-on:messageSent="getMessages()"
                    />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import MessagesContainer from "@/Pages/Chat/messagesContainer";
import InputMessage from "@/Pages/Chat/inputMessage";
import ChatRoomSelection from "@/Pages/Chat/chatRoomSelection";

export default {
    components: {
        ChatRoomSelection,
        InputMessage,
        MessagesContainer,
        AppLayout,
    },
    data: function () {
        return {
            chatRooms: [],
            currentRoom: [],
            messages: []
        }
    },
    watch: {
        currentRoom(val,oldVal) {
            if(oldVal.id){
                this.disconnect(oldVal);
            }
            this.connect();
        }
    },
    methods: {
        connect() {
            if (this.currentRoom.id) {
                let vm = this;
                this.getMessages();
                window.Echo.private('chat.' + this.currentRoom.id)
                    .listen('message.new', e => {
                        vm.getMessages()
                    });
            }
        },
        disconnect(room){
            window.Echo.leave('chat.'+room.id);
        },
        getRooms() {
            axios.get('/chat/rooms')
                .then(response => {
                    this.chatRooms = response.data;
                    this.setRoom(response.data[0]);
                })
                .catch(error => {
                    console.log(error)
                });
        }
        ,
        getMessages() {
            axios.get('/chat/room/' + this.currentRoom.id + '/messages')
                .then(response => {
                    this.messages = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
        ,
        setRoom(room) {
            this.currentRoom = room;
        }
    },
    created() {
        this.getRooms();
    }
}
</script>
