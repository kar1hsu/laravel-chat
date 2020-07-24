<template>
    <div class="card">
        <div class="card-header">聊天室</div>
        <div class="card-body">
            <div class="message" id="message">
                <div class="send">
                    <div class="time">05/22 06:30</div>
                    <div class="msg">
                        <p>你好！欢迎来到梦缘</p>
                    </div>
                </div>
                <div class="show">
                    <div class="time">05/22 06:30</div>
                    <div class="msg">
                        <p>请文明发言</p>
                    </div>
                </div>
            </div>
            <div class="footer">
                <label>
                    <input type="file" name="img" id="sendimg" hidden="hidden"/>
                </label>
                <input type="text" id="text" />
                <p>发送</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log(localStorage.getItem("is_login"))
            //页面是否登录
            if (localStorage.getItem("is_login") === null) {
                //本地存储中是否有token(uid)数据
                this.$router.push("/login");
            }
        },
    }
</script>

<style scoped>

</style>
