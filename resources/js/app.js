// Basic log to confirm script is loaded
console.log("App.js is loaded");

import "./bootstrap";
import Swiper from "swiper";
window.Swiper = Swiper;
document.addEventListener("DOMContentLoaded", function () {
    console.log("Document loaded and ready");

    new Swiper(".swiper-container", {
        slidesPerView: "auto",
        spaceBetween: 10,
        loop: true,
    });

    // This will print each time an element within [x-data] is clicked
    document.querySelector("[x-data]").addEventListener("click", function (e) {
        console.log("Element with [x-data] was clicked");

        // This will print the innerText of the clicked element
        console.log("Clicked element text:", e.target.innerText);

        if (e.target.innerText.includes("Apparel")) {
            console.log("About to initialize Swiper for Apparel");

            setTimeout(() => {
                new Swiper(".swiper-container", {
                    slidesPerView: "auto",
                    spaceBetween: 10,
                    loop: true,
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                });
            }, 100);
        }
    });
});
