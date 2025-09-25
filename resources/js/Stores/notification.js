// Stores/notification.js
import { defineStore } from 'pinia'

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
    }),
    actions: {
        push(notification) {
            this.notifications.unshift(notification)
        },
        clearAll() {
            this.notifications = []
        }
    },
})
