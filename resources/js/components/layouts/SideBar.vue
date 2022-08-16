<template>
  <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Thoughts</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item mt-2">
                  <a class="nav-link" href="/users">Users</a>
                </li>
                <li class="nav-item mt-2">
                  <a class="nav-link" href="/posts">Posts</a>
                </li>
                <li class="nav-item mt-2">
                  <a class="nav-link" href="/admins">Admins</a>
                </li>
              </ul>
              <form class="d-flex mt-2">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
              </form>

              <div class= "mt-2" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </nav>
    <main class="container p-5 mt-5">
        <router-view></router-view>
    </main>
    
</template>
<script>
//importing bootstrap 5
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.min.js";
import {mapActions} from 'vuex'
export default {
 name:"sidebar-layout",
 data(){
        return {
            user:this.$store.state.auth.user
        }
    },
    methods:{
        ...mapActions({
            signOut:"auth/logout"
        }),
        async logout(){
            await axios.post('api/logout').then(({data})=>{
                this.signOut()
                this.$router.push({name:"login"})
            })
        }
    }
}
</script>
<style>
.line {
  width: 100%;
  height: 1px;
  border-bottom: 1px dashed #ddd;
  margin: 40px 0;
}
</style>