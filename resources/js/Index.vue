<template>
    <router-view v-slot="{ Component, route }">
    <transition name="fade">
        <component :is="Component" :key="route.path" />
    </transition>
    </router-view>
</template>

<style>
/* Style the alert box */
.alert {
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Danger alert specific styles */
.alert-danger {
    background-color: #f44336;
    color: white;
}

.alert-success {
    background-color: #43a700;
    color: white;
    height: 45px;
}

/* Style the error list */
.error-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

/* Style each error item */
.error-item {
    margin-bottom: 5px;
}

/* Add a separator between items except the last one */
.error-item:not(:last-child) {
    border-bottom: 1px solid rgba(255, 255, 255, 0.5);
    padding-bottom: 10px;
}

/* Adjustments for RTL */
[dir="rtl"] .error-item {
    text-align: right;
}
</style>