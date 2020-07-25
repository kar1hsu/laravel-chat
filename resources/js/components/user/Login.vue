<template>
    <div class="card">
        <div class="card-header">Login</div>

        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">用户名</label>
                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" v-model="name" value="">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">密码</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" v-model="password" value="">
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary" v-on:click="doLogin()">
                        登录
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "login",
        methods: {
            doLogin: function() {
                let error_flag = 0;
                let error_message = '';

                if(this.name.length < 2 || this.name.length > 16){
                    error_flag = 1;
                    error_message = '请输入2到16位的用户名';
                }else if (this.password.length < 6) {
                    error_flag = 1;
                    error_message = '请输入6位以上密码'
                }

                if(error_flag === 1){
                    this.$message({
                        type: 'warning',
                        message: error_message
                    });
                    return;
                }
                axios.post('/api/user/login',{
                    name: this.name,
                    password: this.password
                }).then(response => {
                    let data = response.data;
                    if(data.code === 1001){
                        this.$message({
                            type: 'warning',
                            message: data.message
                        });
                        return;
                    }
                    localStorage.setItem('is_login', 1);
                    localStorage.setItem('name', data.data.name);
                    localStorage.setItem('token', data.data.token);
                    console.log(response.data)
                    window.location.href="/";
                })
            }
        },
        data() {
            return {
                name : '',
                password : '',
            }
        }
    }
</script>

<style scoped>

</style>
