<template>
  <!-- <div v-if="!isLoginRoute"> -->
  <a-layout-header class="header">
    <a-row style="background-color: black; height: 50px">
      <a-col :span="4">
        <img alt="ATI Logo" src="/images/drug-logo.png" height="43px" width="200px" />
      </a-col>
      <a-col :span="18"></a-col>
      <a-col :span="2">
        <div :style="{ marginTop: '8px', marginLeft: '20px' }">
          <a-dropdown-button>
            <div>
              <img src="/images/user-1.png" alt="User Image" height="25px" width="30px" />
            </div>
            <template #overlay>
              <a-menu>
                <a-menu-item key="1">
                  <AntDesignIcon.ProfileOutlined />
                  Profile
                </a-menu-item>
                <a-menu-item key="2">
                  <AntDesignIcon.KeyOutlined />
                  Change password
                </a-menu-item>
                <a-menu-item key="3">
                  <router-link :to="{ name: 'login' }" class="custom-router-link">
                    <AntDesignIcon.LogoutOutlined />
                    Logout
                  </router-link>
                </a-menu-item>
              </a-menu>
            </template>
            <template #icon>
              <AntDesignIcon.UserOutlined />
            </template>
          </a-dropdown-button>
        </div>
      </a-col>
    </a-row>
  </a-layout-header>

  <a-layout style="min-height: 100vh">
    <a-layout-sider collapsible width="250">
      <a-menu theme="dark" mode="inline">
        <router-link to="/admin" class="custom-router-link">
          <a-menu-item key="1">
            <AntDesignIcon.HomeOutlined />
            <span>Dashboard</span>
          </a-menu-item>
        </router-link>
        <router-link :to="{ name: 'hr' }" class="custom-router-link">
          <a-menu-item key="2">
            <AntDesignIcon.DesktopOutlined />
            <span>HR</span>
          </a-menu-item>
        </router-link>
        <a-sub-menu key="sub1">
          <template #title>
            <span>
              <AntDesignIcon.UserOutlined />
              <span>Project Management</span>
            </span>
          </template>
          <router-link :to="{ name: 'project_1' }" class="custom-router-link">
            <a-menu-item key="3">Project 1 </a-menu-item>
          </router-link>
          <router-link :to="{ name: 'project_2' }" class="custom-router-link">
            <a-menu-item key="4">Project 2</a-menu-item>
          </router-link>
          <router-link :to="{ name: 'project_3' }" class="custom-router-link">
            <a-menu-item key="5">Project 3</a-menu-item>
          </router-link>
        </a-sub-menu>
        <a-sub-menu key="sub2">
          <template #title>
            <span>
              <AntDesignIcon.TeamOutlined />
              <span>Work Report</span>
            </span>
          </template>
          <router-link :to="{ name: 'work_report_one' }" class="custom-router-link">
            <a-menu-item key="6">Work 1</a-menu-item>
          </router-link>
          <router-link :to="{ name: 'work_report_two' }" class="custom-router-link">
            <a-menu-item key="8">Work 2</a-menu-item>
          </router-link>
          <router-link :to="{ name: 'work_report_three' }" class="custom-router-link">
            <a-menu-item>Work 3</a-menu-item>
          </router-link>
        </a-sub-menu>
        <router-link :to="{ name: 'support' }" class="custom-router-link">
          <a-menu-item key="9">
            <AntDesignIcon.FileOutlined />
            <span>Support</span>
          </a-menu-item>
        </router-link>
      </a-menu>
    </a-layout-sider>
    <a-layout>
      <a-layout-content style="margin: 8px 8px">
        <div :style="{ background: '#fff', minHeight: '100%' }">
          <template>
            <a-carousel arrows dots-class="slick-dots slick-thumb">
              <template #customPaging="props">
                <a>
                  <img :src="getImgUrl(props.i)" />
                </a>
              </template>
              <div v-for="item in 4" :key="item">
                <img :src="getImgUrl(item - 1)" />
              </div>
            </a-carousel>
          </template>
          <router-view></router-view>
        </div>
      </a-layout-content>
      <a-layout-footer style="text-align: center">
        © copyright, all rights reserved developed & maintained by ATI Limited.
      </a-layout-footer>
    </a-layout>
  </a-layout>
  <!-- </div> -->

  <!-- <router-view v-else></router-view> -->
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import * as AntDesignIcon from '@ant-design/icons-vue'

const baseUrl =
  'https://raw.githubusercontent.com/vueComponent/ant-design-vue/main/components/carousel/demo/'

const getImgUrl = (i: number) => {
  return `${baseUrl}abstract0${i + 1}.jpg`
}

const isLoginRoute = ref(false)
const route = useRoute()

onMounted(() => {
  isLoginRoute.value = route.path === '/'
})
</script>

<style scoped>
.custom-router-link {
  text-decoration: none;
  color: inherit;
}
footer {
  max-height: 10px !important;
}
button {
  padding: 0px !important;
}
</style>
