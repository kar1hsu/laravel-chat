<template>
    <div class="card">
        <div class="card-header">聊天室</div>

        <div class="card-body">
            啊啊啊啊啊啊啊
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
