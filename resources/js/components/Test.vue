<template>
    <div class="row">
        <div class="col-md-2">
            <div class="card-header">好友列表
                <button class="float-right btn btn-info" v-on:click="addFriend()">添加好友</button>
            </div>
            <div>
                <ul class="list-group">
                    <li class="list-group-item" v-for="friend in friends" :key="friend.friend_user_id" v-on:click="pickFriend(friend.friend_user_id, friend.name)">
                        {{ friend.name }}<span class="float-right"></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card" v-for="room in rooms" :key="room.friend_user_id" v-if="friend_user_id === room.friend_user_id">
                <div class="card-header">{{ room.title }}</div>
                <div class="card-body">
                    <div class="message pre-scrollable" style="height: 600px;overflow:auto;" id="message">
                         <div style="overflow:hidden;" v-for="message in messages">
                            <div :class="message.user_id === uuid ? 'float-right' : ''" v-if="friend_user_id === message.user_id">
                                <div class="content">{{ message.name }} ： {{ message.content }}</div>
                                <div class="time"><p>{{ message.time }}</p></div>
                            </div>
                            <div :class="message.user_id === uuid ? 'float-right' : ''" v-else-if="uuid === message.user_id && friend_user_id === message.friend_id">
                                <div class="content">{{ message.name }} ： {{ message.content }}</div>
                                <div class="time"><p>{{ message.time }}</p></div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"><hr style="border-top:1px dashed #987cb9;" width="100%" color="#987cb9" size=1></div>
                    <div class="footer">
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="sendMessage" placeholder="" @keydown.enter="sendForFriend()">
                            <button type="submit" class="btn btn-primary" v-on:click="sendForFriend()">发送(Enter)</button>
                        </div>
                    </div>
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
                window.location.href="/login";
                return;
            }
            this.init();
            axios.post('/api/user/getFriend', {
                token: localStorage.getItem("token")
            }).then(response => {
                console.log(response.data)
                this.friends = response.data.data;
                if(this.friends !== null){
                    this.pickFriend(this.friends[0].friend_user_id, this.friends[0].name)
                }
            })
            this.uuid = localStorage.getItem("uuid")
            this.token = localStorage.getItem("token")

        },
        data() {
            return{
                friends : [],
                postData : {},
                messages : [],
                sendMessage : null,
                friend_user_id : null,
                token : null,
                uuid : null,
                rooms : [],
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
                    'token' : this.token,
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
                        if(data.send_type === 'friend'){
                            this.messages.push({
                                name: data.from_user_name,
                                user_id: data.from_user_id,
                                friend_id: data.to_friend_user_id,
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
            sendForFriend: function () {

                let content = this.sendMessage;
                if(content == null){
                    return;
                }
                if(content.split(" ").join("").length === 0){
                    this.$message({
                        type: 'warning',
                        message: '不能发送空白信息'
                    });
                    this.sendMessage = null;
                    return;
                }
                // 发送消息
                this.postData = {
                    'token' : this.token,
                    'type' : 'send',
                    'send_type' : 'friend',
                    'friend_user_id' : this.friend_user_id,
                    'content' : content,
                };
                this.send();
                this.sendMessage = null;
            },
            scrollToMessages: function () {
                this.$nextTick(() => {
                    let div = document.getElementById('message')
                    div.scrollTop = div.scrollHeight
                })
            },
            addFriend: function () {
                this.$messageBox.alert('请输入好友用户名', '提示', {
                    showInput: true,
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(({ value }) => {
                    if(value == null){
                        this.$message({
                            type: 'warning',
                            message: '不能发送空白信息'
                        });
                        return;
                    }
                    if(value.split(" ").join("").length === 0){
                        this.$message({
                            type: 'warning',
                            message: '不能发送空白信息'
                        });
                        return;
                    }
                    axios.post('/api/user/addFriend', {
                        token : this.token,
                        name: value
                    }).then(response => {
                        console.log(response.data)
                        if(response.data.code !== 1000){
                            this.$message({
                                type: 'warning',
                                message: response.data.message
                            });
                        }
                        this.friends.push({
                            name: response.data.data.name,
                            friend_user_id: response.data.data.friend_user_id,
                        });
                        this.$message({
                            type: 'success',
                            message: '添加成功'
                        });
                    })
                }).catch(() => {

                });
            },
            pickFriend: function (friend_user_id, name) {
                this.friend_user_id = friend_user_id;
                let flag = false;
                this.rooms.find(function(value) {
                    if(value.friend_user_id === friend_user_id) {
                        flag = true;
                    }
                })
                if(flag === false){
                    this.rooms.push({
                        'title' : '正在和 '+name+' 聊天',
                        'friend_user_id' : friend_user_id,
                    });
                }
            }
        },
        destroyed () {
            // 销毁监听
            this.socket.onclose = this.close
        },
        watch: {
            'messages': 'scrollToMessages', //监听滚动条
            'friend_user_id': 'scrollToMessages' //监听滚动条
        }
    }
</script>

<style scoped>
    .pre-scrollable{
        overflow:hidden;
    }
    .pre-scrollable{
        overflow-x: hidden;
        overflow-y: auto;
        height: 100%;
    }
    /*滚动条样式*/
    .pre-scrollable::-webkit-scrollbar {/*滚动条整体样式*/
        width: 4px;     /*高宽分别对应横竖滚动条的尺寸*/
        height: 4px;

    }
    .pre-scrollable::-webkit-scrollbar-thumb {/*滚动条里面小方块*/
        border-radius: 5px;
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
        background: rgba(0,0,0,0.2);
    }
    .pre-scrollable::-webkit-scrollbar-track {/*滚动条里面轨道*/
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
        border-radius: 0;
        background: rgba(0,0,0,0.1);
    }
    .float-right{
        margin-right: 20px;
    }
</style>
