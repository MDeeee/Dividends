<script>
    import {VueSimplebar } from "vue-simplebar";
    import {
        layoutComputed
    } from "@/state/helpers";

    import MetisMenu from "metismenujs/dist/metismenujs";

    export default {
        components: {
            VueSimplebar ,
        },
        props: {
            isCondensed: {
                type: Boolean,
                default: false,
            },
            type: {
                type: String,
                required: true,
            },
            width: {
                type: String,
                required: true,
            },
        },
        computed: {
            ...layoutComputed,
        },
        mounted() {
            this.activeMenuLinks();
        },
        methods: {
            activeMenuLinks(){
                // eslint-disable-next-line no-unused-vars
                var menuRef = new MetisMenu("#side-menu");
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
                        parent.classList.add("mm-active");
                        const parent2 = parent.parentElement.closest("ul");
                        if (parent2 && parent2.id !== "side-menu") {
                            parent2.classList.add("mm-show");

                            const parent3 = parent2.parentElement;
                            if (parent3) {
                                parent3.classList.add("mm-active");
                                var childAnchor = parent3.querySelector(".has-arrow");
                                var childDropdown = parent3.querySelector(".has-dropdown");
                                if (childAnchor) childAnchor.classList.add("mm-active");
                                if (childDropdown) childDropdown.classList.add("mm-active");

                                const parent4 = parent3.parentElement;
                                if (parent4 && parent4.id !== "side-menu") {
                                    parent4.classList.add("mm-show");
                                    const parent5 = parent4.parentElement;
                                    if (parent5 && parent5.id !== "side-menu") {
                                        parent5.classList.add("mm-active");
                                        const childanchor = parent5.querySelector(".is-parent");
                                        if (childanchor && parent5.id !== "side-menu") {
                                            childanchor.classList.add("mm-active");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            onRoutechange() {
                setTimeout(() => {
                    let currentPositionEle = document.getElementsByClassName("mm-active")[0];
                    const currentPosition = currentPositionEle !== undefined ? currentPositionEle.offsetTo : 0;
                    if (currentPosition > 400)
                        this.$refs.currentMenu.SimpleBar.getScrollElement().scrollTop =
                        currentPosition + 200;
                }, 300);
            },
        },
        watch: {
            $route: {
                handler: "onRoutechange",
                immediate: true,
                deep: true,
            },
            type: {
                immediate: true,
                handler(newVal, oldVal) {
                    if (newVal !== oldVal) {
                        switch (newVal) {
                            case "dark":
                                document.body.setAttribute("data-sidebar", "dark");
                                document.body.removeAttribute("data-topbar");
                                document.body.removeAttribute("data-sidebar-size");
                                break;
                            case "light":
                                document.body.setAttribute("data-topbar", "dark");
                                document.body.removeAttribute("data-sidebar");
                                document.body.removeAttribute("data-sidebar-size");
                                document.body.classList.remove("vertical-collpsed");
                                break;
                            case "compact":
                                document.body.setAttribute("data-sidebar-size", "small");
                                document.body.setAttribute("data-sidebar", "dark");
                                document.body.classList.remove("vertical-collpsed");
                                document.body.removeAttribute("data-topbar", "dark");
                                break;
                            case "icon":
                                document.body.setAttribute("data-keep-enlarged", "true");
                                document.body.classList.add("vertical-collpsed");
                                document.body.setAttribute("data-sidebar", "dark");
                                document.body.removeAttribute("data-topbar", "dark");
                                break;
                            case "colored":
                                document.body.setAttribute("data-sidebar", "colored");
                                document.body.removeAttribute("data-keep-enlarged");
                                document.body.classList.remove("vertical-collpsed");
                                document.body.removeAttribute("data-sidebar-size");
                                break;
                            default:
                                document.body.setAttribute("data-sidebar", "dark");
                                break;
                        }
                    }
                },
            },
            width: {
                immediate: true,
                handler(newVal, oldVal) {
                    if (newVal !== oldVal) {
                        switch (newVal) {
                            case "boxed":
                                document.body.setAttribute("data-layout-size", "boxed");
                                break;
                            case "fluid":
                                document.body.setAttribute("data-layout-mode", "fluid");
                                document.body.removeAttribute("data-layout-size");
                                break;
                            default:
                                document.body.setAttribute("data-layout-mode", "fluid");
                                break;
                        }
                    }
                },
            },
        },
    };

</script>
<template>
    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">
        <vue-simplebar @scroll-y-reach-end="100" class="h-100" id="my-element" >
            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul id="side-menu" class="metismenu list-unstyled">
                    <li class="menu-title">Menu</li>
                    <li>
                        <router-link to="/" class="side-nav-link-ref">
                            <i class="mdi mdi-desktop-mac-dashboard"></i>
                            <span>Dashboard</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/calendar" class="side-nav-link-ref">
                            <i class="bx ri-calendar-event-fill"></i>
                            <span>Calendar</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/scrape" class="side-nav-link-ref">
                            <i class="bx ri-search-line"></i>
                            <span>Scrape</span>
                        </router-link>
                    </li>
                </ul>
            </div>
            <!-- Sidebar -->
        </vue-simplebar>
    </div>
    <!-- Left Sidebar End -->
</template>
