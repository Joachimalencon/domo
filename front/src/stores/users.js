import {defineStore} from 'pinia'
import {ref} from 'vue'
import userRepository from "@/repository/userRepository.js";

export const useUserStore = defineStore('users', () => {
    const users = ref([])

    async function getUsers() {
        try {
            const response = await userRepository.getUsers()
            if (!response) {
                console.error('Error fetching users:', response.status)
            }
            users.value = await response
        } catch (error) {
            console.error('Error fetching users:', error)
        }
    }

    async function approveUser(userId) {
        try {
            const response = await userRepository.approveUser(userId)
            if (!response) {
                console.error('Error approving users:', response.status)
            }
            users.value = await response
        } catch (error) {
            console.error('Error approving users:', error)
        }
    }

    async function revokeUser(userId) {
        try {
            const response = await userRepository.revokeUser(userId)
            if (!response) {
                console.error('Error revoking users:', response.status)
            }
            users.value = await response
        } catch (error) {
            console.error('Error revoking users:', error)
        }
    }


    return {
        users,
        getUsers,
        approveUser,
        revokeUser
    }
})
