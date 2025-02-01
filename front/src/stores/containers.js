import {defineStore} from 'pinia'
import {ref} from 'vue'
import containerRepository from '@/repository/containerRepository.js';

export const useContainerStore = defineStore('containers', () => {
    const containers = ref([])

    async function getContainers() {
        try {
            const response = await containerRepository.getContainers()
            if (!response) {
                console.error('Error fetching containers:', response.status)
            }
            containers.value = await response
        } catch (error) {
            console.error('Error fetching containers:', error)
        }
    }

    async function startContainer(containerId) {
        try {
            const response = await containerRepository.startContainer(containerId)
            if (!response) {
                console.error('Error starting container:', response.status)
            }
            await getContainers();
        } catch (error) {
            console.error('Error starting container:', error)
        }
    }

    async function stopContainer(containerId) {
        try {
            const response = await containerRepository.stopContainer(containerId)
            if (!response) {
                console.error('Error stopping container:', response.status)
            }
            await getContainers();
        } catch (error) {
            console.error('Error stopping container:', error)
        }
    }

    return {
        containers,
        getContainers,
        startContainer,
        stopContainer
    }
})
