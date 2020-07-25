<template>
    <div class="card">
        <div class="card-header">聊天室</div>
        <div class="card-body">
            <div class="message" style="height: 480px;overflow: auto;" id="message">
                <div class="send">
                    <div class="msg">KarlXu：</div>
                    <div class="msg">你好！欢迎来到梦缘</div>
                    <div class="time"><p>05/22 06:30</p></div>
                </div>
            </div>
            <div class="divider"><hr style="border-top:1px dashed #987cb9;" width="100%" color="#987cb9" size=1></div>
            <div class="footer">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="send message">
                    <button type="submit" class="btn btn-primary">发送</button>
                </div>
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
