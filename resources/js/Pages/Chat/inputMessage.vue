<template>
    <div class="relative h-10 m-1">
        <div class="grid grid-cols-6" style="border:1px solid #e6e6e6">
            <input type="text"
                   v-model="message"
                   @keyup.enter="sendMessage()"
                   placeholder="Message.."
                   class="col-span-5 focus:ring-0 outline-none p-1">
            <button @click="sendMessage()"
                    class="place-self-end bg-gray-500 hover:bg-blue-700 rounded p-1 mt-1 text-white"
            >Send
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "inputMessage",
    props: ['room'],
    data:function (){
        return {
            message:''
        }
    },
    methods:{
        sendMessage(){
            if(this.message == ""){
                return;
            }
            axios.post('/chat/room/'+this.room.id+'/newMessage',{
                message:this.message
            })
            .then(response=>{
                if (response.status == 201){
                    this.message = "";
                    this.$emit('messageSent');
                }
            })
            .catch(error=>{
                console.log(error);
            });
        }
    }
}
</script>

<style scoped>

</style>
