<template>
    <div class="card">
        <div class="card-header">聊天室</div>
        <div class="card-body">
            <div class="message" style="height: 480px;overflow: auto;" id="message">
                <div class="send" v-for="message in messages">
                    <div class="name">{{ message.name }}</div>
                    <div class="content">{{ message.content }}</div>
                    <div class="time"><p>{{ message.time }}</p></div>
                </div>
            </div>
            <div class="divider"><hr style="border-top:1px dashed #987cb9;" width="100%" color="#987cb9" size=1></div>
            <div class="footer">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="send message">
                    <button type="submit" class="btn btn-primary" v-on:click="sendForRoom()">发送</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            //页面是否登录
            if (localStorage.getItem("is_login") === null) {
                //本地存储中是否有token(uid)数据
                this.$router.push("/login");
            }
            this.init();
        },
        data() {
            return{
                postData : {},
                paramCodeData : {},
                messages : [],
            }
        },
        methods: {
            init: function () {
                if(typeof(WebSocket) === "undefined"){
                    this.$message({
                        type: 'warning',
                        message: '您的浏览器不支持socket'
                    });
                    return;
                }
                // 实例化socket
                this.socket = new WebSocket('ws://127.0.0.1:8080')
                // 监听socket连接
                this.socket.onopen = this.open
                // 监听socket错误信息
                this.socket.onerror = this.error
                // 监听socket消息
                this.socket.onmessage = this.getMessage
            },
            open: function () {
                // 登录
                this.postData = {
                    'token' : localStorage.getItem("token"),
                    // 'token' : 111,
                    'type' : 'login',
                };
                this.send();
                console.log("socket连接成功")
            },
            error: function () {
                this.$message({
                    type: 'warning',
                    message: '连接错误'
                });
            },
            getMessage: function (msg) {
                console.log(msg.data);
                let data = JSON.parse(msg.data);
                if(data.code !== 1000){
                    this.$message({
                        type: 'warning',
                        message: data.message
                    });
                }
                switch (data.type) {
                    case "ping":
                        break;
                    case "send":
                        if(data.send_type === 'room'){
                            this.messages.push({
                                name: data.from_client_name,
                                content: data.content,
                                time: data.time,
                            });
                        }
                        break;
                }

            },
            send: function () {
                this.socket.send(JSON.stringify(this.postData))
            },
            close: function () {
                console.log("socket已经关闭")
            },
            sendForRoom: function () {
                // 发送群消息
                this.postData = {
                    'token' : localStorage.getItem("token"),
                    // 'token' : 111,
                    'type' : 'send',
                    'send_type' : 'room',
                    'content' : 'room',
                };
                this.send();
            }
        },
        destroyed () {
            // 销毁监听
            this.socket.onclose = this.close
        }
    }
</script>

<style scoped>

</style>
