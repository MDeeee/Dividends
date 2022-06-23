<script>
import { layoutMethods } from "@/state/helpers";

export default {
  methods: {
    ...layoutMethods,

    /**
     * Menu clicked show the submenu
     */
    onMenuClick(event) {
      event.preventDefault();
      const nextEl = event.target.nextSibling;
      if (nextEl && !nextEl.classList.contains("show")) {
        const parentEl = event.target.parentNode;
        if (parentEl) {
          parentEl.classList.remove("show");
        }
        nextEl.classList.add("show");
      } else if (nextEl) {
        nextEl.classList.remove("show");
      }
      return false;
    },
    change_layout(layout) {
      return this.changeLayoutType({ layoutType: layout });
    },
    topbarLight() {
      document.body.setAttribute("data-topbar", "light");
      document.body.removeAttribute("data-layout-size", "boxed");
    },
    boxedWidth() {
      document.body.setAttribute("data-layout-size", "boxed");
      document.body.removeAttribute("data-topbar", "light");
      document.body.setAttribute("data-topbar", "dark");
    },
    activeMenuLiks(){
      var links = document.getElementsByClassName("side-nav-link-ref");
      var matchingMenuItem = null;
      for (var i = 0; i < links.length; i++) {
        if (window.location.pathname === links[i].pathname) {
          matchingMenuItem = links[i];
          break;
        }
      }
      if (matchingMenuItem) {
        matchingMenuItem.classList.add("active");
        var parent = matchingMenuItem.parentElement;

        if (parent) {
          parent.classList.add("active");
        }
      }
    }
  },
  mounted() {
    this.activeMenuLiks();
  },
};
</script>

<template>
  <div class="topnav">
    <div class="container-fluid">
      <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
        <div class="collapse navbar-collapse" id="topnav-menu-content">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link to="/" class="nav-link side-nav-link-ref">
                  <i class="mdi mdi-desktop-mac-dashboard"></i>
                  <span>Dashboard</span>
              </router-link>
            </li>
            <li class="nav-item dropdown">
              <router-link to="/calendar" class="nav-link side-nav-link-ref" >
                <i class="bx ri-calendar-event-fill"></i> 
                <span>Calendar</span>
              </router-link>
            </li>
            <li class="nav-item dropdown">
              <router-link to="/scrape" class="nav-link side-nav-link-ref" >
                <i class="bx ri-search-line"></i> 
                <span>Scrape</span>
              </router-link>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
</template>