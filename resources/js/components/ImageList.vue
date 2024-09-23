<template>
    <div class="image-container">
        <h1 class="title">Upload and View Images</h1>

        <form class="upload-form" @submit.prevent="uploadImage" enctype="multipart/form-data">
            <input type="file" id="file" ref="file" @change="onFileChange" style="display: none;"/>
            <button type="button" class="upload-btn" @click="triggerFileSelection">Select Image</button>
            <button type="submit" class="upload-btn">Upload</button>
        </form>

        <div class="image-viewer">
            <button class="arrow left" @click="prevImage">←</button>
            <div v-for="(image, index) in images" :key="image.id">
                <img v-if="currentImageIndex === index" :src="image.file_path" :alt="`Image ${index + 1}`" class="image" />
            </div>
            <button class="arrow right" @click="nextImage">→</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            images: [],
            image: null,
            currentImageIndex: 0,
        }
    },

    async mounted() {
        const API_URL = '/api/images';
        try {
            const response = await axios.get(API_URL);
            this.images = response.data;
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        triggerFileSelection() {
            this.$refs.file.click();
        },
        onFileChange(e) {
            this.image = e.target.files[0];
        },
        async uploadImage() {
            let formData = new FormData();
            formData.append('image', this.image);
            await axios.post('/api/images', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if (response.status == 201) {
                    this.image = null;
                    //push new image to the existing images array
                    this.images = [...this.images, response.data];
                }
            }).catch(error => {
                console.error(error);
            });
        },
        nextImage() {
            if (this.currentImageIndex < this.images.length - 1) {
                this.currentImageIndex++;
            }
        },
        prevImage() {
            if (this.currentImageIndex > 0) {
                this.currentImageIndex--;
            }
        }
    }
}
</script>
<style scoped>
.image-container {
    background: #eee;
    padding: 0;
    text-align: center;
    width: 100vw;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    box-sizing: border-box;
}

.title {
    color: #333;
    font-size: 2em;
    margin: 0;
}

.upload-form {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.upload-btn {
    display: inline-block;
    font-size: 1em;
    padding: 10px 20px;
    margin: 0 10px;
    background: #333;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.upload-btn:hover {
    background: #777;
}

.image-viewer {
    position: relative;
    margin: 0 auto;
    width: 100%;
    height: calc(100vh - 60px - 2em);
    overflow: hidden;
}

.image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    padding: 10px;
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 1;
}

.arrow:hover {
    background: rgba(0, 0, 0, 0.7);
}

.arrow.left {
    left: 10px;
}

.arrow.right {
    right: 10px;
}
</style>
