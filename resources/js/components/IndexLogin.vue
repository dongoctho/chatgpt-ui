<template>
    <div class="container-fluid ">
        <div class="row h-100vh">
            <div class="col-sm-7 bg-left">
                <p class="mt-3 ms-4">ChatGPT</p>
                <div class="square"></div>
                <div class="square"></div>
                <main class="main-container d-block">
                    <div class="d-flex align-items-center text-ani justify-content-center flex-wrap">
                        <p v-for="(char, index) in animatedText" :key="index" class="animated-char">{{ char === ' ' ? '&nbsp;' : char }}</p>
                    </div>
                </main>
            </div>
            <div class="col-sm-5 bg-right d-flex justify-content-center align-items-center">
                <div class="">
                    <main class="main-container">
                        <section class="content-wrapper">
                            <div class="title-wrapper">
                                <h1 class="title text-center">Get started</h1>
                            </div>
                            <div class="login-container">
                                <div class="social-section">
                                        <button type="submit" class="btn" @click="redirectLoginWithGoogle">
                                            <span class="social-logo-wrapper">
                                                <img src="../../images/google-logo-NePEveMl.svg" alt="" class="d-inline">
                                            </span>
                                            <span class="fw-bolder">Login with Google</span>
                                        </button>
                                </div>
                            </div>
                        </section>
                        <div class="square"></div>
                    </main>
                    <footer>
                        <div class="d-flex justify-content-center align-items-center w-100">
                            <img src="../../images/logoChatgptWhite-2.svg" alt="" class="d-inline">
                            <p class="mb-0 ms-2">OpenAi</p>
                        </div>
                        <div class="text-center d-flex align-items-center justify-content-center oai-footer">
                        <a href="https://openai.com/policies/terms-of-use" target="_blank">Terms of Use</a>
                        <span class="separator"></span>
                        <a href="https://openai.com/policies/privacy-policy" target="_blank">Privacy Policy</a>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useToast } from 'vue-toastification';
export default {
    data() {
        return {
            originalText: "Hello Vnexter",
            animatedText: "",
            error: window.sessionData.error || null,
        };
    },
    created() {
    this.showErrorToast();
    },
    methods: {
        async redirectLoginWithGoogle() {
            try {
                window.location.href = '/auth/google';
            } catch (error) {
                console.error('Error during Google login:', error);
            }
        },
        animateText() {
            this.animatedText = "";
            const chars = this.originalText.split("");
            chars.forEach((char, index) => {
                setTimeout(() => {
                    this.animatedText += char;
                }, index * 180);
            });
        },
        showErrorToast() {
            if (this.error) {
                const toast = useToast();
                toast.error(this.error, {
                    timeout: 5000,
                    closeOnClick: true,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: false,
                    closeButton: "button",
                    icon: true,
                    rtl: false
                });
            }
        }
    },
    mounted() {
        this.animateText();
    }
}
</script>

<style scoped>
    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-size: 24px;
    }
    .animated-char {
        opacity: 0;
        animation: fadeIn 0.5s ease forwards;
    }
    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    .h-100vh{
        height: 100vh;
    }
    .bg-left {
        color:red;
        background-color:rgb(0,0,46) ;

    }
    .bg-left p{
        color: #fff;
        font-weight: bolder;
        font-size: 18px;
    }
    .bg-left .text-ani p{
        font-size: 30px;
        font-weight: bolder;
    }
    .bg-right {
        background-color: rgb(0,0,0);
    }
    .main-container {
        flex: 1 0 auto;
        min-height: 0;
        display: grid;
        box-sizing: border-box;
        grid-template-rows: [left-start center-start right-start] 1fr [left-end center-end right-end];
        grid-template-columns: [left-start center-start] 1fr [left-end right-start] 1fr [center-end right-end];
        align-items: center;
        justify-content: center;
        justify-items: center;
        grid-column-gap: 160px;
        column-gap: 160px;
        padding: 40px;
        width: 100%;
    }
    .content-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: auto;
        white-space: normal;
        border-radius: 5px;
        position: relative;
        grid-area: center;
        box-shadow: none;
        vertical-align: baseline;
        box-sizing: content-box;
    }
    .title-wrapper {
        padding: 40px 40px 0px;
        box-sizing: content-box;
    }
    .title {
        font-size: 25px;
        margin: 24px 0 0;
        color: #fff;
        width: 320px;
        font-weight: 700;
    }
    .login-container {
        padding: 0 40px 40px;
        border-radius: 3px;
        box-shadow: none;
        width: 320px;
        box-sizing: content-box;
        flex-shrink: 0;
    }
    .login-btn {
        position: relative;
        height: 52px;
        width: 320px;
        background-color: rgb(60,70,255);
        margin: 24px 0 0;
        border-radius: 6px;
        padding: 4px 16px;
        font: inherit;
        border-width: 0px;
    }
    .login-btn:hover{
        background-color: rgb(5, 12, 146);
    }
    .divider-wrapper{
        border: none;
        font-size: 12px;
        padding: 24px 0 0;
        width: 320px;
    }
    .divider-wrapper:before, .divider-wrapper:after {
        content: "";
        border-bottom: 1px solid #c2c8d0;
        flex: 1 0 auto;
        height: .5em;
        margin: 0;
    }
    .divider{
        height: 12px;
        flex: .2 0 auto;
        color: #fff;
    }
    .social-section{
        margin-top: 24px;
    }
    .social-section button{
        width: 320px;
        border: 1px solid #c2c8d0;
        border-radius: 6px;
        font-size: 16px;
        align-items: center;
        background-color: #fff;
        height: 52px;
        cursor: pointer;
        padding: 0 8px 0 20px;
        color: #2d333a;
        margin-bottom: 8px;
        display: flex;
        outline: 0;
    }
    .social-section button:hover{
        background-color: #b8b8b8;
    }
    .social-logo-wrapper{
        left: 26px;
        top: 50%;
    }
    .social-logo-wrapper img{
        width: 20px;
        height: 20px;
        margin-right: 20px;
        margin-bottom: 3px;
    }
    .square{
        height: 100px;
        width: 100px;
    }
    footer p{
        color: #fff;
    }
    .oai-footer{
        font-size: 14px;
        padding: 12px 0 24px;
    }
    .oai-footer a{
        color: #fff;
        text-decoration: none;
    }
    .oai-footer a:hover{
        color: rgb(60,70,255);
    }
    .separator:before {
        content: " | ";
        margin: 0 8px;
        color: #fff;
    }
</style>
