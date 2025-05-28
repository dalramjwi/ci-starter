const urls = [
  "https://i.postimg.cc/4dQP9Z9w/1.jpg",
  "https://i.postimg.cc/0yhVgVvG/2.jpg",
  "https://i.postimg.cc/sgMT0tkD/3.jpg",
  "https://i.postimg.cc/4dgwynmm/4.jpg",
  "https://i.postimg.cc/R0fXvwCk/5.jpg",
];

function loadImage(url) {
  const image = new Image();

  return new Promise((resolve) => {
    image.onload = () => resolve(image);
    image.crossOrigin = "Anonymous";
    image.src = url;
  });
}

document.addEventListener("DOMContentLoaded", async () => {
  const canvas = document.getElementById("buffer");
  const context = canvas.getContext("2d");
  const { width, height } = canvas;
  const { backgroundColor } = getComputedStyle(document.body);
  const tvScreen = document.querySelector(".tv__screen");
  const viewContainer = document.getElementById("view-container");
  const loader = document.querySelector(".loader");

  context.fillStyle = backgroundColor;
  context.fillRect(0, 0, width, height);

  const images = await Promise.all(urls.map(loadImage));

  let currentImageIndex = 0;

  const drawCurrentImage = () => {
    context.fillRect(0, 0, width, height);
    context.drawImage(images[currentImageIndex], 0, 0);
  };
  if (tvScreen && viewContainer) {
    tvScreen.addEventListener("mouseenter", function () {
      viewContainer.style.display = "block";
      loader.style.display = "none";
    });
    tvScreen.addEventListener("mouseleave", () => {
      viewContainer.style.display = "none";
      loader.style.display = "block";
    });
  }
  setInterval(() => {
    currentImageIndex = (currentImageIndex + 1) % images.length;

    drawCurrentImage();
  }, 3000);

  drawCurrentImage();
});
