<template>
    <div class="card">
        <div class="card-header">Register</div>

        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">用户名</label>
                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="name" value="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">邮箱</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">密码</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">确认密码</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            注册
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "register"
    }
</script>

<style scoped>

</style>
