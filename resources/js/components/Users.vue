<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">This is the users page</p>
                    </div>
                </div>
            </div>
        </div>
        <SideBarVue></SideBarVue>
    </div>
</template>
<script>
import SideBarVue from './layouts/SideBar.vue';
export default {
    name:"users",
    data(){
        return {
            user:this.$store.state.auth.user
        }
    }
}
</script>